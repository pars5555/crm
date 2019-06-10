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
        
        public function getTotalBalance($ignoreLessThan = 0) {
            $sql = "SELECT SUM(balance) as total FROM `%s` "
                    . "WHERE `balance` > %s and closed=0 and invalid=0 and deleted=0";
            $sqlQuery = sprintf($sql, $this->getTableName(), $ignoreLessThan);
            $total = $this->fetchField($sqlQuery, 'total');
            
            $sql = "SELECT SUM(`sold_amount`) as `sold_total` FROM `%s` "
                    . "WHERE `balance` > %s  and `balance` >= `sold_amount` and closed=0 and invalid=0 and deleted=0";
            $sqlQuery = sprintf($sql, $this->getTableName(), $ignoreLessThan);
            $soldTotal = $this->fetchField($sqlQuery, 'sold_total');
            
            
            return floatval($total) - floatval($soldTotal);
        }

        public function getAllDeliveredTotal() {
            $sql = "SELECT SUM(amazon_total) as total FROM `%s` "
                    . "INNER JOIN purse_orders ON "
                    . "FIND_IN_SET(`purse_orders`.id , vanilla_cards.`external_orders_ids`) "
                    . "WHERE status='delivered'";
            $sqlQuery = sprintf($sql, $this->getTableName());
            $total = $this->fetchField($sqlQuery, 'total');
            return floatval($total);
        }

    }

}
