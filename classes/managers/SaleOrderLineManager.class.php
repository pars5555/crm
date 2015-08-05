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
            $dto = $this->createDto();
            $dto->setSaleOrderId($saleOrderId);
            $dto->setProductId($productId);
            $dto->setQuantity($quantity);
            $dto->setUnitPrice($unitPrice);
            $dto->setCurrencyId($currencyId);
            $productUnitCost = floatval(ProductManager::getInstance()->calculateProductCost($productId));
            $dto->setUnitCost($productUnitCost);
            $orderDate = SaleOrderManager::getInstance()->selectByPK($saleOrderId)->getOrderDate();
            $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
            $profit = $quantity * ($unitPrice - $productUnitCost) * $rate;
            $dto->setTotalProfit($profit);
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

        public function getAllProductCountInNonCancelledSaleOrders() {
            return $this->mapper->getAllProductCountInNonCancelledSaleOrders();
        }

    }

}
