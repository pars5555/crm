<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\preorder {

    use crm\loads\AdminLoad;
    use crm\managers\AttachmentManager;
    use crm\managers\CurrencyManager;
    use crm\managers\PartnerManager;
    use crm\managers\PaymentMethodManager;
    use crm\managers\ProductManager;
    use crm\managers\PreorderManager;
    use NGS;

    class ListLoad extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $this->addParam('payment_methods', PaymentMethodManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
            $this->addParam('partners', PartnerManager::getInstance()->selectAdvance('*', [], ['name']));
            $this->addParam('products', ProductManager::getInstance()->selectAdvance('*', [], ['name']));

            $limit = 100;
            list($where, $offset, $sortByFieldName, $selectedFilterSortByAscDesc) = $this->initFilters($limit);
            $preorders = PreorderManager::getInstance()->getPreordersFull($where, $sortByFieldName, $selectedFilterSortByAscDesc, $offset, $limit);
            $count = PreorderManager::getInstance()->getLastSelectAdvanceRowsCount();
            $this->addParam('preorders', $preorders);
            $attachments = AttachmentManager::getInstance()->getEntitiesAttachments($preorders, 'preorder');
            $this->addParam('attachments', $attachments);
            if (count($preorders) == 0 && $count > 0) {
                $this->redirectIncludedParamsExeptPaging();
            }
            $pagesCount = ceil($count / $limit);
            $this->addParam('pagesCount', $pagesCount);

            $currencyManager = CurrencyManager::getInstance();
            $this->addParam('currencies', $currencyManager->mapDtosById($currencyManager->selectAdvance('*', ['active', '=', 1])));
        }

        private function redirectIncludedParamsExeptPaging() {
            $url = "preorder.list?";
            if (isset(NGS()->args()->prt)) {
                $url .= "prt=" . NGS()->args()->prt . '&';
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
            $where = ['deleted', '=', 0];
            //partner
            $selectedFilterPartnerId = 0;
            if (isset(NGS()->args()->prt)) {
                $selectedFilterPartnerId = intval(NGS()->args()->prt);
            }
            $this->addParam('selectedFilterPartnerId', $selectedFilterPartnerId);
            if ($selectedFilterPartnerId > 0) {
                if (!empty($where)) {
                    $where[] = 'AND';
                }
                $where[] = 'partner_id';
                $where[] = '=';
                $where[] = $selectedFilterPartnerId;
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
            return NGS()->getTemplateDir() . "/main/preorder/list.tpl";
        }

        public function getSortByFields() {
            return ['order_date' => 'Date', 'payment_deadline' => 'Payment Deadline'];
        }

    }

}
