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

    use crm\loads\NgsLoad;
    use crm\security\RequestGroups;
    use NGS;

    class ContentLoad extends NgsLoad {

        public function load() {
            
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/warranty/content.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
