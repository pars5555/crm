<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package managers
 * @version 6.0
 *
 */

namespace crm\managers {

    use crm\dal\mappers\SaleOrderLineMapper;

    class SaleOrderLineManager extends AdvancedAbstractManager {

        /**
         * @var $instance
         */
        public static $instance;

        /**
         * Returns an singleton instance of this class
         *
         * @param object $config
         * @param object $args
         * @return
         */
        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new SaleOrderLineManager(SaleOrderLineMapper::getInstance());
            }
            return self::$instance;
        }

        public function deleteWhereIdNotIdIds($saleOrderId, $ids) {
            $dtos = $this->selectByField('sale_order_id', $saleOrderId);
            foreach ($dtos as $dto) {
                if (!in_array($dto->getId(), $ids)) {
                    $this->deleteByPK($dto->getId());
                }
            }
            return true;
        }

        public function replaceProductId($productId, $replaceProductId) {
            return $this->mapper->updateAdvance(['product_id','=', $productId], ['product_id'=>$replaceProductId]);
            
        }
        
        public function updateSaleOrderLine($saleOrderId, $id, $productId, $quantity, $unitPrice, $currencyId) {
            $unitPrice = floatval($unitPrice);
            $quantity = floatval($quantity);
            $dto = $this->selectByPk($id);
            $dto->setProductId($productId);
            $dto->setQuantity($quantity);
            $dto->setUnitPrice($unitPrice);
            $dto->setCurrencyId($currencyId);
            $productUnitCostInBaseCurrency = ProductManager::getInstance()->calculateProductCost($productId, $quantity, $saleOrderId);
            $dto->setUnitCost(json_encode($productUnitCostInBaseCurrency));
            $saleOrderDto = SaleOrderManager::getInstance()->selectByPk($saleOrderId);
            $orderDate = $saleOrderDto->getOrderDate();
            $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
            $dto->setCurrencyRate($rate);
            if ($saleOrderDto->getNonProfit() == 0) {
                $profit = $quantity * $unitPrice * $rate - $quantity * ProductManager::getInstance()->calculateProductTotalCost($productUnitCostInBaseCurrency);
                $dto->setTotalProfit($profit);
            } else {
                $dto->setTotalProfit(0);
            }
            $so = SaleOrderManager::getInstance()->selectByPk($saleOrderId);
            PartnerManager::getInstance()->setPartnerHidden($so->getPartnerId(), 0);
            return $this->updateByPk($dto);
        }

        public function createSaleOrderLine($saleOrderId, $productId, $quantity, $unitPrice, $currencyId) {
            $unitPrice = floatval($unitPrice);
            $quantity = floatval($quantity);
            $dto = $this->createDto();
            $dto->setSaleOrderId($saleOrderId);
            $dto->setProductId($productId);
            $dto->setQuantity($quantity);
            $dto->setUnitPrice($unitPrice);
            $dto->setCurrencyId($currencyId);
            $productUnitCostInBaseCurrency = ProductManager::getInstance()->calculateProductCost($productId, $quantity);
            $dto->setUnitCost(json_encode($productUnitCostInBaseCurrency));
            $saleOrderDto = SaleOrderManager::getInstance()->selectByPk($saleOrderId);
            $orderDate = $saleOrderDto->getOrderDate();
            $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
            $dto->setCurrencyRate($rate);
            if ($saleOrderDto->getNonProfit() == 0) {
                $profit = $quantity * $unitPrice * $rate - ProductManager::getInstance()->calculateProductTotalCost($productUnitCostInBaseCurrency);
                $dto->setTotalProfit($profit);
            } else {
                $dto->setTotalProfit(0);
            }
            $so = SaleOrderManager::getInstance()->selectByPk($saleOrderId);
            PartnerManager::getInstance()->setPartnerHidden($so->getPartnerId(), 0);
            return $this->insertDto($dto);
        }

        public function getSaleOrderLinesFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            $rows = $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit);
            $productIds = array();
            $currencyIds = array();
            foreach ($rows as $row) {
                $productIds[] = $row->getProductId();
                $currencyIds[] = $row->getCurrencyId();
            }
            $productIds = array_unique($productIds);
            $currencyIds = array_unique($currencyIds);
            $productDtos = ProductManager::getInstance()->selectByPKs($productIds, true);
            $currencyDtos = CurrencyManager::getInstance()->selectByPKs($currencyIds, true);
            foreach ($rows as $row) {
                $row->setProductDto($productDtos[$row->getProductId()]);
                $row->setCurrencyDto($currencyDtos[$row->getCurrencyId()]);
            }
            return $rows;
        }

        public function getProductCountInNonCancelledSaleOrders($productId, $exceptSaleOrderId = 0, $dateBefore = null) {
            return $this->mapper->getProductCountInNonCancelledSaleOrders($productId, $exceptSaleOrderId, $dateBefore);
        }

        public function getProductsCountInNonCancelledSaleOrders($productId) {
            $rows = $this->mapper->getProductsCountInNonCancelledSaleOrders($productId);
            $ret = [];
            foreach ($rows as $row) {
                $ret[$row->product_id] = $row->product_qty;
            }
            return $ret;
        }

        public function getAllProductCountInNonCancelledSaleOrders($partnerId = false, $excludePartnerIdsStr = '0') {
            return $this->mapper->getAllProductCountInNonCancelledSaleOrders($partnerId, $excludePartnerIdsStr);
        }

        public function getTotalProfitSumInNonCancelledSaleOrders($startDate, $endDate) {
            return $this->mapper->getTotalProfitSumInNonCancelledSaleOrders($startDate, $endDate);
        }

        public function getAllNonCancelledExpenseSaleOrders($startDate, $endDate) {
            return $this->mapper->getAllNonCancelledExpenseSaleOrders($startDate, $endDate);
        }

        public function getProductsSaleOrders($productIds, $partnerId = false, &$productLastSellPrice = []) {
            $soLines = [];
            if (!empty($productIds)) {
                $soLines = $this->mapper->getNonCancelledProductsSaleOrders($productIds, $partnerId);
            }
            $soIdsMappedByProductId = [];
            $saleOrdersProductPrice = [];
            $allSaleOrdersIds = [];
            foreach ($soLines as $sol) {
                $soIdsMappedByProductId [$sol->getProductId()][] = $sol->getSaleOrderId();
                $productLastSellPrice[$sol->getProductId()] = $sol->getUnitPrice();
                $allSaleOrdersIds[] = intval($sol->getSaleOrderId());
                $saleOrdersProductPrice[intval($sol->getSaleOrderId())][$sol->getProductId()] = $sol->getUnitPrice();
            }
            $allSaleOrdersIds = array_unique($allSaleOrdersIds);
            $saleOrdersMappedById = [];
            if (!empty($allSaleOrdersIds)) {
                $idsSql = '(' . implode(',', $allSaleOrdersIds) . ')';
                $where = ['id', 'IN', $idsSql];
                if ($partnerId > 0) {
                    $where = array_merge($where, ['AND', 'partner_id', '=', $partnerId]);
                }
                $saleOrdersMappedById = SaleOrderManager::getInstance()->selectAdvance('*', $where, null, null, null, null, true);
            }


            foreach ($soIdsMappedByProductId as &$r) {
                $r = array_unique($r);
            }
            $ret = [];            
            foreach ($productIds as $productId) {
                if (!array_key_exists($productId, $soIdsMappedByProductId)) {
                    $soIdsMappedByProductId[$productId] = [];
                }
                $ret[$productId] = [];
                foreach ($soIdsMappedByProductId[$productId] as $soId) {
                    if (array_key_exists($soId, $saleOrdersMappedById)) {
                        $ret[$productId][] = $saleOrdersMappedById[$soId];
                        $saleOrdersMappedById[$soId]->setProductPrice($productId, $saleOrdersProductPrice[$soId][$productId]);
                    }
                }
            }
            return $ret;
        }

        public function getNonCancelledProductsSaleOrders($productIds) {
            $dtos = $this->mapper->getNonCancelledProductsSaleOrders($productIds);
            $ret = [];
            foreach ($dtos as $dto) {
                $ret [$dto->getProductId()][] = $dto;
            }
            return $ret;
        }

        public function getNonCancelledProductSaleOrders($productId, $saleOrderId, $date, $excludePartnerIdsStr = '0') {
            return $this->mapper->getNonCancelledProductSaleOrders($productId, $saleOrderId, $date, $excludePartnerIdsStr);
        }

    }

}
