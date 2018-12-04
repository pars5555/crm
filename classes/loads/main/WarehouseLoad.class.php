<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main {

use crm\loads\AdminLoad;
use crm\managers\CurrencyRateManager;
use crm\managers\PartnerManager;
use crm\managers\ProductManager;
use crm\managers\PurchaseOrderLineManager;
use crm\managers\SaleOrderLineManager;
use crm\managers\SettingManager;
use crm\managers\WarehouseManager;
use crm\security\RequestGroups;
use NGS;

    class WarehouseLoad extends AdminLoad {

        public function load() {
            $pwarehousesProductsQuantity = $this->loadPartnersWarehouses();

            $productsQuantity = WarehouseManager::getInstance()->getAllProductsQuantity();
            $productsPrice = WarehouseManager::getInstance()->getAllProductsPrice(array_keys($productsQuantity));
            $productsMappedById = ProductManager::getInstance()->getProductListFull([], 'name', 'ASC');
            $productIds = array_keys($productsMappedById);
            
            $productsPurchaseOrders = PurchaseOrderLineManager::getInstance()->getProductsPurchaseOrders($productIds);
            $productsSaleOrders = SaleOrderLineManager::getInstance()->getProductsSaleOrders($productIds);
            $partnerIds = [];
            foreach ($productsSaleOrders as $sos) {
                foreach ($sos as $so) {
                    $partnerIds[] = intval($so->getPartnerId());
                }
            }
            foreach ($productsPurchaseOrders as $pos) {
                foreach ($pos as $po) {
                    $partnerIds[] = intval($po->getPartnerId());
                }
            }
            $partnerIdsSql = implode(',', array_unique($partnerIds));
            $partnersMappedByIds = PartnerManager::getInstance()->selectAdvance(['name', 'id'], ['id', 'in', "($partnerIdsSql)"], null, null, null, null, true);
            $usdRate = CurrencyRateManager::getInstance()->getCurrencyRate(1);



            $this->addParam('pwarehousesProductsQuantity', $pwarehousesProductsQuantity);
            $this->addParam('products', $productsMappedById);
            $this->addParam('usd_rate', $usdRate);
            $this->addParam('productsQuantity', $productsQuantity);
            $this->addParam('productsPrice', $productsPrice);
            $this->addParam('productsPurchaseOrder', $productsPurchaseOrders);
            $this->addParam('productsSaleOrder', $productsSaleOrders);
            $this->addParam('partnersMappedByIds', $partnersMappedByIds);
            $total = 0;
            $totalStock = 0;
            foreach ($productsQuantity as $pId => $qty) {
                $total += floatval($productsPrice[$pId]) * floatval($qty);
                $productStockPrice = $productsMappedById[$pId]->getStockPrice();
                if ($productStockPrice <= 0.01) {
                    $productStockPrice = floatval($productsPrice[$pId]);
                }
                $totalStock += floatval($productStockPrice) * floatval($qty);
            }
            $this->addParam('total', $total);
            $this->addParam('total_stock', $totalStock);
            $this->addParam('showprofit', isset($_COOKIE['showprofit']) ? $_COOKIE['showprofit'] : 0);
            $this->addParam('vahagn_cookie', isset($_COOKIE['vahagn']) ? $_COOKIE['vahagn'] : 0);
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/warehouse.tpl";
        }

        private function loadPartnersWarehouses() {
            $warehouse_partners = SettingManager::getInstance()->getSetting('warehouse_partners');
            $warehouse_partners_array = explode(',', $warehouse_partners);
            $productIds = [];
            $pwarehousesProductsQuantity = [];
            foreach ($warehouse_partners_array as $partnerId) {
                $productsQuantity = WarehouseManager::getInstance()->getWarehousePartnerProductsQuantity($partnerId);
                foreach ($productsQuantity as $productId => $qty) {
                    if (!isset($pwarehousesProductsQuantity[$productId])) {
                        $pwarehousesProductsQuantity[$productId] = 0;
                    }
                    $pwarehousesProductsQuantity[$productId] += $qty;
                }
                $productIds = array_merge(array_keys($productsQuantity));
            }
            return $pwarehousesProductsQuantity;
        }

    }

}
