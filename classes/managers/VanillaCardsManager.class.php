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
        private $filteredAdminId;
        private $globalWhere = [];

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

        public function __construct($mapper) {
            $adminId = NGS()->getSessionManager()->getUserId();
            $this->filteredAdminId = false;
            if ($adminId > 0) {
                $userType = \crm\managers\AdminManager::getInstance()->getById($adminId)->getType();
                if ($userType !== 'root') {
                    $this->filteredAdminId = $adminId;
                    $this->globalWhere = [1000 => 'AND', 1001 => 'admin_id', 1002 => '=', 1003 => $adminId];
                }
            }
            parent::__construct($mapper);
        }

        public function getDeliveredOrdersTotal($monthsCount = 0, $telegramChatIdsSql = "") {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            return $this->mapper->getDeliveredOrdersTotal($date, $telegramChatIdsSql, $this->filteredAdminId);
        }

        public function getTotalCanclledOrdersPendingBalance($monthsCount = null, $telegramChatIdsSql = "") {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            return $this->mapper->getTotalCanclledOrdersPendingBalance($date, $telegramChatIdsSql, $this->filteredAdminId);
        }

        public function getAllChatIds() {
            $orders = $this->selectAdvance('*', [], null, null, null, null, false, "", 'GROUP BY telegram_chat_id');
            $chatIds = [];
            foreach ($orders as $order) {
                $telegramChatId = trim($order->getTelegramChatId());
                if (!empty($telegramChatId)) {
                    $chatIds[] = $telegramChatId;
                }
            }
            return $chatIds;
        }

        public function getConfirmedAndPendigTransactionsTotalByTransactionNames($merchartNammes = [], $monthsCount = null, $telegramChatIdsSql = "") {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            $where = ['1', '=', '1', 'AND', '('];
            foreach ($merchartNammes as $merchant) {
                $m = strtolower($merchant);
                $where = array_merge($where, ['lower(`transaction_history`)', 'like', "'%$m%'", 'OR']);
            }
            $where = array_slice($where, 0, -1);
            $where[] = ')';
            if (!empty($date)) {
                $where = array_merge($where, ['AND', 'created_at', '>=', "'$date'"]);
            }
            if (!empty($telegramChatIdsSql)) {
                $where = array_merge($where, ['AND', 'telegram_chat_id', 'in', $telegramChatIdsSql]);
            }
            if (!empty($this->filteredAdminId)) {
                $where = array_merge($where, ['AND', 'admin_id', '=', $this->filteredAdminId]);
            }
            $rows = $this->selectAdvance('*', $where + $this->globalWhere);
            $totalConfirmed = 0;
            $totalPending = 0;
            foreach ($rows as $row) {
                $totalConfirmed += $row->getNotPendingAmountByMerchantName($merchartNammes);
                $totalPending += $row->getPendingAmountByMerchantName($merchartNammes);
            }
            return [$totalConfirmed, $totalPending];
        }

        public function getPendingOrdersTotal($monthsCount = 0, $telegramChatIdsSql = "") {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            return $this->mapper->getPendingOrdersTotal($date, $telegramChatIdsSql, $this->filteredAdminId);
        }

        public function getTotalInitialBalanceExcludeSaleToOthers($monthsCount = 0, $telegramChatIdsSql = "") {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            return $this->mapper->getTotalInitialBalanceExcludeSaleToOthers($date, $telegramChatIdsSql, $this->filteredAdminId);
        }

        public function getTotalBalance($ignoreLessThan = 10, $telegramChatIdsSql = "") {
            return $this->mapper->getTotalBalance($ignoreLessThan, $telegramChatIdsSql, $this->filteredAdminId);
        }

        public function isBotWorking() {
            $twoMinuteAgo = date('Y-m-d H:i:s', strtotime("-15 minutes"));
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
