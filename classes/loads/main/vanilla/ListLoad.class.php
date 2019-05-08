<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\vanilla {

    use crm\loads\AdminLoad;
    use crm\managers\VanillaCardsManager;
    use NGS;

    class ListLoad extends AdminLoad {

        public function load() {
            $limit = 100;
            list($offset, $balance) = $this->initFilters($limit);
            $where = ['1', '=', '1'];
            if ($balance>0) {
                $where = array_merge($where, ['AND', '(', 'balance', '>=', $balance]);
            }
            $rows = VanillaCardsManager::getInstance()->selectAdvance('*', [], 'id', 'desc', $offset, $limit);
            $count = VanillaCardsManager::getInstance()->getLastSelectAdvanceRowsCount();
            $pagesCount = ceil($count / $limit);
            $this->addParam('pagesCount', $pagesCount);
            $this->addParam('rows', $rows);
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

            $balance = 0;
            if (isset(NGS()->args()->bal)) {
                $balance = floatval(NGS()->args()->bal);
            }

            $this->addParam('balance', $balance);
            return [$offset, $balance];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/vanilla/list.tpl";
        }

    }

}
