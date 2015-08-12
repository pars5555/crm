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
    use crm\security\RequestGroups;
    use NGS;

    class CreateLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $this->addParam('req', isset($_SESSION['action_request']) ? $_SESSION['action_request'] : []);
            unset($_SESSION['action_request']);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/manufacturer/create.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}