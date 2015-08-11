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

    class CancelBillingAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $billingId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Billing ID is missing';
                $this->redirect('billing/list');
            }
            $paymentTransactionManager = PaymentTransactionManager::getInstance();
            $billingDto = $paymentTransactionManager->selectByPK($billingId);
            if (!isset($billingDto)) {
                $_SESSION['error_message'] = 'Billing with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('billing/list');
            }
            if ($billingDto->getCancelled() == 1) {
                $_SESSION['error_message'] = 'Billing with ID ' . NGS()->args()->id . ' is already cancelled.';
                $this->redirect('billing/list');
            }
            $note = NGS()->args()->note;
            $paymentTransactionManager->cancelPayment($billingId, $note);
            $_SESSION['success_message'] = 'Billing Successfully cancelled!';
            $this->redirectToReferer();
        }

    }

}
