<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\general {

use crm\loads\AdminLoad;
use crm\security\RequestGroups;
use NGS;

    class GeneralLoad  extends AdminLoad {

        public function load() {
        }

        public function getDefaultLoads() {
            $loads = array();
            $loads["profitCalculation"]["action"] = "crm.loads.main.general.profit";
            $loads["profitCalculation"]["args"] = array();
            $loads["cashboxCalculation"]["action"] = "crm.loads.main.general.cashbox";
            $loads["cashboxCalculation"]["args"] = array();
            return $loads;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/general/general.tpl";
        }


    }

}
