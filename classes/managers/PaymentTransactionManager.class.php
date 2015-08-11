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
            $paymentDto = $this->selectByPK($id);
            if (isset($paymentDto)) {
                $paymentDto->setCancelled(1);
                $paymentDto->setCancelNote($note);
                $this->updateByPk($paymentDto);
                return true;
            }
            return false;
        }
        
        public function undoCancelPayment($id) {
            $paymentDto = $this->selectByPK($id);
            if (isset($paymentDto)) {
                $paymentDto->setCancelled(0);
                $paymentDto->setCancelNote("");
                $this->updateByPk($paymentDto);
                return true;
            }
            return false;
        }

        public function createPaymentOrder($partnerId, $paymentMethodId, $currencyId, $amount, $date, $note) {
            $partnerManager = PartnerManager::getInstance();
            $partner = $partnerManager->selectByPK($partnerId);
            if (empty($partner)) {
                throw new NgsErrorException("Partner does not exists with given id: " . $partnerId);
            }
            $paymentMethodManager = PaymentMethodManager::getInstance();
            $paymentMethod = $paymentMethodManager->selectByPK($paymentMethodId);
            if (empty($paymentMethod)) {
                throw new NgsErrorException("PaymentMethod does not exists with given id: " . $paymentMethodId);
            }
            $dto = $this->createDto();
            $dto->setPartnerId($partnerId);
            $dto->setPaymentMethodId($paymentMethodId);
            $dto->setCurrencyId($currencyId);
            $dto->setAmount($amount);
            $dto->setDate($date);
            $dto->setNote($note);
            return $this->insertDto($dto);
        }

        public function getPartnerPaymentTransactions($partnerId) {
            return $this->selectAdvance('*', ['partner_id', '=', $partnerId, 'AND', 'amount', '>', 0]);
        }
        public function getPartnerBillingTransactions($partnerId) {
            return $this->selectAdvance('*', ['partner_id', '=', $partnerId, 'AND', 'amount', '<', 0]);
        }

        public function getPartnersPaymentTransactions($partnerIds) {
            $rows = $this->selectAdvance('*', ['partner_id', 'in', '(' . implode(',', $partnerIds) . ')', 'and', 'amount', '>', '0']);
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
            $rows = $this->selectAdvance('*', ['partner_id', 'in', '(' . implode(',', $partnerIds) . ')', 'and', 'amount', '<', '0']);
            $ret = array();
            foreach ($partnerIds as $partnerId) {
                $ret[$partnerId] = [];
            }
            foreach ($rows as $row) {
                $ret [intval($row->getPartnerId())][] = $row;
            }
            return $ret;
        }

        public function getNonCancelledPaymentOrdersByCurrency($currencyId) {
            return $this->mapper->getNonCancelledPaymentOrdersByCurrency($currencyId);
        }

    }

}
