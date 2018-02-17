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

namespace crm\actions\main\rorder {

    use crm\actions\BaseAction;
    use crm\managers\RecipientOrderManager;
    use NGS;
    use ngs\framework\exceptions\RedirectException;

    class CreateRecipientOrderAction extends BaseAction {

        public function service() {
            try {
                list($recipientId, $date, $paymentDeadline, $note) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            $recipientOrderId = RecipientOrderManager::getInstance()->createRecipientOrder($recipientId, $date, $note);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Order Successfully created!';
            $this->redirect('rorder/' . $recipientOrderId);
        }

        private function getFormData() {
            $this->validateFormData();
            $note = "";
            if (isset(NGS()->args()->note)) {
                $note = NGS()->args()->note;
            }
            $recipientId = intval(NGS()->args()->recipientId);
            $date = NGS()->args()->order_date;
            return array($recipientId, $date, $note);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->order_date)) {
                throw new RedirectException('rorder/create', "Invalid Date.");
            }

            if (!isset(NGS()->args()->recipientId) || !is_numeric(NGS()->args()->recipientId) || NGS()->args()->recipientId <= 0) {
                throw new RedirectException('rorder/create', "Invalid Recipient.");
            }
        }

    }

}
