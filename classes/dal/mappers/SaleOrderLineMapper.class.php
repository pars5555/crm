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

    use crm\dal\dto\SaleOrderLineDto;

    class SaleOrderLineMapper extends AdvancedAbstractMysqlMapper {

        private static $instance;
        public $tableName = "sale_order_lines";

        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new SaleOrderLineMapper();
            }
            return self::$instance;
        }

        public function createDto() {
            return new SaleOrderLineDto();
        }

        public function getPKFieldName() {
            return "id";
        }

        public function getTableName() {
            return $this->tableName;
        }

        public function getProductCountInNonCancelledSaleOrders($productId) {
            $sql = "SELECT SUM(quantity) AS `product_qty` FROM `%s` INNER JOIN  "
                    . " `sale_orders` ON `sale_order_id` = `sale_orders`.`id` "
                    . "WHERE `sale_orders`.`cancelled` = 0 AND product_id=:id";
            $sqlQuery = sprintf($sql, $this->getTableName());
            $qty = $this->fetchField($sqlQuery, 'product_qty', array("id" => $productId));
            return isset($qty) ? floatval($qty) : 0;
        }

        public function getAllProductCountInNonCancelledSaleOrders() {
            $sql = "SELECT product_id, SUM(quantity) AS `product_qty` FROM `%s` INNER JOIN  "
                    . " `sale_orders` ON `sale_order_id` = `sale_orders`.`id` "
                    . "WHERE `sale_orders`.`cancelled` = 0 GROUP by `product_id`";
            $sqlQuery = sprintf($sql, $this->getTableName());
            $productIdQtyObjects = $this->fetchRows($sqlQuery);
            $ret = [];
            foreach ($productIdQtyObjects as $productIdQtyObject) {
                $product_id = intval($productIdQtyObject->product_id);
                $qty = floatval($productIdQtyObject->product_qty);
                $ret [$product_id] = $qty;
            }
            return $ret;
        }

        public function getTotalProfitSumInNonCancelledSaleOrders($startDate, $endDate) {
            $sql = "SELECT SUM(total_profit) AS `profit` FROM `%s` INNER JOIN  "
                    . " `sale_orders` ON `sale_order_id` = `sale_orders`.`id` "
                    . "WHERE `sale_orders`.`cancelled` = 0 AND `order_date`>='%s' AND `order_date`<=DATE_ADD('%s' ,INTERVAL 1 DAY)";
            $sqlQuery = sprintf($sql, $this->getTableName(), $startDate, $endDate);
            $profitSum = $this->fetchField($sqlQuery, 'profit');
            return isset($profitSum) ? floatval($profitSum) : 0;
        }

    }

}
