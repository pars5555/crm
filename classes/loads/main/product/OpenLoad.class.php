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
    use crm\security\RequestGroups;
    use NGS;

    class OpenLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $paymentId = NGS()->args()->id;
            $products = ProductManager::getInstance()->getProductListFull(['id', '=', $paymentId]);
            if (!empty($products)) {
                $product = $products[0];
                $this->addParam('product', $product);
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
