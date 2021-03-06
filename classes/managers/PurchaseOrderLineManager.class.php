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

    use crm\dal\mappers\PurchaseOrderLineMapper;

    class PurchaseOrderLineManager extends AdvancedAbstractManager {

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
                self::$instance = new PurchaseOrderLineManager(PurchaseOrderLineMapper::getInstance());
            }
            return self::$instance;
        }

        public function getPurchaseOrderLinesFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
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
                if (isset($productDtos[$row->getProductId()])) {
                    $row->setProductDto($productDtos[$row->getProductId()]);
                }
                $row->setCurrencyDto($currencyDtos[$row->getCurrencyId()]);
            }
            return $rows;
        }

        public function replaceProductId($productId, $replaceProductId) {
            return $this->mapper->updateAdvance(['product_id', '=', $productId], ['product_id' => $replaceProductId]);
        }

        public function deleteWhereIdNotIdIds($purchaseOrderId, $ids) {
            $dtos = $this->selectByField('purchase_order_id', $purchaseOrderId);
            foreach ($dtos as $dto) {
                if (!in_array($dto->getId(), $ids)) {
                    $this->deleteByPK($dto->getId());
                }
            }
            return true;
        }

        public function updatePurchaseOrderLine($purchaseOrderId, $id, $productId, $quantity, $unitPrice, $currencyId) {
            $dto = $this->selectByPk($id);
            $dto->setProductId($productId);
            $dto->setQuantity($quantity);
            $dto->setUnitPrice($unitPrice);
            $dto->setCurrencyId($currencyId);
            $po = PurchaseOrderManager::getInstance()->selectByPk($purchaseOrderId);
            $orderDate = $po->getOrderDate();
            $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
            $dto->setCurrencyRate($rate);
            PartnerManager::getInstance()->setPartnerHidden($po->getPartnerId(), 0);
            return $this->updateByPk($dto);
        }

        public function createPurchaseOrderLine($purchaseOrderId, $productId, $quantity, $unitPrice, $currencyId) {
            $dto = $this->createDto();
            $dto->setPurchaseOrderId($purchaseOrderId);
            $dto->setProductId($productId);
            $dto->setQuantity($quantity);
            $dto->setUnitPrice($unitPrice);
            $dto->setCurrencyId($currencyId);
            $po = PurchaseOrderManager::getInstance()->selectByPk($purchaseOrderId);
            $orderDate = $po->getOrderDate();
            $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
            $dto->setCurrencyRate($rate);
            $ret = $this->insertDto($dto);
            PartnerManager::getInstance()->setPartnerHidden($po->getPartnerId(), 0);
            return $ret;
        }

        public function getProductCountInNonCancelledPurchaseOrders($productId) {
            return $this->mapper->getProductCountInNonCancelledPurchaseOrders($productId);
        }

        public function getNonCancelledProductPurchaseOrders($productId, $date = null, $excludePartnerWarehase = '0') {
            return $this->mapper->getNonCancelledProductPurchaseOrders($productId, $date, $excludePartnerWarehase);
        }

        public function getNonCancelledProductsPurchaseOrders($productIds) {
            if (empty($productIds)) {
                return [];
            }
            $dtos = $this->mapper->getNonCancelledProductsPurchaseOrders($productIds);
            $ret = [];
            foreach ($dtos as $dto) {
                $ret [$dto->getProductId()][] = $dto;
            }
            return $ret;
        }

        public function getLastDaysPurchases($days = 10) {
            $pos = PurchaseOrderManager::getInstance()->selectAdvance('id', 
                    ['cancelled', '=', 0, 'AND','ABS(DATEDIFF(DATE(`order_date`), DATE(now())))', '<=', $days], null, null, null, null, true);
            if (empty($pos))
            {
                return [];
            }
            $poIdsSql = '(' . implode(',', array_keys($pos)) . ')';
            $pols = $this->selectAdvance('product_id', ['purchase_order_id', 'in', $poIdsSql]);
            $ret = [];
            foreach ($pols as $pol) {
                $ret[] = $pol->getProductId(); 
            }
            return $ret;
        }

        public function getProductsPurchaseOrders($productIds, $partnerId = false) {
            if (empty($productIds)) {
                return [];
            }
            $poLines = $this->mapper->getNonCancelledProductsPurchaseOrders($productIds);
            $poIdsMappedByProductId = [];
            $allPurchaseOrdersIds = [];
            $purchaseOrdersProductPrice = [];
            foreach ($poLines as $pol) {
                $poIdsMappedByProductId [$pol->getProductId()][] = $pol->getPurchaseOrderId();
                $allPurchaseOrdersIds[] = intval($pol->getPurchaseOrderId());
                $purchaseOrdersProductPrice[intval($pol->getPurchaseOrderId())][$pol->getProductId()] = $pol->getUnitPrice();
            }
            $allPurchaseOrdersIds = array_unique($allPurchaseOrdersIds);
            if (!empty($allPurchaseOrdersIds)) {
                $idsSql = '(' . implode(',', $allPurchaseOrdersIds) . ')';
                $where = ['id', 'IN', $idsSql];
                if ($partnerId > 0) {
                    $where = array_merge($where, ['AND', 'partner_id', '=', $partnerId]);
                }
                $pos = PurchaseOrderManager::getInstance()->selectAdvance('*', $where, null, null, null, null, true);
            }


            foreach ($poIdsMappedByProductId as &$r) {
                $r = array_unique($r);
            }
            $ret = [];            
            foreach ($productIds as $productId) {
                if (!array_key_exists($productId, $poIdsMappedByProductId)) {
                    $poIdsMappedByProductId[$productId] = [];
                }
                $ret[$productId] = [];
                foreach ($poIdsMappedByProductId[$productId] as $poId) {
                    if (array_key_exists($poId, $pos)) {
                        $ret[$productId][] = $pos[$poId];
                        $pos[$poId]->setProductPrice($productId, $purchaseOrdersProductPrice[$poId][$productId]);
                    }
                }
            }
            return $ret;
        }
        
        public function getAllProductCountInNonCancelledPurchaseOrders($partnerId = false, $excludePartnerIdsStr = '0') {
            return $this->mapper->getAllProductCountInNonCancelledPurchaseOrders($partnerId, $excludePartnerIdsStr);
        }

        public function getAllProductPriceInNonCancelledPurchaseOrders() {
            $rows = $this->mapper->getAllProductPriceInNonCancelledPurchaseOrders();
            $ret = [];
            foreach ($rows as $productId => $productPriceInMainCurrency) {
                if (!isset($ret[$productId])) {
                    $ret[$productId] = 0;
                }
                $ret[$productId] += $productPriceInMainCurrency;
            }
            return $ret;
        }

    }

}
