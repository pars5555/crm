<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\vanilla {

    use crm\loads\AdminLoad;
    use crm\managers\PartnerManager;
    use crm\managers\SettingManager;
    use crm\managers\VanillaCardsManager;
    use NGS;

    class ListLoad extends AdminLoad {

        public function load() {
            self::initLoad($this);
        }

        public static function initLoad($load) {
            $limit = 100;
            list($offset, $sortByFieldName, $selectedFilterSortByAscDesc, $balance, $searchText, $selectedFilterShowDeleted) = self::initFilters($limit, $load);
            $where = ['1', '=', '1'];
            if ($selectedFilterShowDeleted === 'no') {
                $where = array_merge($where, ['AND', 'deleted', '=', '0', 'AND', 'closed', '=', '0']);
            }
            if ($balance > 0) {
                $where = array_merge($where, ['AND', 'balance', '>=', $balance]);
            }
            if (!empty($searchText)) {

                $words = $parts = preg_split('/\s+/', $searchText);
                foreach ($words as $word) {
                    $where = array_merge($where, ['AND', '(', 'number', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'note', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'year', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'cvv', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'month', 'like', "'%$word%'", ')']);
                }
            }
            $barney_partner_id = intval(SettingManager::getInstance()->getSetting('barney_partner_id'));
            $debt = PartnerManager::getInstance()->calculatePartnerDebtBySalePurchaseAndPaymentTransations($barney_partner_id);
            $dollarDebt = 0;
            if (isset($debt[1])) {
                $dollarDebt = floatval($debt[1]);
            }
            $totalSuccess = VanillaCardsManager::getInstance()->getAllDeliveredTotal();
            $rows = VanillaCardsManager::getInstance()->selectAdvance('*', $where, $sortByFieldName, $selectedFilterSortByAscDesc, $offset, $limit);
            $count = VanillaCardsManager::getInstance()->getLastSelectAdvanceRowsCount();
            if (count($rows) === 0) {
                $load->addParam('selectedFilterPage', 1);
                $rows = VanillaCardsManager::getInstance()->selectAdvance('*', $where, $sortByFieldName, $selectedFilterSortByAscDesc, $offset, 0);
                $count = VanillaCardsManager::getInstance()->getLastSelectAdvanceRowsCount();
            }
            $pagesCount = ceil($count / $limit);
            $load->addParam('pagesCount', $pagesCount);
            $load->addParam('rows', $rows);
            $load->addParam('totalSuccess', $totalSuccess);
            $load->addParam('debt', $dollarDebt);

            $externalOrderIdsArray = [];
            foreach ($rows as $row) {
                $externalOrdersIds = $row->getExternalOrdersIds();
                if (!empty($externalOrdersIds)) {
                    $externalOrderIdsArray = array_merge($externalOrderIdsArray, array_map('intval', explode(',', $externalOrdersIds)));
                }
            }
            $exOrdersMappedById = \crm\managers\PurseOrderManager::getInstance()->selectByPKs($externalOrderIdsArray, true);

            self::addOrdersInfoToRows($rows, $exOrdersMappedById);
            $totalBalance = VanillaCardsManager::getInstance()->getTotalBalance(10);
            $load->addParam('total_balance', $totalBalance);
        }

        private static function initFilters($limit, $load) {
            //pageing
            $selectedFilterPage = 1;
            if (isset(NGS()->args()->pg)) {
                $selectedFilterPage = intval(NGS()->args()->pg);
            }
            $load->addParam('selectedFilterPage', $selectedFilterPage);
            $offset = 0;
            if ($selectedFilterPage > 1) {
                $offset = ($selectedFilterPage - 1) * intval($limit);
            }

            //sorting
            $sortByFields = $load->getSortByFields();
            $load->addParam('sortFields', $sortByFields);
            $selectedFilterSortBy = 'id';
            if (isset(NGS()->args()->srt)) {
                if (array_key_exists(NGS()->args()->srt, $sortByFields)) {
                    $selectedFilterSortBy = NGS()->args()->srt;
                }
            }
            //var_dump($selectedFilterSortBy);exit;
            $selectedFilterSortByAscDesc = 'DESC';
            if (isset(NGS()->args()->ascdesc)) {
                if (in_array(strtoupper(NGS()->args()->ascdesc), ['ASC', 'DESC'])) {
                    $selectedFilterSortByAscDesc = strtoupper(NGS()->args()->ascdesc);
                }
            }



            $minBalance = 0;
            if (isset(NGS()->args()->bal)) {
                $minBalance = floatval(NGS()->args()->bal);
            }
            $searchText = '';
            if (isset(NGS()->args()->st)) {
                $searchText = trim(NGS()->args()->st);
            }
            $selectedFilterShowDeleted = 'no';
            if (isset(NGS()->args()->shd)) {
                if (in_array(strtolower(NGS()->args()->shd), ['no', 'yes'])) {
                    $selectedFilterShowDeleted = strtolower(NGS()->args()->shd);
                }
            }
            $load->addParam('searchText', $searchText);
            $load->addParam('selectedFilterShowDeleted', $selectedFilterShowDeleted);
            $load->addParam('minBalance', $minBalance);
            $load->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
            $load->addParam('selectedFilterSortBy', $selectedFilterSortBy);
            return [$offset, $selectedFilterSortBy, $selectedFilterSortByAscDesc, $minBalance, $searchText, $selectedFilterShowDeleted];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/vanilla/list.tpl";
        }

        private static function addOrdersInfoToRows($rows, $exOrdersMappedById) {
            foreach ($rows as $row) {
                $externalOrdersIds = $row->getExternalOrdersIds();
                if (!empty($externalOrdersIds)) {
                    $orderIds = explode(',', $externalOrdersIds);
                    foreach ($orderIds as $orderId) {
                        $orderId = trim($orderId);
                        if (!isset($exOrdersMappedById[$orderId])) {
                            continue;
                        }
                        $amazonTotal = $exOrdersMappedById[$orderId]->getAmazonTotal();
                        if ($exOrdersMappedById[$orderId]->getStatus() !== 'canceled') {
                            $row->addOrderAmount($amazonTotal);
                        }
                        if ($exOrdersMappedById[$orderId]->getStatus() === 'delivered') {
                            $row->addSucceedAmountsText($amazonTotal);
                        }
                    }
                }
            }
        }

        public function getSortByFields() {
            return ['id' => 'ID', 'balance' => 'Balance'];
        }

    }

}
