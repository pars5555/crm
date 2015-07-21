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

    class PurchasesLoad extends \crm\loads\NgsLoad {

        public function load() {
        }

        public function getTemplate() {
            return NGS()->getTemplateDir(). "/main/purchases.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
