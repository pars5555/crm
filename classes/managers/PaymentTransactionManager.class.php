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

    use crm\dal\mappers\PaymentTransactionMapper;
    use ngs\framework\exceptions\NgsErrorException;

    class PaymentTransactionManager extends AdvancedAbstractManager {

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
                self::$instance = new PaymentTransactionManager(PaymentTransactionMapper::getInstance());
            }
            return self::$instance;
        }

        public function updateAllOrdersCurrencyRates() {
            $allDtos = $this->selectAll();
            foreach ($allDtos as $dto) {
                $currencyId = $dto->getCurrencyId();
                $date = $dto->getDate();
                $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($date, $currencyId);
                $dto->setCurrencyRate($rate);
                $this->updateByPk($dto);
            }
            return count($allDtos);
        }

        public function getPaymentListFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            $rows = $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit);
            $partnerIds = array();
            $paymentMethodIds = array();
            $currenciesIds = array();
            foreach ($rows as $row) {
                $partnerIds[] = $row->getPartnerId();
                $paymentMethodIds[] = $row->getPaymentMethodId();
                $currenciesIds[] = $row->getCurrencyId();
            }
            $partnerIds = array_unique($partnerIds);
            $paymentMethodIds = array_unique($paymentMethodIds);
            $currenciesIds = array_unique($currenciesIds);
            $partnerDtos = PartnerManager::getInstance()->selectByPKs($partnerIds, true);
            $paymentMethodDtos = PaymentMethodManager::getInstance()->selectByPKs($paymentMethodIds, true);
            $currencyDtos = CurrencyManager::getInstance()->selectByPKs($currenciesIds, true);
            foreach ($rows as $row) {
                $row->setPartnerDto($partnerDtos[$row->getPartnerId()]);
                $row->setPaymentMethodDto($paymentMethodDtos[$row->getPaymentMethodId()]);
                $row->setCurrencyDto($currencyDtos[$row->getCurrencyId()]);
            }
            return $rows;
        }

        public function cancelPayment($id, $note) {
            $paymentDto = $this->selectByPk($id);
            if (isset($paymentDto)) {
                PartnerManager::getInstance()->setPartnerHidden($paymentDto->getPartnerId(), 0);
                $paymentDto->setCancelled(1);
                $paymentDto->setCancelNote($note);
                $this->updateByPk($paymentDto);
                return true;
            }
            return false;
        }

        public function undoCancelPayment($id) {
            $paymentDto = $this->selectByPk($id);
            if (isset($paymentDto)) {
                $paymentDto->setCancelled(0);
                $paymentDto->setCancelNote("");
                $this->updateByPk($paymentDto);
                PartnerManager::getInstance()->setPartnerHidden($paymentDto->getPartnerId(), 0);
                return true;
            }
            return false;
        }

        public function createPaymentOrder($partnerId, $paymentMethodId, $currencyId, $amount, $date, $note, $signature = '[]', $paid = true, $isExpense = false) {
            $lastRows = $this->selectAdvance('*', [], 'id', 'desc', 0, 1);
            $lastRow = $lastRows[0];
            $lastRowCreatedAt = $lastRow->getCreatedAt();


            $timeFirst = strtotime($lastRowCreatedAt);
            $timeSecond = strtotime(date('y-m-d H:i:s'));
            $differenceInSeconds = $timeSecond - $timeFirst;
            if (abs($differenceInSeconds) <= 3) {
                throw new NgsErrorException("Duplicated transation submitted, please check last submitted transation");
            }

            $partnerManager = PartnerManager::getInstance();
            $partner = $partnerManager->selectByPk($partnerId);
            if (empty($partner)) {
                throw new NgsErrorException("Partner does not exists with given id: " . $partnerId);
            }
            $paymentMethodManager = PaymentMethodManager::getInstance();
            $paymentMethod = $paymentMethodManager->selectByPk($paymentMethodId);
            if (empty($paymentMethod)) {
                throw new NgsErrorException("PaymentMethod does not exists with given id: " . $paymentMethodId);
            }
            $partnerManager->setPartnerHidden($partnerId, 0);
            $dto = $this->createDto();
            $dto->setPartnerId($partnerId);
            $dto->setPaymentMethodId($paymentMethodId);
            $dto->setCurrencyId($currencyId);
            $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($date, $currencyId);
            $dto->setCurrencyRate($rate);
            $dto->setAmount($amount);
            $dto->setDate($date);
            $dto->setNote($note);
            $dto->setIsExpense($isExpense);
            $dto->setPaid($paid);
            $dto->setSignature($signature);
            
            $paymentTransactionManager = PaymentTransactionManager::getInstance();
            $usdCashbox = -$paymentTransactionManager->getNonCancelledPaymentOrdersByCurrency($date, 1);
            $amdCashbox = -$paymentTransactionManager->getNonCancelledPaymentOrdersByCurrency($date, 2);
            $cb = new \stdClass();
            $cb->amd = $amdCashbox;
            $cb->usd= $usdCashbox;
            $dto->setCashboxAmount(json_encode($cb));
            $dto->setCreatedAt(date('y-m-d H:i:s'));
            return $this->insertDto($dto);
        }

        public function updatePaymentOrder($id, $partnerId, $paymentMethodId, $currencyId, $amount, $date, $note, $signature = "[]", $paid = true, $isExpense = false) {
            $partnerManager = PartnerManager::getInstance();
            $partner = $partnerManager->selectByPk($partnerId);
            if (empty($partner)) {
                throw new NgsErrorException("Partner does not exists with given id: " . $partnerId);
            }
            $paymentMethodManager = PaymentMethodManager::getInstance();
            $paymentMethod = $paymentMethodManager->selectByPk($paymentMethodId);
            if (empty($paymentMethod)) {
                throw new NgsErrorException("PaymentMethod does not exists with given id: " . $paymentMethodId);
            }
            $dto = $this->selectByPk($id);
            $partnerManager->setPartnerHidden($partnerId, 0);
            if ($dto) {
                $dto->setPartnerId($partnerId);
                $dto->setPaymentMethodId($paymentMethodId);
                $dto->setCurrencyId($currencyId);
                $rate = CurrencyRateManager::getInstance()->getCurrencyRateByDate($date, $currencyId);
                $dto->setCurrencyRate($rate);
                $dto->setAmount($amount);
                $dto->setDate($date);
                $dto->setNote($note);
                $dto->setIsExpense($isExpense);
                $dto->setPaid($paid);
                $dto->setSignature($signature);
                return $this->updateByPk($dto);
            }
            return false;
        }

        public function getPartnerPaymentTransactions($partnerId) {
            return $this->selectAdvance('*', ['deleted', '=', 0, 'AND', 'partner_id', '=', $partnerId, 'AND', 'amount', '>', 0]);
        }

        public function getPartnerBillingTransactions($partnerId, $days = false) {
            $where = ['deleted', '=', 0, 'AND', 'partner_id', '=', $partnerId, 'AND', 'amount', '<', 0];
            if ($days > 0) {
                $days_ago = date('Y-m-d', strtotime("-$days days"));
                $where[] = 'AND';
                $where[] = '`date`';
                $where[] = '<';
                $where[] = "'$days_ago'";
            }
            return $this->selectAdvance('*', $where);
        }

        public function getPartnersPaymentTransactions($partnerIds) {
            $rows = $this->selectAdvance('*', ['deleted', '=', 0, 'AND', 'partner_id', 'in', '(' . implode(',', $partnerIds) . ')', 'and', 'amount', '>', '0']);
            $ret = array();
            foreach ($partnerIds as $partnerId) {
                $ret[$partnerId] = [];
            }
            foreach ($rows as $row) {
                $ret [intval($row->getPartnerId())][] = $row;
            }
            return $ret;
        }

        public function getPartnersBillingTransactions($partnerIds) {
            $rows = $this->selectAdvance('*', ['deleted', '=', 0, 'AND', 'partner_id', 'in', '(' . implode(',', $partnerIds) . ')', 'and', 'amount', '<', '0']);
            $ret = array();
            foreach ($partnerIds as $partnerId) {
                $ret[$partnerId] = [];
            }
            foreach ($rows as $row) {
                $ret [intval($row->getPartnerId())][] = $row;
            }
            return $ret;
        }

        public function getNonCancelledPaymentOrdersByCurrency($date, $currencyId) {
            return $this->mapper->getNonCancelledPaymentOrdersByCurrency($date, $currencyId);
        }

        public function getAllNonCancelledExpensePayments($startDate, $endDate) {
            return $this->mapper->getAllNonCancelledExpensePayments($startDate, $endDate);
        }

    }

}
