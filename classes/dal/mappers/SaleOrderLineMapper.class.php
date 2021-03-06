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

        public function getProductCountInNonCancelledSaleOrders($productId, $exceptSaleOrderId, $dateBefore) {
            $sql = "SELECT SUM(quantity) AS `product_qty` FROM `%s` INNER JOIN  "
                    . " `sale_orders` ON `sale_order_id` = `sale_orders`.`id` "
                    . "WHERE `sale_orders`.`cancelled` = 0 AND `sale_orders`.`id`!=:soId AND product_id=:id";
            $sqlQuery = sprintf($sql, $this->getTableName());
            if (!empty($dateBefore)) {
                $sqlQuery .= " AND `order_date`<='$dateBefore'";
            }
            $qty = $this->fetchField($sqlQuery, 'product_qty', array("soId" => $exceptSaleOrderId, "id" => $productId));
            return isset($qty) ? floatval($qty) : 0;
        }

        public function getProductsCountInNonCancelledSaleOrders($productIds) {
            $productIdsImploded = implode(',', $productIds);
            $sql = "SELECT product_id, SUM(quantity) AS `product_qty` FROM `%s` INNER JOIN  "
                    . " `sale_orders` ON `sale_order_id` = `sale_orders`.`id` "
                    . "WHERE `sale_orders`.`cancelled` = 0 AND product_id IN (%s) GROUP BY `product_id`";
            $sqlQuery = sprintf($sql, $this->getTableName(), $productIdsImploded);
            $rows = $this->fetchRows($sqlQuery);
            return $rows;
        }

        public function getAllProductCountInNonCancelledSaleOrders($partnerId = false, $excludePartnerIdsStr = '0') {
            $sql = "SELECT product_id, SUM(quantity) AS `product_qty` FROM `%s` INNER JOIN  "
                    . " `sale_orders` ON `sale_order_id` = `sale_orders`.`id` "
                    . "WHERE `sale_orders`.`cancelled` = 0 %s GROUP by `product_id`";
            $skip = "";
            if ($partnerId > 0) {
                $skip = "AND `sale_orders`.partner_id = $partnerId";
            }
            $skip .= " AND `sale_orders`.partner_id not in ($excludePartnerIdsStr)";
            $sqlQuery = sprintf($sql, $this->getTableName(), $skip);
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
                    . "WHERE `sale_orders`.`cancelled` = 0 AND `sale_orders`.`is_expense` = 0 AND `order_date`>='%s' AND `order_date`<=DATE_ADD('%s' ,INTERVAL 1 DAY)";
            $sqlQuery = sprintf($sql, $this->getTableName(), $startDate, $endDate);
            $profitSum = $this->fetchField($sqlQuery, 'profit');
            return isset($profitSum) ? floatval($profitSum) : 0;
        }

        public function getAllNonCancelledExpenseSaleOrders($startDate, $endDate) {
            $sql = "SELECT * FROM `%s` INNER JOIN  "
                    . " `sale_orders` ON `sale_order_id` = `sale_orders`.`id` "
                    . "WHERE `sale_orders`.`cancelled` = 0 AND `sale_orders`.`is_expense` = 1 AND "
                    . "`order_date`>='%s' AND `order_date`<=DATE_ADD('%s' ,INTERVAL 1 DAY) ORDER BY `order_date` DESC";
            $sqlQuery = sprintf($sql, $this->getTableName(), $startDate, $endDate);
            return $this->fetchRows($sqlQuery);
        }

        public function getNonCancelledProductsSaleOrders($productIds, $partnerId = false) {
            $productIdsExploded = implode(',', $productIds);
            $sql = "SELECT * FROM `%s` INNER JOIN  "
                    . " `sale_orders` ON `sale_order_id` = `sale_orders`.`id` "
                    . "WHERE `sale_orders`.`cancelled` = 0 AND product_id IN (%s) %s ORDER BY `order_date` ASC";
            $partnerSql = "";
            if (!empty($partnerId)){
                $partnerSql = "AND partner_id = ". $partnerId;
            }
            $sqlQuery = sprintf($sql, $this->getTableName(), $productIdsExploded, $partnerSql);
            
            return $this->fetchRows($sqlQuery);
        }

        public function getNonCancelledProductSaleOrders($productId, $exceptSaleOrderId, $dateBefore, $excludePartnerIdsStr) {
            $sql = "SELECT * FROM `%s` INNER JOIN  "
                    . " `sale_orders` ON `sale_order_id` = `sale_orders`.`id` "
                    . "WHERE `sale_orders`.`cancelled` = 0  AND `sale_orders`.`id`!=:soId AND product_id=:id %s ORDER BY `order_date` ASC";
            $subSql = "";
            if (!empty($dateBefore)) {
                $subSql .= " AND `order_date`<='$dateBefore'";
            }
            $subSql .= " AND `sale_orders`.partner_id not in ($excludePartnerIdsStr)";
            $sqlQuery = sprintf($sql, $this->getTableName(), $subSql);
            return $this->fetchRows($sqlQuery, ['id' => $productId, "soId" => $exceptSaleOrderId]);
        }

    }

}
