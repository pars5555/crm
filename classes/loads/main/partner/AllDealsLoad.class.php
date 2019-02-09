<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\partner {

    use crm\loads\AdminLoad;
    use crm\managers\CalculationManager;
    use crm\managers\CurrencyManager;
    use crm\managers\PartnerManager;
    use crm\managers\PaymentTransactionManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\SaleOrderManager;
    use crm\security\RequestGroups;
    use NGS;

    class AllDealsLoad extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            self::loadParams($this);
        }

        public static function loadParams($load = false) {
            if (isset(NGS()->args()->id)) {
                $partnerId = intval(NGS()->args()->id);
                $partner = PartnerManager::getInstance()->selectByPk($partnerId);
            }
            if (isset(NGS()->args()->slug) && !empty(NGS()->args()->slug)) {
                $partners = PartnerManager::getInstance()->selectByField('slug', NGS()->args()->slug);
                $partner = $partners[0];
                $partnerId = intval($partner->getId());
            }
            if (empty($partner)) {
                echo 'partner not found';
                exit;
            }

            if ($load) {
                $load->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], null, null, null, null, true));
                $load->addParam('partner', $partner);
            }
            $partnerSaleOrders = SaleOrderManager::mapDtosById(SaleOrderManager::getInstance()->getPartnerSaleOrders($partnerId));
            $partnerPurchaseOrders = PurchaseOrderManager::mapDtosById(PurchaseOrderManager::getInstance()->getPartnerPurchaseOrders($partnerId));
            $partnerPaymentTransactions = PaymentTransactionManager::mapDtosById(PaymentTransactionManager::getInstance()->getPartnerPaymentTransactions($partnerId));
            $partnerBillingTransactions = PaymentTransactionManager::mapDtosById(PaymentTransactionManager::getInstance()->getPartnerBillingTransactions($partnerId));
            $sales = self::mapByIdAndGivenField('sale_', 'order_date', $partnerSaleOrders);
            $purchases = self::mapByIdAndGivenField('purchase_', 'order_date', $partnerPurchaseOrders);
            $paments = self::mapByIdAndGivenField('payment_', 'date', $partnerPaymentTransactions);
            $billings = self::mapByIdAndGivenField('billing_', 'date', $partnerBillingTransactions);
            $allDeals = self::mergeAllDeals($sales, $purchases, $paments, $billings, $partnerSaleOrders, $partnerPurchaseOrders, $partnerPaymentTransactions, $partnerBillingTransactions);
            if ($load) {
                $load->addParam('allDeals', $allDeals);
            }

            CalculationManager::getInstance()->calculatePartnerAllDealesDebtHistory($partnerId, array_reverse($allDeals, true));
            return $allDeals;
        }

        public function getRequestGroup() {
            if (isset(NGS()->args()->id)) {
                return RequestGroups::$adminRequest;
            }
            return RequestGroups::$guestRequest;
        }

        private static function mergeAllDeals($sale, $purchase, $payment, $billing, $partnerSaleOrders, $partnerPurchaseOrders, $partnerPaymentTransactions, $partnerBillingTransactions) {
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
                    default:
                        break;
                }
            }
            return $ret;
        }

        private static function mapByIdAndGivenField($keyPrefix, $fieldName, $partnerTransactions) {
            $ret = [];
            foreach ($partnerTransactions as $partnerTransaction) {
                $ret [$keyPrefix . $partnerTransaction->getId()] = $partnerTransaction->$fieldName;
            }
            return $ret;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/partner/all_deals.tpl";
        }

    }

}
