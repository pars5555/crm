<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\partner {

    use crm\loads\AdminLoad;
    use crm\managers\AttachmentManager;
    use crm\managers\CalculationManager;
    use crm\managers\CurrencyManager;
    use crm\managers\PartnerInitialDebtManager;
    use crm\managers\PartnerManager;
    use crm\managers\PaymentTransactionManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\SaleOrderManager;
    use NGS;

    class ListLoad extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $limit = 100;
            list($offset, $sortByFieldName, $selectedFilterSortByAscDesc, $selectedFilterHidden, $selectedFilterHasDebt, $searchText) = $this->initFilters($limit);
            $partnerManager = PartnerManager::getInstance();
            $where = ['1', '=', '1'];
            if ($selectedFilterHidden !== 'all') {
                $where = array_merge($where, ['and', 'hidden', '=', 0]);
            }
            if (!empty($searchText)) {
                $where = array_merge($where, ['AND', '(', 'name', 'like', "'%$searchText%'"]);
                $where = array_merge($where, ['OR', 'phone', 'like', "'%$searchText%'", ')']);
            }
            $join = '';
            $groupBy = '';
            if ($selectedFilterHasDebt === 'yes') {
                $join = 'LEFT JOIN partner_debt_cache on partner_debt_cache.partner_id=partners.id';

                $groupBy = 'GROUP BY partners.id';
                $where = array_merge($where, ['and', 'partner_debt_cache.amount', '>', 0]);
            }
            if ($selectedFilterHasDebt === 'no') {
                $join = 'LEFT JOIN partner_debt_cache on partner_debt_cache.partner_id=partners.id';
                $groupBy = 'GROUP BY partners.id';
                $where = array_merge($where, ['and', 'partner_debt_cache.amount', '<', 0]);
            }
            $partners = $partnerManager->selectAdvance('partners.*', $where, $sortByFieldName, $selectedFilterSortByAscDesc, $offset, $limit, false, $join, $groupBy);
            $partnerIds = $partnerManager->getDtosIdsArray($partners);
            $partnersInitialDebt = [];
            $partnersSaleOrdersMappedByPartnerId = [];
            $partnersPurchaseOrdersMappedByPartnerId = [];
            $partnersPaymentTransactionsMappedByPartnerId = [];
            $partnersBillingTransactionsMappedByPartnerId = [];

            if (!empty($partnerIds)) {
                $partnersSaleOrdersMappedByPartnerId = SaleOrderManager::getInstance()->getPartnersSaleOrders($partnerIds);
                $partnersPurchaseOrdersMappedByPartnerId = PurchaseOrderManager::getInstance()->getPartnersPurchaseOrders($partnerIds);
                $partnersPaymentTransactionsMappedByPartnerId = PaymentTransactionManager::getInstance()->getPartnersPaymentTransactions($partnerIds);
                $partnersBillingTransactionsMappedByPartnerId = PaymentTransactionManager::getInstance()->getPartnersBillingTransactions($partnerIds);
                $partnersInitialDebt = PartnerInitialDebtManager::getInstance()->getPartnersInitialDebt($partnerIds);
            }
            $partnersDebt = CalculationManager::getInstance()->calculatePartnersDebtBySalePurchaseAndPaymentTransations($partnersSaleOrdersMappedByPartnerId, $partnersPurchaseOrdersMappedByPartnerId, $partnersPaymentTransactionsMappedByPartnerId, $partnersBillingTransactionsMappedByPartnerId, $partnersInitialDebt);
            $this->addParam('partnersSaleOrdersMappedByPartnerId', $partnersSaleOrdersMappedByPartnerId);
            $this->addParam('partnersPurchaseOrdersMappedByPartnerId', $partnersPurchaseOrdersMappedByPartnerId);
            $this->addParam('partnersPaymentTransactionsMappedByPartnerId', $partnersPaymentTransactionsMappedByPartnerId);
            $this->addParam('partnersBillingTransactionsMappedByPartnerId', $partnersBillingTransactionsMappedByPartnerId);
            $this->addParam('partnersDebt', $partnersDebt);

            $count = PartnerManager::getInstance()->getLastSelectAdvanceRowsCount();
            $this->addParam('partners', $partners);
            $attachments = AttachmentManager::getInstance()->getEntitiesAttachments($partners, 'partner');
            $this->addParam('attachments', $attachments);
            if (count($partners) == 0 && $count > 0) {
                $this->redirectIncludedParamsExeptPaging();
            }
            $pagesCount = ceil($count / $limit);
            $this->addParam('pagesCount', $pagesCount);

            $currencyManager = CurrencyManager::getInstance();
            $this->addParam('currencies', $currencyManager->mapDtosById($currencyManager->selectAdvance('*', ['active', '=', 1], ['name'])));
        }

        private function redirectIncludedParamsExeptPaging() {
            $url = "partners?";
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
            $this->addParam('selectedFilterHidden', $selectedFilterHidden);
            $this->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
            $this->addParam('selectedFilterSortBy', $selectedFilterSortBy);

            return [$offset, $selectedFilterSortBy, $selectedFilterSortByAscDesc, $selectedFilterHidden, $selectedFilterHasDebt, $searchText];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/partner/list.tpl";
        }

        public function getSortByFields() {
            return ['name' => 'Name', 'email' => 'Email'];
        }

    }

}
