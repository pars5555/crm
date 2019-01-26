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

    class CreateWhishlistAction extends BaseAction {

        public function service() {
            try {
                list($name, $asin_list, $target_price) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            $whishlistId = WhishlistManager::getInstance()->addRow($name, $asin_list, $target_price);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Whishlist Order Successfully created!';
            $this->redirect('whishlist/' . $whishlistId);
        }

        private function getFormData() {
            $this->validateFormData();
            $name = NGS()->args()->name;
            $asin_list= NGS()->args()->asin_list;
            $stripped = preg_replace('/\s+/', ' ', $sentence);
            $target_price= floatval(NGS()->args()->target_price);
            return array($name, $asin_list, $target_price);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->name)) {
                throw new RedirectException('whishlist/create', "Please input Product Name");
            }
        }

    }

}
