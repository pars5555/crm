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

namespace crm\actions\main\partner {

    use crm\actions\BaseAction;
    use crm\managers\PartnerManager;
    use NGS;
    use ngs\framework\exceptions\RedirectException;

    class UpdatePartnerAction extends BaseAction {

        public function service() {
            try {
                list($id, $name, $email, $address) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            PartnerManager::getInstance()->updatePartner($id, $name, $email, $address);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Partner Successfully updated!';
            $this->redirect('partner/' . $id);
        }

        private function getFormData() {
            $this->validateFormData();
            $id = NGS()->args()->id;
            $name = NGS()->args()->name;
            $email = NGS()->args()->email;
            $address = "";
            if (isset(NGS()->args()->address)) {
                $address = NGS()->args()->address;
            }
            return array($id, $name, $email, $address);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->id)) {
                throw new RedirectException('partner/edit', "Partner Id is missing.");
            }
            if (empty(NGS()->args()->name)) {
                throw new RedirectException('partner/edit/' . NGS()->args()->id, "Partner Name can not be empty.");
            }
            if (!filter_var(NGS()->args()->email, FILTER_VALIDATE_EMAIL)) {
                throw new RedirectException('partner/edit/' . NGS()->args()->id, "Invalid email address.");
            }
            $email = NGS()->args()->email;
            $partnerDtos = PartnerManager::getInstance()->selectByField('email', $email);
            if (!empty($partnerDtos)) {
                if ($partnerDtos[0]->getId() != NGS()->args()->id) {
                    throw new RedirectException('partner/edit/' . NGS()->args()->id, "Partner already exists with given email address");
                }
            }
        }

    }

}
    