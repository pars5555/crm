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

    use crm\dal\mappers\PartnerInitialDeptMapper;

    class PartnerInitialDeptManager extends AdvancedAbstractManager {

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
                self::$instance = new PartnerInitialDeptManager(PartnerInitialDeptMapper::getInstance());
            }
            return self::$instance;
        }

        public function addRow($partnerId, $amount, $currencyId, $note) {
            $dto = $this->createDto();
            $dto->setPartnerId($partnerId);
            $dto->setAmount($amount);
            $dto->setCurrencyId($currencyId);
            $dto->setNote($note);
            return $this->insertDto($dto);
        }

        public function getInitialDeptsFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            $rows = $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit);
            $currencyIds = array();
            foreach ($rows as $row) {
                $currencyIds [] = intval($row->getCurrencyId());
            }
            $currencyIds = array_unique($currencyIds);
            $currencyDtos = CurrencyManager::getInstance()->selectByPKs($currencyIds, true);
            foreach ($rows as $row) {
                $row->setCurrencyDto($currencyDtos[$row->getCurrencyId()]);
            }
            return $rows;
        }

        public function getPartnerInitialDept($partnerId) {
            $dtos = $this->selectByField('partner_id', $partnerId);
            $ret = [];
            foreach ($dtos as $dto) {
                $currencyId = intval($dto->getCurrencyId());
                if (!array_key_exists($currencyId, $ret)) {
                    $ret[$currencyId] = 0;
                }
                $ret[$currencyId] += floatval($dto->getAmount());
            }
            return $ret;
        }

        public function getPartnersInitialDept($partnersIds) {
            if (is_array($partnersIds)) {
                $partnersIds = implode(',', $partnersIds);
            }
            $partnersIds = '(' . $partnersIds . ')';
            $dtos = $this->selectAdvance('*', ['partner_id', 'in', $partnersIds]);
            $ret = [];
            foreach ($dtos as $dto) {
                $partnerId = intval($dto->getPartnerId());
                $currencyId = intval($dto->getCurrencyId());
                if (!array_key_exists($partnerId, $ret)) {
                    $ret[$partnerId] = [];
                }
                if (!array_key_exists($currencyId, $ret[$partnerId])) {
                    $ret[$partnerId][$currencyId] = 0;
                }
                $ret[$partnerId][$currencyId] += floatval($dto->getAmount());
            }
            return $ret;
        }

    }

}
