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

    class DeletePaymentAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $paymentId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Payment ID is missing';
                $this->redirect('payment/list');
            }
            $paymentTransactionManager = PaymentTransactionManager::getInstance();
            $paymentTransactionManager->updateField($paymentId, 'deleted', 1);
            $_SESSION['success_message'] = 'Payment Successfully deleted!';
            if (strpos($_SERVER['HTTP_REFERER'], 'payment/list') === false) {
                $this->redirect('payment/list');
            } else {
                $this->redirectToReferer();
            }
        }

    }

}
