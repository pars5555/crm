<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\vanilla {

    use NGS;

    class GuestListLoad extends \crm\loads\GuestLoad {

        public function load() {
            if (!isset(NGS()->args()->token) || NGS()->args()->token!=='e6627c68907f351abc0a1f49f23cbaaa'){
                exit;
            }
            ListLoad::initLoad($this);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/vanilla/guest_list.tpl";
        }

    }

}
