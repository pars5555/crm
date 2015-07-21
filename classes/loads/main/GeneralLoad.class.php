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

    use \crm\security\RequestGroups;

    class GeneralLoad extends \crm\loads\NgsLoad {

        public function load() {
            $uomManager = \crm\managers\UomManager::getInstance();
            $all = $uomManager->selectAll();
            $this->addParam("all", $all);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir(). "/main/general.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
