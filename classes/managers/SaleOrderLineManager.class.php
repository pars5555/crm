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

        public function createSaleOrderLine($saleOrderId, $productId, $quantity, $unitPrice, $currencyId) {
            $unitPrice = floatval($unitPrice);
            $quantity = floatval($quantity);
            $dto = $this->createDto();
            $dto->setSaleOrderId($saleOrderId);
            $dto->setProductId($productId);
            $dto->setQuantity($quantity);
            $dto->setUnitPrice($unitPrice);
            $dto->setCurrencyId($currencyId);
            $productUnitCostInBaseCurrency = floatval(ProductManager::getInstance()->calculateProductCost($productId));
            $dto->setUnitCost($productUnitCostInBaseCurrency);
            $saleOrderDto = SaleOrderManager::getInstance()->selectByPK($saleOrderId);
            $orderDate = $saleOrderDto->getOrderDate();
            $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
            $dto->setCurrencyRate($rate);
            if ($saleOrderDto->getNonProfit() == 0) {
                $profit = $quantity * ($unitPrice * $rate - $productUnitCostInBaseCurrency);
                $dto->setTotalProfit($profit);
            } else {
                $dto->setTotalProfit(0);
            }
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

        public function getProductCountInNonCancelledSaleOrders($productId) {
            return $this->mapper->getProductCountInNonCancelledSaleOrders($productId);
        }

        public function getProductsCountInNonCancelledSaleOrders($productId) {
            $rows = $this->mapper->getProductsCountInNonCancelledSaleOrders($productId);
            $ret = [];
            foreach ($rows as $row) {
                $ret[$row->product_id] = $row->product_qty;
            }
            return $ret;
        }

        public function getAllProductCountInNonCancelledSaleOrders() {
            return $this->mapper->getAllProductCountInNonCancelledSaleOrders();
        }

        public function getTotalProfitSumInNonCancelledSaleOrders($startDate, $endDate) {
            return $this->mapper->getTotalProfitSumInNonCancelledSaleOrders($startDate, $endDate);
        }

        public function getAllNonCancelledExpenseSaleOrders($startDate, $endDate) {
            return $this->mapper->getAllNonCancelledExpenseSaleOrders($startDate, $endDate);
        }

        public function getProductsSaleOrders($productIds) {
            $soLines = $this->mapper->getNonCancelledProductsSaleOrders($productIds);
            $soIdsMappedByProductId = [];
            $allSaleOrdersIds = [];
            foreach ($soLines as $sol) {
                $soIdsMappedByProductId [$sol->getProductId()][] = $sol->getSaleOrderId();
                $allSaleOrdersIds[] = intval($sol->getSaleOrderId());
            }
            $allSaleOrdersIds = array_unique($allSaleOrdersIds);
            $idsSql = '(' . implode(',', $allSaleOrdersIds) . ')';
            $sos = SaleOrderManager::getInstance()->selectAdvance('*', ['id', 'IN', $idsSql], null, null, null, null, true);


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
                    $ret[$productId][] = $sos[$soId];
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

    }

}
