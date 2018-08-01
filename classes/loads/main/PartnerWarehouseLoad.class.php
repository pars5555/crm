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
    use NGS;

    class PartnerWarehouseLoad extends AdminLoad {

        public function load() {
            $warehouse_partners = SettingManager::getInstance()->getSetting('warehouse_partners');
            $warehouse_partners_array = explode(',', $warehouse_partners);
            $partnerId = $warehouse_partners_array[0];
            if (!empty(NGS()->args()->partner_id)) {
                $partnerId = NGS()->args()->partner_id;
            }

            $warehousePartners = PartnerManager::getInstance()->selectAdvance('*', ['id', 'in', "($warehouse_partners)"], null, null, null, null, true);
            $productsQuantity = WarehouseManager::getInstance()->getWarehousePartnerProductsQuantity($partnerId);
            $productIds = array_keys($productsQuantity);
            $productsPrice = WarehouseManager::getInstance()->getAllProductsPrice($productIds);
            $productIdsSql = '(' . implode(',', $productIds) . ')';
            $products = ProductManager::getInstance()->getProductListFull(['id', 'in', $productIdsSql], 'name', 'ASC');

            $productIds = ProductManager::getDtosIdsArray($products);
            $productsPurchaseOrders = PurchaseOrderLineManager::getInstance()->getProductsPurchaseOrders($productIds, $partnerId);
            $productsSaleOrders = SaleOrderLineManager::getInstance()->getProductsSaleOrders($productIds, $partnerId);
            $usdRate = CurrencyRateManager::getInstance()->getCurrencyRate(1);
            $this->addParam('products', $products);
            $this->addParam('usd_rate', $usdRate);
            $this->addParam('productsQuantity', $productsQuantity);
            $this->addParam('productsPrice', $productsPrice);
            $this->addParam('productsPurchaseOrder', $productsPurchaseOrders);
            $this->addParam('productsSaleOrder', $productsSaleOrders);
            $this->addParam('selected_partner_id', $partnerId);
            $this->addParam('warehousePartners', $warehousePartners);

            $total = 0;
            foreach ($productsQuantity as $pId => $qty) {
                $total += floatval($productsPrice[$pId]) * floatval($qty);
            }
            $this->addParam('total', $total);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/partner_warehouse.tpl";
        }

    }

}
