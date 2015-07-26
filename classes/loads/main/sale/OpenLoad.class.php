<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\sale {

use crm\loads\NgsLoad;
use crm\managers\CurrencyManager;
use crm\managers\ProductManager;
use crm\managers\SaleOrderManager;
use crm\managers\SettingManager;
use crm\security\RequestGroups;
use NGS;

    class OpenLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $this->addParam('products', ProductManager::getInstance()->selectAdvance('*', [], ['name']));
            $this->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
            $paymentId = NGS()->args()->id;
            $saleOrders = SaleOrderManager::getInstance()->getSaleOrdersFull(['id', '=', $paymentId]);
            if (!empty($saleOrders)) {
                $saleOrder = $saleOrders[0];
                $this->addParam('saleOrder', $saleOrder);
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/sale/open.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
