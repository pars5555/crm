<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\product {

    use crm\loads\NgsLoad;
    use crm\managers\ProductManager;
    use crm\managers\PurchaseOrderLineManager;
    use crm\managers\SaleOrderLineManager;
    use crm\security\RequestGroups;
    use NGS;

    class OpenLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $productId = NGS()->args()->id;
            $products = ProductManager::getInstance()->getProductListFull(['id', '=', $productId]);
            if (!empty($products)) {
                $productSaleQuantity = SaleOrderLineManager::getInstance()->getProductCountInNonCancelledSaleOrders($productId);
                $productPurchaseQuantity = PurchaseOrderLineManager::getInstance()->getProductCountInNonCancelledPurchaseOrders($productId);
                $productQuantity = $productPurchaseQuantity - $productSaleQuantity;
                $product = $products[0];
                $this->addParam('product', $product);
                $this->addParam('productQuantity', $productQuantity);
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/product/open.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
