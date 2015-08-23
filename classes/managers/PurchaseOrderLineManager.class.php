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
                $row->setProductDto($productDtos[$row->getProductId()]);
                $row->setCurrencyDto($currencyDtos[$row->getCurrencyId()]);
            }
            return $rows;
        }

        public function createPurchaseOrderLine($purchaseOrderId, $productId, $quantity, $unitPrice, $currencyId) {
            $dto = $this->createDto();
            $dto->setPurchaseOrderId($purchaseOrderId);
            $dto->setProductId($productId);
            $dto->setQuantity($quantity);
            $dto->setUnitPrice($unitPrice);
            $dto->setCurrencyId($currencyId);
            $orderDate = PurchaseOrderManager::getInstance()->selectByPK($purchaseOrderId)->getOrderDate();
            $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
            $dto->setCurrencyRate($rate);

            return $this->insertDto($dto);
        }

        public function getProductCountInNonCancelledPurchaseOrders($productId) {
            return $this->mapper->getProductCountInNonCancelledPurchaseOrders($productId);
        }

        public function getNonCancelledProductPurchaseOrders($productId) {
            return $this->mapper->getNonCancelledProductPurchaseOrders($productId);
        }

        public function getNonCancelledProductsPurchaseOrders($productIds) {
            $dtos = $this->mapper->getNonCancelledProductsPurchaseOrders($productIds);
            $ret = [];
            foreach ($dtos as $dto) {
                $ret [$dto->getProductId()][] = $dto;
            }
            return $ret;
        }
        
        public function getProductsPurchaseOrders($productIds) {
            $dtos = $this->mapper->getNonCancelledProductsPurchaseOrders($productIds);
            $ret = [];
            foreach ($dtos as $dto) {
                $ret [$dto->getProductId()][] = $dto->getPurchaseOrderId();
            }
            foreach ($ret as &$r) {
                $r = array_unique($r);
            }
            return $ret;
        }

        public function getAllProductCountInNonCancelledPurchaseOrders() {
            return $this->mapper->getAllProductCountInNonCancelledPurchaseOrders();
        }

        public function getAllNonCancelledExpensePurchaseOrders($startDate, $endDate) {
            return $this->mapper->getAllNonCancelledExpensePurchaseOrders($startDate, $endDate);
        }

    }

}
