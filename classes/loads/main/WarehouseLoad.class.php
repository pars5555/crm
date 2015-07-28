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
use crm\managers\WarehouseManager;
use crm\security\RequestGroups;
use NGS;

    class WarehouseLoad extends NgsLoad {

        public function load() {
            $productsQuantity = WarehouseManager::getInstance()->getAllProductsQuantity();
            $products = ProductManager::getInstance()->getProductListFull([], 'name', 'ASC');
            $this->addParam('products', $products);
            $this->addParam('productsQuantity', $productsQuantity);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/warehouse.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
