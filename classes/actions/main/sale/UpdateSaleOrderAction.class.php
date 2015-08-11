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
                list($id, $partnerId, $date, $note) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            SaleOrderManager::getInstance()->updateSaleOrder($id, $partnerId, $date, $note);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Sale Order Successfully updated!';
            $this->redirect('sale/' . $id);
        }

        private function getFormData() {
            $this->validateFormData();
            $id = intval(NGS()->args()->id);
            $note = "";
            if (isset(NGS()->args()->note)) {
                $note = intval(NGS()->args()->note);
            }
            $year = intval(NGS()->args()->saleOrderDateYear);
            $month = intval(NGS()->args()->saleOrderDateMonth);
            $day = intval(NGS()->args()->saleOrderDateDay);
            $hour = intval(NGS()->args()->saleOrderTimeHour);
            $minute = intval(NGS()->args()->saleOrderTimeMinute);
            $partnerId = intval(NGS()->args()->partnerId);
            $date = "$year-$month-$day $hour:$minute";
            return array($id, $partnerId, $date, $note);
        }

        private function validateFormData() {
            if (!isset(NGS()->args()->id) || !is_numeric(NGS()->args()->id) || NGS()->args()->id <= 0) {
                throw new RedirectException('sale/list' . NGS()->args()->id, "Invalid Sale Order.");
            }
            if (empty(NGS()->args()->saleOrderDateYear)) {
                throw new RedirectException('sale/edit/' . NGS()->args()->id, "Invalid Date.");
            }
            if (empty(NGS()->args()->saleOrderDateMonth)) {
                throw new RedirectException('sale/edit/' . NGS()->args()->id, "Invalid Date.");
            }
            if (empty(NGS()->args()->saleOrderDateDay)) {
                throw new RedirectException('sale/edit/' . NGS()->args()->id, "Invalid Date.");
            }
            if (empty(NGS()->args()->saleOrderTimeHour)) {
                throw new RedirectException('sale/edit/' . NGS()->args()->id, "Invalid Time.");
            }
            if (empty(NGS()->args()->saleOrderTimeMinute)) {
                throw new RedirectException('sale/edit/' . NGS()->args()->id, "Invalid Time.");
            }
            if (!isset(NGS()->args()->partnerId) || !is_numeric(NGS()->args()->partnerId) || NGS()->args()->partnerId <= 0) {
                throw new RedirectException('sale/edit/' . NGS()->args()->id, "Invalid Partner.");
            }
        }

    }

}
