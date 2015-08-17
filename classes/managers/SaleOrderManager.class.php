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

    use crm\dal\mappers\SaleOrderMapper;

    class SaleOrderManager extends AdvancedAbstractManager {

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
                self::$instance = new SaleOrderManager(SaleOrderMapper::getInstance());
            }
            return self::$instance;
        }

        public function cancelSaleOrder($id, $note) {
            $saleOrderDto = $this->selectByPK($id);
            if (isset($saleOrderDto)) {
                $saleOrderDto->setCancelled(1);
                $saleOrderDto->setCancelNote($note);
                $this->updateByPk($saleOrderDto);
                return true;
            }
            return false;
        }
        
        public function setBilled($id, $billed) {
            $saleOrderDto = $this->selectByPK($id);
            if (isset($saleOrderDto)) {
                $saleOrderDto->setBilled($billed);
                if ($billed == 1) {
                    $saleOrderDto->setBilledAt(date('Y-m-d H:i:s'));
                }
                $this->updateByPk($saleOrderDto);
                return true;
            }
            return false;
        }

        public function restoreSaleOrder($id) {
            $saleOrderDto = $this->selectByPK($id);
            if (isset($saleOrderDto)) {
                $saleOrderDto->setCancelled(0);
                $saleOrderDto->setCancelNote("");
                $this->updateByPk($saleOrderDto);
                return true;
            }
            return false;
        }

        public function createSaleOrder($partnerId, $date, $billingDeadlineDate, $note) {
            $dto = $this->createDto();
            $dto->setPartnerId($partnerId);
            $dto->setOrderDate($date);
            $dto->setBillingDeadline($billingDeadlineDate);
            $dto->setNote($note);
            return $this->insertDto($dto);
        }

        public function updateSaleOrder($id, $partnerId, $date, $billingDeadlineDate, $note) {
            $dto = $this->selectByPK($id);
            if ($dto) {
                $dto->setPartnerId($partnerId);
                $dto->setOrderDate($date);
                $dto->setBillingDeadline($billingDeadlineDate);
                $dto->setNote($note);
                return $this->updateByPk($dto);
            }
        }

        public function getPartnerSaleOrders($partnerId) {
            return $this->getSaleOrdersFull(['partner_id', '=', $partnerId]);
        }

        public function getPartnersSaleOrders($partnerIds) {
            $rows = $this->getSaleOrdersFull(['partner_id', 'in', '(' . implode(',', $partnerIds) . ')']);
            $ret = array();
            foreach ($partnerIds as $partnerId) {
                $ret[$partnerId] = [];
            }
            foreach ($rows as $row) {
                $ret [intval($row->getPartnerId())][] = $row;
            }
            return $ret;
        }

        public function getSaleOrdersFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            $rows = $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit);
            $partnerIds = array();
            $saleOrderIds = array();
            foreach ($rows as $row) {
                $partnerIds[] = intval($row->getPartnerId());
                $saleOrderIds[] = intval($row->getId());
            }
            $partnerIds = array_unique($partnerIds);
            $saleOrderIds = array_unique($saleOrderIds);
            $partnerDtos = PartnerManager::getInstance()->selectByPKs($partnerIds, true);
            $saleOrderLinesDtos = [];
            if (!empty($saleOrderIds)) {
                $saleOrderLinesDtos = SaleOrderLineManager::getInstance()->getSaleOrderLinesFull(['sale_order_id', 'in', '(' . implode(',', $saleOrderIds) . ')']);
                $amount = [];
                $profits = [];
                foreach ($saleOrderLinesDtos as $saleOrderLine) {
                    $saleOrderId = intval($saleOrderLine->getSaleOrderId());
                    $currencyId = intval($saleOrderLine->getCurrencyId());
                    $unitPrice = floatval($saleOrderLine->getUnitPrice());
                    $quantity = floatval($saleOrderLine->getQuantity());
                    $profit = floatval($saleOrderLine->getTotalProfit());
                    if (!array_key_exists($saleOrderId, $amount)) {
                        $amount[$saleOrderId] = [];
                    }
                    if (!array_key_exists($currencyId, $amount[$saleOrderId])) {
                        $amount[$saleOrderId][$currencyId] = 0;
                    }
                    $amount[$saleOrderId][$currencyId] += $unitPrice * $quantity;
                    if (!array_key_exists($saleOrderId, $profits)) {
                        $profits[$saleOrderId] = 0;
                    }
                    $profits[$saleOrderId] += $profit;
                }
            }
            if (!empty($saleOrderIds)) {
                $saleOrderLinesDtosMappedBySaleOrderId = $this->mapSaleOrderLinesBySaleOrderId($saleOrderLinesDtos);
                foreach ($saleOrderIds as $saleOrderId) {
                    if (!array_key_exists($saleOrderId, $saleOrderLinesDtosMappedBySaleOrderId)) {
                        $saleOrderLinesDtosMappedBySaleOrderId[$saleOrderId] = [];
                    }
                    if (!array_key_exists($saleOrderId, $amount)) {
                        $amount[$saleOrderId] = [];
                    }
                    if (!array_key_exists($saleOrderId, $profits)) {
                        $profits[$saleOrderId] = 0;
                    }
                }
            }
            foreach ($rows as $row) {
                $saleOrderId = intval($row->getId());
                $row->setPartnerDto($partnerDtos[intval($row->getPartnerId())]);
                $row->setSaleOrderLinesDtos($saleOrderLinesDtosMappedBySaleOrderId[$saleOrderId]);
                $row->setTotalAmount($amount[$saleOrderId]);
                $row->setTotalProfit($profits[$saleOrderId]);
            }
            return $rows;
        }

        private function mapSaleOrderLinesBySaleOrderId($saleOrderLinesDtos) {
            $ret = [];
            foreach ($saleOrderLinesDtos as $saleOrderLinesDto) {
                $soId = $saleOrderLinesDto->getSaleOrderId();
                $ret[$soId][] = $saleOrderLinesDto;
            }
            return $ret;
        }

    }

}
