<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\payment {

    use crm\loads\NgsLoad;
    use crm\managers\CurrencyManager;
    use crm\managers\PartnerManager;
    use crm\managers\PaymentTransactionManager;
    use crm\security\RequestGroups;
    use NGS;

    class ListLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $limit = 100;
            list($where, $offset, $sortByFieldName, $selectedFilterSortByAscDesc) = $this->initFilters($limit);
            $payments = PaymentTransactionManager::getInstance()->getPaymentListFull($where, $sortByFieldName, $selectedFilterSortByAscDesc, $offset, $limit);
            $this->addParam('payments', $payments);
            $count = PaymentTransactionManager::getInstance()->getLastSelectAdvanceRowsCount();
            if (count($payments) == 0 && $count > 0) {
                $this->redirectIncludedParamsExeptPaging();
            }
            $pagesCount = intval($count / $limit);
            $this->addParam('pagesCount', $pagesCount);
        }

        private function redirectIncludedParamsExeptPaging() {
            $url = "payment/list?";
            if (isset(NGS()->args()->prt)) {
                $url .= "prt=" . NGS()->args()->prt . '&';
            }
            if (isset(NGS()->args()->cur)) {
                $url .= "cur=" . NGS()->args()->cur . '&';
            }
            if (isset(NGS()->args()->srt)) {
                $url .= "srt=" . NGS()->args()->srt . '&';
            }
            if (isset(NGS()->args()->ascdesc)) {
                $url .= "ascdesc=" . NGS()->args()->ascdesc . '&';
            }
            $this->redirect(trim($url, '&?'));
        }

        private function initFilters($limit) {
            $where = [];
            //partner
            $selectedFilterPartnerId = 0;
            if (isset(NGS()->args()->prt)) {
                $selectedFilterPartnerId = intval(NGS()->args()->prt);
            }
            $this->addParam('selectedFilterPartnerId', $selectedFilterPartnerId);
            if ($selectedFilterPartnerId > 0) {
                $where[] = 'partner_id';
                $where[] = '=';
                $where[] = $selectedFilterPartnerId;
            }

            //currency
            $selectedFilterCurrencyId = 0;
            if (isset(NGS()->args()->cur)) {
                $selectedFilterCurrencyId = intval(NGS()->args()->cur);
            }
            $this->addParam('selectedFilterCurrencyId', $selectedFilterCurrencyId);
            if ($selectedFilterCurrencyId > 0) {
                $where[] = 'currency_id';
                $where[] = '=';
                $where[] = $selectedFilterCurrencyId;
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
            $this->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
            $this->addParam('selectedFilterSortBy', $selectedFilterSortBy);

            return [$where, $offset, $selectedFilterSortBy, $selectedFilterSortByAscDesc];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/payment/list.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

        public function getSortByFields() {
            return ['date' => 'Date', 'amount' => 'Amount'];
        }

    }

}
