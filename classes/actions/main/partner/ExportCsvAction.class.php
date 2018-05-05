<?php

namespace crm\actions\main\partner {

    use crm\actions\BaseAction;
    use crm\managers\CalculationManager;
    use crm\managers\CurrencyManager;
    use crm\managers\PartnerInitialDebtManager;
    use crm\managers\PartnerManager;
    use crm\managers\PaymentTransactionManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\SaleOrderManager;
    use NGS;

    class ExportCsvAction extends BaseAction {

        public function service() {
            list($sortByFieldName, $selectedFilterSortByAscDesc, $selectedFilterHidden, $selectedFilterHasDebt) = $this->initFilters();
            $currencyManager = CurrencyManager::getInstance();
            $partnerManager = PartnerManager::getInstance();
            $where = ['1', '=', '1'];
            if ($selectedFilterHidden !== 'all') {
                $where = array_merge($where, ['and', 'hidden', '=', 0]);
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
            $partners = $partnerManager->selectAdvance('partners.*', $where, $sortByFieldName, $selectedFilterSortByAscDesc, 0, 0, false, $join, $groupBy);
            $partnerIds = $partnerManager->getDtosIdsArray($partners);
            $partnersSaleOrdersMappedByPartnerId = [];
            $partnersPurchaseOrdersMappedByPartnerId = [];
            $partnersInitialDebt = [];
            if (!empty($partnerIds)) {
                $partnersSaleOrdersMappedByPartnerId = SaleOrderManager::getInstance()->getPartnersSaleOrders($partnerIds);
                $partnersPurchaseOrdersMappedByPartnerId = PurchaseOrderManager::getInstance()->getPartnersPurchaseOrders($partnerIds);
                $partnersPaymentTransactionsMappedByPartnerId = PaymentTransactionManager::getInstance()->getPartnersPaymentTransactions($partnerIds);
                $partnersBillingTransactionsMappedByPartnerId = PaymentTransactionManager::getInstance()->getPartnersBillingTransactions($partnerIds);
                $partnersInitialDebt = PartnerInitialDebtManager::getInstance()->getPartnersInitialDebt($partnerIds);
            }
            $partnersDebt = CalculationManager::getInstance()->calculatePartnersDebtBySalePurchaseAndPaymentTransations($partnersSaleOrdersMappedByPartnerId, $partnersPurchaseOrdersMappedByPartnerId, $partnersPaymentTransactionsMappedByPartnerId, $partnersBillingTransactionsMappedByPartnerId, $partnersInitialDebt);


            $currencies = $currencyManager->mapDtosById($currencyManager->selectAdvance('*', ['active', '=', 1], ['name']));
            $this->exportCsv($partners, $partnersDebt, $currencies);
        }

        public function getSortByFields() {
            return ['name' => 'Name', 'email' => 'Email'];
        }

        private function initFilters() {
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

            return [$selectedFilterSortBy, $selectedFilterSortByAscDesc, $selectedFilterHidden, $selectedFilterHasDebt];
        }

        public function exportCsv($partners, $partnersDebt, $currencies) {
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=observers.csv');
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
            $largeArraySize = 0;
            $largeArray = [];

            foreach ($partners as $partner) {
                if (isset($partnersDebt[$partner->getId()]) && count($partnersDebt[$partner->getId()]) > $largeArraySize) {
                    $largeArraySize = count($partnersDebt[$partner->getId()]);
                    $largeArray = $partnersDebt[$partner->getId()];
                }
            }
            $currenciesSymbolArray = [];
            foreach ($largeArray as $currencyId => $value) {
                $currencyDto = $currencies[$currencyId];
                $templateChar = $currencyDto->getTemplateChar();
                $currenciesSymbolArray [] = $templateChar;
            }
            $headerFieldNames = ['Name', 'Email', 'Phone Number'];
            $headerFieldNames = array_merge($headerFieldNames, $currenciesSymbolArray);
            fputcsv($output, $headerFieldNames);

            fputcsv($output, []);
            foreach ($partners as $partner) {
                $debts = [];
                if (isset($partnersDebt[$partner->getId()])) {
                    foreach ($partnersDebt[$partner->getId()] as $currencyId => $amount) {
                        $debts[] = $amount;
                    }
                }
                $fieldValues = array_merge([$partner->getName(), $partner->getEmail(), $partner->getPhone()], $debts);
                fputcsv($output, $fieldValues);
            }

            exit;
        }

    }

}