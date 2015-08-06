<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\billing {

    use crm\loads\NgsLoad;
    use crm\managers\PaymentTransactionManager;
    use crm\security\RequestGroups;
    use NGS;

    class OpenLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $paymentId = NGS()->args()->id;
            $payments = PaymentTransactionManager::getInstance()->getPaymentListFull(['id', '=', $paymentId]);
            if (!empty($payments)) {
                $payment = $payments[0];
                $this->addParam('billing', $payment);
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/billing/open.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
