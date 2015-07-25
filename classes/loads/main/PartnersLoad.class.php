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

    use crm\loads\NgsLoad;
    use crm\security\RequestGroups;
    use NGS;

    class PartnersLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $this->addParam('req', isset($_SESSION['action_request']) ? $_SESSION['action_request'] : []);
            $this->addParam('show_create_form', isset($_SESSION['action_request']));
            unset($_SESSION['action_request']);
        }

        public function getDefaultLoads() {
            $loads = array();
            $loads["partner_list"]["action"] = "crm.loads.main.partners_list";
            $loads["partner_list"]["args"] = array();
            return $loads;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/partner/partners.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
