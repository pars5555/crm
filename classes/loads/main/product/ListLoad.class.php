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
use crm\managers\ProductManager;
use crm\managers\PurchaseOrderLineManager;
use crm\managers\SaleOrderLineManager;
use crm\security\RequestGroups;
use NGS;

    class ListLoad  extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $limit = 100;
            list($offset, $sortByFieldName, $selectedFilterSortByAscDesc) = $this->initFilters($limit);
            $products = ProductManager::getInstance()->getProductListFull([], $sortByFieldName, $selectedFilterSortByAscDesc, $offset, $limit);
            $productIds = ProductManager::getDtosIdsArray($products);
            $productsPurchaseOrder = PurchaseOrderLineManager::getInstance()->getProductsPurchaseOrders($productIds);
            $productsSaleOrder = SaleOrderLineManager::getInstance()->getProductsSaleOrders($productIds);
            
            $this->addParam('productsPurchaseOrder', $productsPurchaseOrder);
            $this->addParam('productsSaleOrder', $productsSaleOrder);
            $this->addParam('products', $products);
            $count = ProductManager::getInstance()->getLastSelectAdvanceRowsCount();
            if (count($products) == 0 && $count > 0) {
                $this->redirectIncludedParamsExeptPaging();
            }
            $pagesCount = ceil($count / $limit);
            $this->addParam('pagesCount', $pagesCount);
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
            $this->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
            $this->addParam('selectedFilterSortBy', $selectedFilterSortBy);

            return [$offset, $selectedFilterSortBy, $selectedFilterSortByAscDesc];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/product/list.tpl";
        }


        public function getSortByFields() {
            return ['name' => 'Name', 'model' => 'Model'];
        }

    }

}
