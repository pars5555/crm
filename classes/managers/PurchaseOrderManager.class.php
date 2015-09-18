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

        public function getPartnerPurchaseOrders($partnerId) {
            return $this->getPurchaseOrdersFull(['partner_id', '=', $partnerId]);
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

        public function getPurchaseOrdersFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            $opDtos = $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit);
            $partnerIds = array();
            $purchaseOrderIds = array();
            foreach ($opDtos as $poDto) {
                $partnerIds[] = intval($poDto->getPartnerId());
                $purchaseOrderIds[] = intval($poDto->getId());
            }
            $partnerIds = array_unique($partnerIds);
            $purchaseOrderIds = array_unique($purchaseOrderIds);
            $partnerDtos = PartnerManager::getInstance()->selectByPKs($partnerIds, true);
            $purchaseOrderLinesDtos = [];
            if (!empty($purchaseOrderIds)) {
                $purchaseOrderLinesDtos = PurchaseOrderLineManager::getInstance()->getPurchaseOrderLinesFull(['purchase_order_id', 'in', '(' . implode(',', $purchaseOrderIds) . ')']);
                $amount = [];
                foreach ($purchaseOrderLinesDtos as $purchaseOrderLine) {
                    $purchaseOrderId = intval($purchaseOrderLine->getPurchaseOrderId());
                    $currencyId = intval($purchaseOrderLine->getCurrencyId());
                    $unitPrice = floatval($purchaseOrderLine->getUnitPrice());
                    $quantity = floatval($purchaseOrderLine->getQuantity());
                    if (!array_key_exists($purchaseOrderId, $amount)) {
                        $amount[$purchaseOrderId] = [];
                    }
                    if (!array_key_exists($currencyId, $amount[$purchaseOrderId])) {
                        $amount[$purchaseOrderId][$currencyId] = 0;
                    }

                    $amount[$purchaseOrderId][$currencyId] += $unitPrice * $quantity;
                }
            }
            if (!empty($purchaseOrderIds)) {
                $purchaseOrderLinesDtosMappedByPurchaseOrderId = $this->mapPurchaseOrderLinesByPurchaseOrderId($purchaseOrderLinesDtos);
                foreach ($purchaseOrderIds as $purchaseOrderId) {
                    if (!array_key_exists($purchaseOrderId, $purchaseOrderLinesDtosMappedByPurchaseOrderId)) {
                        $purchaseOrderLinesDtosMappedByPurchaseOrderId[$purchaseOrderId] = [];
                    }
                    if (!array_key_exists($purchaseOrderId, $amount)) {
                        $amount[$purchaseOrderId] = [];
                    }
                }
            }
            foreach ($opDtos as $poDto) {
                $purchaseOrderId = intval($poDto->getId());
                $poDto->setPartnerDto($partnerDtos[intval($poDto->getPartnerId())]);
                $poDto->setPurchaseOrderLinesDtos($purchaseOrderLinesDtosMappedByPurchaseOrderId[$purchaseOrderId]);
                $poDto->setTotalAmount($amount[$purchaseOrderId]);
            }
            return $opDtos;
        }

        public function updateAllOrderLines() {
            $allPurchaseOrders = $this->getPurchaseOrdersFull(['cancelled', '=', 0], 'order_date', 'ASC');
            foreach ($allPurchaseOrders as $purchaseOrder) {
                $purchaseOrderLinesDtos = $purchaseOrder->getPurchaseOrderLinesDtos();
                foreach ($purchaseOrderLinesDtos as $purchaseOrderLinesDto) {
                    PurchaseOrderLineManager::getInstance()->updatePurchaseOrderLine($purchaseOrder->getId(), $purchaseOrderLinesDto->getId(), $purchaseOrderLinesDto->getProductId(), $purchaseOrderLinesDto->getQuantity(), $purchaseOrderLinesDto->getUnitPrice(), $purchaseOrderLinesDto->getCurrencyId());
                }
            }
            return true;
        }
        
        public function updateAllLinesCurrencyRates() {
            $allPurchaseOrders = $this->getPurchaseOrdersFull();
            foreach ($allPurchaseOrders as $purchaseOrder) {
                $purchaseOrderLinesDtos = $purchaseOrder->getPurchaseOrderLinesDtos();
                foreach ($purchaseOrderLinesDtos as $purchaseOrderLinesDto) {
                    $orderDate = $purchaseOrder->getOrderDate();
                    $currencyId = $purchaseOrderLinesDto->getCurrencyId();
                    $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
                    $purchaseOrderLinesDto->setCurrencyRate($rate);
                    PurchaseOrderLineManager::getInstance()->updateByPK($purchaseOrderLinesDto);
                }
            }
            return count($allPurchaseOrders);
        }

        public function setPaid($id, $paid) {
            $purchaseOrderDto = $this->selectByPK($id);
            if (isset($purchaseOrderDto)) {
                $purchaseOrderDto->setPaid($paid);
                if ($paid == 1) {
                    $purchaseOrderDto->setPaidAt(date('Y-m-d H:i:s'));
                }
                $this->updateByPk($purchaseOrderDto);
                return true;
            }
            return false;
        }

        public function cancelPurchaseOrder($id, $note) {
            $purchaseOrderDto = $this->selectByPK($id);
            if (isset($purchaseOrderDto)) {
                $purchaseOrderDto->setCancelled(1);
                $purchaseOrderDto->setCancelNote($note);
                $this->updateByPk($purchaseOrderDto);
                return true;
            }
            return false;
        }

        public function restorePurchaseOrder($id) {
            $purchaseOrderDto = $this->selectByPK($id);
            if (isset($purchaseOrderDto)) {
                $purchaseOrderDto->setCancelled(0);
                $purchaseOrderDto->setCancelNote("");
                $this->updateByPk($purchaseOrderDto);
                return true;
            }
            return false;
        }

        public function createPurchaseOrder($partnerId, $date, $paymentDeadlineDate, $note) {
            $dto = $this->createDto();
            $dto->setPartnerId($partnerId);
            $dto->setOrderDate($date);
            $dto->setPaymentDeadline($paymentDeadlineDate);
            $dto->setNote($note);
            return $this->insertDto($dto);
        }

        public function updatePurchaseOrder($id, $partnerId, $date, $paymentDeadlineDate, $note) {
            $dto = $this->selectByPK($id);
            if ($dto) {
                $dto->setPartnerId($partnerId);
                $dto->setOrderDate($date);
                $dto->setPaymentDeadline($paymentDeadlineDate);
                $dto->setNote($note);
                return $this->updateByPk($dto);
            }
        }

        private function mapPurchaseOrderLinesByPurchaseOrderId($purchaseOrderLinesDtos) {
            $ret = [];
            foreach ($purchaseOrderLinesDtos as $purchaseOrderLinesDto) {
                $poId = $purchaseOrderLinesDto->getPurchaseOrderId();
                $ret[$poId][] = $purchaseOrderLinesDto;
            }
            return $ret;
        }

    }

}
