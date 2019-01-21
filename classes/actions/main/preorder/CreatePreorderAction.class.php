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

namespace crm\actions\main\preorder {

    use crm\actions\BaseAction;
    use crm\managers\PreorderManager;
    use NGS;
    use ngs\framework\exceptions\RedirectException;

    class CreatePreorderAction extends BaseAction {

        public function service() {
            try {
                list($partnerId, $date, $paymentDeadline, $note) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            $preorderId = PreorderManager::getInstance()->createPreorder($partnerId, $date, $paymentDeadline, $note);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Preorder Order Successfully created!';
            $this->redirect('preorder/' . $preorderId);
        }

        private function getFormData() {
            $this->validateFormData();
            $note = "";
            if (isset(NGS()->args()->note)) {
                $note = NGS()->args()->note;
            }
            $partnerId = intval(NGS()->args()->partnerId);
            $date = NGS()->args()->order_date;
            $paymentDeadlineDate = NGS()->args()->payment_deadline;
            return array($partnerId, $date, $paymentDeadlineDate, $note);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->order_date)) {
                throw new RedirectException('preorder/create', "Invalid Date.");
            }

            if (!isset(NGS()->args()->partnerId) || !is_numeric(NGS()->args()->partnerId) || NGS()->args()->partnerId <= 0) {
                throw new RedirectException('preorder/create', "Invalid Partner.");
            }
            if (empty(NGS()->args()->payment_deadline)) {
                throw new RedirectException('preorder/create', "Invalid Payment Date.");
            }
        }

    }

}
