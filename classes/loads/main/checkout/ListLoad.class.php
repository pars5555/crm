<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\checkout {

    use crm\dal\dto\PurseOrderDto;
    use crm\loads\AdminLoad;
    use crm\managers\AttachmentManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\PurseOrderManager;
    use crm\managers\RecipientManager;
    use crm\managers\SettingManager;

    class ListLoad extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $limit = 200;
            list($offset, $sortByFieldName, $selectedFilterSortByAscDesc, $where, $words, $searchText,
                    $problematic) = $this->initFilters($limit, $this);
            if ($problematic == 1) {
                $orders = PurseOrderManager::getInstance()->getProblematicOrders($where, true);
            } else {
                
                $orders = PurseOrderManager::getInstance()->getOrders($where, $sortByFieldName, $selectedFilterSortByAscDesc, $offset, $limit);
            }
            $count = PurseOrderManager::getInstance()->getLastSelectAdvanceRowsCount();

            $ordersPuposedToNotReceivedToDestinationCounty = PurseOrderManager::getInstance()->getOrdersPuposedToNotReceivedToDestinationCounty(true);

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



            $pagesCount = ceil($count / $limit);

            $this->addParam('changed_orders', NGS()->args()->changed_orders);
            $this->addParam('count', $count);

            $this->addParam('pagesCount', $pagesCount);
            $this->addParam('orders', $orders);

            $attachments = AttachmentManager::getInstance()->getEntitiesAttachments($orders, 'btc');
            $checkoutAttachments = AttachmentManager::getInstance()->getEntitiesAttachments($orders, 'checkout');
            $this->addParam('attachments', $attachments);
            $this->addParam('checkout_attachments', $checkoutAttachments);

            $pos = PurchaseOrderManager::getInstance()->getBtcPurchaseOrders($orders);
            $this->addParam('btc_purchase_orders', $pos);

            $btc_products_days_diff_for_delivery_date = intval(SettingManager::getInstance()->getSetting('btc_products_days_diff_for_delivery_date'));
            $this->addParam('btc_products_days_diff_for_delivery_date', $btc_products_days_diff_for_delivery_date);


            $this->addParam('recipients', RecipientManager::getInstance()->selectAdvance('*', [], ['first_name', 'last_name']));
            $this->addParam('checkout_order_statuses', PurseOrderDto::CHECKOUT_ORDER_STATUSES);


            $u1 = SettingManager::getInstance()->getSetting('onex_unit_address');
            $u2 = SettingManager::getInstance()->getSetting('nova_unit_address');
            $u3 = SettingManager::getInstance()->getSetting('globbing_unit_address');
            $u4 = SettingManager::getInstance()->getSetting('shipex_unit_address');
            $this->addParam('virtual_unit_addresses', [$u1, $u2, $u3, $u4]);
        }

        public static function initFilters($limit = 10000, $load = null) {

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
            $selectedFilterCheckoutStatus = 'active';
            if (isset(NGS()->args()->chst)) {
                if (in_array(strtolower(NGS()->args()->chst), ['all', 'active', 'inactive'])) {
                    $selectedFilterCheckoutStatus = strtolower(NGS()->args()->chst);
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
            $showUnitAddressesOrdersOnly = 0;
            if (isset(NGS()->args()->vu)) {
                $showUnitAddressesOrdersOnly = intval(NGS()->args()->vu);
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
                $selectedFilterCheckoutStatus = 'active';
                $selectedFilterStatus = 'all';
                $selectedFilterShippingType = 'all';
                $orderType = 'all';
                $selectedFilterRecipientId = 0;
                $offset = 0;
                $showUnitAddressesOrdersOnly = 0;
                $selectedFilterPage = 1;
            }
            if (!empty($load)) {
                $load->addParam('problematic', $problematic);
                $load->addParam('showUnitAddressesOrdersOnly', $showUnitAddressesOrdersOnly);
                $load->addParam('searchText', $searchText);
                $load->addParam('selectedFilterRecipientId', $selectedFilterRecipientId);
                $load->addParam('selectedFilterAccount', $selectedFilterAccount);
                $load->addParam('selectedFilterCheckoutStatus', $selectedFilterCheckoutStatus);
                $load->addParam('selectedFilterStatus', $selectedFilterStatus);
                $load->addParam('selectedFilterShippingType', $selectedFilterShippingType);
                $load->addParam('orderType', $orderType);
                $load->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
                $load->addParam('selectedFilterSortBy', $selectedFilterSortBy);
            }

            $where = ['1', '=', '1'];
            if ($selectedFilterAccount !== 'all') {
                $where = array_merge($where, ['AND ', 'account_name', '=', "'purse_$selectedFilterAccount'"]);
            }
            $activeStatusesSql = "('open', 'shipping', 'shipped', 'partially_delivered', 'under_balance','under_balance.confirming', 'accepted')";
            if ($selectedFilterStatus === 'active') {
                $where = array_merge($where, ['AND ', 'status', 'in', $activeStatusesSql]);
            }
            if ($selectedFilterStatus === 'inactive') {
                $where = array_merge($where, ['AND ', 'status', 'not in', $activeStatusesSql]);
            }
            if ($selectedFilterCheckoutStatus === 'active') {
                $where = array_merge($where, ['AND ', 'checkout_order_status', '<', 20]);
            }
            if ($selectedFilterCheckoutStatus === 'inactive') {
                $where = array_merge($where, ['AND ', 'checkout_order_status', '>=', 20]);
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
            if ($showUnitAddressesOrdersOnly == 1) {
                $fakeRecipientUnitAddressesSql = RecipientManager::getInstance()->getFakeRecipientUnitAddressesSql();
                $where = array_merge($where, ['AND', 'unit_address', 'in', "($fakeRecipientUnitAddressesSql)"]);
                    
            }else{
                $where = array_merge($where, ['AND', 'checkout_order_id', '>', 0]);
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
            return [$offset, $selectedFilterSortBy, $selectedFilterSortByAscDesc, $where, $words, $searchText, $problematic];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/checkout/list.tpl";
        }

        public static function getSortByFields() {
            return ['created_at' => 'Created Date', 'status' => 'Status', 'updated_at' => 'Changed', 'buyer_name' => 'Buyer'];
        }

    }

}
