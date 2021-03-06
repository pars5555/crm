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

    use crm\dal\dto\PreorderLineDto;

    class PreorderLineMapper extends AdvancedAbstractMysqlMapper {

        private static $instance;
        public $tableName = "preorder_lines";

        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new PreorderLineMapper();
            }
            return self::$instance;
        }

        public function createDto() {
            return new PreorderLineDto();
        }

        public function getPKFieldName() {
            return "id";
        }

        public function getTableName() {
            return $this->tableName;
        }

        public function getProductCountInNonCancelledPreorders($productId) {
            $sql = "SELECT SUM(quantity) AS `product_qty` FROM `%s` INNER JOIN  "
                    . " `preorders` ON `preorder_id` = `preorders`.`id` "
                    . "WHERE `preorders`.`cancelled` = 0 AND product_id=:id";
            $sqlQuery = sprintf($sql, $this->getTableName());
            $qty = $this->fetchField($sqlQuery, 'product_qty', array("id" => $productId));
            return isset($qty) ? floatval($qty) : 0;
        }

        public function getNonCancelledProductPreorders($productId, $date, $excludePartnerIdsStr = '0') {
            $sql = "SELECT *, `preorder_lines`.`id` as `id` FROM `%s` INNER JOIN  "
                    . " `preorders` ON `preorder_id` = `preorders`.`id` "
                    . "WHERE `preorders`.`cancelled` = 0 AND product_id=:id %s %s ORDER BY `order_date` ASC";
            $skip = " AND `preorders`.partner_id not in ($excludePartnerIdsStr)";
            if ($date != null) {
                $sqlQuery = sprintf($sql, $this->getTableName(), "AND `order_date`<='" . $date . "'", $skip);
            } else {
                $sqlQuery = sprintf($sql, $this->getTableName(), '', $skip);
            }
            return $this->fetchRows($sqlQuery, array("id" => $productId));
        }

        public function getNonCancelledProductsPreorders($productIds) {
            $productIdsExploded = implode(',', $productIds);
            $sql = "SELECT *, `preorder_lines`.`id` as `id` FROM `%s` INNER JOIN  "
                    . " `preorders` ON `preorder_id` = `preorders`.`id` "
                    . "WHERE `preorders`.`cancelled` = 0 AND product_id IN (%s) ORDER BY `order_date` ASC";
            $sqlQuery = sprintf($sql, $this->getTableName(), $productIdsExploded);
            return $this->fetchRows($sqlQuery);
        }

        public function getAllProductCountInNonCancelledPreorders($partnerId = false, $excludePartnerIdsStr = '0') {
            $sql = "SELECT product_id, SUM(quantity) AS `product_qty` FROM `%s` INNER JOIN  "
                    . " `preorders` ON `preorder_id` = `preorders`.`id` "
                    . "WHERE `preorders`.`cancelled` = 0 %s GROUP by `product_id`";
            $skip = "";
            if ($partnerId > 0) {
                $skip = "AND preorders.partner_id = $partnerId";
            }
            $skip .= " AND preorders.partner_id not in ($excludePartnerIdsStr)";
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

        public function getAllProductPriceInNonCancelledPreorders() {
            $sql = "SELECT product_id, SUM(unit_price)*`currency_rate` AS `product_price` FROM `%s` INNER JOIN  "
                    . " `preorders` ON `preorder_id` = `preorders`.`id` "
                    . "WHERE `preorders`.`cancelled` = 0 GROUP by `product_id`, `currency_id`";
            $sqlQuery = sprintf($sql, $this->getTableName());
            $productIdQtyObjects = $this->fetchRows($sqlQuery);
            $ret = [];
            foreach ($productIdQtyObjects as $productIdQtyObject) {
                $product_id = intval($productIdQtyObject->product_id);
                $qty = floatval($productIdQtyObject->product_price);
                $ret [$product_id] = $qty;
            }
            return $ret;
        }

    }

}
