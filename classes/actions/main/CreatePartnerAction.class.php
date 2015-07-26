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

namespace crm\actions\main {

use crm\actions\BaseAction;
use crm\managers\PartnerManager;
use NGS;
use ngs\framework\exceptions\RedirectException;

    class CreatePartnerAction extends BaseAction {

        public function service() {
            try {
                list($name, $email, $address) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            $partnerId = PartnerManager::getInstance()->createPartner($name, $email, $address);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Partner Successfully created!';
            $this->redirect('partner/'.$partnerId);
        }

        private function getFormData() {
            $this->validateFormData();
            $name = NGS()->args()->name;
            $email = NGS()->args()->email;
            $address = "";
            if (isset(NGS()->args()->address)) {
                $address = NGS()->args()->address;
            }
            return array($name, $email, $address);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->name)) {
                throw new RedirectException('partner/create', "Partner Name can not be empty.");
            }
            if (!filter_var(NGS()->args()->email, FILTER_VALIDATE_EMAIL)) {
                throw new RedirectException('partner/create', "Invalid email address.");
            }
            $email = NGS()->args()->email;
            $partnerDtos = PartnerManager::getInstance()->selectByField('email', $email);
            if (!empty($partnerDtos)) {
                throw new RedirectException('partner/create', "Partner already exists with given email address");
            }
        }

    }

}
