<?php

/**
 * main site action for all ngs site's actions
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2014
 * @package actions.site
 * @version 6.0
 *
 */

namespace crm\actions\cron {

    use crm\actions\BaseAction;
    use crm\managers\ProductManager;
    use crm\managers\PurseOrderManager;

    class SetProductsImagesAction extends BaseAction {

        public function service() {
            $allOrders = PurseOrderManager::getInstance()->selectAdvance(['id', 'product_name', 'image_url'], [], [], "", null, null, false, "", 'GROUP BY product_name');
            $products = ProductManager::getInstance()->selectAll();
            foreach ($products as $product) {
                ProductManager::getInstance()->findAndSetProoductImageFromPurseOrders($product, $allOrders);
            }
        }

    }

}
