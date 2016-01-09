<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\partner {

    use crm\loads\AdminLoad;
    use crm\security\RequestGroups;
    use NGS;

    class CreateLoad  extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $this->addParam('req', isset($_SESSION['action_request']) ? $_SESSION['action_request'] : []);
            unset($_SESSION['action_request']);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/partner/create.tpl";
        }


    }

}
