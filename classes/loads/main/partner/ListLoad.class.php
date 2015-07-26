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

    use crm\loads\NgsLoad;
    use crm\managers\PartnerManager;
    use crm\managers\PaymentTransactionManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\SaleOrderManager;
    use crm\security\RequestGroups;
    use NGS;

    class ListLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $limit = 100;
            list($offset, $sortByFieldName, $selectedFilterSortByAscDesc) = $this->initFilters($limit);
            $partnerManager = PartnerManager::getInstance();
            $partners = $partnerManager->selectAdvance('*', [], $sortByFieldName, $selectedFilterSortByAscDesc, $offset, $limit);
            $partnerIds = $partnerManager->getDtosIdsArray($partners);
            $partnersSaleOrdersMappedByPartnerId = SaleOrderManager::getInstance()->getPartnersSaleOrders($partnerIds);
            $partnersPurchaseOrdersMappedByPartnerId = PurchaseOrderManager::getInstance()->getPartnersPurchaseOrders($partnerIds);
            $partnersTransactionsMappedByPartnerId = PaymentTransactionManager::getInstance()->getPartnersTransactions($partnerIds);

            $this->calculatePartnerDeptBySalePurchaseAndPaymentTransations($partnersSaleOrdersMappedByPartnerId, $partnersPurchaseOrdersMappedByPartnerId, $partnersTransactionsMappedByPartnerId);
            $this->addParam('partnersSaleOrdersMappedByPartnerId', $partnersSaleOrdersMappedByPartnerId);
            $this->addParam('partnersPurchaseOrdersMappedByPartnerId', $partnersPurchaseOrdersMappedByPartnerId);
            $this->addParam('partnersTransactionsMappedByPartnerId', $partnersTransactionsMappedByPartnerId);

            $this->addParam('partners', $partners);
            $count = PartnerManager::getInstance()->getLastSelectAdvanceRowsCount();
            if (count($partners) == 0 && $count > 0) {
                $this->redirectIncludedParamsExeptPaging();
            }
            $pagesCount = intval($count / $limit);
            $this->addParam('pagesCount', $pagesCount);
        }

        private function calculatePartnerDeptBySalePurchaseAndPaymentTransations($partnersSaleOrdersMappedByPartnerId, $partnersPurchaseOrdersMappedByPartnerId, $partnersTransactionsMappedByPartnerId) {
            $partnersDept = [];
            foreach ($partnersSaleOrdersMappedByPartnerId as $partnerId => $saleOrders) {
                foreach ($saleOrders as $saleOrder) {
                    foreach ($saleOrder->getSaleOrderLinesDtos() as $saleOrderLine) {
                        $currencyId = $saleOrderLine->getCurrencyId();
                        $amount = $saleOrderLine->getAmount();
                        if (!array_key_exists($partnerId, $partnersDept)) {
                            $partnersDept[$partnerId] = [];
                        }
                        if (!array_key_exists($currencyId, $partnersDept[$partnerId])) {
                            $partnersDept[$partnerId][$currencyId] = 0;
                        }

                        $partnersDept[$partnerId][$currencyId] += $amount;
                    }
                }
            }
            foreach ($partnersPurchaseOrdersMappedByPartnerId as $partnerId => $purchaseOrders) {
                foreach ($purchaseOrders as $purchaseOrder) {
                    foreach ($purchaseOrder->getPurchaseOrderLinesDtos() as $purchaseOrderLine) {
                        $currencyId = $purchaseOrderLine->getCurrencyId();
                        $amount = $purchaseOrderLine->getAmount();
                        if (!array_key_exists($partnerId, $partnersDept)) {
                            $partnersDept[$partnerId] = [];
                        }
                        if (!array_key_exists($currencyId, $partnersDept[$partnerId])) {
                            $partnersDept[$partnerId][$currencyId] = 0;
                        }
                        $partnersDept[$partnerId][$currencyId] -= $amount;
                    }
                }
            }
            foreach ($partnersTransactionsMappedByPartnerId as $partnerId => $transactions) {
                foreach ($transactions as $transaction) {
                    $currencyId = $transaction->getCurrencyId();
                    $amount = $transaction->getAmount();
                    if (!array_key_exists($partnerId, $partnersDept)) {
                        $partnersDept[$partnerId] = [];
                    }
                    if (!array_key_exists($currencyId, $partnersDept[$partnerId])) {
                        $partnersDept[$partnerId][$currencyId] = 0;
                    }
                    $partnersDept[$partnerId][$currencyId] += $amount;
                }
            }
        }

        private function redirectIncludedParamsExeptPaging() {
            $url = "partners?";
            if (isset(NGS()->args()->srt)) {
                $url .= "srt=" . NGS()->args()->srt . '&';
            }
            if (isset(NGS()->args()->ascdesc)) {
                $url .= "ascdesc=" . NGS()->args()->ascdesc . '&';
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
            $this->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
            $this->addParam('selectedFilterSortBy', $selectedFilterSortBy);

            return [$offset, $selectedFilterSortBy, $selectedFilterSortByAscDesc];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/partner/list.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

        public function getSortByFields() {
            return ['name' => 'Name', 'email' => 'Email'];
        }

    }

}
