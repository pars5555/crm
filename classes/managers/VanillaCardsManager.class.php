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

    use crm\dal\mappers\VanillaCardsMapper;
    use crm\managers\AdvancedAbstractManager;
    use crm\managers\VanillaCardsManager;

    class VanillaCardsManager extends AdvancedAbstractManager {

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
                self::$instance = new VanillaCardsManager(VanillaCardsMapper::getInstance());
            }
            return self::$instance;
        }

        public function getDeliveredOrdersTotal($monthsCount = 0) {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            return $this->mapper->getDeliveredOrdersTotal($date);
        }

        public function getTotalCanclledOrdersPendingBalance($monthsCount = null) {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            return $this->mapper->getTotalCanclledOrdersPendingBalance($date);
        }

        public function getConfirmedAndPendigTransactionsTotalByTransactionNames($merchartNammes = [], $monthsCount = null) {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            $where = ['1', '=', '1', 'AND', '('];
            foreach ($merchartNammes as $word) {
                $where = array_merge($where, ['transaction_history', 'like', "'%$word%'", 'OR']);
            }
            $where = array_slice($where, 0, -1);
            $where[] = ')';
            if (!empty($date)) {
                $where = array_merge($where, ['AND', 'created_at', '>=', "'$date'"]);
            }
            $rows = $this->selectAdvance('*', $where);
            $totalConfirmed = 0;
            $totalPending = 0;
            foreach ($rows as $row) {
                $totalConfirmed += $row->getNotPendingAmountByMerchantName($merchartNammes);
                $totalPending += $row->getPendingAmountByMerchantName($merchartNammes);
            }
            return [$totalConfirmed, $totalPending];
        }

        public function getPendingOrdersTotal($monthsCount = 0) {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            return $this->mapper->getPendingOrdersTotal($date);
        }

        public function getTotalInitialBalanceExcludeSaleToOthers($monthsCount = 0) {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            return $this->mapper->getTotalInitialBalanceExcludeSaleToOthers($date);
        }

        public function getTotalBalance($ignoreLessThan = 10) {
            return $this->mapper->getTotalBalance($ignoreLessThan);
        }

        public function isBotWorking() {
            $twoMinuteAgo = date('Y-m-d H:i:s', strtotime("-2 minutes"));
            $rows = $this->selectAdvance('updated_at', [], 'updated_at', 'desc', 0, 1);
            if (empty($rows)) {
                return true;
            }
            $row = $rows[0];
            if ($row->getUpdatedAt() <= $twoMinuteAgo) {
                return $row->getUpdatedAt();
            }
            return true;
        }

        public function addRow() {
            $dto = $this->createDto();
            $dto->setCreatedAt(date('Y-m-d H:i:s'));
            return $this->insertDto($dto);
        }

        public function getBtcRate() {
            return $this->selectAdvance('*', ['name', '=', "'BTC'"], 'id', 'DESC', 0, 1)[0]->getRate();
        }

    }

}
