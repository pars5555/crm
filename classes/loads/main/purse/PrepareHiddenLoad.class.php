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

namespace crm\loads\main\purse {

    use crm\loads\AdminLoad;
    use crm\managers\ProductManager;
    use crm\managers\PurseOrderManager;
    use NGS;

    class PrepareHiddenLoad extends AdminLoad {

        public function load() {
            $id = intval(NGS()->args()->id);
            $order = PurseOrderManager::getInstance()->selectByPk($id);

            $meta = json_decode($order->getMeta());
            if (empty($meta) || !isset($meta->items) || empty($meta)) {
                $meta = new \stdClass();
                $product = new \stdClass();
                $product->name = $order->getProductName();
                $product->quantity = intval($order->getQuantity());
                $product->fiat_price = $order->getAmazonTotal() / intval(max($product->quantity, 1));
                if ($order->getSupposedPurchasePrice() > 0) {
                    $product->fiat_price = floatval($order->getSupposedPurchasePrice()) / intval(max($product->quantity, 1));
                }
                $meta->items = [$product];
            }
            $ret = [];
            foreach ($meta->items as $item) {
                $name = $item->name;
                $quantity = intval($item->quantity);
                $price = floatval($item->fiat_price) / intval(max($quantity, 1));
                if ($order->getSupposedPurchasePrice() > 0) {
                    $product->fiat_price = floatval($order->getSupposedPurchasePrice()) / intval(max($quantity, 1));
                }
                list($product, $allProductSortBySimilatity) = ProductManager::getInstance()->getMostSimilarProduct($name);
                $ret[] = ['product_list' => $allProductSortBySimilatity, 'product' => $product, 'actual_name' => $name, 'quantity' => $quantity, 'purchase_price' => $price];
            }
            $this->addParam('data', $ret);
            $this->addParam('purse_order_id', $id);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/purse/create_purchase.tpl";
        }

    }

}
