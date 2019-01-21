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

    use crm\dal\mappers\PreorderLineMapper;

    class PreorderLineManager extends AdvancedAbstractManager {

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
                self::$instance = new PreorderLineManager(PreorderLineMapper::getInstance());
            }
            return self::$instance;
        }

        public function getPreorderLinesFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
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

        public function deleteWhereIdNotIdIds($preorderId, $ids) {
            $dtos = $this->selectByField('preorder_id', $preorderId);
            foreach ($dtos as $dto) {
                if (!in_array($dto->getId(), $ids)) {
                    $this->deleteByPK($dto->getId());
                }
            }
            return true;
        }

        public function updatePreorderLine($preorderId, $id, $productId, $quantity, $unitPrice, $currencyId) {
            $dto = $this->selectByPk($id);
            $dto->setProductId($productId);
            $dto->setQuantity($quantity);
            $dto->setUnitPrice($unitPrice);
            $dto->setCurrencyId($currencyId);
            $po = PreorderManager::getInstance()->selectByPk($preorderId);
            $orderDate = $po->getOrderDate();
            $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
            $dto->setCurrencyRate($rate);
            ProductManager::getInstance()->updateProductCostForOneUnit($productId);
            PartnerManager::getInstance()->setPartnerHidden($po->getPartnerId(), 0);
            return $this->updateByPk($dto);
        }

        public function createPreorderLine($preorderId, $productId, $quantity, $unitPrice, $currencyId) {
            $dto = $this->createDto();
            $dto->setPreorderId($preorderId);
            $dto->setProductId($productId);
            $dto->setQuantity($quantity);
            $dto->setUnitPrice($unitPrice);
            $dto->setCurrencyId($currencyId);
            $po = PreorderManager::getInstance()->selectByPk($preorderId);
            $orderDate = $po->getOrderDate();
            $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
            $dto->setCurrencyRate($rate);
            $ret = $this->insertDto($dto);
            ProductManager::getInstance()->updateProductCostForOneUnit($productId);
            PartnerManager::getInstance()->setPartnerHidden($po->getPartnerId(), 0);
            return $ret;
        }

        public function getProductCountInNonCancelledPreorders($productId) {
            return $this->mapper->getProductCountInNonCancelledPreorders($productId);
        }

        public function getNonCancelledProductPreorders($productId, $date = null, $excludePartnerWarehase = '0') {
            return $this->mapper->getNonCancelledProductPreorders($productId, $date, $excludePartnerWarehase);
        }

        public function getNonCancelledProductsPreorders($productIds) {
            if (empty($productIds)) {
                return [];
            }
            $dtos = $this->mapper->getNonCancelledProductsPreorders($productIds);
            $ret = [];
            foreach ($dtos as $dto) {
                $ret [$dto->getProductId()][] = $dto;
            }
            return $ret;
        }

        public function getLastDaysPreorders($days = 10) {
            $pos = PreorderManager::getInstance()->selectAdvance('id', 
                    ['cancelled', '=', 0, 'AND','ABS(DATEDIFF(DATE(`order_date`), DATE(now())))', '<=', $days], null, null, null, null, true);
            if (empty($pos))
            {
                return [];
            }
            $poIdsSql = '(' . implode(',', array_keys($pos)) . ')';
            $pols = $this->selectAdvance('product_id', ['preorder_id', 'in', $poIdsSql]);
            $ret = [];
            foreach ($pols as $pol) {
                $ret[] = $pol->getProductId(); 
            }
            return $ret;
        }

        public function getProductsPreorders($productIds, $partnerId = false) {
            if (empty($productIds)) {
                return [];
            }
            $poLines = $this->mapper->getNonCancelledProductsPreorders($productIds);
            $poIdsMappedByProductId = [];
            $allPreordersIds = [];
            foreach ($poLines as $po) {
                $poIdsMappedByProductId [$po->getProductId()][] = $po->getPreorderId();
                $allPreordersIds[] = intval($po->getPreorderId());
            }
            $allPreordersIds = array_unique($allPreordersIds);
            if (!empty($allPreordersIds)) {
                $idsSql = '(' . implode(',', $allPreordersIds) . ')';
                $where = ['id', 'IN', $idsSql];
                if ($partnerId > 0) {
                    $where = array_merge($where, ['AND', 'partner_id', '=', $partnerId]);
                }
                $pos = PreorderManager::getInstance()->selectAdvance('*', $where, null, null, null, null, true);
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
                    }
                }
            }
            return $ret;
        }

        public function getAllProductCountInNonCancelledPreorders($partnerId = false, $excludePartnerIdsStr = '0') {
            return $this->mapper->getAllProductCountInNonCancelledPreorders($partnerId, $excludePartnerIdsStr);
        }

        public function getAllProductPriceInNonCancelledPreorders() {
            $rows = $this->mapper->getAllProductPriceInNonCancelledPreorders();
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
