<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main {

use crm\loads\NgsLoad;
use crm\managers\CurrencyManager;
use crm\managers\PartnerManager;
use crm\managers\PaymentMethodManager;
use crm\managers\PaymentTransactionManager;
use crm\managers\SettingManager;
use crm\security\RequestGroups;
use NGS;

    class PaymentsListLoad extends NgsLoad {

        public function load() {
            $this->addParam('partners', PartnerManager::getInstance()->selectAdvance('*', [], ['name']));
            $this->addParam('payments', PaymentTransactionManager::getInstance()->getPaymentsListFull());
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/payment/payments_list.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
