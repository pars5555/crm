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

    class CancelPaymentAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $paymentId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Payment ID is missing';
                $this->redirect('payment/list');
            }
            $paymentTransactionManager = PaymentTransactionManager::getInstance();
            $paymentDto = $paymentTransactionManager->selectByPK($paymentId);
            if (!isset($paymentDto)) {
                $_SESSION['error_message'] = 'Payment with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('payment/list');
            }
            if ($paymentDto->getCancelled() == 1) {
                $_SESSION['error_message'] = 'Payment with ID ' . NGS()->args()->id . ' is already cancelled.';
                $this->redirect('payment/list');
            }
            $note = NGS()->args()->note;
            $paymentTransactionManager->cancelPayment($paymentId, $note);
            $_SESSION['success_message'] = 'Payment Successfully cancelled!';
            $this->redirect('payment/' . $paymentId);
        }

    }

}
