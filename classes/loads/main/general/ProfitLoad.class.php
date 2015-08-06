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
    use crm\managers\SaleOrderLineManager;
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

            $profit = SaleOrderLineManager::getInstance()->getTotalProfitSumInNonCancelledSaleOrders($fromDate, $toDate);
            $this->addParam("profit", $profit);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/general/profit.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
