<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\settings {

    use crm\loads\AdminLoad;
    use NGS;

    class IndexLoad  extends AdminLoad {

        public function load() {
            $rows = \crm\managers\SettingManager::getInstance()->selectAll();
            $this->addParam('rows', $rows);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/settings/index.tpl";
        }


    }

}
