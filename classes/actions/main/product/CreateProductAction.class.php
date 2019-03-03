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
    use ngs\framework\exceptions\RedirectException;

    class CreateProductAction extends BaseAction {

        public function service() {
            try {
                list($name, $model, $manufacturerId, $uomId, $weight) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            $manufacturer = \crm\managers\ManufacturerManager::getInstance()->selectByPk($manufacturerId);
            $productId = ProductManager::getInstance()->createProduct($name, $model, $manufacturer->getName(), $uomId, $weight);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Product Successfully created!';
            $this->redirect('product/create');
        }

        private function getFormData() {
            $this->validateFormData();
            $name = NGS()->args()->name;
            $model = "";
            if (isset(NGS()->args()->model)) {
                $model = NGS()->args()->model;
            }
            $weight = 0;
            if (isset(NGS()->args()->weight)) {
                $weight = floatval(NGS()->args()->weight);
            }
            $uomId = intval(NGS()->args()->uomId);
            $manufacturerId = intval(NGS()->args()->manufacturerId);
            return array($name, $model, $manufacturerId, $uomId, $weight);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->name)) {
                throw new RedirectException('product/create', "Please input product Name.");
            }
            if (empty(NGS()->args()->uomId)) {
                throw new RedirectException('product/create', "Please select product's Units of Measurement.");
            }
        }

    }

}
