<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\payment {

    use crm\loads\AdminLoad;
    use crm\managers\AttachmentManager;
    use crm\managers\PaymentTransactionManager;
    use NGS;

    class OpenLoad extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $paymentId = NGS()->args()->id;
            $payments = PaymentTransactionManager::getInstance()->getPaymentListFull(['id', '=', $paymentId]);
            if (!empty($payments)) {
                $payment = $payments[0];
                $this->addParam('payment', $payment);
                $attachments = AttachmentManager::getInstance()->getEntityAttachments($paymentId, 'payment');
                $this->addParam('attachments', $attachments);
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/payment/open.tpl";
        }

    }

}
