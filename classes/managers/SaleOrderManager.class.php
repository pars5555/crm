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
                PartnerManager::getInstance()->setPartnerHidden($saleOrderDto->getPartnerId(), 0);
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

        public function setNonProfit($id, $nonProfit) {
            $nonProfit = intval($nonProfit);
            $saleOrderDto = $this->selectByPK($id);
            if (isset($saleOrderDto)) {
                $saleOrderDto->setNonProfit($nonProfit);
                $this->updateByPk($saleOrderDto);
                if ($nonProfit == 1) {
                    $solDtos = SaleOrderLineManager::getInstance()->selectByField('sale_order_id', $id);
                    foreach ($solDtos as $solDto) {
                        SaleOrderLineManager::getInstance()->updateField($solDto->getId(), 'total_profit', 0);
                    }
                } else {
                    $this->recalculateProfit($id);
                }
                return true;
            }
            return false;
        }

        private function recalculateProfit($soId) {
            $soDtos = $this->getSaleOrdersFull(['id', '=', $soId]);
            if (!empty($soDtos)) {
                $soDto = $soDtos[0];
            } else {
                return False;
            }
            $saleOrderLinesDtos = $soDto->getSaleOrderLinesDtos();
            SaleOrderLineManager::getInstance()->deleteByField('sale_order_id', $soId);
            foreach ($saleOrderLinesDtos as $saleOrderLinesDto) {
                SaleOrderLineManager::getInstance()->createSaleOrderLine($soId, $saleOrderLinesDto->getProductId(), $saleOrderLinesDto->getQuantity(), $saleOrderLinesDto->getUnitPrice(), $saleOrderLinesDto->getCurrencyId());
            }
        }

        public function restoreSaleOrder($id) {
            $saleOrderDto = $this->selectByPK($id);
            if (isset($saleOrderDto)) {
                PartnerManager::getInstance()->setPartnerHidden($saleOrderDto->getPartnerId(), 0);
                $saleOrderDto->setCancelled(0);
                $saleOrderDto->setCancelNote("");
                $this->updateByPk($saleOrderDto);
                return true;
            }
            return false;
        }

        public function updateAllDependingSaleOrderLines($saleOrderId) {
            $productsIds = $this->getProductsIdsInOrder($saleOrderId);
            $this->updateAllOrderLinesThatContainsProducts($productsIds);
        }

        public function getProductsIdsInOrder($saleOrderId) {
            $soLines = SaleOrderLineManager::getInstance()->selectAdvance('*', ['sale_order_id', '=', $saleOrderId]);
            $productIds = [];
            foreach ($soLines as $soLine) {
                $productIds [] = $soLine->getProductId();
            }
            return $productIds;
        }

        public function updateAllOrderLinesThatContainsProducts($productIds) {
            $productsSaleOrders = SaleOrderLineManager::getInstance()->getProductsSaleOrders($productIds);
            foreach ($productsSaleOrders as $productSaleOrders) {
                foreach ($productSaleOrders as $productSaleOrder) {
                    $this->updateAllOrderLines($productSaleOrder->getId());
                }
            }
        }

        public function delete($id) {
            SaleOrderLineManager::getInstance()->deleteByField('sale_order_id', $id);
            return $this->deleteByPK($id);
        }

        public function updateAllOrderLines($saleOrderId = 0) {
            if ($saleOrderId > 0) {
                $allSaleOrders = $this->getSaleOrdersFull(['id', '=', $saleOrderId]);
            } else {
                $allSaleOrders = $this->getSaleOrdersFull(['cancelled', '=', 0], 'order_date', 'ASC');
            }
            foreach ($allSaleOrders as $saleOrder) {
                $saleOrderLinesDtos = $saleOrder->getSaleOrderLinesDtos();
                foreach ($saleOrderLinesDtos as $saleOrderLinesDto) {
                    SaleOrderLineManager::getInstance()->updateSaleOrderLine($saleOrder->getId(), $saleOrderLinesDto->getId(), $saleOrderLinesDto->getProductId(), $saleOrderLinesDto->getQuantity(), $saleOrderLinesDto->getUnitPrice(), $saleOrderLinesDto->getCurrencyId());
                }
            }
            return true;
        }

        public function updateAllLinesCurrencyRates() {
            $allSaleOrders = $this->getSaleOrdersFull();
            foreach ($allSaleOrders as $saleOrder) {
                $saleOrderLinesDtos = $saleOrder->getSaleOrderLinesDtos();
                foreach ($saleOrderLinesDtos as $saleOrderLinesDto) {
                    $orderDate = $saleOrder->getOrderDate();
                    $currencyId = $saleOrderLinesDto->getCurrencyId();
                    $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($orderDate, $currencyId);
                    $saleOrderLinesDto->setCurrencyRate($rate);
                    SaleOrderLineManager::getInstance()->updateByPK($saleOrderLinesDto);
                }
            }
            return count($allSaleOrders);
        }

        public function createSaleOrder($partnerId, $date, $billingDeadlineDate, $isExpense, $note) {
            $dto = $this->createDto();
            $dto->setPartnerId($partnerId);
            $dto->setOrderDate($date);
            $dto->setBillingDeadline($billingDeadlineDate);
            $dto->setIsExpense($isExpense);
            $dto->setNote($note);
            return $this->insertDto($dto);
        }

        public function updateSaleOrder($id, $partnerId, $date, $billingDeadlineDate, $isExpense, $note) {
            $dto = $this->selectByPK($id);
            if ($dto) {
                $dto->setPartnerId($partnerId);
                $dto->setOrderDate($date);
                $dto->setBillingDeadline($billingDeadlineDate);
                $dto->setIsExpense($isExpense);
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
        
        public function getPartnerSaleOrdersTotal($partnerId) {
            $where = ['cancelled', '=', 0, 'AND', 'partner_id', '=', $partnerId];
            $sos = $this->getSaleOrdersFull($where);
            $total = [];
            foreach ($sos as $so) {
                foreach ($so->getTotalAmount() as $currencyId => $amount) {
                    if (!array_key_exists($currencyId, $total)) {
                        $total[$currencyId] = 0;
                    }
                    $total[$currencyId] += $amount;
                }
            }
            return $total;
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
                $amountInMainCurrency = [];
                $profits = [];
                foreach ($saleOrderLinesDtos as $saleOrderLine) {
                    $saleOrderId = intval($saleOrderLine->getSaleOrderId());
                    $currencyId = intval($saleOrderLine->getCurrencyId());
                    $currencyRate = floatval($saleOrderLine->getCurrencyRate());
                    $unitPrice = floatval($saleOrderLine->getUnitPrice());
                    $quantity = floatval($saleOrderLine->getQuantity());
                    $profit = floatval($saleOrderLine->getTotalProfit());
                    if (!array_key_exists($saleOrderId, $amount)) {
                        $amount[$saleOrderId] = [];
                    }
                    if (!array_key_exists($saleOrderId, $amountInMainCurrency)) {
                        $amountInMainCurrency[$saleOrderId] = 0;
                    }
                    if (!array_key_exists($currencyId, $amount[$saleOrderId])) {
                        $amount[$saleOrderId][$currencyId] = 0;
                    }
                    $amount[$saleOrderId][$currencyId] += $unitPrice * $quantity;
                    $amountInMainCurrency[$saleOrderId] += ($unitPrice * $quantity * $currencyRate);
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
                    if (!array_key_exists($saleOrderId, $amountInMainCurrency)) {
                        $amountInMainCurrency[$saleOrderId] = 0;
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
                $row->setTotalAmountInMainCurrency($amountInMainCurrency[$saleOrderId]);
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
