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
    use crm\managers\ProductManager;
    use crm\managers\PurchaseOrderLineManager;
    use crm\managers\SaleOrderLineManager;
    use crm\managers\WarehouseManager;
    use crm\security\RequestGroups;
    use NGS;

    class WarehouseLoad  extends AdminLoad {

        public function load() {
            $productsQuantity = WarehouseManager::getInstance()->getAllProductsQuantity();
            $products = ProductManager::getInstance()->getProductListFull([], 'name', 'ASC');
            $productIds = ProductManager::getDtosIdsArray($products);
            $productsPurchaseOrders = PurchaseOrderLineManager::getInstance()->getProductsPurchaseOrders($productIds);
            $productsSaleOrders = SaleOrderLineManager::getInstance()->getProductsSaleOrders($productIds);
            $this->addParam('products', $products);
            $this->addParam('productsQuantity', $productsQuantity);
            $this->addParam('productsPurchaseOrder', $productsPurchaseOrders);
            $this->addParam('productsSaleOrder', $productsSaleOrders);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/warehouse.tpl";
        }


    }

}
