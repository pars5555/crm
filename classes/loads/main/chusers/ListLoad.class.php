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
            $this->addParam('rows', []);
        }

        
        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/chusers/list.tpl";
        }

    }

}
