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

namespace crm\actions\main\recipient {

    use crm\actions\BaseAction;
    use crm\managers\RecipientManager;
    use NGS;
    use ngs\framework\exceptions\RedirectException;

    class CreateRecipientAction extends BaseAction {

        public function service() {
            try {
                list($name, $email, $meta,$documents,$phone) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            $recipientId = RecipientManager::getInstance()->createRecipient($name, $email, $meta,$documents,$phone);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Recipient Successfully created!';
            $this->redirectToReferer();
        }

        private function getFormData() {
            $this->validateFormData();
            $name = NGS()->args()->name;
            $email = NGS()->args()->email;
            $meta = "";
            if (isset(NGS()->args()->meta)) {
                $meta = NGS()->args()->meta;
            }
            $documents= "";
            if (isset(NGS()->args()->documents)) {
                $documents = NGS()->args()->documents;
            }
            $phone = "";
            if (isset(NGS()->args()->phone)) {
                $phone = NGS()->args()->phone;
            }
            return array($name, $email, $meta,$documents,$phone);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->name)) {
                throw new RedirectException('recipient/create', "Recipient Name can not be empty.");
            }
            if (!filter_var(NGS()->args()->email, FILTER_VALIDATE_EMAIL)) {
                throw new RedirectException('recipient/create', "Invalid email address.");
            }            
        }

    }

}
