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

    use crm\managers\PurseOrderManager;
    use NGS;

    class GuestListLoad extends \crm\loads\GuestLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $limit = 200;
            list($offset, $sortByFieldName, $selectedFilterSortByAscDesc, $selectedFilterHidden, $selectedFilterStatus, $searchText) = $this->initFilters($limit);
                $where = ['account_name', '=', "'purse_checkout'"];
            $activeStatusesSql = "('open', 'shipping', 'shipped', 'partially_delivered', 'under_balance', 'accepted')";
            if ($selectedFilterStatus === 'active') {
                $where = array_merge($where, ['AND ', 'status', 'in', $activeStatusesSql]);
            }
            if ($selectedFilterStatus === 'inactive') {
                $where = array_merge($where, ['AND ', 'status', 'not in', $activeStatusesSql]);
            }
            if ($selectedFilterHidden !== 'all') {
                $where = array_merge($where, ['AND ', 'hidden', '=', 0]);
            }
            if (!empty($searchText)) {
                $where = array_merge($where, ['AND', '(', 'product_name', 'like', "'%$searchText%'"]);
                $where = array_merge($where, ['OR', 'order_number', 'like', "'%$searchText%'"]);
                $where = array_merge($where, ['OR', 'amazon_order_number', 'like', "'%$searchText%'"]);
                $where = array_merge($where, ['OR', 'recipient_name', 'like', "'%$searchText%'"]);
                $where = array_merge($where, ['OR', 'serial_number', 'like', "'%$searchText%'"]);
                $where = array_merge($where, ['OR', 'tracking_number', 'like', "'%$searchText%'", ')']);
            }
            $orders = PurseOrderManager::getInstance()->getOrders($where, $sortByFieldName, $selectedFilterSortByAscDesc, $offset, $limit);
            $count = PurseOrderManager::getInstance()->getLastSelectAdvanceRowsCount();

            $ordersPuposedToNotReceivedToDestinationCounty = PurseOrderManager::getInstance()->getOrdersPuposedToNotReceivedToDestinationCounty($where, $sortByFieldName, $selectedFilterSortByAscDesc, $offset, $limit);

            $totalPuposedToNotReceived = 0;
            $searchedItemCount = 0;
            foreach ($ordersPuposedToNotReceivedToDestinationCounty as $order) {
                $productName = $order->getProductName();
                if (!empty($searchText) &&  stripos($productName, $searchText) !== false) {
                    $searchedItemCount += 1;
                }
                $totalPuposedToNotReceived += floatval($order->getAmazonTotal());
            }


            $pagesCount = ceil($count / $limit);

            $this->addParam('total_puposed_to_not_received', $totalPuposedToNotReceived);
            $this->addParam('not_received_orders_count', count($ordersPuposedToNotReceivedToDestinationCounty));
            $this->addParam('changed_orders', NGS()->args()->changed_orders);
            $this->addParam('count', $count);
            $this->addParam('searchedItemPuposedCount', $searchedItemCount);
            
            $this->addParam('pagesCount', $pagesCount);
            $this->addParam('orders', $orders);
            
            $btc_products_days_diff_for_delivery_date = intval(\crm\managers\SettingManager::getInstance()->getSetting('btc_products_days_diff_for_delivery_date'));
            $this->addParam('btc_products_days_diff_for_delivery_date', $btc_products_days_diff_for_delivery_date);
        }

        private function initFilters($limit) {


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
            $selectedFilterSortBy = 0;
            if (isset(NGS()->args()->srt)) {
                if (array_key_exists(NGS()->args()->srt, $sortByFields)) {
                    $selectedFilterSortBy = NGS()->args()->srt;
                }
            }
            $selectedFilterSortByAscDesc = 'ASC';
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
            
            $selectedFilterStatus = 'active';
            if (isset(NGS()->args()->stts)) {
                if (in_array(strtolower(NGS()->args()->stts), ['all', 'active', 'inactive'])) {
                    $selectedFilterStatus = strtolower(NGS()->args()->stts);
                }
            }
            $searchText = '';
            if (isset(NGS()->args()->st)) {
                
                $selectedFilterStatus = 'all';
                $searchText = trim(NGS()->args()->st);
            }

            $this->addParam('searchText', $searchText);
            $this->addParam('selectedFilterHidden', $selectedFilterHidden);
            $this->addParam('selectedFilterStatus', $selectedFilterStatus);
            $this->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
            $this->addParam('selectedFilterSortBy', $selectedFilterSortBy);

            return [$offset, $selectedFilterSortBy, $selectedFilterSortByAscDesc, $selectedFilterHidden, $selectedFilterStatus, $searchText];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/purse/guest_list.tpl";
        }

        public function getSortByFields() {
            return ['status' => 'Status', 'updated_at' => 'Changed', 'buyer_name' => 'Buyer'];
        }

    }

}
