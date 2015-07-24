<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main {

    use crm\loads\NgsLoad;
    use crm\managers\CurrencyManager;
    use crm\managers\PartnerManager;
    use crm\managers\PaymentTransactionManager;
    use crm\security\RequestGroups;
    use NGS;

    class PaymentsListLoad extends NgsLoad {

        public function load() {
            $this->addParam('partners', PartnerManager::getInstance()->selectAdvance('*', [], ['name']));
            $this->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));

            $limit = 100;
            list($where, $offset, $limit) = $this->initFilters($limit);
            $payments = PaymentTransactionManager::getInstance()->getPaymentsListFull($where, null, 'ASC', $offset, $limit);
            $this->addParam('payments', $payments);
            $count = PaymentTransactionManager::getInstance()->getLastSelectAdvanceRowsCount();
            if (count($payments) == 0 && $count > 0) {
                $this->redirectIncludedParamsExeptPaging();
            }
            $pagesCount = intval($count / $limit);
            $this->addParam('pagesCount', $pagesCount);
        }

        private function redirectIncludedParamsExeptPaging() {
            $url = "payments?";
            if (isset(NGS()->args()->prt)) {
                $url .= "prt=" . NGS()->args()->prt . '&';
            }
            if (isset(NGS()->args()->cur)) {
                $url .= "cur=" . NGS()->args()->cur . '&';
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
            return [$where, $offset, $limit];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/payment/payments_list.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
