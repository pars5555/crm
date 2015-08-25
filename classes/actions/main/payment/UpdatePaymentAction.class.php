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

    class UpdatePaymentAction extends BaseAction {

        public function service() {
            try {
                list($id, $partnerId, $paymentMethodId, $currencyId, $amount, $date,$isExpense, $note) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            PaymentTransactionManager::getInstance()->updatePaymentOrder($id, $partnerId, $paymentMethodId, $currencyId, $amount, $date, $isExpense,$note);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Payment Successfully updated!';
            $this->redirect('payment/' . $id);
        }

        private function getFormData() {
            $this->validateFormData();
            $note = "";
            if (isset(NGS()->args()->note)) {
                $note = NGS()->args()->note;
            }
            $isExpense = 0;
            if (isset(NGS()->args()->isExpense)) {
                $isExpense = 1;
            }
            $id = intval(NGS()->args()->id);
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
            return array($id, $partnerId, $paymentMethodId, $currencyId, $amount, $date, $isExpense,$note);
        }

        private function validateFormData() {
            if (!isset(NGS()->args()->id) || !is_numeric(NGS()->args()->id) || NGS()->args()->id <= 0) {
                throw new RedirectException('payment/list', "Invalid Partner.");
            }
            if (empty(NGS()->args()->paymentDateYear)) {
                throw new RedirectException('payment/edit/' . NGS()->args()->id, "Invalid Date.");
            }
            if (empty(NGS()->args()->paymentDateMonth)) {
                throw new RedirectException('payment/edit/' . NGS()->args()->id, "Invalid Date.");
            }
            if (empty(NGS()->args()->paymentDateDay)) {
                throw new RedirectException('payment/edit/' . NGS()->args()->id, "Invalid Date.");
            }
            if (empty(NGS()->args()->paymentTimeHour)) {
                throw new RedirectException('payment/edit/' . NGS()->args()->id, "Invalid Time.");
            }
            if (empty(NGS()->args()->paymentTimeMinute)) {
                throw new RedirectException('payment/edit/' . NGS()->args()->id, "Invalid Time.");
            }
            if (!isset(NGS()->args()->partnerId) || !is_numeric(NGS()->args()->partnerId) || NGS()->args()->partnerId <= 0) {
                throw new RedirectException('payment/edit/' . NGS()->args()->id, "Invalid Partner.");
            }
            if (!isset(NGS()->args()->paymentMethodId) || !is_numeric(NGS()->args()->paymentMethodId) || NGS()->args()->paymentMethodId <= 0) {
                throw new RedirectException('payment/edit/' . NGS()->args()->id, "Invalid Payment Method.");
            }
            if (!isset(NGS()->args()->currencyId) || !is_numeric(NGS()->args()->currencyId) || NGS()->args()->currencyId <= 0) {
                throw new RedirectException('payment/edit/' . NGS()->args()->id, "Invalid Currency.");
            }
            if (!isset(NGS()->args()->amount) || !is_numeric(NGS()->args()->amount)) {
                throw new RedirectException('payment/edit/' . NGS()->args()->id, "Invalid Amount.");
            }
        }

    }

}
