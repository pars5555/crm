<?php

/**
 *
 * NGS admin login
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2015
 * @package admin.loads
 * @version 1.0.0
 *
 */

namespace crm\loads {

    use crm\security\UserGroups;
    use NGS;

    class LoginLoad extends NgsLoad {

        public function load() {
            $userType = NGS()->getSessionManager()->getUserType();
            if (isset($userType) && $userType != UserGroups::$GUEST) {
                $this->redirect('');
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/login.tpl";
        }

    }

}
