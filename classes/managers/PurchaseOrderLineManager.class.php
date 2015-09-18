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
            $dto = $this->selectByPK($id);
            $dto->setProductId($productId);
            $dto->setQuantity($quantity);
            $dto->setUnitPrice($unitPrice);
            $dto->setCurrencyId($currencyId);
            $orderDate = PurchaseOrderManager::getInstance()->selectByPK($purchaseOrderId)->getOrderDate();
            $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
            $dto->setCurrencyRate($rate);
            ProductManager::getInstance()->updateProductCostForOneUnit($productId);
            return $this->updateByPk($dto);
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
            ProductManager::getInstance()->updateProductCostForOneUnit($productId);
            return $this->insertDto($dto);
        }

        public function getProductCountInNonCancelledPurchaseOrders($productId) {
            return $this->mapper->getProductCountInNonCancelledPurchaseOrders($productId);
        }

        public function getNonCancelledProductPurchaseOrders($productId, $date = null) {
            return $this->mapper->getNonCancelledProductPurchaseOrders($productId, $date);
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
            $poLines = $this->mapper->getNonCancelledProductsPurchaseOrders($productIds);
            $poIdsMappedByProductId = [];
            $allPurchaseOrdersIds = [];
            foreach ($poLines as $po) {
                $poIdsMappedByProductId [$po->getProductId()][] = $po->getPurchaseOrderId();
                $allPurchaseOrdersIds[] = intval($po->getPurchaseOrderId());
            }
            $allPurchaseOrdersIds = array_unique($allPurchaseOrdersIds);
            $idsSql = '(' . implode(',', $allPurchaseOrdersIds) . ')';
            $pos = PurchaseOrderManager::getInstance()->selectAdvance('*', ['id', 'IN', $idsSql], null, null, null, null, true);


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
                    $ret[$productId][] = $pos[$poId];
                }
            }
            return $ret;
        }

        public function getAllProductCountInNonCancelledPurchaseOrders() {
            return $this->mapper->getAllProductCountInNonCancelledPurchaseOrders();
        }

    }

}
