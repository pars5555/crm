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

namespace crm\actions\main\preorder {

    use crm\actions\BaseAction;
    use crm\managers\PreorderManager;
    use NGS;
    use ngs\framework\exceptions\RedirectException;

    class UpdatePreorderAction extends BaseAction {

        public function service() {
            try {
                list($id, $partnerId, $date, $paymentDeadlineDate, $note, $purse_order_ids) = $this->getFormData();
            } catch (RedirectException $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $_SESSION['action_request'] = $_REQUEST;
                $this->redirect($exc->getRedirectTo());
            }
            PreorderManager::getInstance()->updatePreorder($id, $partnerId, $date, $paymentDeadlineDate, $note, $purse_order_ids);
            unset($_SESSION['action_request']);
            $_SESSION['success_message'] = 'Preorder Order Successfully updated!';
            $this->redirect('preorder/' . $id);
        }

        private function getFormData() {
            $this->validateFormData();
            $id = intval(NGS()->args()->id);
            $note = "";
            if (isset(NGS()->args()->note)) {
                $note = NGS()->args()->note;
            }
            $purse_order_ids = "";
            if (!empty(NGS()->args()->purse_order_ids)) {
                $purse_order_ids = trim(NGS()->args()->purse_order_ids);
                $purse_order_ids = preg_replace('/\s+/', '', $purse_order_ids);
            }
            
            $partnerId = intval(NGS()->args()->partnerId);
            $date = NGS()->args()->order_date;
            $paymentDeadlineDate = NGS()->args()->payment_deadline;
            return array($id, $partnerId, $date, $paymentDeadlineDate, $note, $purse_order_ids);
        }

        private function validateFormData() {
            if (!isset(NGS()->args()->id) || !is_numeric(NGS()->args()->id) || NGS()->args()->id <= 0) {
                throw new RedirectException('sale/list' . NGS()->args()->id, "Invalid Preorder Order.");
            }
            if (empty(NGS()->args()->order_date)) {
                throw new RedirectException('preorder/edit/' . NGS()->args()->id, "Invalid Date.");
            }

            if (!isset(NGS()->args()->partnerId) || !is_numeric(NGS()->args()->partnerId) || NGS()->args()->partnerId <= 0) {
                throw new RedirectException('preorder/edit/' . NGS()->args()->id, "Invalid Partner.");
            }
            if (empty(NGS()->args()->payment_deadline)) {
                throw new RedirectException('preorder/edit' . NGS()->args()->id, "Invalid Payment Date.");
            }
        }

    }

}
