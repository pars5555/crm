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

        public function getAllProductsPrice($productsIds) {
            $ret = [];
            $usdRate = floatval(\crm\managers\CurrencyRateManager::getInstance()->getCurrencyRate(1));
            foreach ($productsIds as $pid) {
                $prCosts = ProductManager::getInstance()->calculateProductCost($pid, 1);
                $costInUsd = ProductManager::getInstance()->calculateProductTotalCost($prCosts);
                $ret[$pid] = $costInUsd / $usdRate;
            }
            return $ret;
        }

        public function getAllProductsQuantity($partnerId = false) {

            $allProductQuantityInPurchaseOrders = PurchaseOrderLineManager::getInstance()->getAllProductCountInNonCancelledPurchaseOrders($partnerId);
            $allProductQuantityInSaleOrders = SaleOrderLineManager::getInstance()->getAllProductCountInNonCancelledSaleOrders($partnerId);
            $productQtyMappedByProductId = [];
            if ($partnerId > 0) {
                foreach ($allProductQuantityInSaleOrders as $productId => $productQty) {
                    $productQtyMappedByProductId[$productId] = $productQty;
                }
            } else {
                foreach ($allProductQuantityInPurchaseOrders as $productId => $productQty) {
                    $productQtyMappedByProductId[$productId] = $productQty;
                }
            }
            if ($partnerId > 0) {
                foreach ($allProductQuantityInPurchaseOrders as $productId => $productQty) {
                    if (!array_key_exists($productId, $productQtyMappedByProductId)) {
                        $productQtyMappedByProductId[$productId] = 0;
                    }
                    $productQtyMappedByProductId[$productId] -= $productQty;
                }
                
            } else {
                foreach ($allProductQuantityInSaleOrders as $productId => $productQty) {
                    if (!array_key_exists($productId, $productQtyMappedByProductId)) {
                        $productQtyMappedByProductId[$productId] = 0;
                    }
                    $productQtyMappedByProductId[$productId] -= $productQty;
                }
            }
            $ret = [];
            foreach ($productQtyMappedByProductId as $key => $r) {
                if ($r > 0) {
                    $ret[$key] = $r;
                }
            }
            return $ret;
        }

    }

}
