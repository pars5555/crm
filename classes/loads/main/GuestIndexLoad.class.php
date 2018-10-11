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

    use crm\loads\GuestLoad;
    use NGS;

    class GuestIndexLoad extends GuestLoad {

        public function load() {
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/index.tpl";
        }

    }

}
