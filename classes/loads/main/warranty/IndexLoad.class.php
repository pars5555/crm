<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\warranty {

    use crm\loads\AdminLoad;
    use crm\security\RequestGroups;
    use NGS;

    class IndexLoad  extends AdminLoad {

        public function load() {
            
        }

        public function getDefaultLoads() {
            $loads = array();
            $loads["serialNumbers"]["action"] = "crm.loads.main.warranty.content";
            $loads["serialNumbers"]["args"] = array();
            return $loads;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/warranty/index.tpl";
        }

    }

}
