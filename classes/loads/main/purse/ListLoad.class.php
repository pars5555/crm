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
            list($offset, $sortByFieldName, $selectedFilterSortByAscDesc, $selectedFilterAccount, $selectedFilterHidden, $searchText) = $this->initFilters($limit);
            $where = ['1', '=', '1'];
            if ($selectedFilterAccount !== 'purse_all') {
                $where = array_merge($where, ['AND ', 'account_name', '=', "'$selectedFilterAccount'"]);
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
            $pagesCount = ceil($count / $limit);

            $this->addParam('changed_orders', NGS()->args()->changed_orders);
            $this->addParam('count', $count);
            $this->addParam('pagesCount', $pagesCount);
            $this->addParam('orders', $orders);
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
            $selectedFilterAccount = 'all';
            if (isset(NGS()->args()->acc)) {
                if (in_array(strtolower(NGS()->args()->acc), ['pars', 'info', 'checkout'])) {
                    $selectedFilterAccount = strtolower(NGS()->args()->acc);
                }
            }
            $searchText = '';
            if (isset(NGS()->args()->st)) {
                $searchText = trim(NGS()->args()->st);
            }

            $this->addParam('searchText', $searchText);
            $this->addParam('selectedFilterAccount', $selectedFilterAccount);
            $this->addParam('selectedFilterHidden', $selectedFilterHidden);
            $this->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
            $this->addParam('selectedFilterSortBy', $selectedFilterSortBy);

            return [$offset, $selectedFilterSortBy, $selectedFilterSortByAscDesc, 'purse_' . $selectedFilterAccount, $selectedFilterHidden ,$searchText];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/purse/list.tpl";
        }

        public function getSortByFields() {
            return ['status' => 'Status', 'updated_at' => 'Changed', 'buyer_name' => 'Buyer'];
        }

    }

}
