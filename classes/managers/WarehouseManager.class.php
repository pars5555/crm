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

    class WarehouseManager {

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
                self::$instance = new WarehouseManager();
            }
            return self::$instance;
        }

        public function getAllProductsQuantity() {
            $allProductQuantityInPurchaseOrders = PurchaseOrderLineManager::getInstance()->getAllProductCountInNonCancelledPurchaseOrders();
            $allProductQuantityInSaleOrders = SaleOrderLineManager::getInstance()->getAllProductCountInNonCancelledSaleOrders();
            $ret = [];
            foreach ($allProductQuantityInPurchaseOrders as $productId => $productQty) {
                $ret[$productId] = $productQty;
            }
            foreach ($allProductQuantityInSaleOrders as $productId => $productQty) {
                if (!array_key_exists($productId, $ret)) {
                    $ret[$productId] = 0;
                }
                $ret[$productId] -= $productQty;
            }
            return $ret;
        }

    }

}
