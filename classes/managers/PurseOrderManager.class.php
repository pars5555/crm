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

    use crm\dal\mappers\PurseOrderMapper;

    class PurseOrderManager extends AdvancedAbstractManager {

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
                self::$instance = new PurseOrderManager(PurseOrderMapper::getInstance());
            }
            return self::$instance;
        }

        public function getOrders($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            $rows = $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit, true);
            $ids = [];
            foreach ($rows as $row) {
                $ids[] = $row->getId();
            }
            if (empty($ids)) {
                return $rows;
            }
            $idsSql = '(' . implode(",", $ids) . ')';
            $historyRows = PurseOrderHistoryManager::getInstance()->selectAdvance('*', ['order_id', 'in', $idsSql], 'id');
            foreach ($historyRows as $historyRow) {
                $rows[$historyRow->getOrderId()]->addHistory($historyRow);
            }
            return $rows;
        }

        public function archiveIfnotExists($orderNumbers) {
            $orderNumbersSql = "('" . implode("','", $orderNumbers) . "')";
            $rows = $this->selectAdvance('id',['`order_number`', 'not in', $orderNumbersSql]);
            foreach ($rows as $row) {
                $this->updateField($row->getId(), 'hidden', 1);
            }
        }

        public function insertOrUpdateOrder($orderNumber, $productTitle, $orderStatus, $imgName, $amazonOrderNumber, $purseTotal, $buyerName) {
            $dtos = $this->selectByField('order_number', $orderNumber);
            if (!empty($dtos)) {
                $dto = $dtos[0];
                $changed = $this->addHistoryIfOrderChanged($dto, $orderStatus, $amazonOrderNumber, $purseTotal, $buyerName);
                $dto->setStatus($orderStatus);
                $dto->setAmazonOrderNumber($amazonOrderNumber);
                $dto->setPurseTotal($purseTotal);
                $dto->setBuyerName($buyerName);
                if ($changed) {
                    $dto->setUpdatedAt(date('Y-m-d H:i:s'));
                }
                $this->updateByPk($dto);
                if ($changed) {
                    return true;
                }
            } else {
                $dto = $this->createDto();
                $dto->setOrderNumber($orderNumber);
                $dto->setProductName($productTitle);
                $dto->setStatus($orderStatus);
                $dto->setImageUrl($imgName);
                $dto->setAmazonOrderNumber($amazonOrderNumber);
                $dto->setBuyerName($buyerName);
                $dto->setPurseTotal($purseTotal);
                $dto->setUpdatedAt(date('Y-m-d H:i:s'));
                $dto->setCreatedAt(date('Y-m-d H:i:s'));
                return $this->insertDto($dto);
            }
        }

        private function addHistoryIfOrderChanged($dto, $orderStatus, $amazonOrderNumber, $purseTotal, $buyerName) {
            if ($dto->getStatus() !== $orderStatus || $dto->getAmazonOrderNumber() !== $amazonOrderNumber ||
                    intval($dto->getPurseTotal()) !== intval($purseTotal) || $dto->getBuyerName() !== $buyerName) {
                PurseOrderHistoryManager::getInstance()->addRow($dto->getId(), $orderStatus, $amazonOrderNumber, $purseTotal);
                return true;
            }
            return false;
        }

    }

}
