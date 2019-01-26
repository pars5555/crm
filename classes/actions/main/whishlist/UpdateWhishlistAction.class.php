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

namespace crm\actions\main\whishlist {

    use crm\actions\BaseAction;
    use crm\managers\WhishlistManager;
    use NGS;
    use ngs\framework\exceptions\RedirectException;

    class UpdateWhishlistAction extends BaseAction {

        public function service() {
            try {
                list($id, $name, $asin_list, $target_price) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
             WhishlistManager::getInstance()->updateRow($id, $name, $asin_list, $target_price);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Whishlist Order Successfully updated!';
            $this->redirect('whishlist/' . $id);
        }

         private function getFormData() {
            $this->validateFormData();
            $id= intval(NGS()->args()->id);
            $name = NGS()->args()->name;
            $asin_list= NGS()->args()->asin_list;
            $asin_list = preg_replace('/\s+/', '', $asin_list);
            $target_price= floatval(NGS()->args()->target_price);
            return array($id, $name, $asin_list, $target_price);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->id)) {
                throw new RedirectException('whishlist/create', "Invalid Id: ". NGS()->args()->id);
            }
            if (empty(NGS()->args()->name)) {
                throw new RedirectException('whishlist/create', "Please input Product Name");
            }
        }

    }

}
