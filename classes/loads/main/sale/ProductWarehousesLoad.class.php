<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\sale {

    use crm\loads\AdminLoad;
    use NGS;

    class ProductWarehousesLoad extends AdminLoad {

        public function load() {
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/sale/product_warehouses.tpl";
        }

    }

}
