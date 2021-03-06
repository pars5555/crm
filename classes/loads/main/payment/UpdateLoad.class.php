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
    use crm\managers\CurrencyManager;
    use crm\managers\PartnerManager;
    use crm\managers\PaymentMethodManager;
    use crm\managers\PaymentTransactionManager;
    use crm\managers\SettingManager;
    use crm\security\RequestGroups;
    use DateTime;
    use NGS;

    class UpdateLoad  extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $id = intval(NGS()->args()->id);
            $paymentOrder = PaymentTransactionManager::getInstance()->selectByPk($id);
            if ($paymentOrder) {
                if (!isset($_SESSION['action_request'])) {
                    $_SESSION['action_request'] = [
                        'date' => $this->cutSecondsFromDateTime($paymentOrder->getDate()),
                        'partnerId' => $paymentOrder->getPartnerId(),
                        'billingMethodId' => $paymentOrder->getPaymentMethodId(),
                        'currencyId' => $paymentOrder->getCurrencyId(),
                        'note' => $paymentOrder->getNote(),
                        'signature' => $paymentOrder->getSignature(),
                        'amount' => $paymentOrder->getAmount(),
                        'isExpense' => $paymentOrder->getIsExpense(),
                        'paid' => $paymentOrder->getPaid()
                    ];
                }
                $this->addParam("paymentOrder", $paymentOrder);
                $this->addParam('req', $_SESSION['action_request']);
                unset($_SESSION['action_request']);
                $this->addParam('payment_methods', PaymentMethodManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
                $this->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
                $this->addParam('partners', PartnerManager::getInstance()->selectAdvance('*', [], ['name']));
                $this->addParam('defaultCurrencyId', SettingManager::getInstance()->getSetting('default_currency_id'));
                $this->addParam('defaultPaymentMethodId', SettingManager::getInstance()->getSetting('default_payment_method_id'));
            }
        }

        private function cutSecondsFromDateTime($date) {
            if ($date != 0) {
                $d = DateTime::createFromFormat("Y-m-d H:i:s", $date);
                return $d->format('Y-m-d H:i');
            }
            return null;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/payment/update.tpl";
        }


    }

}
