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

    use crm\dal\mappers\PurchaseOrderMapper;

    class PurchaseOrderManager extends AdvancedAbstractManager {

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
                self::$instance = new PurchaseOrderManager(PurchaseOrderMapper::getInstance());
            }
            return self::$instance;
        }

        public function getPartnersPurchaseOrders($partnerIds) {
            $rows = $this->getPurchaseOrdersFull(['partner_id', 'in', '(' . implode(',', $partnerIds) . ')']);
            $ret = array();
            foreach ($partnerIds as $partnerId) {
                $ret[$partnerId] = [];
            }
            foreach ($rows as $row) {
                $ret [intval($row->getPartnerId())][] = $row;
            }
            return $ret;
        }

        public function getPurchaseOrdersFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = 0, $limit = 10000) {
            $rows = $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit);
            $partnerIds = array();
            $purchaseOrderIds = array();
            foreach ($rows as $row) {
                $partnerIds[] = $row->getPartnerId();
                $purchaseOrderIds[] = $row->getId();
            }
            $partnerIds = array_unique($partnerIds);
            $purchaseOrderIds = array_unique($purchaseOrderIds);
            $partnerDtos = PartnerManager::getInstance()->selectByPKs($partnerIds, true);
            $purchaseOrderLinesDtos = [];
            if (!empty($purchaseOrderIds)) {
                $purchaseOrderLinesDtos = PurchaseOrderLineManager::getInstance()->getPurchaseOrderLinesFull(['purchase_order_id', 'in', '(' . implode(',', $purchaseOrderIds) . ')']);
            }
            $purchaseOrderLinesDtosMappedByPurchaseOrderId = $this->mapPurchaseOrderLinesByPurchaseOrderId($purchaseOrderLinesDtos);
            foreach ($rows as $row) {
                $row->setPartnerDto($partnerDtos[$row->getPartnerId()]);
                $row->setPurchaseOrderLinesDtos($purchaseOrderLinesDtosMappedByPurchaseOrderId[$row->getId()]);
            }
            return $rows;
        }

        private function mapPurchaseOrderLinesByPurchaseOrderId($purchaseOrderLinesDtos) {
            $ret = [];
            foreach ($purchaseOrderLinesDtos as $purchaseOrderLinesDto) {
                $soId = $purchaseOrderLinesDto->getPurchaseOrderId();
                $ret[$soId][] = $purchaseOrderLinesDto;
            }
            return $ret;
        }

    }

}
