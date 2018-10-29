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

    use crm\loads\AdminLoad;
    use crm\managers\CalculationManager;
    use crm\managers\CryptoRateManager;
    use crm\managers\PartnerInitialDebtManager;
    use crm\managers\PaymentTransactionManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\PurseOrderManager;
    use crm\managers\SaleOrderManager;
    use crm\managers\SettingManager;
    use crm\managers\WarehouseManager;
    use NGS;

    class GeneralLoad extends AdminLoad {

        public function load() {
            $partnerDebtTotal = $this->getPartnerDebtTotalAmount();
            $warehouseTotal = $this->getWarehouseTotalUsdAmount();
            $purseTotal = $this->getPurseTotalUsdAmount();
            $purseBalanceTotal = $this->getPurseBalancesTotalAmount();
            $partnerWarehouseTotal = $this->getPartnerWarehausesTotalAmount();
            
            $this->addParam('partnerDebtTotal', $partnerDebtTotal);
            $this->addParam('$warehouseTotal', $warehouseTotal);
            $this->addParam('$purseTotal', $purseTotal);
            $this->addParam('$purseBalanceTotal', $purseBalanceTotal);
            $this->addParam('$partnerWarehouseTotal', $partnerWarehouseTotal);
            $this->addParam('capital', $warehouseTotal + $purseTotal + $purseBalanceTotal + $partnerWarehouseTotal + $partnerDebtTotal);
        }

        public function getDefaultLoads() {
            $loads = array();
            $loads["profitCalculation"]["action"] = "crm.loads.main.general.profit";
            $loads["profitCalculation"]["args"] = array();
            $loads["cashboxCalculation"]["action"] = "crm.loads.main.general.cashbox";
            $loads["cashboxCalculation"]["args"] = array();
            return $loads;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/general/general.tpl";
        }

        private function getPurseTotalUsdAmount() {
            $ordersPuposedToNotReceivedToDestinationCounty = PurseOrderManager::getInstance()->getOrdersPuposedToNotReceivedToDestinationCounty();
            $totalPuposedToNotReceived = 0;
            foreach ($ordersPuposedToNotReceivedToDestinationCounty as $order) {
                $totalPuposedToNotReceived += floatval($order->getAmazonTotal());
            }
            return $totalPuposedToNotReceived;
        }

        private function getWarehouseTotalUsdAmount() {
            $productsQuantity = WarehouseManager::getInstance()->getAllProductsQuantity();
            $productsPrice = WarehouseManager::getInstance()->getAllProductsPrice(array_keys($productsQuantity));
            $total = 0;
            foreach ($productsQuantity as $pId => $qty) {
                $total += floatval($productsPrice[$pId]) * floatval($qty);
            }
            return $total;
        }

        public function getPurseBalancesTotalAmount() {
            $purse_checkout_meta = json_decode(SettingManager::getInstance()->getSetting('purse_checkout_meta', '{}'));
            $purse_pars_meta = json_decode(SettingManager::getInstance()->getSetting('purse_pars_meta', '{}'));
            $purse_info_meta = json_decode(SettingManager::getInstance()->getSetting('purse_info_meta', '{}'));
            $total = 0;
            if (isset($purse_checkout_meta->wallet)) {
                $total += round($purse_checkout_meta->wallet->BTC->balance->active, 3);
            }
            if (isset($purse_pars_meta->wallet)) {
                $total += round($purse_pars_meta->wallet->BTC->balance->active, 3);
            }
            if (isset($purse_info_meta->wallet)) {
                $total += round($purse_info_meta->wallet->BTC->balance->active, 3);
            }
            return $total * CryptoRateManager::getInstance()->getBtcRate();
        }

        public function getPartnerWarehausesTotalAmount() {
            $wps = SettingManager::getInstance()->getSetting('warehouse_partners');
            $wps_ids = explode(',', $wps);
            $total = 0;
            foreach ($wps_ids as $partnerId) {
                $posTotals = PurchaseOrderManager::getInstance()->getPartnerPurchaseOrdersTotal($partnerId);
                $sosTotals = SaleOrderManager::getInstance()->getPartnerSaleOrdersTotal($partnerId);
                $total += $sosTotals[1];
                if (array_key_exists(1, $posTotals)) {
                    $total -= $posTotals[1];
                }
            }
            return $total;
        }

        public function getPartnerDebtTotalAmount() {
            $partners = \crm\managers\PartnerManager::getInstance()->selectByField('included_in_capital', 1);
            $partnerIds = [];
            foreach ($partners as $partner) {
                $partnerIds[] = $partner->getId();
            }
            if (empty($partnerIds))
            {
                return 0;
            }
            $partnersSaleOrdersMappedByPartnerId = SaleOrderManager::getInstance()->getPartnersSaleOrders($partnerIds);
            $partnersPurchaseOrdersMappedByPartnerId = PurchaseOrderManager::getInstance()->getPartnersPurchaseOrders($partnerIds);
            $partnersPaymentTransactionsMappedByPartnerId = PaymentTransactionManager::getInstance()->getPartnersPaymentTransactions($partnerIds);
            $partnersBillingTransactionsMappedByPartnerId = PaymentTransactionManager::getInstance()->getPartnersBillingTransactions($partnerIds);
            $partnersInitialDebt = PartnerInitialDebtManager::getInstance()->getPartnersInitialDebt($partnerIds);
            $partnersDebt = CalculationManager::getInstance()->calculatePartnersDebtBySalePurchaseAndPaymentTransations($partnersSaleOrdersMappedByPartnerId, $partnersPurchaseOrdersMappedByPartnerId, $partnersPaymentTransactionsMappedByPartnerId, $partnersBillingTransactionsMappedByPartnerId, $partnersInitialDebt);
            $totalUsd = 0;
            $totalAmd = 0;
            foreach ($partnersDebt as $partnerId => $debt) {
                if (isset($debt[1])) {
                    $totalUsd += $debt[1];
                }
                if (isset($debt[2])) {
                    $totalAmd += $debt[2];
                }
            }
            $usdRate = floatval(\crm\managers\CurrencyRateManager::getInstance()->getCurrencyRate(1));
            return $totalUsd + $totalAmd / $usdRate;
        }

    }

}
