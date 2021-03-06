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

    use crm\dal\dto\RecipientOrderLineDto;

    class RecipientOrderLineMapper extends AdvancedAbstractMysqlMapper {

        private static $instance;
        public $tableName = "recipient_order_lines";

        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new RecipientOrderLineMapper();
            }
            return self::$instance;
        }

        public function createDto() {
            return new RecipientOrderLineDto();
        }

        public function getPKFieldName() {
            return "id";
        }

        public function getTableName() {
            return $this->tableName;
        }

        public function getProductCountInNonCancelledRecipientOrders($productId) {
            $sql = "SELECT SUM(quantity) AS `product_qty` FROM `%s` INNER JOIN  "
                    . " `recipient_orders` ON `recipient_order_id` = `recipient_orders`.`id` "
                    . "WHERE `recipient_orders`.`cancelled` = 0 AND product_id=:id";
            $sqlQuery = sprintf($sql, $this->getTableName());
            $qty = $this->fetchField($sqlQuery, 'product_qty', array("id" => $productId));
            return isset($qty) ? floatval($qty) : 0;
        }

        public function getNonCancelledProductRecipientOrders($productId, $date) {
            $sql = "SELECT *, `recipient_order_lines`.`id` as `id` FROM `%s` INNER JOIN  "
                    . " `recipient_orders` ON `recipient_order_id` = `recipient_orders`.`id` "
                    . "WHERE `recipient_orders`.`cancelled` = 0 AND product_id=:id %s ORDER BY `order_date` ASC";
            if ($date != null) {
                $sqlQuery = sprintf($sql, $this->getTableName(), "AND `order_date`<='" . $date . "'");
            } else {
                $sqlQuery = sprintf($sql, $this->getTableName(), '');
            }
            return $this->fetchRows($sqlQuery, array("id" => $productId));
        }

        public function getNonCancelledProductsRecipientOrders($productIds) {
            $productIdsExploded = implode(',', $productIds);
            $sql = "SELECT *, `recipient_order_lines`.`id` as `id` FROM `%s` INNER JOIN  "
                    . " `recipient_orders` ON `recipient_order_id` = `recipient_orders`.`id` "
                    . "WHERE `recipient_orders`.`cancelled` = 0 AND product_id IN (%s) ORDER BY `order_date` ASC";
            $sqlQuery = sprintf($sql, $this->getTableName(), $productIdsExploded);
            return $this->fetchRows($sqlQuery);
        }

        public function getAllProductCountInNonCancelledRecipientOrders() {
            $sql = "SELECT product_id, SUM(quantity) AS `product_qty` FROM `%s` INNER JOIN  "
                    . " `recipient_orders` ON `recipient_order_id` = `recipient_orders`.`id` "
                    . "WHERE `recipient_orders`.`cancelled` = 0 GROUP by `product_id`";
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

    }

}
