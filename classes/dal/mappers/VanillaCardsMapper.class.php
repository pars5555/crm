<?php

/**
 *
 * Mysql mapper class is extended class from AbstractMysqlMapper.
 * It contatins all read and write functions which are working with its table.
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2015
 * @package crm.dal.mappers
 * @version 2.0.0
 *
 */

namespace crm\dal\mappers {

    use crm\dal\dto\VanillaCardsDto;

    class VanillaCardsMapper extends AdvancedAbstractMysqlMapper {

        private static $instance;
        public $tableName = "vanilla_cards";

        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new VanillaCardsMapper();
            }
            return self::$instance;
        }

        public function createDto() {
            return new VanillaCardsDto();
        }

        public function getPKFieldName() {
            return "id";
        }

        public function getTableName() {
            return $this->tableName;
        }

        

        public function getTotalInitialBalanceExcludeSaleToOthers($datetime = null, $telegramChatIdsSql = "",$adminId = 0) {
            $sql = "SELECT SUM(initial_balance) as initial_balance_total, SUM(sold_amount) as sold_amount_total FROM `%s` WHERE bonus_supplied = 0 ";
            
            if (!empty($datetime)) {
                $sql .= " AND created_at >= '" . $datetime . "'";
            }
            if (!empty($telegramChatIdsSql)) {
                $sql .= " AND telegram_chat_id in " . $telegramChatIdsSql. "";
            }
            if (!empty($adminId)) {
                $sql .= " AND vanilla_cards.admin_id = " . $adminId;
            }
            $sqlQuery = sprintf($sql, $this->getTableName());
            $row = $this->fetchRow($sqlQuery);
            return round(floatval($row->initial_balance_total - $row->sold_amount_total), 2);
        }

        public function getTotalCanclledOrdersPendingBalance($datetime = null, $telegramChatIdsSql = "",$adminId = 0) {
            $sql = "SELECT SUM(amazon_total) as total FROM `%s` "
                    . "INNER JOIN purse_orders ON "
                    . "FIND_IN_SET(`purse_orders`.id , vanilla_cards.`external_orders_ids`) "
                    . "WHERE status='cancled' AND vanilla_cards.closed=0 and vanilla_cards.invalid=0 and vanilla_cards.deleted=0";
            if (!empty($datetime)) {
                $sql .= " AND vanilla_cards.`created_at` >= '" . $datetime . "'";
            }
             if (!empty($adminId)) {
                $sql .= " AND vanilla_cards.admin_id = " . $adminId;
            }
            if (!empty($telegramChatIdsSql)) {
                $sql .= " AND telegram_chat_id in " . $telegramChatIdsSql. "";
            }
            $sqlQuery = sprintf($sql, $this->getTableName());
            $total = $this->fetchField($sqlQuery, 'total');
            return floatval($total);
        }
        
        public function getTotalBalance($ignoreLessThan = 0, $telegramChatIdsSql = "",$adminId = 0) {
            $sql = "SELECT SUM(balance) as total FROM `%s` "
                    . "WHERE `balance` > %s and closed=0 and invalid=0 and deleted=0";
            if (!empty($telegramChatIdsSql)) {
                $sql .= " AND telegram_chat_id in " . $telegramChatIdsSql. "";
            }            
             if (!empty($adminId)) {
                $sql .= " AND vanilla_cards.admin_id = " . $adminId;
            }
            $sqlQuery = sprintf($sql, $this->getTableName(), $ignoreLessThan);
            $total = $this->fetchField($sqlQuery, 'total');

            $sql = "SELECT SUM(`sold_amount`) as `sold_total` FROM `%s` "
                    . "WHERE `balance` > %s  and `balance` >= `sold_amount` and closed=0 and invalid=0 and deleted=0";
             if (!empty($adminId)) {
                $sql .= " AND admin_id = " . $adminId;
            }
            $sqlQuery = sprintf($sql, $this->getTableName(), $ignoreLessThan);
            $soldTotal = $this->fetchField($sqlQuery, 'sold_total');


            return floatval($total) - floatval($soldTotal);
        }

        public function getDeliveredOrdersTotal($datetime = null, $telegramChatIdsSql = "", $adminId = 0) {
            $sql = "SELECT SUM(amazon_total) as total FROM `%s` "
                    . "INNER JOIN purse_orders ON "
                    . "FIND_IN_SET(`purse_orders`.id , vanilla_cards.`external_orders_ids`) "
                    . "WHERE status='delivered'";
            if (!empty($datetime)) {
                $sql .= " AND vanilla_cards.`created_at` >= '" . $datetime . "'";
            }
            if (!empty($adminId)) {
                $sql .= " AND vanilla_cards.admin_id = " . $adminId;
            }
            if (!empty($telegramChatIdsSql)) {
                $sql .= " AND telegram_chat_id in " . $telegramChatIdsSql. "";
            }
            $sqlQuery = sprintf($sql, $this->getTableName());
            $total = $this->fetchField($sqlQuery, 'total');
            return floatval($total);
        }

        public function getPendingOrdersTotal($datetime = null, $telegramChatIdsSql = "", $adminId = 0) {
            $sql = "SELECT SUM(amazon_total) as total FROM `%s` "
                    . "INNER JOIN purse_orders ON "
                    . "FIND_IN_SET(`purse_orders`.id , vanilla_cards.`external_orders_ids`) "
                    . "WHERE status='shipping'";
            if (!empty($datetime)) {
                $sql .= " AND vanilla_cards.`created_at` >= '" . $datetime . "'";
            }
            if (!empty($adminId)) {
                $sql .= " AND vanilla_cards.admin_id = " . $adminId;
            }
            if (!empty($telegramChatIdsSql)) {
               $sql .= " AND telegram_chat_id in " . $telegramChatIdsSql. "";
            }
            $sqlQuery = sprintf($sql, $this->getTableName());
            $total = $this->fetchField($sqlQuery, 'total');
            return floatval($total);
        }

    }

}
