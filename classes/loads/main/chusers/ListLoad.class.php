<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\chusers {

    use crm\loads\AdminLoad;

    class ListLoad extends AdminLoad {

        public function load() {
            $limit = 100;
            list($offset, $sortByFieldName, $selectedFilterSortByAscDesc, $searchText) = $this->initFilters($limit);
            $where = ['1', '=', '1'];
            if (!empty($searchText)) {
                $where = array_merge($where, ['AND', '(', 'first_name', 'like', "'%$searchText%'"]);
                $where = array_merge($where, ['OR', 'last_name', 'like', "'%$searchText%'"]);
                $where = array_merge($where, ['OR', 'phone', 'like', "'%$searchText%'"]);
                $where = array_merge($where, ['OR', 'email', 'like', "'%$searchText%'", ')']);
            }
            $res = \crm\managers\CheckoutManager::getInstance()->selectAdvance('User', [], $where, [], [$sortByFieldName => $selectedFilterSortByAscDesc], $offset, $limit);
            $count = $res->count;
            $pagesCount = ceil($count / $limit);
            $this->addParam('rows', $res->entities);
            $this->addParam('pagesCount', $pagesCount);
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
            $selectedFilterSortBy = '';
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
            $searchText = '';
            if (isset(NGS()->args()->st)) {
                $searchText = trim(NGS()->args()->st);
            }
            $this->addParam('searchText', $searchText);
            $this->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
            $this->addParam('selectedFilterSortBy', $selectedFilterSortBy);

            return [$offset, $selectedFilterSortBy, $selectedFilterSortByAscDesc, $searchText];
        }

        public function getSortByFields() {
            return ['name' => 'Name', 'email' => 'Email'];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/chusers/list.tpl";
        }

    }

}
