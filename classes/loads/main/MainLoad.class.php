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

    class MainLoad extends \crm\loads\NgsLoad {

        public function load() {
            
        }

        public function getDefaultLoads() {
            $package = NGS()->getRoutesEngine()->getPackage();
            if (empty($package) || $package == 'default')
            {
                $package = 'general';
            }
            $loads = array();
            $loads["nested_load"]["action"] = "crm.loads.main.".$package;
            $loads["nested_load"]["args"] = array();
            return $loads;
        }

        public function getTemplate() {
           return NGS()->getTemplateDir()."/main/index.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
