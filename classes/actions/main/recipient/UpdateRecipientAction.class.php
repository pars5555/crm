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
    use crm\managers\PecipientManager;
    use NGS;
    use ngs\framework\exceptions\RedirectException;

    class UpdatePecipientAction extends BaseAction {

        public function service() {
            try {
                list($id, $name, $email, $address, $phone, $initialDebts) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            PecipientManager::getInstance()->updatePecipient($id, $name, $email, $address, $phone, $initialDebts);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Pecipient Successfully updated!';
            $this->redirect('recipient/' . $id);
        }

        private function getFormData() {
            $this->validateFormData();
            $id = NGS()->args()->id;
            $name = NGS()->args()->name;
            $email = NGS()->args()->email;
            $initialDebts = NGS()->args()->initialDebts;
            $initialDebtsDecoded = [];
            if (!empty($initialDebts)) {
                foreach ($initialDebts as $initialDebtJson) {
                    $initialDebtObject = json_decode($initialDebtJson);
                    $initialDebtsDecoded[] = $initialDebtObject;
                }
            }
            $address = "";
            if (isset(NGS()->args()->address)) {
                $address = NGS()->args()->address;
            }
            $phone = "";
            if (isset(NGS()->args()->phone)) {
                $phone = NGS()->args()->phone;
            }
            return array($id, $name, $email, $address, $phone, $initialDebtsDecoded);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->id)) {
                throw new RedirectException('recipient/edit', "Pecipient Id is missing.");
            }
            if (empty(NGS()->args()->name)) {
                throw new RedirectException('recipient/edit/' . NGS()->args()->id, "Pecipient Name can not be empty.");
            }
            if (!filter_var(NGS()->args()->email, FILTER_VALIDATE_EMAIL)) {
                throw new RedirectException('recipient/edit/' . NGS()->args()->id, "Invalid email address.");
            }
            $email = NGS()->args()->email;
            $recipientDtos = PecipientManager::getInstance()->selectByField('email', $email);
            if (!empty($recipientDtos)) {
                if ($recipientDtos[0]->getId() != NGS()->args()->id) {
                    throw new RedirectException('recipient/edit/' . NGS()->args()->id, "Pecipient already exists with given email address");
                }
            }
        }

    }

}
    