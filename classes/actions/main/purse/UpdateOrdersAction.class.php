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

namespace crm\actions\main\purse {

    use crm\actions\BaseAction;
    use crm\managers\PurseOrderManager;
    use crm\managers\SettingManager;
    use NGS;

    class UpdateOrdersAction extends BaseAction {

        public function service() {
            $accountName = NGS()->args()->account_name;
            $token = SettingManager::getInstance()->getSetting($accountName);
            $res = PurseOrderManager::getInstance()->getActiveOrders($token);
            if (empty($res)) {
                $this->addParam('success', false);
                $this->addParam('message', 'can not fetch active orders');
                return;
            }
            //$result = \crm\managers\PurseOrderManager::getInstance()->emptyAccount($accountName);
            foreach ($res['results'] as $order) {
                $result = \crm\managers\PurseOrderManager::getInstance()->insertOrUpdateOrderFromPurseObject($accountName, $order);
            }
            
            $res = PurseOrderManager::getInstance()->getInactiveOrders($token);
            if (empty($res)) {
                $this->addParam('success', false);
                $this->addParam('message', 'can not fetch inactive orders');
                return;
            }
            foreach ($res['results'] as $order) {
                $result = \crm\managers\PurseOrderManager::getInstance()->insertOrUpdateOrderFromPurseObject($accountName, $order);
            }
        }

    }

}
