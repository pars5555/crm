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

namespace crm\loads\main\product {

    use crm\loads\AdminLoad;
    use crm\managers\ProductManager;
    use NGS;

    class PrepareMergeLoad extends AdminLoad {

        public function load() {
            $id = intval(NGS()->args()->id);
            $product = ProductManager::getInstance()->selectByPk($id);
            list($similarProduct, $allProductSortBySimilatity) = ProductManager::getInstance()->getMostSimilarProduct($product->getName());
            $this->addParam('products', $allProductSortBySimilatity);
            $this->addParam('product', $product);
            $this->addParam('dst_product', $similarProduct);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/product/merge_and_delete.tpl";
        }

    }

}
