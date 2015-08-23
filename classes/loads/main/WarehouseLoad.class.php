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

use crm\loads\NgsLoad;
use crm\managers\ProductManager;
use crm\managers\PurchaseOrderLineManager;
use crm\managers\WarehouseManager;
use crm\security\RequestGroups;
use NGS;

    class WarehouseLoad extends NgsLoad {

        public function load() {
            $productsQuantity = WarehouseManager::getInstance()->getAllProductsQuantity();
            $products = ProductManager::getInstance()->getProductListFull([], 'name', 'ASC');
            $productIds = ProductManager::getDtosIdsArray($products);
            $productsPrice = ProductManager::getInstance()->calculateProductsCost($productIds);
            $productsPurchaseOrder = PurchaseOrderLineManager::getInstance()->getProductsPurchaseOrders($productIds);
            $this->addParam('products', $products);
            $this->addParam('productsQuantity', $productsQuantity);
            $this->addParam('productsPrice', $productsPrice);
            $this->addParam('productsPurchaseOrder', $productsPurchaseOrder);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/warehouse.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
