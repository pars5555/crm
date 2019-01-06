<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\recipient {

    use crm\loads\AdminLoad;
    use crm\managers\AttachmentManager;
    use crm\managers\CurrencyManager;
    use crm\managers\PurseOrderManager;
    use crm\managers\RecipientManager;
    use NGS;

    class ListLoad extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $limit = 1000;
            list($offset, $searchText, $sortByFieldName, $selectedFilterSortByAscDesc, $selectedFilterDeleted, $selectedFilterHasDebt) = $this->initFilters($limit);

            $where = ['1', '=', '1'];
            if (!empty($searchText)) {
                $words = preg_split('/\s+/', $searchText);
                foreach ($words as $word) {
                    $where = array_merge($where, ['AND', '(', 'first_name', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'last_name', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'express_unit_address', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'standard_unit_address', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'onex_express_unit', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'onex_standard_unit', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'nova_express_unit', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'nova_standard_unit', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'note', 'like', "'%$word%'", ')']);
                }
            }

            $recipientManager = RecipientManager::getInstance();
            if ($selectedFilterDeleted !== 'all') {
                $where = array_merge($where, ['and', 'deleted', '=', 0]);
            }
            $join = '';
            $groupBy = '';

            $recipients = $recipientManager->selectAdvance('recipients.*', $where, $sortByFieldName, $selectedFilterSortByAscDesc, $offset, $limit, false, $join, $groupBy);
            $recipientIds = $recipientManager->getDtosIdsArray($recipients);
            $recipientsRecentOrdersMappedByrecipientId = [];
            if (!empty($recipientIds)) {
                $recipientsRecentOrdersMappedByrecipientId = PurseOrderManager::getInstance()->getRecipientsRecentOrders($recipientIds);
            }
            $this->addParam('recipientsRecentOrdersMappedByRecipientId', $recipientsRecentOrdersMappedByrecipientId);
            $this->addParam('recipients', $recipients);
            $count = RecipientManager::getInstance()->getLastSelectAdvanceRowsCount();
            $attachments = AttachmentManager::getInstance()->getEntitiesAttachments($recipients, 'recipient');
            $this->addParam('attachments', $attachments);

            if (count($recipients) == 0 && $count > 0) {
                $this->redirectIncludedParamsExeptPaging();
            }
            $pagesCount = ceil($count / $limit);
            $this->addParam('pagesCount', $pagesCount);

            $currencyManager = CurrencyManager::getInstance();
            $this->addParam('currencies', $currencyManager->mapDtosById($currencyManager->selectAdvance('*', ['active', '=', 1], ['name'])));
        }

        private function redirectIncludedParamsExeptPaging() {
            $url = "recipient?";
            if (isset(NGS()->args()->srt)) {
                $url .= "srt=" . NGS()->args()->srt . '&';
            }
            if (isset(NGS()->args()->ascdesc)) {
                $url .= "ascdesc=" . NGS()->args()->ascdesc . '&';
            }
            if (isset(NGS()->args()->hddn)) {
                $url .= "hddn=" . NGS()->args()->hddn . '&';
            }
            $this->redirect(trim($url, '&?'));
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
            $selectedFilterSortBy = 'favorite';
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
            $selectedFilterDeleted = 'no';
            if (isset(NGS()->args()->del)) {
                if (in_array(strtolower(NGS()->args()->del), ['all', 'no'])) {
                    $selectedFilterDeleted = strtolower(NGS()->args()->del);
                }
            }
            $selectedFilterShowStandardUnits = 'no';
            if (isset(NGS()->args()->ssu)) {
                if (in_array(strtolower(NGS()->args()->ssu), ['yes', 'no'])) {
                    $selectedFilterShowStandardUnits = strtolower(NGS()->args()->ssu);
                }
            }

            $selectedFilterHasDebt = 'all';
            if (isset(NGS()->args()->hasdebt)) {
                if (in_array(strtolower(NGS()->args()->hasdebt), ['all', 'no', 'yes'])) {
                    $selectedFilterHasDebt = strtolower(NGS()->args()->hasdebt);
                }
            }

            $searchText = '';
            if (isset(NGS()->args()->st)) {

                $searchText = trim(NGS()->args()->st);
            }

            $this->addParam('searchText', $searchText);
            $this->addParam('selectedFilterHasDebt', $selectedFilterHasDebt);
            $this->addParam('selectedFilterDeleted', $selectedFilterDeleted);
            $this->addParam('selectedFilterShowStandardUnits', $selectedFilterShowStandardUnits);
            $this->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
            $this->addParam('selectedFilterSortBy', $selectedFilterSortBy);

            return [$offset, $searchText, $selectedFilterSortBy, $selectedFilterSortByAscDesc, $selectedFilterDeleted, $selectedFilterHasDebt];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/recipient/list.tpl";
        }

        public function getSortByFields() {
            return ['favorite' => 'Favorite', 'first_name, last_name' => 'Name', 'email' => 'Email'];
        }

    }

}
