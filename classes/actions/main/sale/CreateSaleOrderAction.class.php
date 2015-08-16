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

    class CreateSaleOrderAction extends BaseAction {

        public function service() {
            try {
                list($partnerId, $date, $billingDeadlineDate, $note) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            $saleOrderId = SaleOrderManager::getInstance()->createSaleOrder($partnerId, $date, $billingDeadlineDate, $note);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Sale Order Successfully created!';
            $this->redirect('sale/' . $saleOrderId);
        }

        private function getFormData() {
            $this->validateFormData();
            $note = "";
            if (isset(NGS()->args()->note)) {
                $note = NGS()->args()->note;
            }
            $year = intval(NGS()->args()->saleOrderDateYear);
            $month = intval(NGS()->args()->saleOrderDateMonth);
            $day = intval(NGS()->args()->saleOrderDateDay);
            $hour = intval(NGS()->args()->saleOrderTimeHour);
            $minute = intval(NGS()->args()->saleOrderTimeMinute);
            $partnerId = intval(NGS()->args()->partnerId);
            $billingDeadlineYear = intval(NGS()->args()->billingDeadlineDateYear);
            $billingDeadlineMonth = intval(NGS()->args()->billingDeadlineDateMonth);
            $billingDeadlineDay = intval(NGS()->args()->billingDeadlineDateDay);
            $date = "$year-$month-$day $hour:$minute";
            $billingDeadlineDate = "$billingDeadlineYear-$billingDeadlineMonth-$billingDeadlineDay";
            return array($partnerId, $date, $billingDeadlineDate, $note);
        }

        private function validateFormData() {
            if (empty(NGS()->args()->saleOrderDateYear)) {
                throw new RedirectException('sale/create', "Invalid Date.");
            }
            if (empty(NGS()->args()->saleOrderDateMonth)) {
                throw new RedirectException('sale/create', "Invalid Date.");
            }
            if (empty(NGS()->args()->saleOrderDateDay)) {
                throw new RedirectException('sale/create', "Invalid Date.");
            }
            if (empty(NGS()->args()->saleOrderTimeHour)) {
                throw new RedirectException('sale/create', "Invalid Time.");
            }
            if (empty(NGS()->args()->saleOrderTimeMinute)) {
                throw new RedirectException('sale/create', "Invalid Time.");
            }
            if (!isset(NGS()->args()->partnerId) || !is_numeric(NGS()->args()->partnerId) || NGS()->args()->partnerId <= 0) {
                throw new RedirectException('sale/create', "Invalid Partner.");
            }
            if (empty(NGS()->args()->billingDeadlineDateYear)) {
                throw new RedirectException('sale/create', "Invalid Payment Date.");
            }
            if (empty(NGS()->args()->billingDeadlineDateMonth)) {
                throw new RedirectException('sale/create', "Invalid Payment Date.");
            }
            if (empty(NGS()->args()->billingDeadlineDateDay)) {
                throw new RedirectException('sale/create', "Invalid Payment Date.");
            }
        }

    }

}
