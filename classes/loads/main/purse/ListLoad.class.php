<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\purse {

    use crm\loads\AdminLoad;
    use crm\managers\PurseOrderManager;
    use NGS;

    class ListLoad extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $limit = 200;
            list($offset, $sortByFieldName, $selectedFilterSortByAscDesc, $selectedFilterAccount, $selectedFilterRecipientId, $selectedFilterHidden, $selectedFilterShippingType, $selectedFilterStatus, $searchText, $problematic, $regOrdersInWarehouse) = $this->initFilters($limit);
            $where = ['1', '=', '1'];
            if ($selectedFilterAccount !== 'purse_all') {
                $where = array_merge($where, ['AND ', 'account_name', '=', "'$selectedFilterAccount'"]);
            }

            $activeStatusesSql = "('open', 'shipping', 'shipped', 'partially_delivered', 'under_balance','under_balance.confirming', 'accepted')";
            if ($selectedFilterStatus === 'active') {
                $where = array_merge($where, ['AND ', 'status', 'in', $activeStatusesSql]);
            }
            if ($selectedFilterStatus === 'inactive') {
                $where = array_merge($where, ['AND ', 'status', 'not in', $activeStatusesSql]);
            }
            if ($selectedFilterHidden !== 'all') {
                $where = array_merge($where, ['AND ', 'hidden', '=', 0]);
            }
            if ($selectedFilterShippingType !== 'all') {
                $where = array_merge($where, ['AND ', 'shipping_type', '=', "'$selectedFilterShippingType'"]);
            }
            if ($selectedFilterRecipientId > 0) {
                $recipientUnitAddressesSql = \crm\managers\RecipientManager::getInstance()->getRecipientUnitAddresses($selectedFilterRecipientId, true);
                $where = array_merge($where, ['AND ', 'unit_address', 'in', $recipientUnitAddressesSql]);
            }
            if (!empty($searchText)) {
                if (strpos($searchText, ' ') === false) {
                    $where = array_merge($where, ['AND', '(', 'product_name', 'like', "'%$searchText%'"]);
                    $where = array_merge($where, ['OR', 'order_number', 'like', "'%$searchText%'"]);
                    $where = array_merge($where, ['OR', 'amazon_order_number', 'like', "'%$searchText%'"]);
                    $where = array_merge($where, ['OR', 'recipient_name', 'like', "'%$searchText%'"]);
                    $where = array_merge($where, ['OR', 'serial_number', 'like', "'%$searchText%'"]);
                    $where = array_merge($where, ['OR', 'note', 'like', "'%$searchText%'"]);
                    $where = array_merge($where, ['OR', 'buyer_name', 'like', "'%$searchText%'"]);
                    $where = array_merge($where, ['OR', 'tracking_number', 'like', "'%$searchText%'", ')']);
                    $words = [$searchText];
                } else {
                    $words = $parts = preg_split('/\s+/', $searchText);
                    foreach ($words as $word) {
                        $where = array_merge($where, ['AND', '(', 'product_name', 'like', "'%$word%'"]);
                        $where = array_merge($where, ['OR', 'recipient_name', 'like', "'%$word%'", ')']);
                    }
                }
            }
            if (!empty($regOrdersInWarehouse)) {
                $orders = PurseOrderManager::getInstance()->getNotRegisteredOrdersInWarehouse($regOrdersInWarehouse);
                $count = count($orders);
            } else {
                if ($problematic == 1) {
                    $orders = PurseOrderManager::getInstance()->getProblematicOrders($where);
                } else {
                    $orders = PurseOrderManager::getInstance()->getOrders($where, $sortByFieldName, $selectedFilterSortByAscDesc, $offset, $limit);
                }
                $count = PurseOrderManager::getInstance()->getLastSelectAdvanceRowsCount();

                $ordersPuposedToNotReceivedToDestinationCounty = PurseOrderManager::getInstance()->getOrdersPuposedToNotReceivedToDestinationCounty();

                $totalPuposedToNotReceived = 0;
                $searchedItemCount = 0;
                $searchedItemCountThatHasTrackingNumber = 0;
                foreach ($ordersPuposedToNotReceivedToDestinationCounty as $order) {
                    $productName = $order->getProductName();
                    if (!empty($searchText)) {
                        $allWordsAreinProductTitle = true;
                        foreach ($words as $word) {
                            if (stripos($productName, $word) === false) {
                                $allWordsAreinProductTitle = false;
                                break;
                            }
                        }
                        if ($allWordsAreinProductTitle) {
                            if (strlen($order->getTrackingNumber()) > 3) {
                                $searchedItemCountThatHasTrackingNumber += intval($order->getQuantity());
                            }
                            $searchedItemCount += intval($order->getQuantity());
                        }
                    }
                    $totalPuposedToNotReceived += floatval($order->getAmazonTotal());
                }
                $this->addParam('total_puposed_to_not_received', $totalPuposedToNotReceived);
                $this->addParam('not_received_orders_count', count($ordersPuposedToNotReceivedToDestinationCounty));
                $this->addParam('searchedItemPuposedCount', $searchedItemCount);
                $this->addParam('searchedItemCountThatHasTrackingNumber', $searchedItemCountThatHasTrackingNumber);
            }



            $pagesCount = ceil($count / $limit);

            $this->addParam('changed_orders', NGS()->args()->changed_orders);
            $this->addParam('count', $count);

            $this->addParam('pagesCount', $pagesCount);
            $this->addParam('orders', $orders);

            $btc_products_days_diff_for_delivery_date = intval(\crm\managers\SettingManager::getInstance()->getSetting('btc_products_days_diff_for_delivery_date'));
            $this->addParam('btc_products_days_diff_for_delivery_date', $btc_products_days_diff_for_delivery_date);

            $purse_checkout_meta = json_decode(\crm\managers\SettingManager::getInstance()->getSetting('purse_checkout_meta', '{}'));
            $purse_pars_meta = json_decode(\crm\managers\SettingManager::getInstance()->getSetting('purse_pars_meta', '{}'));
            $purse_info_meta = json_decode(\crm\managers\SettingManager::getInstance()->getSetting('purse_info_meta', '{}'));
            if (isset($purse_checkout_meta->wallet)) {
                $this->addParam('checkout_btc_balance', round($purse_checkout_meta->wallet->BTC->balance->active, 3));
                $this->addParam('checkout_btc_address', $purse_checkout_meta->wallet->BTC->legacy_address);
            }
            if (isset($purse_pars_meta->wallet)) {
                $this->addParam('pars_btc_balance', round($purse_pars_meta->wallet->BTC->balance->active, 3));
                $this->addParam('pars_btc_address', $purse_pars_meta->wallet->BTC->legacy_address);
            }
            if (isset($purse_info_meta->wallet)) {
                $this->addParam('info_btc_balance', round($purse_info_meta->wallet->BTC->balance->active, 3));
                $this->addParam('info_btc_address', $purse_info_meta->wallet->BTC->legacy_address);
            }


            $parsUpdatedDate = PurseOrderManager::getInstance()->getAccountUpdatedDateString('purse_pars');
            $infoUpdatedDate = PurseOrderManager::getInstance()->getAccountUpdatedDateString('purse_info');
            $checkoutUpdatedDate = PurseOrderManager::getInstance()->getAccountUpdatedDateString('purse_checkout');
            $this->addParam('parsUpdatedDate', $parsUpdatedDate);
            $this->addParam('infoUpdatedDate', $infoUpdatedDate);
            $this->addParam('checkoutUpdatedDate', $checkoutUpdatedDate);

            $this->addParam('btc_rate', \crm\managers\CryptoRateManager::getInstance()->getBtcRate());


            $this->addParam('recipients', \crm\managers\RecipientManager::getInstance()->selectAdvance('*', [], ['first_name', 'last_name']));
        }

        private function initFilters($limit) {

            $regOrdersInWarehouse = [];
            if (isset(NGS()->args()->roiw)) {
                $regOrdersInWarehouseStr = trim(NGS()->args()->roiw);
                if (!empty($regOrdersInWarehouseStr)) {
                    $regOrdersInWarehouseStr = preg_replace('/\s+/', ';', $regOrdersInWarehouseStr);
                    $regOrdersInWarehouseStr = str_replace(',', ';', $regOrdersInWarehouseStr);
                    $regOrdersInWarehouse = explode(';', $regOrdersInWarehouseStr);
                    $limit = 1000;
                }
            }

            //pageing
            $selectedFilterPage = 1;
            if (isset(NGS()->args()->pg)) {
                $selectedFilterPage = intval(NGS()->args()->pg);
            }
            $this->addParam('selectedFilterPage', $selectedFilterPage);
            $offset = 0;
            if ($selectedFilterPage > 1) {
                $offset = ($selectedFilterPage - 1) * intval($limit);
            }

            //sorting
            $sortByFields = $this->getSortByFields();
            $this->addParam('sortFields', $sortByFields);
            $selectedFilterSortBy = 'created_at';
            if (isset(NGS()->args()->srt)) {
                if (array_key_exists(NGS()->args()->srt, $sortByFields)) {
                    $selectedFilterSortBy = NGS()->args()->srt;
                }
            }
            $selectedFilterSortByAscDesc = 'DESC';
            if (isset(NGS()->args()->ascdesc)) {
                if (in_array(strtoupper(NGS()->args()->ascdesc), ['ASC', 'DESC'])) {
                    $selectedFilterSortByAscDesc = strtoupper(NGS()->args()->ascdesc);
                }
            }
            $selectedFilterHidden = 'no';
            if (isset(NGS()->args()->hddn)) {
                if (in_array(strtolower(NGS()->args()->hddn), ['all', 'no'])) {
                    $selectedFilterHidden = strtolower(NGS()->args()->hddn);
                }
            }
            $selectedFilterAccount = 'all';
            if (isset(NGS()->args()->acc)) {
                if (in_array(strtolower(NGS()->args()->acc), ['pars', 'info', 'checkout'])) {
                    $selectedFilterAccount = strtolower(NGS()->args()->acc);
                }
            }
            $selectedFilterStatus = 'active';
            if (isset(NGS()->args()->stts)) {
                if (in_array(strtolower(NGS()->args()->stts), ['all', 'active', 'inactive'])) {
                    $selectedFilterStatus = strtolower(NGS()->args()->stts);
                }
            }
            $selectedFilterRecipientId = 0;
            if (isset(NGS()->args()->rcpt)) {
                $selectedFilterRecipientId = intval(NGS()->args()->rcpt);
            }
            $selectedFilterShippingType = 'all';
            if (isset(NGS()->args()->sht)) {
                $selectedFilterShippingType = strtolower(NGS()->args()->sht);
            }
            $problematic = 0;
            if (isset(NGS()->args()->pr)) {
                $problematic = intval(NGS()->args()->pr);
            }
            $searchText = '';
            if (isset(NGS()->args()->st)) {

                $searchText = trim(NGS()->args()->st);
            }


            if (!empty($regOrdersInWarehouse)) {
                $problematic = 0;
                $searchText = '';
                $selectedFilterAccount = '';
                $selectedFilterHidden = 'no';
                $selectedFilterStatus = 'all';
                $selectedFilterShippingType = 'all';
                $selectedFilterRecipientId = 0;
                $offset = 0;
                $selectedFilterPage = 1;
            }

            $this->addParam('problematic', $problematic);
            $this->addParam('searchText', $searchText);
            $this->addParam('selectedFilterRecipientId', $selectedFilterRecipientId);
            $this->addParam('selectedFilterAccount', $selectedFilterAccount);
            $this->addParam('notRegOrdersInWarehouse', $regOrdersInWarehouse);
            $this->addParam('selectedFilterHidden', $selectedFilterHidden);
            $this->addParam('selectedFilterStatus', $selectedFilterStatus);
            $this->addParam('selectedFilterShippingType', $selectedFilterShippingType);
            $this->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
            $this->addParam('selectedFilterSortBy', $selectedFilterSortBy);

            return [$offset, $selectedFilterSortBy, $selectedFilterSortByAscDesc, 'purse_' . $selectedFilterAccount, $selectedFilterRecipientId, $selectedFilterHidden, $selectedFilterShippingType, $selectedFilterStatus, $searchText, $problematic, $regOrdersInWarehouse];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/purse/list.tpl";
        }

        public function getSortByFields() {
            return ['created_at' => 'Created Date', 'status' => 'Status', 'updated_at' => 'Changed', 'buyer_name' => 'Buyer'];
        }

    }

}
