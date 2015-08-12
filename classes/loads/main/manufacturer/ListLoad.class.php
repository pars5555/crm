<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\manufacturer {

    use crm\loads\NgsLoad;
    use crm\managers\ManufacturerManager;
    use crm\security\RequestGroups;
    use NGS;

    class ListLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $manufacturerManager = ManufacturerManager::getInstance();
            $manufacturers = $manufacturerManager->selectAll();
            $this->addParam('manufacturers', $manufacturers);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/manufacturer/list.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
