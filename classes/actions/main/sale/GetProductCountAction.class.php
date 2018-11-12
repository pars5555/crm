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

namespace crm\actions\main\sale {

    use crm\actions\BaseAction;
    use crm\managers\ProductManager;
    use NGS;
    use ngs\framework\exceptions\NgsErrorException;

    class getProductCountAction extends BaseAction {

        public function service() {
            if (!isset(NGS()->args()->product_id)) {
                new NgsErrorException('Missing Product ID!');
            }
            $product_id = intval(NGS()->args()->product_id);
            $productDto = ProductManager::getInstance()->selectByPk($product_id);
            if (!isset($productDto)) {
                new NgsErrorException('Product does not exist with given ID: ' . NGS()->args()->product_id);
            }
            $productQuantityInStock = ProductManager::getInstance()->calculateProductQuantityInStock($product_id);
            $this->addParam('quantity', $productQuantityInStock);
        }

    }

}
