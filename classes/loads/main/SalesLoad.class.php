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
use crm\managers\PartnerManager;
use crm\managers\PaymentMethodManager;
use crm\managers\SaleOrderManager;
use crm\security\RequestGroups;

    class SalesLoad extends NgsLoad {

        public function load() {
            $saleOrderManager = SaleOrderManager::getInstance();
            
            $this->addParam('payment_methods', PaymentMethodManager::getInstance() ->selectAll());
            $this->addParam('partners', PartnerManager::getInstance() ->selectAdvance('*', [], ['name']));
            
        }

        public function getTemplate() {
            return NGS()->getTemplateDir(). "/main/sales.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
