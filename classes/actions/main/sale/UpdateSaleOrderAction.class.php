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

namespace crm\actions\main\sale {

    use crm\actions\BaseAction;
    use crm\managers\SaleOrderManager;
    use NGS;
    use ngs\framework\exceptions\RedirectException;

    class UpdateSaleOrderAction extends BaseAction {

        public function service() {
            try {
                list($id, $partnerId, $date, $billingDeadlineDate, $isExpense, $note) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            SaleOrderManager::getInstance()->updateSaleOrder($id, $partnerId, $date, $billingDeadlineDate, $isExpense, $note);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Sale Order Successfully updated!';
            $this->redirect('sale/' . $id);
        }

        private function getFormData() {
            $this->validateFormData();
            $id = intval(NGS()->args()->id);
            $note = "";
            if (isset(NGS()->args()->note)) {
                $note = NGS()->args()->note;
            }
            $isExpense = 0;
            if (isset(NGS()->args()->isExpense)) {
                $isExpense = 1;
            }
            $partnerId = intval(NGS()->args()->partnerId);
            $date = NGS()->args()->order_date;
            $billingDeadlineDate = NGS()->args()->billing_deadline;
            return array($id, $partnerId, $date, $billingDeadlineDate, $isExpense, $note);
        }

        private function validateFormData() {
            if (!isset(NGS()->args()->id) || !is_numeric(NGS()->args()->id) || NGS()->args()->id <= 0) {
                throw new RedirectException('sale/list' . NGS()->args()->id, "Invalid Sale Order.");
            }
            if (empty(NGS()->args()->order_date)) {
                throw new RedirectException('sale/edit/' . NGS()->args()->id, "Invalid Date.");
            }
            if (!isset(NGS()->args()->partnerId) || !is_numeric(NGS()->args()->partnerId) || NGS()->args()->partnerId <= 0) {
                throw new RedirectException('sale/edit/' . NGS()->args()->id, "Invalid Partner.");
            }
            if (empty(NGS()->args()->billing_deadline)) {
                throw new RedirectException('sale/edit' . NGS()->args()->id, "Invalid Payment Date.");
            }
        }

    }

}
