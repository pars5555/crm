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
            $productsMappedById = ProductManager::getInstance()->selectAdvance('*',[], 'category_id', 'ASC', null,null,true);
            foreach ($productsIds as $pid) {
                $prCosts = ProductManager::getInstance()->calculateProductCost($pid, 1, 0, true, true);
                $costInUsd = ProductManager::getInstance()->calculateProductTotalCost($prCosts);
                $ret[$pid] = $costInUsd / $usdRate;
                if (floatval($ret[$pid]) > floatval($productsMappedById[$pid]->getStockPrice()) && floatval($productsMappedById[$pid]->getStockPrice()) > 1) {
                    $ret[$pid] = floatval($productsMappedById[$pid]->getStockPrice());
                }
            }
            return $ret;
        }

        public function getWarehousePartnerProductsQuantity($partnerId) {
            $allProductQuantityInPurchaseOrders = PurchaseOrderLineManager::getInstance()->getAllProductCountInNonCancelledPurchaseOrders($partnerId);
            $allProductQuantityInSaleOrders = SaleOrderLineManager::getInstance()->getAllProductCountInNonCancelledSaleOrders($partnerId);
            $productQtyMappedByProductId = [];
            foreach ($allProductQuantityInSaleOrders as $productId => $productQty) {
                $productQtyMappedByProductId[$productId] = $productQty;
            }
            foreach ($allProductQuantityInPurchaseOrders as $productId => $productQty) {
                if (!array_key_exists($productId, $productQtyMappedByProductId)) {
                    $productQtyMappedByProductId[$productId] = 0;
                }
                $productQtyMappedByProductId[$productId] -= $productQty;
            }
            $ret = [];
            foreach ($productQtyMappedByProductId as $key => $r) {
                $ret[$key] = $r;
            }
            return $ret;
        }

        public function getAllProductsQuantity($includePartnerWarehase = False) {
            $excludePartnerIdsStr = '0';
            if ($includePartnerWarehase) {
                $excludePartnerIdsStr = SettingManager::getInstance()->getSetting('warehouse_partners');
            }

            $allProductQuantityInPurchaseOrders = PurchaseOrderLineManager::getInstance()->getAllProductCountInNonCancelledPurchaseOrders(false, $excludePartnerIdsStr);
            $allProductQuantityInSaleOrders = SaleOrderLineManager::getInstance()->getAllProductCountInNonCancelledSaleOrders(false, $excludePartnerIdsStr);
            $productQtyMappedByProductId = [];
            foreach ($allProductQuantityInPurchaseOrders as $productId => $productQty) {
                $productQtyMappedByProductId[$productId] = $productQty;
            }
            foreach ($allProductQuantityInSaleOrders as $productId => $productQty) {
                if (!array_key_exists($productId, $productQtyMappedByProductId)) {
                    $productQtyMappedByProductId[$productId] = 0;
                }
                $productQtyMappedByProductId[$productId] -= $productQty;
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
