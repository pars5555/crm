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
        
        public function getDeliveredOrdersTotal($monthsCount = 0, $telegramChatIdsSql = "") {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            return $this->mapper->getDeliveredOrdersTotal($date, $telegramChatIdsSql);
        }

        public function getTotalCanclledOrdersPendingBalance($monthsCount = null, $telegramChatIdsSql = "") {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            return $this->mapper->getTotalCanclledOrdersPendingBalance($date, $telegramChatIdsSql);
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
            foreach ($merchartNammes as $word) {
                $where = array_merge($where, ['transaction_history', 'like', "'%$word%'", 'OR']);
            }
            $where = array_slice($where, 0, -1);
            $where[] = ')';
            if (!empty($date)) {
                $where = array_merge($where, ['AND', 'created_at', '>=', "'$date'"]);
            }
            if (!empty($telegramChatIdsSql)) {
                $where = array_merge($where, ['AND', 'telegram_chat_id', 'in', $telegramChatIdsSql]);
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

        public function getPendingOrdersTotal($monthsCount = 0, $telegramChatIdsSql = "") {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            return $this->mapper->getPendingOrdersTotal($date, $telegramChatIdsSql);
        }

        public function getTotalInitialBalanceExcludeSaleToOthers($monthsCount = 0, $telegramChatIdsSql = "") {
            $date = null;
            if ($monthsCount > 0) {
                $date = date('Y-m-d H:i:s', strtotime('-' . $monthsCount . ' month'));
            }
            return $this->mapper->getTotalInitialBalanceExcludeSaleToOthers($date, $telegramChatIdsSql);
        }

        public function getTotalBalance($ignoreLessThan = 10, $telegramChatIdsSql = "", $notRootMaxBalanceToShow = 50) {
            return $this->mapper->getTotalBalance($ignoreLessThan, $telegramChatIdsSql, $notRootMaxBalanceToShow);
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
