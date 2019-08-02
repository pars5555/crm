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
    use crm\managers\AttachmentManager;
    use crm\managers\CryptoRateManager;
    use crm\managers\PreorderManager;
    use crm\managers\ProductManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\PurseOrderHistoryManager;
    use crm\managers\PurseOrderManager;
    use crm\managers\RecipientManager;
    use crm\managers\SettingManager;
    use NGS;

    class ListLoad extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $limit = 200;
            list($text1, $text2, $pendingPreordersOrderIds) = PreorderManager::getInstance()->getPerndingPreordersText();
            $this->addParam('preorder_text1', $text1);
            $this->addParam('preorder_text2', $text2);
            $this->addParam('preorder_order_ids', $pendingPreordersOrderIds);
            list($offset, $sortByFieldName, $selectedFilterSortByAscDesc, $where, $words, $searchText,
                    $problematic, $new_changed, $local_carrier_name, $regOrdersInWarehouse) = $this->initFilters($limit, $this);
            if (!empty($regOrdersInWarehouse)) {
                $orders = PurseOrderManager::getInstance()->getNotRegisteredOrdersInWarehouse($regOrdersInWarehouse, $local_carrier_name);
                $count = count($orders);
            } else {
                if ($problematic == 1) {
                    $orders = PurseOrderManager::getInstance()->getProblematicOrders($where);
                } elseif ($new_changed == 1) {
                    $idsArray = PurseOrderHistoryManager::getInstance()->getLast12HoursChangedOrderIds();
                    $orders = PurseOrderManager::getInstance()->selectByPKs($idsArray);
                } else {
                    $where = array_merge($where, ['AND', 'checkout_order_id', 'IS NULL']);
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

                $allProducts = ProductManager::getInstance()->selectAll(true);
                $this->addParam('all_products', $allProducts);
            }



            $pagesCount = ceil($count / $limit);

            $this->addParam('count', $count);

            $this->addParam('pagesCount', $pagesCount);
            $this->addParam('orders', $orders);

            $attachments = AttachmentManager::getInstance()->getEntitiesAttachments($orders, 'btc');
            $this->addParam('attachments', $attachments);

            $pos = PurchaseOrderManager::getInstance()->getBtcPurchaseOrders($orders);
            $this->addParam('btc_purchase_orders', $pos);

            $btc_products_days_diff_for_delivery_date = intval(SettingManager::getInstance()->getSetting('btc_products_days_diff_for_delivery_date'));
            $this->addParam('btc_products_days_diff_for_delivery_date', $btc_products_days_diff_for_delivery_date);



            $purse_checkout_meta = json_decode(SettingManager::getInstance()->getSetting('purse_checkout_meta', '{}'));
            $purse_pars_meta = json_decode(SettingManager::getInstance()->getSetting('purse_pars_meta', '{}'));
            $purse_info_meta = json_decode(SettingManager::getInstance()->getSetting('purse_info_meta', '{}'));
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

            $this->addParam('btc_rate', CryptoRateManager::getInstance()->getBtcRate());


            $allRecipients = RecipientManager::getInstance()->selectAdvance('*', [], ['email', 'first_name', 'last_name']);
            $recipientsMappedByUnitAddress = [];
            foreach ($allRecipients as $recipient) {
                $recipientsMappedByUnitAddress [$recipient->getExpressUnitAddress()] = $recipient;
                $recipientsMappedByUnitAddress [$recipient->getStandardUnitAddress()] = $recipient;
                $recipientsMappedByUnitAddress [$recipient->getOnexExpressUnit()] = $recipient;
                $recipientsMappedByUnitAddress [$recipient->getOnexStandardUnit()] = $recipient;
                $recipientsMappedByUnitAddress [$recipient->getNovaExpressUnit()] = $recipient;
                $recipientsMappedByUnitAddress [$recipient->getNovaStandardUnit()] = $recipient;
            }
            $this->addParam('recipientsMappedByUnitAddress', $recipientsMappedByUnitAddress);
        }

        public static function initFilters($limit = 10000, $load = null) {

            $regOrdersInWarehouse = [];
            $local_carrier_name = "";
            if (isset(NGS()->args()->roiw)) {
                $regOrdersInWarehouseStr = trim(NGS()->args()->roiw);
                if (!empty($regOrdersInWarehouseStr)) {
                    $regOrdersInWarehouseStr = preg_replace('/\s+/', ';', $regOrdersInWarehouseStr);
                    $regOrdersInWarehouseStr = str_replace(',', ';', $regOrdersInWarehouseStr);
                    $regOrdersInWarehouse = explode(';', $regOrdersInWarehouseStr);
                    $local_carrier_name = NGS()->args()->cn;
                    $limit = 1000;
                }
            }
            
            //pageing
            $selectedFilterPage = 1;
            if (isset(NGS()->args()->pg)) {
                $selectedFilterPage = intval(NGS()->args()->pg);
            }
            if (!empty($load)) {
                $load->addParam('selectedFilterPage', $selectedFilterPage);
            }
            $offset = 0;
            if ($selectedFilterPage > 1) {
                $offset = ($selectedFilterPage - 1) * intval($limit);
            }

            //sorting
            $sortByFields = self::getSortByFields();
            if (!empty($load)) {
                $load->addParam('sortFields', $sortByFields);
            }
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
                $selectedFilterAccount = strtolower(NGS()->args()->acc);
            }
            $selectedFilterMerchant = 'all';
            if (isset(NGS()->args()->mrch)) {
                $selectedFilterMerchant = strtolower(NGS()->args()->mrch);
            }
            $selectedFilterStatus = 'active';
            if (isset(NGS()->args()->stts)) {
                if (in_array(strtolower(NGS()->args()->stts), ['all', 'active', 'inactive'])) {
                    $selectedFilterStatus = strtolower(NGS()->args()->stts);
                }
            }
            $selectedFiltereStatus = 'all';
            if (isset(NGS()->args()->estts)) {
                $selectedFiltereStatus = strtolower(NGS()->args()->estts);
            }
            $selectedFiltereAdmin = 'all';
            if (isset(NGS()->args()->adm)) {
                $selectedFiltereAdmin = strtolower(NGS()->args()->adm);
            }
            
            if (!empty($load)) {
                $all_merchant_names_list = explode(',', SettingManager::getInstance()->getSetting('all_merchant_names_list'));
                $load->addParam('all_merchant_names_list', $all_merchant_names_list);

                $accountNames = PurseOrderManager::getInstance()->getAllAccountNames($selectedFilterMerchant);

                $load->addParam('account_names', $accountNames);
            }
            $selectedFilterRecipientId = 0;
            if (isset(NGS()->args()->rcpt)) {
                $selectedFilterRecipientId = intval(NGS()->args()->rcpt);
            }
            $orderType = 'all';
            if (isset(NGS()->args()->tp)) {
                $orderType = strtolower(NGS()->args()->tp);
            }
            $selectedFilterShippingType = 'all';
            if (isset(NGS()->args()->sht)) {
                $selectedFilterShippingType = strtolower(NGS()->args()->sht);
            }
            $problematic = 0;
            if (isset(NGS()->args()->pr)) {
                NGS()->args()->nc = 0;
                $problematic = intval(NGS()->args()->pr);
            }
            $new_changed = 0;
            if (isset(NGS()->args()->nc)) {
                $new_changed = intval(NGS()->args()->nc);
                NGS()->args()->pr = 0;
                $problematic = 0;
            }
            $searchText = '';
            if (isset(NGS()->args()->st)) {

                $searchText = trim(NGS()->args()->st);
            }


            if (!empty($regOrdersInWarehouse)) {
                $problematic = 0;
                $new_changed = 0;
                $searchText = '';
                $selectedFilterAccount = 'all';
                $selectedFilterMerchant = 'all';
                $selectedFilterHidden = 'no';
                $selectedFilterStatus = 'all';
                $selectedFiltereStatus = 'all';
                $selectedFiltereAdmin = 'all';
                $selectedFilterShippingType = 'all';
                $orderType = 'all';
                $selectedFilterRecipientId = 0;
                $offset = 0;
                $selectedFilterPage = 1;
            }
            if (!empty($load)) {
                $load->addParam('problematic', $problematic);
                $load->addParam('new_changed', $new_changed);
                $load->addParam('searchText', $searchText);
                $load->addParam('selectedFilterRecipientId', $selectedFilterRecipientId);
                $load->addParam('selectedFilterAccount', $selectedFilterAccount);
                $load->addParam('selectedFilterMerchant', $selectedFilterMerchant);
                $load->addParam('notRegOrdersInWarehouse', $regOrdersInWarehouse);
                $load->addParam('selectedFilterHidden', $selectedFilterHidden);
                $load->addParam('selectedFilterStatus', $selectedFilterStatus);
                $load->addParam('selectedFilterAdmin', $selectedFiltereAdmin);
                $load->addParam('selectedFiltereStatus', $selectedFiltereStatus);
                $load->addParam('selectedFilterShippingType', $selectedFilterShippingType);
                $load->addParam('orderType', $orderType);
                $load->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
                $load->addParam('selectedFilterSortBy', $selectedFilterSortBy);
            }

            $where = ['1', '=', '1'];
            
            
            if ($selectedFilterAccount !== 'all') {
                $where = array_merge($where, ['AND ', 'account_name', '=', "'$selectedFilterAccount'"]);
            }
            if ($selectedFilterMerchant !== 'all') {
                $where = array_merge($where, ['AND ', 'account_name', 'like', "'%$selectedFilterMerchant%'"]);
            }
            $activeStatusesSql = "('open', 'shipping', 'shipped', 'partially_delivered', 'under_balance','under_balance.confirming', 'accepted')";
            if ($selectedFilterStatus === 'active') {
                $where = array_merge($where, ['AND ', 'status', 'in', $activeStatusesSql]);
            }
            if ($selectedFilterStatus === 'musho') {
                $where = array_merge($where, ['AND ', 'admin_id', '=', 9]);
            }
            if ($selectedFilterStatus === 'lilit') {
                $where = array_merge($where, ['AND ', 'admin_id', '<>', 9]);
            }
            if ($selectedFilterStatus === 'inactive') {
                $where = array_merge($where, ['AND ', 'status', 'not in', $activeStatusesSql]);
            }
            if ($selectedFiltereStatus !== 'all') {
                $where = array_merge($where, ['AND ', 'status', '=', "'$selectedFiltereStatus'"]);
            }
            if ($selectedFilterHidden !== 'all') {
                $where = array_merge($where, ['AND ', 'hidden', '=', 0]);
            }
            if ($selectedFilterShippingType !== 'all') {
                $where = array_merge($where, ['AND ', 'shipping_type', '=', "'$selectedFilterShippingType'"]);
            }
            if ($orderType !== 'all') {
                $orderTypeVal = $orderType == 'external' ? 1 : 0;
                $where = array_merge($where, ['AND ', 'external', '=', $orderTypeVal]);
            }
            if ($selectedFilterRecipientId > 0) {
                $recipientUnitAddressesSql = RecipientManager::getInstance()->getRecipientUnitAddresses($selectedFilterRecipientId, true);
                $where = array_merge($where, ['AND ', 'unit_address', 'in', $recipientUnitAddressesSql]);
            }
            $words = [];
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
            
            $rowIdsArray = [];
            if (isset(NGS()->args()->ids)) {
                $rowIdsStr = trim(NGS()->args()->ids);
                if (!empty($rowIdsStr)) {
                    $rowIdsStr = preg_replace('/\s+/', '', $rowIdsStr);
                    $rowIdsStr = str_replace(',', ';', $rowIdsStr);
                    $rowIdsArray = explode(';', $rowIdsStr);                    
                    $rowIdsArray  = array_map('trim', $rowIdsArray );
                    $rowIdsArraySql = '('.implode(',', $rowIdsArray).')';
                    $where = ['id', 'in', $rowIdsArraySql];
                }
            }
            
            
            return [$offset, $selectedFilterSortBy, $selectedFilterSortByAscDesc, $where, $words, $searchText, $problematic, $new_changed, $local_carrier_name, $regOrdersInWarehouse, $rowIdsArray];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/purse/list.tpl";
        }

        public static function getSortByFields() {
            return ['created_at' => 'Created Date', 'status' => 'Status', 'updated_at' => 'Changed', 'buyer_name' => 'Buyer'];
        }

    }

}
