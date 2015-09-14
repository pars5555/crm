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

    class UpdatePurchaseOrderAction extends BaseAction {

        public function service() {
            try {
                list($id, $partnerId, $date, $paymentDeadlineDate, $note) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            PurchaseOrderManager::getInstance()->updatePurchaseOrder($id, $partnerId, $date, $paymentDeadlineDate, $note);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Purchase Order Successfully updated!';
            $this->redirect('purchase/' . $id);
        }

        private function getFormData() {
            $this->validateFormData();
            $id = intval(NGS()->args()->id);
            $note = "";
            if (isset(NGS()->args()->note)) {
                $note = NGS()->args()->note;
            }
            $partnerId = intval(NGS()->args()->partnerId);
            $date = NGS()->args()->order_date;
            $paymentDeadlineDate = NGS()->args()->payment_deadline;
            return array($id, $partnerId, $date, $paymentDeadlineDate, $note);
        }

        private function validateFormData() {
            if (!isset(NGS()->args()->id) || !is_numeric(NGS()->args()->id) || NGS()->args()->id <= 0) {
                throw new RedirectException('sale/list' . NGS()->args()->id, "Invalid Purchase Order.");
            }
            if (empty(NGS()->args()->order_date)) {
                throw new RedirectException('purchase/edit/' . NGS()->args()->id, "Invalid Date.");
            }

            if (!isset(NGS()->args()->partnerId) || !is_numeric(NGS()->args()->partnerId) || NGS()->args()->partnerId <= 0) {
                throw new RedirectException('purchase/edit/' . NGS()->args()->id, "Invalid Partner.");
            }
            if (empty(NGS()->args()->payment_deadline)) {
                throw new RedirectException('purchase/edit' . NGS()->args()->id, "Invalid Payment Date.");
            }
        }

    }

}
