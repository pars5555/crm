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

    class UpdateProductAction extends BaseAction {

        public function service() {
            try {
                list($id, $name, $model, $manufacturerId, $uomId, $weight) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            ProductManager::getInstance()->updateProduct($id, $name, $model, $manufacturerId, $uomId, $weight);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Product Successfully updated!';
            $this->redirect('product/' . $id);
        }

        private function getFormData() {
            $this->validateFormData();
            $name = NGS()->args()->name;
            $id = intval(NGS()->args()->id);
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
            return array($id, $name, $model, $manufacturerId, $uomId, $weight);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->id)) {
                throw new RedirectException('product/list', "Missing product ID.");
            }
            if (empty(NGS()->args()->name)) {
                throw new RedirectException('product/edit/' . NGS()->args()->id, "Please input product Name.");
            }
            if (empty(NGS()->args()->uomId)) {
                throw new RedirectException('product/edit/' . NGS()->args()->id, "Please select product's Units of Measurement.");
            }
        }

    }

}
