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

    use crm\dal\mappers\PreorderMapper;

    class PreorderManager extends AdvancedAbstractManager {

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
                self::$instance = new PreorderManager(PreorderMapper::getInstance());
            }
            return self::$instance;
        }

        public function getPartnerPreorders($partnerId) {
            return $this->getPreordersFull(['deleted', '=', 0, 'AND', 'partner_id', '=', $partnerId]);
        }

        public function getBtcPreorders($btcOrders) {
            $btcOrderIds = [];
            foreach ($btcOrders as $btcOrder) {
                $btcOrderIds[] = $btcOrder->getId();
            }
            if (empty($btcOrderIds)) {
                return [];
            }
            $btcOrderIdsSql = '(' . implode(',', $btcOrderIds) . ')';
            $pos = $this->selectAdvance(['id', 'purse_order_id'], ['purse_order_id', 'in', $btcOrderIdsSql]);
            $ret = [];
            foreach ($pos as $po) {
                $ret[$po->getPurseOrderId()] = $po->getId();
            }
            return $ret;
        }

        public function getPartnerPreordersTotal($partnerId) {
            $where = ['cancelled', '=', 0, 'AND', 'partner_id', '=', $partnerId];
            $pos = $this->getPreordersFull($where);
            $total = [];
            foreach ($pos as $po) {
                foreach ($po->getTotalAmount() as $currencyId => $amount) {
                    if (!array_key_exists($currencyId, $total)) {
                        $total[$currencyId] = 0;
                    }
                    $total[$currencyId] += $amount;
                }
            }
            return $total;
        }

        public function getPartnersPreorders($partnerIds) {
            $rows = $this->getPreordersFull(['partner_id', 'in', '(' . implode(',', $partnerIds) . ')']);
            $ret = array();
            foreach ($partnerIds as $partnerId) {
                $ret[$partnerId] = [];
            }
            foreach ($rows as $row) {
                $ret [intval($row->getPartnerId())][] = $row;
            }
            return $ret;
        }

        public function getPreordersFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            $opDtos = $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit);
            $partnerIds = array();
            $preorderIds = array();
            foreach ($opDtos as $poDto) {
                $partnerIds[] = intval($poDto->getPartnerId());
                $preorderIds[] = intval($poDto->getId());
            }
            $partnerIds = array_unique($partnerIds);
            $preorderIds = array_unique($preorderIds);
            $partnerDtos = PartnerManager::getInstance()->selectByPKs($partnerIds, true);
            $preorderLinesDtos = [];
            if (!empty($preorderIds)) {
                $preorderLinesDtos = PreorderLineManager::getInstance()->getPreorderLinesFull(['preorder_id', 'in', '(' . implode(',', $preorderIds) . ')']);
                $amount = [];
                foreach ($preorderLinesDtos as $preorderLine) {
                    $preorderId = intval($preorderLine->getPreorderId());
                    $currencyId = intval($preorderLine->getCurrencyId());
                    $unitPrice = floatval($preorderLine->getUnitPrice());
                    $quantity = floatval($preorderLine->getQuantity());
                    if (!array_key_exists($preorderId, $amount)) {
                        $amount[$preorderId] = [];
                    }
                    if (!array_key_exists($currencyId, $amount[$preorderId])) {
                        $amount[$preorderId][$currencyId] = 0;
                    }

                    $amount[$preorderId][$currencyId] += $unitPrice * $quantity;
                }
            }
            if (!empty($preorderIds)) {
                $preorderLinesDtosMappedByPreorderId = $this->mapPreorderLinesByPreorderId($preorderLinesDtos);
                foreach ($preorderIds as $preorderId) {
                    if (!array_key_exists($preorderId, $preorderLinesDtosMappedByPreorderId)) {
                        $preorderLinesDtosMappedByPreorderId[$preorderId] = [];
                    }
                    if (!array_key_exists($preorderId, $amount)) {
                        $amount[$preorderId] = [];
                    }
                }
            }
            foreach ($opDtos as $poDto) {
                $preorderId = intval($poDto->getId());
                $poDto->setPartnerDto($partnerDtos[intval($poDto->getPartnerId())]);
                $poDto->setPreorderLinesDtos($preorderLinesDtosMappedByPreorderId[$preorderId]);
                $poDto->setTotalAmount($amount[$preorderId]);
            }
            return $opDtos;
        }

        public function getPerndingPreordersText() {
            $pendingPreordersOrderIds = $this->getPendingPreordersOrderIds();
            $btcOrders = PurseOrderManager::getInstance()->selectByPKs($pendingPreordersOrderIds);
            $cancelledMessages = [];
            foreach ($btcOrders as $btcOrder) {
                if ($btcOrder->getStatus() == 'canceled'){
                    $cancelledMessages[] = $btcOrder->getOrderNumber();
                }
            }
            $notDonePreorders = $this->selectAdvance(['purse_order_ids', 'partner_id'], ['is_done', '=', 0]);
            $preordersMesaages = [];
            foreach ($notDonePreorders as $notDonePreorder) {
                $partnerName = PartnerManager::getInstance()->getPartnerName($notDonePreorder->getPartnerId()); 
                $preordersMesaages[] = $notDonePreorder->getId(). ' '. $partnerName;
            }
            return [implode('<br>', $preordersMesaages), implode('<br>', $cancelledMessages)];
        }
        
        private function getPendingPreordersOrderIds() {
            $preorders = $this->selectAdvance(['purse_order_ids'], ['is_done', '=', 0]);
            $purseOrderIds = [];
            foreach ($preorders as $preorder) {
                $purseOrderIdsStr = $preorder->getPurseOrderIds();
                if (!empty($purseOrderIdsStr)) {
                    $purseOrderIds = array_merge($purseOrderIds, explode(',', $purseOrderIdsStr));
                }
            }
            return $purseOrderIds;
        }

        public function getProductsIdsInOrder($preorderId) {
            $poLines = PreorderLineManager::getInstance()->selectAdvance('*', ['preorder_id', '=', $preorderId]);
            $productIds = [];
            foreach ($poLines as $poLine) {
                $productIds [] = $poLine->getProductId();
            }
            return $productIds;
        }

        public function updateAllOrderLines() {
            $allPreorders = $this->getPreordersFull(['cancelled', '=', 0], 'order_date', 'ASC');
            foreach ($allPreorders as $preorder) {
                $preorderLinesDtos = $preorder->getPreorderLinesDtos();
                foreach ($preorderLinesDtos as $preorderLinesDto) {
                    PreorderLineManager::getInstance()->updatePreorderLine($preorder->getId(), $preorderLinesDto->getId(), $preorderLinesDto->getProductId(), $preorderLinesDto->getQuantity(), $preorderLinesDto->getUnitPrice(), $preorderLinesDto->getCurrencyId());
                }
            }
            return true;
        }

        public function updateAllLinesCurrencyRates() {
            $allPreorders = $this->getPreordersFull();
            foreach ($allPreorders as $preorder) {
                $preorderLinesDtos = $preorder->getPreorderLinesDtos();
                foreach ($preorderLinesDtos as $preorderLinesDto) {
                    $orderDate = $preorder->getOrderDate();
                    $currencyId = $preorderLinesDto->getCurrencyId();
                    $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
                    $preorderLinesDto->setCurrencyRate($rate);
                    PreorderLineManager::getInstance()->updateByPK($preorderLinesDto);
                }
            }
            return count($allPreorders);
        }

        public function delete($id) {
            PreorderLineManager::getInstance()->deleteByField('preorder_id', $id);
            return $this->deleteByPK($id);
        }

        public function setPaid($id, $paid) {
            $preorderDto = $this->selectByPk($id);
            if (isset($preorderDto)) {
                $preorderDto->setPaid($paid);
                if ($paid == 1) {
                    $preorderDto->setPaidAt(date('Y-m-d H:i:s'));
                }
                $this->updateByPk($preorderDto);
                return true;
            }
            return false;
        }

        public function cancelPreorder($id, $note) {
            $preorderDto = $this->selectByPk($id);
            PartnerManager::getInstance()->setPartnerHidden($preorderDto->getPartnerId(), 0);
            if (isset($preorderDto)) {
                $preorderDto->setCancelled(1);
                $preorderDto->setCancelNote($note);
                $this->updateByPk($preorderDto);
                return true;
            }
            return false;
        }

        public function restorePreorder($id) {
            $preorderDto = $this->selectByPk($id);
            PartnerManager::getInstance()->setPartnerHidden($preorderDto->getPartnerId(), 0);
            if (isset($preorderDto)) {
                $preorderDto->setCancelled(0);
                $preorderDto->setCancelNote("");
                $this->updateByPk($preorderDto);
                return true;
            }
            return false;
        }

        public function createPreorder($partnerId, $date, $paymentDeadlineDate, $note, $paid = 0, $purseOrderId = 0) {
            $dto = $this->createDto();
            $dto->setPartnerId($partnerId);
            $dto->setOrderDate($date);
            $dto->setPaymentDeadline($paymentDeadlineDate);
            $dto->setPaid($paid);
            $dto->setNote($note);
            $dto->setPurseOrderId($purseOrderId);
            return $this->insertDto($dto);
        }

        public function updatePreorder($id, $partnerId, $date, $paymentDeadlineDate, $note, $purse_order_ids = '') {
            $dto = $this->selectByPk($id);
            if ($dto) {
                $dto->setPartnerId($partnerId);
                $dto->setOrderDate($date);
                $dto->setPaymentDeadline($paymentDeadlineDate);
                $dto->setNote($note);
                $dto->setPurseOrderIds($purse_order_ids);
                return $this->updateByPk($dto);
            }
        }

        private function mapPreorderLinesByPreorderId($preorderLinesDtos) {
            $ret = [];
            foreach ($preorderLinesDtos as $preorderLinesDto) {
                $poId = $preorderLinesDto->getPreorderId();
                $ret[$poId][] = $preorderLinesDto;
            }
            return $ret;
        }

    }

}
