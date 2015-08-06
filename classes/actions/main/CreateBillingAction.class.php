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
    use crm\managers\PaymentTransactionManager;
    use NGS;
    use ngs\framework\exceptions\RedirectException;

    class CreateBillingAction extends BaseAction {

        public function service() {
            try {
                list($partnerId, $billingMethodId, $currencyId, $amount, $date, $note) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }

            $paymentTransactionManager = PaymentTransactionManager::getInstance();
            $billingId = $paymentTransactionManager->createPaymentOrder($partnerId, $billingMethodId, $currencyId, -$amount, $date, $note);

            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Billing Successfully created!';
            $this->redirect('billing/' . $billingId);
        }

        private function getFormData() {
            $this->validateFormData();
            $note = "";
            if (isset(NGS()->args()->note)) {
                $note = NGS()->args()->note;
            }
            $year = intval(NGS()->args()->billingDateYear);
            $month = intval(NGS()->args()->billingDateMonth);
            $day = intval(NGS()->args()->billingDateDay);
            $hour = intval(NGS()->args()->billingTimeHour);
            $minute = intval(NGS()->args()->billingTimeMinute);
            $partnerId = intval(NGS()->args()->partnerId);
            $billingMethodId = intval(NGS()->args()->billingMethodId);
            $currencyId = intval(NGS()->args()->currencyId);
            $amount = floatval(NGS()->args()->amount);
            $date = "$year-$month-$day $hour:$minute";
            return array($partnerId, $billingMethodId, $currencyId, $amount, $date, $note);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->billingDateYear)) {
                throw new RedirectException('billing/create', "Invalid Date.");
            }
            if (empty(NGS()->args()->billingDateMonth)) {
                throw new RedirectException('billing/create', "Invalid Date.");
            }
            if (empty(NGS()->args()->billingDateDay)) {
                throw new RedirectException('billing/create', "Invalid Date.");
            }
            if (empty(NGS()->args()->billingTimeHour)) {
                throw new RedirectException('billing/create', "Invalid Time.");
            }
            if (empty(NGS()->args()->billingTimeMinute)) {
                throw new RedirectException('billing/create', "Invalid Time.");
            }
            if (!isset(NGS()->args()->partnerId) || !is_numeric(NGS()->args()->partnerId) || NGS()->args()->partnerId <= 0) {
                throw new RedirectException('billing/create', "Invalid Partner.");
            }
            if (!isset(NGS()->args()->billingMethodId) || !is_numeric(NGS()->args()->billingMethodId) || NGS()->args()->billingMethodId <= 0) {
                throw new RedirectException('billing/create', "Invalid Payment Method.");
            }
            if (!isset(NGS()->args()->currencyId) || !is_numeric(NGS()->args()->currencyId) || NGS()->args()->currencyId <= 0) {
                throw new RedirectException('billing/create', "Invalid Currency.");
            }
            if (!isset(NGS()->args()->amount) || !is_numeric(NGS()->args()->amount)) {
                throw new RedirectException('billing/create', "Invalid Amount.");
            }
        }

    }

}
