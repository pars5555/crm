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

    class UpdateManufacturerAction extends BaseAction {

        public function service() {
            try {
                list($id, $name, $link) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            ManufacturerManager::getInstance()->updateManufacturer($id, $name, $link);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Manufacturer Successfully created!';
            $this->redirect('manufacturer/edit/' . $id);
        }

        private function getFormData() {
            $this->validateFormData();
            $id = intval(NGS()->args()->id);
            $name = NGS()->args()->name;
            $link = "";
            if (isset(NGS()->args()->link)) {
                $link = NGS()->args()->link;
            }
            return array($id, $name, $link);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->name)) {
                throw new RedirectException('manufacturer/create', "Manufacturer Name can not be empty.");
            }
        }

    }

}
