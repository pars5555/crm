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

use crm\loads\NgsLoad;
use crm\managers\CurrencyManager;
use crm\managers\PaymentTransactionManager;
use crm\managers\SettingManager;
use crm\security\RequestGroups;
use NGS;

    class GeneralLoad extends NgsLoad {

        public function load() {
            $selectCurrencyId = SettingManager::getInstance()->getSetting('default_currency_id');
            if (isset(NGS()->args()->cur)) {
                $selectCurrencyId = intval(NGS()->args()->cur);
            }
            $this->addParam('selectedCurrencyId', $selectCurrencyId);
            $this->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
            $paymentTransactionManager = PaymentTransactionManager::getInstance();
            $nonCancelledPaymentOrdersByCurrency = $paymentTransactionManager->getNonCancelledPaymentOrdersByCurrency($selectCurrencyId);
            $cashboxAmount = -$nonCancelledPaymentOrdersByCurrency;
            $this->addParam("amount", $cashboxAmount);
        }

        public function getDefaultLoads() {
            $loads = array();
            $loads["profitCalculation"]["action"] = "crm.loads.main.general.profit";
            $loads["profitCalculation"]["args"] = array();
            return $loads;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/general/general.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
