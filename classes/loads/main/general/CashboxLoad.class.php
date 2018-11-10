<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\general {

    use crm\loads\AdminLoad;
    use crm\managers\CurrencyManager;
    use crm\managers\PaymentTransactionManager;
    use crm\managers\SettingManager;
    use NGS;

    class CashboxLoad  extends AdminLoad {

        public function load() {
            list($date, $curr) = $this->getFormData();
            $this->addParam('date', $date);
            $this->addParam('selectedCurrencyId', $curr);
            $this->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
            $paymentTransactionManager = PaymentTransactionManager::getInstance();
            $nonCancelledPaymentOrdersByCurrency = $paymentTransactionManager->getNonCancelledPaymentOrdersByCurrency($date, $curr);
            $cashboxAmount = -$nonCancelledPaymentOrdersByCurrency;
            $this->addParam("amount", $cashboxAmount);            
        }

        private function getFormData() {
            $date = date('Y-m-d');
            if (!empty(NGS()->args()->date)) {
                $date = NGS()->args()->date;
            }
            $selectCurrencyId = SettingManager::getInstance()->getSetting('default_currency_id');
            if (isset(NGS()->args()->cur)) {
                $selectCurrencyId = intval(NGS()->args()->cur);
            }
            return array($date, $selectCurrencyId);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/general/cashbox.tpl";
        }


    }

}
