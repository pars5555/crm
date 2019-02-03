<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main {

    use crm\loads\AdminLoad;
    use crm\security\RequestGroups;
    use NGS;

    class IndexLoad extends AdminLoad {

        public function load() {
            
            $this->addParam('sticky_note', \crm\managers\SettingManager::getInstance()->getSetting('sticky_note'));
        }
        
        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/index.tpl";
        }

    }

}
