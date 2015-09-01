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

    use crm\dal\dto\PaymentTransactionDto;

    class PaymentTransactionMapper extends AdvancedAbstractMysqlMapper {

        private static $instance;
        public $tableName = "payment_transactions";

        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new PaymentTransactionMapper();
            }
            return self::$instance;
        }

        public function createDto() {
            return new PaymentTransactionDto();
        }

        public function getPKFieldName() {
            return "id";
        }

        public function getTableName() {
            return $this->tableName;
        }

        
        public function getNonCancelledPaymentOrdersByCurrency($date, $currencyId) {
            $sql = "SELECT SUM(amount) AS amount FROM `%s` WHERE `cancelled` = 0 AND `paid` = 1 AND currency_id=:id AND `date`<=DATE_ADD('%s' ,INTERVAL 1 DAY)";
            $sqlQuery = sprintf($sql, $this->getTableName(), $date);
            $qty = $this->fetchField($sqlQuery, 'amount', array("id" => $currencyId));
            return isset($qty) ? floatval($qty) : 0;
        }
        
        public function getAllNonCancelledExpensePayments($startDate, $endDate) {
            $sql = "SELECT * FROM `%s` "
                    . "WHERE `cancelled` = 0 AND `is_expense` = 1 AND "
                    . "`date`>='%s' AND `date`<=DATE_ADD('%s' ,INTERVAL 1 DAY)";
            $sqlQuery = sprintf($sql, $this->getTableName(), $startDate, $endDate);
            return $this->fetchRows($sqlQuery);
        }
    }

}
