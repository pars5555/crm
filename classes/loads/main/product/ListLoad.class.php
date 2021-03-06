<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\product {

    use crm\loads\AdminLoad;
    use crm\managers\PartnerManager;
    use crm\managers\ProductManager;
    use crm\managers\PurchaseOrderLineManager;
    use crm\managers\SaleOrderLineManager;
    use NGS;

    class ListLoad extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $limit = 100;
            list($offset, $sortByFieldName, $selectedFilterSortByAscDesc, $selectedFilterHidden, $searchText) = $this->initFilters($limit);
            $where = ['1', '=', '1'];
            if ($selectedFilterHidden !== 'all') {
                $where = array_merge($where, ['AND ', 'hidden', '=', 0]);
            }
            if (!empty($searchText)) {
                $words = $parts = preg_split('/\s+/', $searchText);
                foreach ($words as $word) {
                    $where = array_merge($where, ['AND', '(', 'name', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'model', 'like', "'%$word%'", ')']);
                }
            }

            $products = ProductManager::getInstance()->selectAdvance('*', $where, $sortByFieldName, $selectedFilterSortByAscDesc, $offset, $limit);
            $productIds = ProductManager::getDtosIdsArray($products);
            $productsPurchaseOrders = PurchaseOrderLineManager::getInstance()->getProductsPurchaseOrders($productIds);
            $productsSaleOrders = SaleOrderLineManager::getInstance()->getProductsSaleOrders($productIds);

            $this->addParam('productsPurchaseOrder', $productsPurchaseOrders);
            $this->addParam('productsSaleOrder', $productsSaleOrders);
            $this->addParam('products', $products);
            $count = ProductManager::getInstance()->getLastSelectAdvanceRowsCount();
            if (count($products) == 0 && $count > 0) {
                $this->redirectIncludedParamsExeptPaging();
            }
            $pagesCount = ceil($count / $limit);
            $this->addParam('pagesCount', $pagesCount);

            $partnerIds = [];
            foreach ($productsSaleOrders as $sos) {
                foreach ($sos as $so) {
                    $partnerIds[] = intval($so->getPartnerId());
                }
            }
            foreach ($productsPurchaseOrders as $pos) {
                foreach ($pos as $po) {
                    $partnerIds[] = intval($po->getPartnerId());
                }
            }
            $partnerIdsSql = implode(',', array_unique($partnerIds));
            $partnersMappedByIds = [];
            if (!empty($partnerIdsSql)) {
                $partnersMappedByIds = PartnerManager::getInstance()->selectAdvance(['name', 'id'], ['id', 'in', "($partnerIdsSql)"], null, null, null, null, true);
            }
            $this->addParam('partnersMappedByIds', $partnersMappedByIds);
            $this->loadProductModelsAndBrands();
        }
        
        private function loadProductModelsAndBrands() {
            list($models, $brands) = ProductManager::getInstance()->getBrandsAndModels();
            $this->addParam('models', $models);
            $this->addParam('brands', $brands);
        }

        private function redirectIncludedParamsExeptPaging() {
            $url = "product/list?";
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
            $selectedFilterHidden = 'all';
            if (isset(NGS()->args()->hddn)) {
                if (in_array(strtolower(NGS()->args()->hddn), ['all', 'no'])) {
                    $selectedFilterHidden = strtolower(NGS()->args()->hddn);
                }
            }
            $searchText = '';
            if (isset(NGS()->args()->st)) {
                $searchText = trim(NGS()->args()->st);
            }

            $this->addParam('searchText', $searchText);
            $this->addParam('selectedFilterHidden', $selectedFilterHidden);
            $this->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
            $this->addParam('selectedFilterSortBy', $selectedFilterSortBy);

            return [$offset, $selectedFilterSortBy, $selectedFilterSortByAscDesc, $selectedFilterHidden, $searchText];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/product/list.tpl";
        }

        public function getSortByFields() {
            return ['name' => 'Name', 'model' => 'Model'];
        }

    }

}
