<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\rwarehouse {

    use crm\loads\AdminLoad;
    use crm\managers\WarehouseMovesManager;
    use crm\managers\WarehousesManager;
    use NGS;

    class ListLoad extends AdminLoad {

        public function load() {
            list($selectedWhId) = $this->initFilters();
            $products = WarehouseMovesManager::getInstance()->getWarehouseProducts($selectedWhId);

            $whs = WarehousesManager::getInstance()->selectAll();
            $this->addParam('whs', $whs);
            $this->addParam('products', $products);
        }

        private function initFilters() {
            $selectedWhId = 0;
            if (isset(NGS()->args()->wh)) {
                $selectedWhId = intval(NGS()->args()->wh);
            }
            $this->addParam('selectedWhId', $selectedWhId);
            return [$selectedWhId];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/rwarehouse/list.tpl";
        }

        public function getSortByFields() {
            return [];
        }

    }

}
