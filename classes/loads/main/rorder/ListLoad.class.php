<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\rorder {

    use crm\loads\AdminLoad;
    use crm\managers\CurrencyManager;
    use crm\managers\RecipientManager;
    use crm\managers\PaymentMethodManager;
    use crm\managers\ProductManager;
    use crm\managers\RecipientOrderManager;
    use NGS;

    class ListLoad  extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $this->addParam('payment_methods', PaymentMethodManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
            $this->addParam('recipients', RecipientManager::getInstance()->selectAdvance('*', [], ['name']));
            $this->addParam('products', ProductManager::getInstance()->selectAdvance('*', [], ['name']));

            $limit = 100;
            list($where, $offset, $sortByFieldName, $selectedFilterSortByAscDesc) = $this->initFilters($limit);
            $recipientOrders = RecipientOrderManager::getInstance()->getRecipientOrdersFull($where, $sortByFieldName, $selectedFilterSortByAscDesc, $offset, $limit);
            $this->addParam('recipientOrders', $recipientOrders);
            $count = RecipientOrderManager::getInstance()->getLastSelectAdvanceRowsCount();
            if (count($recipientOrders) == 0 && $count > 0) {
                $this->redirectIncludedParamsExeptPaging();
            }
            $pagesCount = ceil($count / $limit);
            $this->addParam('pagesCount', $pagesCount);

            $currencyManager = CurrencyManager::getInstance();
            $this->addParam('currencies', $currencyManager->mapDtosById($currencyManager->selectAdvance('*', ['active', '=', 1])));
        }

        private function redirectIncludedParamsExeptPaging() {
            $url = "purchase.list?";
            if (isset(NGS()->args()->prt)) {
                $url .= "prd=" . NGS()->args()->prt . '&';
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
            //Recipient
            $selectedFilterRecipientId = 0;
            if (isset(NGS()->args()->prt)) {
                $selectedFilterRecipientId = intval(NGS()->args()->prt);
            }
            $this->addParam('selectedFilterRecipientId', $selectedFilterRecipientId);
            if ($selectedFilterRecipientId > 0) {
                $where[] = 'recipient_id';
                $where[] = '=';
                $where[] = $selectedFilterRecipientId;
            }

            $selectedFilterPaid = -1;
            if (isset(NGS()->args()->paid)) {
                $selectedFilterPaid = intval(NGS()->args()->paid);
            }
            $this->addParam('selectedFilterPaid', $selectedFilterPaid);
            if ($selectedFilterPaid === 0 || $selectedFilterPaid === 1) {
                if (!empty($where)) {
                    $where[] = 'AND';
                }
                $where[] = 'paid';
                $where[] = '=';
                $where[] = "'" . $selectedFilterPaid . "'";
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
            return NGS()->getTemplateDir() . "/main/rorder/list.tpl";
        }


        public function getSortByFields() {
            return ['order_date' => 'Date', 'payment_deadline' => 'Payment Deadline'];
        }

    }

}