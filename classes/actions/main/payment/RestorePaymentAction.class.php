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

    class RestorePaymentAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $paymentId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Payment ID is missing';
                $this->redirect('payment/list');
            }
            $paymentTransactionManager = PaymentTransactionManager::getInstance();
            $paymentDto = $paymentTransactionManager->selectByPk($paymentId);
            if (!isset($paymentDto)) {
                $_SESSION['error_message'] = 'Payment with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('payment/list');
            }
            if ($paymentDto->getCancelled() == 0) {
                $_SESSION['error_message'] = 'Payment with ID ' . NGS()->args()->id . ' is not cancelled.';
                $this->redirect('payment/list');
            }
            $note = NGS()->args()->note;
            $paymentTransactionManager->undoCancelPayment($paymentId, $note);
            $_SESSION['success_message'] = 'Payment successfully restored!';
            $this->redirectToReferer();
        }

    }

}
