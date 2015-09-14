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

namespace crm\actions\main\purchase {

    use crm\actions\BaseAction;
    use crm\managers\PurchaseOrderManager;
    use NGS;
    use ngs\framework\exceptions\RedirectException;

    class CreatePurchaseOrderAction extends BaseAction {

        public function service() {
            try {
                list($partnerId, $date, $paymentDeadline, $note) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            $purchaseOrderId = PurchaseOrderManager::getInstance()->createPurchaseOrder($partnerId, $date, $paymentDeadline, $note);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Purchase Order Successfully created!';
            $this->redirect('purchase/' . $purchaseOrderId);
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
                throw new RedirectException('purchase/create', "Invalid Date.");
            }

            if (!isset(NGS()->args()->partnerId) || !is_numeric(NGS()->args()->partnerId) || NGS()->args()->partnerId <= 0) {
                throw new RedirectException('purchase/create', "Invalid Partner.");
            }
            if (empty(NGS()->args()->payment_deadline)) {
                throw new RedirectException('purchase/create', "Invalid Payment Date.");
            }
        }

    }

}
