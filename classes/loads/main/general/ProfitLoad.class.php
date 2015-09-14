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
    use crm\managers\PaymentTransactionManager;
    use crm\managers\SaleOrderLineManager;
    use crm\managers\SaleOrderManager;
    use crm\security\RequestGroups;
    use DateInterval;
    use DatePeriod;
    use DateTime;
    use NGS;

    class ProfitLoad extends NgsLoad {

        public function load() {
            list($startDate, $endDate) = $this->getFormData();
            $this->addParam('startDate', $startDate);
            $this->addParam('endDate', $endDate);
            $profit = SaleOrderLineManager::getInstance()->getTotalProfitSumInNonCancelledSaleOrders($startDate, $endDate);
            $expenseSaleOrderLineRowDtos = SaleOrderLineManager::getInstance()->getAllNonCancelledExpenseSaleOrders($startDate, $endDate);
            $expensePaymentDtos = PaymentTransactionManager::getInstance()->getAllNonCancelledExpensePayments($startDate, $endDate);
            list($saleExpensesInMainCurrency, $paymentExpensesInMainCurrency) = $this->calculateTotalExpense($expenseSaleOrderLineRowDtos, $expensePaymentDtos);
            $profitIncludedExpensed = $profit - $saleExpensesInMainCurrency - $paymentExpensesInMainCurrency;
            $this->addParam("profit", $profitIncludedExpensed);
            $this->addParam("chartData", json_encode(['profit_without_expenses' => $profit, 'payment_expenses' => $paymentExpensesInMainCurrency, 'sale_expenses' => $saleExpensesInMainCurrency]));
            $this->addParam("lineChartData", json_encode($this->prepareLineChartData($startDate, $endDate)));
        }

        private function prepareLineChartData($startDate, $endDate) {
            $begin = new DateTime($startDate);
            $end = (new DateTime($endDate))->modify('+1 day');

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $ret = [];
            foreach ($period as $dt) {
                $ret[$dt->format("Y-m-d")] = [0, 0, 0];
            }
            $profitSaleOrders = SaleOrderManager::getInstance()->getSaleOrdersFull(['cancelled', '=', 0, 'AND', 'is_expense', '=', 0, 'AND', 'order_date', '>=', "'" . $startDate . "'", 'AND', 'order_date', '<=', "DATE_ADD('$endDate' ,INTERVAL 1 DAY)"], ['order_date'], 'DESC');
            foreach ($profitSaleOrders as $profitSaleOrder) {
                $oDate = new DateTime($profitSaleOrder->getOrderDate());
                $sDate = $oDate->format("Y-m-d");
                $ret[$sDate][0] = $profitSaleOrder->getTotalProfit();
            }
            $expenseSaleOrders = SaleOrderManager::getInstance()->getSaleOrdersFull(['cancelled', '=', 0, 'AND', 'is_expense', '=', 1, 'AND', 'order_date', '>=', "'" . $startDate . "'", 'AND', 'order_date', '<=', "DATE_ADD('$endDate' ,INTERVAL 1 DAY)"], ['order_date'], 'DESC');
            foreach ($expenseSaleOrders as $expenseSaleOrder) {
                $oDate = new DateTime($expenseSaleOrder->getOrderDate());
                $sDate = $oDate->format("Y-m-d");
                $ret[$sDate][1] = $expenseSaleOrder->getTotalAmountInMainCurrency();
            }

            $expensePaymentOrders = PaymentTransactionManager::getInstance()->getPaymentListFull(['cancelled', '=', 0, 'AND', 'is_expense', '=', 1, 'AND', 'date', '>=', "'" . $startDate . "'", 'AND', 'date', '<=', "DATE_ADD('$endDate' ,INTERVAL 1 DAY)"], ['date'], 'DESC');
            foreach ($expensePaymentOrders as $expensePaymentOrder) {
                $oDate = new DateTime($expensePaymentOrder->getDate());
                $sDate = $oDate->format("Y-m-d");
                $ret[$sDate][2] = $expensePaymentOrder->getAmount() * $expensePaymentOrder->getCurrencyRate();
            }
            return $ret;
        }

        private function calculateTotalExpense($expenseSaleOrderLineRowDtos, $expensePaymentDtos) {
            $total1 = 0;
            foreach ($expenseSaleOrderLineRowDtos as $sol) {
                $currencyRate = $sol->getCurrencyRate();
                $totalInMainCurrency = floatval($sol->getQuantity()) * floatval($sol->getUnitPrice()) * floatval($currencyRate);
                $total1 += $totalInMainCurrency;
            }
            $total2 = 0;
            foreach ($expensePaymentDtos as $expensePayment) {
                $currencyRate = $expensePayment->getCurrencyRate();
                $totalInMainCurrency = floatval($expensePayment->getAmount()) * floatval($currencyRate);
                $total2 += $totalInMainCurrency;
            }
            return array($total1, $total2);
        }

        private function getFormData() {
            $startDate = date('Y-01-01');
            if (!empty(NGS()->args()->startDate)) {
                $startDate = NGS()->args()->startDate;
            }
            $endDate = date('Y-m-d');
            if (!empty(NGS()->args()->endDate)) {
                $endDate = NGS()->args()->endDate;
            }
            return array($startDate, $endDate);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/general/profit/profit.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
