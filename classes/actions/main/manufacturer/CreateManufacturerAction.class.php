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

namespace crm\actions\main\manufacturer {

    use crm\actions\BaseAction;
    use crm\managers\ManufacturerManager;
    use NGS;
    use ngs\framework\exceptions\RedirectException;

    class CreateManufacturerAction extends BaseAction {

        public function service() {
            try {
                list($name, $link) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            $manufacturerId = ManufacturerManager::getInstance()->createManufacturer($name, $link);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Manufacturer Successfully created!';
            $this->redirect('manufacturer/list');
        }

        private function getFormData() {
            $this->validateFormData();
            $name = NGS()->args()->name;
            $link = "";
            if (isset(NGS()->args()->link)) {
                $link = NGS()->args()->link;
            }
            return array($name, $link);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->name)) {
                throw new RedirectException('manufacturer/create', "Partner Name can not be empty.");
            }
        }

    }

}
