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
            ini_set('memory_limit','4G');
            set_time_limit(0);
            $accountName = NGS()->args()->account_name;
            $token = SettingManager::getInstance()->getSetting($accountName);
            $userInfo = PurseOrderManager::getInstance()->getUserInfo($token);
            if (empty($userInfo) || !isset($userInfo['email'])) {
                $this->addParam('success', false);
                $this->addParam('message', 'Update token');
                return;
            }
            $accountEmail = explode('@',trim($userInfo['email']))[0];
            if (strpos($accountEmail, 'pars')!== false)
            {
                $settingVarName = 'purse_pars_meta';
            }
            if (strpos($accountEmail, 'checkout')!== false)
            {
                $settingVarName = 'purse_checkout_meta';
            }
            if (strpos($accountEmail, 'info')!== false)
            {
                $settingVarName = 'purse_info_meta';
            }
            SettingManager::getInstance()->setSetting($settingVarName, json_encode($userInfo));
            
            
            
            $res = PurseOrderManager::getInstance()->getActiveOrders($token);
            if (empty($res) || !isset($res['results'])) {
                $this->addParam('success', false);
                $this->addParam('message', 'Try again: active orders not fetched');
                return;
            }
            foreach ($res['results'] as $order) {
                $result = \crm\managers\PurseOrderManager::getInstance()->insertOrUpdateOrderFromPurseObject($accountName, $order);
            }
            
            $res = PurseOrderManager::getInstance()->getInactiveOrders($token);
            if (empty($res) || !isset($res['results'])) {
                $this->addParam('success', false);
                $this->addParam('message', 'Try again: inactive orders not fetched');
                return;
            }
            foreach ($res['results'] as $order) {
                $result = \crm\managers\PurseOrderManager::getInstance()->insertOrUpdateOrderFromPurseObject($accountName, $order);
            }
        }

    }

}
