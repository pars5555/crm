<?php

/**
 * main site action for all ngs site's actions
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2014
 * @package actions.site
 * @version 6.0
 *
 */

namespace crm\actions\cron {

    use crm\actions\BaseAction;
    use crm\managers\CalculationManager;
    use crm\managers\CapitalHistoryManager;
    use crm\managers\CryptoRateManager;
    use crm\managers\CurrencyRateManager;
    use crm\managers\PartnerInitialDebtManager;
    use crm\managers\PartnerManager;
    use crm\managers\PaymentTransactionManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\PurseOrderManager;
    use crm\managers\SaleOrderManager;
    use crm\managers\SettingManager;
    use crm\managers\WarehouseManager;
    use crm\security\RequestGroups;

    class CalcCapitalAction extends BaseAction {

        public function service() {
            $partnerDebtTotal = $this->getPartnerDebtTotalAmount();
            $warehouseTotal = $this->getWarehouseTotalUsdAmount();
            $purseTotal = $this->getPurseTotalUsdAmount();
            $purseBalanceTotal = $this->getPurseBalancesTotalAmount();
            $cashboxTotal = $this->getCashboxTotalUsdAmount();
            $capitalData = json_decode(SettingManager::getInstance()->getSetting('capital_data', '{}'), true);
            $capitalData['partner_debt_total'] = round($partnerDebtTotal, 2);
            $capitalData['warehouse_total'] = round($warehouseTotal);
            $capitalData['purse_total'] = round($purseTotal);
            $capitalData['purse_balance_total'] = round($purseBalanceTotal);
            $capitalData['cashbox_total'] = round($cashboxTotal);
            SettingManager::getInstance()->setSetting('capital_data', json_encode($capitalData));
            CapitalHistoryManager::getInstance()->addRow();

            $telegramToken = SettingManager::getInstance()->getSetting('telegram_bot_token');
            $telegramCrmChannelId = SettingManager::getInstance()->getSetting('telegram_crm_channel_id');
            $manager = new \naffiq\telegram\channel\Manager($telegramToken, $telegramCrmChannelId);
            $botDateOrBool = \crm\managers\VanillaCardsManager::getInstance()->isBotWorking();
            if ($botDateOrBool !== true) {
                $manager->postMessage('Vanilla checker stopped! last updated date: '. $botDateOrBool);
            }
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
            $productsQuantity = WarehouseManager::getInstance()->getAllProductsQuantity(true);
            $productsPrice = WarehouseManager::getInstance()->getAllProductsPrice(array_keys($productsQuantity));
            $total = 0;
            foreach ($productsQuantity as $pId => $qty) {
                $total += floatval($productsPrice[$pId]) * floatval($qty);
            }
            return $total;
        }

        private function getPurseBalancesTotalAmount() {
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

        private function getPartnerWarehausesTotalAmount() {
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

        private function getPartnerDebtTotalAmount() {
            $partners = PartnerManager::getInstance()->selectByField('included_in_capital', 1);
            $partnerIds = [];
            foreach ($partners as $partner) {
                $partnerIds[] = $partner->getId();
            }
            if (empty($partnerIds)) {
                return 0;
            }
            $partnersSaleOrdersMappedByPartnerId = SaleOrderManager::getInstance()->getPartnersSaleOrders($partnerIds);
            $partnersPurchaseOrdersMappedByPartnerId = PurchaseOrderManager::getInstance()->getPartnersPurchaseOrders($partnerIds);
            $partnersPaymentTransactionsMappedByPartnerId = PaymentTransactionManager::getInstance()->getPartnersPaymentTransactions($partnerIds);
            $partnersBillingTransactionsMappedByPartnerId = PaymentTransactionManager::getInstance()->getPartnersBillingTransactions($partnerIds);
            $partnersInitialDebt = PartnerInitialDebtManager::getInstance()->getPartnersInitialDebt($partnerIds);
            list($partnersDebt, $partnersZeroDebt) = CalculationManager::getInstance()->calculatePartnersDebtBySalePurchaseAndPaymentTransations($partnersSaleOrdersMappedByPartnerId, $partnersPurchaseOrdersMappedByPartnerId, $partnersPaymentTransactionsMappedByPartnerId, $partnersBillingTransactionsMappedByPartnerId, $partnersInitialDebt);
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
            $usdRate = floatval(CurrencyRateManager::getInstance()->getCurrencyRate(1));
            return $totalUsd + $totalAmd / $usdRate;
        }

        private function getCashboxTotalUsdAmount() {
            $cashboxUsd = -PaymentTransactionManager::getInstance()->getNonCancelledPaymentOrdersByCurrency(date('Y-m-d'), 1);
            $cashboxAmd = -PaymentTransactionManager::getInstance()->getNonCancelledPaymentOrdersByCurrency(date('Y-m-d'), 2);
            $usdRate = floatval(CurrencyRateManager::getInstance()->getCurrencyRate(1));
            return $cashboxUsd + $cashboxAmd / $usdRate;
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
