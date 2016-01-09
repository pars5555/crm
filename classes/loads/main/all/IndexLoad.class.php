<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\all {

    use crm\loads\AdminLoad;
    use crm\managers\AdvancedAbstractManager;
    use crm\managers\CurrencyManager;
    use crm\managers\PaymentTransactionManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\SaleOrderManager;
    use crm\security\RequestGroups;
    use NGS;

    class IndexLoad  extends AdminLoad {

        public function load() {
            list($startDate, $endDate) = $this->getFormData();
            $this->addParam('startDate', $startDate);
            $this->addParam('endDate', $endDate);
            $partnerSaleOrders = AdvancedAbstractManager::mapDtosById(SaleOrderManager::getInstance()->getSaleOrdersFull(
                                    ['cancelled', '=', 0, 'AND', 'order_date', '>=', "'" . $startDate . "'", 'AND', 'order_date',
                                '<=', "DATE_ADD('$endDate' ,INTERVAL 1 DAY)"], ['order_date'], 'DESC'));
            $partnerPurchaseOrders = AdvancedAbstractManager::mapDtosById(PurchaseOrderManager::getInstance()->getPurchaseOrdersFull(
                                    ['cancelled', '=', 0, 'AND', 'order_date', '>=', "'" . $startDate . "'", 'AND', 'order_date',
                                '<=', "DATE_ADD('$endDate' ,INTERVAL 1 DAY)" ], ['order_date'], 'DESC'));
            $partnerPaymentTransactions = AdvancedAbstractManager::mapDtosById(PaymentTransactionManager::getInstance()->getPaymentListFull(
                                    ['cancelled', '=', 0, 'AND', 'amount', '>', 0, 'AND', 'date', '>=', "'" . $startDate . "'", 'AND', 'date',
                                '<=', "DATE_ADD('$endDate' ,INTERVAL 1 DAY)"], ['date'], 'DESC'));
            $partnerBillingTransactions = AdvancedAbstractManager::mapDtosById(PaymentTransactionManager::getInstance()->getPaymentListFull(
                                    ['cancelled', '=', 0, 'AND', 'amount', '<', 0, 'AND', 'date', '>=', "'" . $startDate . "'", 'AND', 'date',
                                '<=', "DATE_ADD('$endDate' ,INTERVAL 1 DAY)"], ['date'], 'DESC'));
            $sales = $this->mapByIdAndGivenField('sale_', 'order_date', $partnerSaleOrders);
            $purchases = $this->mapByIdAndGivenField('purchase_', 'order_date', $partnerPurchaseOrders);
            $paments = $this->mapByIdAndGivenField('payment_', 'date', $partnerPaymentTransactions);
            $billings = $this->mapByIdAndGivenField('billing_', 'date', $partnerBillingTransactions);
            $allDeals = $this->mergeAllDeals($sales, $purchases, $paments, $billings, $partnerSaleOrders, $partnerPurchaseOrders, $partnerPaymentTransactions, $partnerBillingTransactions);
            $this->addParam('allDeals', $allDeals);
            $this->addParam('partnerSaleOrders', $partnerSaleOrders);
            $this->addParam('partnerPurchaseOrders', $partnerPurchaseOrders);
            $this->addParam('partnerPaymentTransactions', $partnerPaymentTransactions);
            $this->addParam('partnerBillingTransactions', $partnerBillingTransactions);
            $this->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], null, null, null, null, true));
        }

        private function getFormData() {
            $startDate = date('Y-m-01');
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

        private function mergeAllDeals($sale, $purchase, $payment, $billing, $partnerSaleOrders, $partnerPurchaseOrders, $partnerPaymentTransactions, $partnerBillingTransactions) {
            $allDeals = array_merge($sale, $purchase, $payment, $billing);
            arsort($allDeals);
            $ret = [];
            foreach ($allDeals as $key => $date) {
                $id = intval(substr($key, strpos($key, "_") + 1));
                $type = substr($key, 0, strpos($key, "_"));
                switch ($type) {
                    case 'sale':
                        $ret [] = [$type, $partnerSaleOrders[$id]];
                        break;
                    case 'purchase':
                        $ret [] = [$type, $partnerPurchaseOrders[$id]];
                        break;
                    case 'payment':
                        $ret [] = [$type, $partnerPaymentTransactions[$id]];
                        break;
                    case 'billing':
                        $ret [] = [$type, $partnerBillingTransactions[$id]];
                        break;
                }
            }
            return $ret;
        }

        private function mapByIdAndGivenField($keyPrefix, $fieldName, $partnerTransactions) {
            $ret = [];
            foreach ($partnerTransactions as $partnerTransaction) {
                $ret [$keyPrefix . $partnerTransaction->getId()] = $partnerTransaction->$fieldName;
            }
            return $ret;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/all/index.tpl";
        }

    }

}
