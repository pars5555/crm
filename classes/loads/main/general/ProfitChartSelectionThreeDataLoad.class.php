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
    use crm\managers\SaleOrderManager;
    use crm\security\RequestGroups;
    use NGS;

    class ProfitChartSelectionThreeDataLoad extends NgsLoad {

        public function load() {
            list($startDate, $endDate) = [NGS()->args()->startDate, NGS()->args()->endDate];
            $expenseSaleOrders = SaleOrderManager::getInstance()->getSaleOrdersFull(['cancelled', '=', 0, 'AND','is_expense', '=', 1, 'AND', 'order_date', '>=', "'" . $startDate . "'", 'AND', 'order_date', '<=', "DATE_ADD('$endDate' ,INTERVAL 1 DAY)"], ['order_date'], 'DESC');
            $this->addParam('currencies', CurrencyManager::getInstance()->mapDtosById(CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1])));
            $this->addParam("expenseSaleOrders", $expenseSaleOrders);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/general/profit/expense_sale_orders_list.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
