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

    use crm\dal\mappers\RecipientOrderLineMapper;

    class RecipientOrderLineManager extends AdvancedAbstractManager {

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
                self::$instance = new RecipientOrderLineManager(RecipientOrderLineMapper::getInstance());
            }
            return self::$instance;
        }

        public function getRecipientOrderLinesFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
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

        public function deleteWhereIdNotIdIds($recipientOrderId, $ids) {
            $dtos = $this->selectByField('recipient_order_id', $recipientOrderId);
            foreach ($dtos as $dto) {
                if (!in_array($dto->getId(), $ids)) {
                    $this->deleteByPK($dto->getId());
                }
            }
            return true;
        }

        public function updateRecipientOrderLine($recipientOrderId, $id, $productId, $quantity, $unitPrice, $currencyId) {
            $dto = $this->selectByPK($id);
            $dto->setProductId($productId);
            $dto->setQuantity($quantity);
            $dto->setUnitPrice($unitPrice);
            $dto->setCurrencyId($currencyId);
            $orderDate = RecipientOrderManager::getInstance()->selectByPK($recipientOrderId)->getOrderDate();
            $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
            $dto->setCurrencyRate($rate);
            ProductManager::getInstance()->updateProductCostForOneUnit($productId);
            return $this->updateByPk($dto);
        }

        public function createRecipientOrderLine($recipientOrderId, $productId, $quantity, $unitPrice, $currencyId) {
            $dto = $this->createDto();
            $dto->setRecipientOrderId($recipientOrderId);
            $dto->setProductId($productId);
            $dto->setQuantity($quantity);
            $dto->setUnitPrice($unitPrice);
            $dto->setCurrencyId($currencyId);
            $orderDate = RecipientOrderManager::getInstance()->selectByPK($recipientOrderId)->getOrderDate();
            $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
            $dto->setCurrencyRate($rate);
            ProductManager::getInstance()->updateProductCostForOneUnit($productId);
            return $this->insertDto($dto);
        }

        public function getProductCountInNonCancelledRecipientOrders($productId) {
            return $this->mapper->getProductCountInNonCancelledRecipientOrders($productId);
        }

        public function getNonCancelledProductRecipientOrders($productId, $date = null) {
            return $this->mapper->getNonCancelledProductRecipientOrders($productId, $date);
        }

        public function getNonCancelledProductsRecipientOrders($productIds) {
            $dtos = $this->mapper->getNonCancelledProductsRecipientOrders($productIds);
            $ret = [];
            foreach ($dtos as $dto) {
                $ret [$dto->getProductId()][] = $dto;
            }
            return $ret;
        }

        public function getProductsRecipientOrders($productIds) {
            $poLines = $this->mapper->getNonCancelledProductsRecipientOrders($productIds);
            $poIdsMappedByProductId = [];
            $allRecipientOrdersIds = [];
            foreach ($poLines as $po) {
                $poIdsMappedByProductId [$po->getProductId()][] = $po->getRecipientOrderId();
                $allRecipientOrdersIds[] = intval($po->getRecipientOrderId());
            }
            $allRecipientOrdersIds = array_unique($allRecipientOrdersIds);
            if (!empty($allRecipientOrdersIds)) {
                $idsSql = '(' . implode(',', $allRecipientOrdersIds) . ')';
                $pos = RecipientOrderManager::getInstance()->selectAdvance('*', ['id', 'IN', $idsSql], null, null, null, null, true);
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
                    if (array_key_exists($poId, $pos)){
                    $ret[$productId][] = $pos[$poId];
                    }
                    
                }
            }
            return $ret;
        }

        public function getAllProductCountInNonCancelledRecipientOrders() {
            return $this->mapper->getAllProductCountInNonCancelledRecipientOrders();
        }

    }

}
