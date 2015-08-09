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

namespace crm\actions\main\product {

    use crm\actions\BaseAction;
    use crm\managers\ProductManager;
    use NGS;

    class DeleteProductAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $productId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Product ID is missing';
                $this->redirect('product/list');
            }
            $productManager = ProductManager::getInstance();
            $productDto = $productManager->selectByPK($productId);
            if (!isset($productDto)) {
                $_SESSION['error_message'] = 'Product with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('product/list');
            }
            $res = $productManager->safeDeleteProduct($productId);
            if ($res) {
                $_SESSION['success_message'] = 'Product Successfully deleted!';
            } else {
                $_SESSION['error_message'] = 'Product can not be deleted. There are Sale and/or Purchase Order(s) that contains this product.';
                $this->redirect('product/list');
            }
            $this->redirect('product/list');
        }

    }

}
