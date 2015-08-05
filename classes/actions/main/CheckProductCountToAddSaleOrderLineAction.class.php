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

namespace crm\actions\main {

    use crm\actions\BaseAction;
    use crm\managers\ProductManager;
    use NGS;
    use ngs\framework\exceptions\NgsErrorException;

    class CheckProductCountToAddSaleOrderLineAction extends BaseAction {

        public function service() {
            if (!isset(NGS()->args()->product_id)) {
                 new NgsErrorException('Missing Product ID!');
            }
            if (!isset(NGS()->args()->quantity)) {
                 new NgsErrorException('Missing Product Quantity!');
            }
            $quantity = floatval(NGS()->args()->quantity);
            $product_id = intval(NGS()->args()->product_id);
            $productDto = ProductManager::getInstance()->selectByPK($product_id);
            if (!isset($productDto)) {
                 new NgsErrorException('Product does not exist with given ID: ' . NGS()->args()->product_id);
            }
            $productQuantityInStock = ProductManager::getInstance()->calculateProductQuantityInStock($product_id);
            if ($quantity > $productQuantityInStock) {
                 new NgsErrorException('Not sufficient product in stock. There is ' . $productQuantityInStock . ' in stock');
            }
        }

    }

}
