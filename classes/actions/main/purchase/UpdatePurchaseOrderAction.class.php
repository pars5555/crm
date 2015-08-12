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
                list($id, $partnerId, $date, $note) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            PurchaseOrderManager::getInstance()->updatePurchaseOrder($id, $partnerId, $date, $note);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Purchase Order Successfully created!';
            $this->redirect('purchase/' . $id);
        }

        private function getFormData() {
            $this->validateFormData();
            $id = intval(NGS()->args()->id);
            $note = "";
            if (isset(NGS()->args()->note)) {
                $note = NGS()->args()->note;
            }
            $year = intval(NGS()->args()->purchaseOrderDateYear);
            $month = intval(NGS()->args()->purchaseOrderDateMonth);
            $day = intval(NGS()->args()->purchaseOrderDateDay);
            $hour = intval(NGS()->args()->purchaseOrderTimeHour);
            $minute = intval(NGS()->args()->purchaseOrderTimeMinute);
            $partnerId = intval(NGS()->args()->partnerId);
            $date = "$year-$month-$day $hour:$minute";
            return array($id, $partnerId, $date, $note);
        }

        private function validateFormData() {
            if (!isset(NGS()->args()->id) || !is_numeric(NGS()->args()->id) || NGS()->args()->id <= 0) {
                throw new RedirectException('sale/list' . NGS()->args()->id, "Invalid Purchase Order.");
            }
            if (empty(NGS()->args()->purchaseOrderDateYear)) {
                throw new RedirectException('purchase/create', "Invalid Date.");
            }
            if (empty(NGS()->args()->purchaseOrderDateMonth)) {
                throw new RedirectException('purchase/create', "Invalid Date.");
            }
            if (empty(NGS()->args()->purchaseOrderDateDay)) {
                throw new RedirectException('purchase/create', "Invalid Date.");
            }
            if (empty(NGS()->args()->purchaseOrderTimeHour)) {
                throw new RedirectException('purchase/create', "Invalid Time.");
            }
            if (empty(NGS()->args()->purchaseOrderTimeMinute)) {
                throw new RedirectException('purchase/create', "Invalid Time.");
            }
            if (!isset(NGS()->args()->partnerId) || !is_numeric(NGS()->args()->partnerId) || NGS()->args()->partnerId <= 0) {
                throw new RedirectException('purchase/create', "Invalid Partner.");
            }
        }

    }

}