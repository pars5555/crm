<?php

/**
 * main site action for all ngs site's actions
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2014
 * @package actions.site
 * @version 6.0
 *
 */

namespace crm\actions\cron {

    use crm\actions\BaseAction;
    use crm\security\RequestGroups;

    class SyncCheckoutUsersAction extends BaseAction {

        public function service() {
            $users = crm\managers\CheckoutManager::getInstance()->getCheckoutUsers();
            if (!empty($users)){
                return false;
            }
            foreach ($users as $user) {
                \crm\managers\CheckoutUsersManager::getInstance()->insertOrUpdateUser($user);
            }
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
