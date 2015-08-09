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

namespace crm\actions\main\payment {

    use crm\actions\BaseAction;
    use crm\managers\PaymentTransactionManager;
    use NGS;
    use ngs\framework\exceptions\RedirectException;

    class CreatePaymentAction extends BaseAction {

        public function service() {
            try {
                list($partnerId, $paymentMethodId, $currencyId, $amount, $date, $note) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }

            $paymentTransactionManager = PaymentTransactionManager::getInstance();
            $paymentId = $paymentTransactionManager->createPaymentOrder($partnerId, $paymentMethodId, $currencyId, $amount, $date, $note);

            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Payment Successfully created!';
            $this->redirect('payment/'.$paymentId);
        }

        private function getFormData() {
            $this->validateFormData();
            $note = "";
            if (isset(NGS()->args()->note)) {
                $note = NGS()->args()->note;
            }
            $year = intval(NGS()->args()->paymentDateYear);
            $month = intval(NGS()->args()->paymentDateMonth);
            $day = intval(NGS()->args()->paymentDateDay);
            $hour = intval(NGS()->args()->paymentTimeHour);
            $minute = intval(NGS()->args()->paymentTimeMinute);
            $partnerId = intval(NGS()->args()->partnerId);
            $paymentMethodId = intval(NGS()->args()->paymentMethodId);
            $currencyId = intval(NGS()->args()->currencyId);
            $amount = floatval(NGS()->args()->amount);
            $date = "$year-$month-$day $hour:$minute";
            return array($partnerId, $paymentMethodId, $currencyId, $amount, $date, $note);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->paymentDateYear)) {
                throw new RedirectException('payment/create', "Invalid Date.");
            }
            if (empty(NGS()->args()->paymentDateMonth)) {
                throw new RedirectException('payment/create', "Invalid Date.");
            }
            if (empty(NGS()->args()->paymentDateDay)) {
                throw new RedirectException('payment/create', "Invalid Date.");
            }
            if (empty(NGS()->args()->paymentTimeHour)) {
                throw new RedirectException('payment/create', "Invalid Time.");
            }
            if (empty(NGS()->args()->paymentTimeMinute)) {
                throw new RedirectException('payment/create', "Invalid Time.");
            }
            if (!isset(NGS()->args()->partnerId) || !is_numeric(NGS()->args()->partnerId) || NGS()->args()->partnerId <= 0) {
                throw new RedirectException('payment/create', "Invalid Partner.");
            }
            if (!isset(NGS()->args()->paymentMethodId) || !is_numeric(NGS()->args()->paymentMethodId) || NGS()->args()->paymentMethodId <= 0) {
                throw new RedirectException('payment/create', "Invalid Payment Method.");
            }
            if (!isset(NGS()->args()->currencyId) || !is_numeric(NGS()->args()->currencyId) || NGS()->args()->currencyId <= 0) {
                throw new RedirectException('payment/create', "Invalid Currency.");
            }
            if (!isset(NGS()->args()->amount) || !is_numeric(NGS()->args()->amount)) {
                throw new RedirectException('payment/create', "Invalid Amount.");
            }
        }

    }

}
