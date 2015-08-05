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
    use crm\managers\SaleOrderManager;
    use crm\security\RequestGroups;
    use NGS;

    class ProfitLoad extends NgsLoad {

        public function load() {
            $fromDate = date('Y-01-01');
            if (isset(NGS()->args()->startDate)) {
                $fromDate = NGS()->args()->startDate;
            }
            $toDate = date('Y-m-d');
            if (isset(NGS()->args()->toDate)) {
                $toDate = NGS()->args()->toDate;
            }
            $this->addParam('fromDate', $fromDate);
            $this->addParam('toDate', $toDate);

            $saleOrders = SaleOrderManager::getInstance()->getSaleOrdersFull(['order_date', '>=', "'" . $fromDate . "'", 'AND', 'order_date', '<=', "'" . $toDate . "'", 'AND', 'cancelled', '=', 0]);
            var_dump($saleOrders);exit;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/general/profit.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
