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

    class DeleteBillingAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $paymentId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Billing ID is missing';
                $this->redirect('billing/list');
            }
            $paymentTransactionManager = PaymentTransactionManager::getInstance();
            $paymentTransactionManager->updateField($paymentId, 'deleted', 1);
            $_SESSION['success_message'] = 'Billing Successfully deleted!';
            if (strpos($_SERVER['HTTP_REFERER'], 'billing/list') === false) {
                $this->redirect('billing/list');
            } else {
                $this->redirectToReferer();
            }
        }

    }

}
