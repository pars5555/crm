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

namespace crm\actions\main\billing {

    use crm\actions\BaseAction;
    use crm\managers\PaymentTransactionManager;
    use NGS;
    use ngs\framework\exceptions\RedirectException;

    class UpdateBillingAction extends BaseAction {

        public function service() {
            try {
                list($id, $partnerId, $billingMethodId, $currencyId, $amount, $date, $note, $signature) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }

            $paymentTransactionManager = PaymentTransactionManager::getInstance();
            $paymentTransactionManager->updatePaymentOrder($id, $partnerId, $billingMethodId, $currencyId, -$amount, $date, $note, $signature);

            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Billing Successfully created!';
            $this->redirect('billing/edit/' . $id);
        }

        private function getFormData() {
            $this->validateFormData();
            $note = "";
            if (isset(NGS()->args()->note)) {
                $note = NGS()->args()->note;
            }
            $id = intval(NGS()->args()->id);
            $year = intval(NGS()->args()->billingDateYear);
            $month = intval(NGS()->args()->billingDateMonth);
            $day = intval(NGS()->args()->billingDateDay);
            $hour = intval(NGS()->args()->billingTimeHour);
            $minute = intval(NGS()->args()->billingTimeMinute);
            $partnerId = intval(NGS()->args()->partnerId);
            $billingMethodId = intval(NGS()->args()->billingMethodId);
            $currencyId = intval(NGS()->args()->currencyId);
            $amount = floatval(NGS()->args()->amount);
            $signature = NGS()->args()->signature;
            $date = "$year-$month-$day $hour:$minute";
            return array($id, $partnerId, $billingMethodId, $currencyId, $amount, $date, $note, $signature);
        }

        private function validateFormData() {
            if (!isset(NGS()->args()->id) || !is_numeric(NGS()->args()->id) || NGS()->args()->id <= 0) {
                throw new RedirectException('billing/list', "Invalid Billing.");
            }
            if (empty(NGS()->args()->billingDateYear)) {
                throw new RedirectException('billing/edit/' . NGS()->args()->id, "Invalid Date.");
            }
            if (empty(NGS()->args()->billingDateMonth)) {
                throw new RedirectException('billing/edit/' . NGS()->args()->id, "Invalid Date.");
            }
            if (empty(NGS()->args()->billingDateDay)) {
                throw new RedirectException('billing/edit/' . NGS()->args()->id, "Invalid Date.");
            }
            if (empty(NGS()->args()->billingTimeHour)) {
                throw new RedirectException('billing/edit/' . NGS()->args()->id, "Invalid Time.");
            }
            if (empty(NGS()->args()->billingTimeMinute)) {
                throw new RedirectException('billing/edit/' . NGS()->args()->id, "Invalid Time.");
            }
            if (!isset(NGS()->args()->partnerId) || !is_numeric(NGS()->args()->partnerId) || NGS()->args()->partnerId <= 0) {
                throw new RedirectException('billing/edit/' . NGS()->args()->id, "Invalid Partner.");
            }
            if (!isset(NGS()->args()->billingMethodId) || !is_numeric(NGS()->args()->billingMethodId) || NGS()->args()->billingMethodId <= 0) {
                throw new RedirectException('billing/edit/' . NGS()->args()->id, "Invalid Payment Method.");
            }
            if (!isset(NGS()->args()->currencyId) || !is_numeric(NGS()->args()->currencyId) || NGS()->args()->currencyId <= 0) {
                throw new RedirectException('billing/edit/' . NGS()->args()->id, "Invalid Currency.");
            }
            if (!isset(NGS()->args()->amount) || !is_numeric(NGS()->args()->amount)) {
                throw new RedirectException('billing/edit/' . NGS()->args()->id, "Invalid Amount.");
            }
        }

    }

}
