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

    use crm\dal\mappers\RecipientOrderMapper;

    class RecipientOrderManager extends AdvancedAbstractManager {

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
                self::$instance = new RecipientOrderManager(RecipientOrderMapper::getInstance());
            }
            return self::$instance;
        }

        public function getRecipientOrders($recipientId) {
            return $this->getRecipientOrdersFull(['recipient_id', '=', $recipientId]);
        }

        public function getRecipientsOrders($recipientIds) {
            $rows = $this->getRecipientOrdersFull(['recipient_id', 'in', '(' . implode(',', $recipientIds) . ')']);
            $ret = array();
            foreach ($recipientIds as $recipientId) {
                $ret[$recipientId] = [];
            }
            foreach ($rows as $row) {
                $ret [intval($row->getRecipientId())][] = $row;
            }
            return $ret;
        }

        public function getRecipientOrdersFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            $roDtos = $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit);
            $recipientIds = array();
            $recipientOrderIds = array();
            foreach ($roDtos as $roDto) {
                $recipientIds[] = intval($roDto->getRecipientId());
                $recipientOrderIds[] = intval($roDto->getId());
            }
            $recipientIds = array_unique($recipientIds);
            $recipientOrderIds = array_unique($recipientOrderIds);
            $recipientDtos = RecipientManager::getInstance()->selectByPKs($recipientIds, true);
            $recipientOrderLinesDtos = [];
            if (!empty($recipientOrderIds)) {
                $recipientOrderLinesDtos = RecipientOrderLineManager::getInstance()->getRecipientOrderLinesFull(['recipient_order_id', 'in', '(' . implode(',', $recipientOrderIds) . ')']);
                $amount = [];
                foreach ($recipientOrderLinesDtos as $recipientOrderLine) {
                    $recipientOrderId = intval($recipientOrderLine->getRecipientOrderId());
                    $currencyId = intval($recipientOrderLine->getCurrencyId());
                    $unitPrice = floatval($recipientOrderLine->getUnitPrice());
                    $quantity = floatval($recipientOrderLine->getQuantity());
                    if (!array_key_exists($recipientOrderId, $amount)) {
                        $amount[$recipientOrderId] = [];
                    }
                    if (!array_key_exists($currencyId, $amount[$recipientOrderId])) {
                        $amount[$recipientOrderId][$currencyId] = 0;
                    }

                    $amount[$recipientOrderId][$currencyId] += $unitPrice * $quantity;
                }
            }
            if (!empty($recipientOrderIds)) {
                $recipientOrderLinesDtosMappedByRecipientOrderId = $this->mapRecipientOrderLinesByRecipientOrderId($recipientOrderLinesDtos);
                foreach ($recipientOrderIds as $recipientOrderId) {
                    if (!array_key_exists($recipientOrderId, $recipientOrderLinesDtosMappedByRecipientOrderId)) {
                        $recipientOrderLinesDtosMappedByRecipientOrderId[$recipientOrderId] = [];
                    }
                    if (!array_key_exists($recipientOrderId, $amount)) {
                        $amount[$recipientOrderId] = [];
                    }
                }
            }
            foreach ($roDtos as $roDto) {
                $recipientOrderId = intval($roDto->getId());
                $roDto->setRecipientDto($recipientDtos[intval($roDto->getRecipientId())]);
                $roDto->setRecipientOrderLinesDtos($recipientOrderLinesDtosMappedByRecipientOrderId[$recipientOrderId]);
                $roDto->setTotalAmount($amount[$recipientOrderId]);
            }
            return $roDtos;
        }

        public function getProductsIdsInOrder($recipientOrderId) {
            $poLines = RecipientOrderLineManager::getInstance()->selectAdvance('*', ['recipient_order_id', '=', $recipientOrderId]);
            $productIds = [];
            foreach ($poLines as $poLine) {
                $productIds [] = $poLine->getProductId();
            }
            return $productIds;
        }

        public function updateAllOrderLines() {
            $allRecipientOrders = $this->getRecipientOrdersFull(['cancelled', '=', 0], 'order_date', 'ASC');
            foreach ($allRecipientOrders as $recipientOrder) {
                $recipientOrderLinesDtos = $recipientOrder->getRecipientOrderLinesDtos();
                foreach ($recipientOrderLinesDtos as $recipientOrderLinesDto) {
                    RecipientOrderLineManager::getInstance()->updateRecipientOrderLine($recipientOrder->getId(), $recipientOrderLinesDto->getId(), $recipientOrderLinesDto->getProductId(), $recipientOrderLinesDto->getQuantity(), $recipientOrderLinesDto->getUnitPrice(), $recipientOrderLinesDto->getCurrencyId());
                }
            }
            return true;
        }

        public function updateAllLinesCurrencyRates() {
            $allRecipientOrders = $this->getRecipientOrdersFull();
            foreach ($allRecipientOrders as $recipientOrder) {
                $recipientOrderLinesDtos = $recipientOrder->getRecipientOrderLinesDtos();
                foreach ($recipientOrderLinesDtos as $recipientOrderLinesDto) {
                    $orderDate = $recipientOrder->getOrderDate();
                    $currencyId = $recipientOrderLinesDto->getCurrencyId();
                    $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
                    $recipientOrderLinesDto->setCurrencyRate($rate);
                    RecipientOrderLineManager::getInstance()->updateByPK($recipientOrderLinesDto);
                }
            }
            return count($allRecipientOrders);
        }

        public function delete($id) {
            RecipientOrderLineManager::getInstance()->deleteByField('recipient_order_id', $id);
            return $this->deleteByPK($id);
        }

        public function setPaid($id, $paid) {
            $recipientOrderDto = $this->selectByPk($id);
            if (isset($recipientOrderDto)) {
                $recipientOrderDto->setPaid($paid);
                if ($paid == 1) {
                    $recipientOrderDto->setPaidAt(date('Y-m-d H:i:s'));
                }
                $this->updateByPk($recipientOrderDto);
                return true;
            }
            return false;
        }

        public function cancelRecipientOrder($id, $note) {
            $recipientOrderDto = $this->selectByPk($id);
            if (isset($recipientOrderDto)) {
                $recipientOrderDto->setCancelled(1);
                $recipientOrderDto->setCancelNote($note);
                $this->updateByPk($recipientOrderDto);
                return true;
            }
            return false;
        }

        public function restoreRecipientOrder($id) {
            $recipientOrderDto = $this->selectByPk($id);
            if (isset($recipientOrderDto)) {
                $recipientOrderDto->setCancelled(0);
                $recipientOrderDto->setCancelNote("");
                $this->updateByPk($recipientOrderDto);
                return true;
            }
            return false;
        }

        public function createRecipientOrder($recipientId, $date,  $note) {
            $dto = $this->createDto();
            $dto->setRecipientId($recipientId);
            $dto->setOrderDate($date);
            $dto->setNote($note);
            return $this->insertDto($dto);
        }

        public function updateRecipientOrder($id, $recipientId, $date, $note) {
            $dto = $this->selectByPk($id);
            if ($dto) {
                $dto->setRecipientId($recipientId);
                $dto->setOrderDate($date);
                $dto->setNote($note);
                return $this->updateByPk($dto);
            }
        }

        private function mapRecipientOrderLinesByRecipientOrderId($recipientOrderLinesDtos) {
            $ret = [];
            foreach ($recipientOrderLinesDtos as $recipientOrderLinesDto) {
                $poId = $recipientOrderLinesDto->getRecipientOrderId();
                $ret[$poId][] = $recipientOrderLinesDto;
            }
            return $ret;
        }

    }

}
