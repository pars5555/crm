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
            list($startDate, $endDate) = $this->getFormData();
            $this->addParam('startDate', $startDate);
            $this->addParam('endDate', $endDate);

            $profit = SaleOrderLineManager::getInstance()->getTotalProfitSumInNonCancelledSaleOrders($startDate, $endDate);
            $expenseRowDtos = SaleOrderLineManager::getInstance()->getAllNonCancelledExpenseSaleOrders($startDate, $endDate);
            $expensesInMainCurrency = $this->calculateTotalExpense($expenseRowDtos);
            $profitIncludedExpensed = $profit - $expensesInMainCurrency;
            $this->addParam("profit", $profitIncludedExpensed);
        }

        private function calculateTotalExpense($expenseRowDtos) {
            $total = 0;
            foreach ($expenseRowDtos as $sol) {
                $currencyRate = $sol->getCurrencyRate();
                $totalInMainCurrency = floatval($sol->getQuantity()) * floatval($sol->getUnitPrice()) * floatval($currencyRate);
                $total += $totalInMainCurrency;
            }
            return $total;
        }

        private function getFormData() {
            $startDate = date('Y-01-01');
            if (isset(NGS()->args()->startDateYear) && isset(NGS()->args()->startDateMonth) && isset(NGS()->args()->startDateDay)) {
                $startYear = intval(NGS()->args()->startDateYear);
                $startmonth = intval(NGS()->args()->startDateMonth);
                $startday = intval(NGS()->args()->startDateDay);
                $startDate = "$startYear-$startmonth-$startday";
            }
            $endDate = date('Y-m-d');
            if (isset(NGS()->args()->endDateYear) && isset(NGS()->args()->endDateMonth) && isset(NGS()->args()->endDateDay)) {
                $endYear = intval(NGS()->args()->endDateYear);
                $endmonth = intval(NGS()->args()->endDateMonth);
                $endDay = intval(NGS()->args()->endDateDay);
                $endDate = "$endYear-$endmonth-$endDay";
            }
            return array($startDate, $endDate);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/general/profit.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
