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

    class RestoreBillingAction extends BaseAction {

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
            if ($billingDto->getCancelled() == 0) {
                $_SESSION['error_message'] = 'Billing with ID ' . NGS()->args()->id . ' is not cancelled.';
                $this->redirect('billing/list');
            }
            $paymentTransactionManager->undoCancelPayment($billingId);
            $_SESSION['success_message'] = 'Billing successfully restored!';
            $this->redirectToReferer();
        }

    }

}
