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

namespace crm\actions\api\checkout {

    use crm\actions\BaseAction;
    use crm\managers\PurseOrderManager;
    use NGS;

    class ChangeOrderStatusAction extends BaseAction {

        public function service() {
            $orderId = intval(NGS()->args()->order_id);
            $status = trim(NGS()->args()->status);
            $ret = PurseOrderManager::getInstance()->changeCheckoutOrderStatus($orderId, $status);
            $this->addParam('success', $ret);
        }

        public function getRequestGroup() {
            return \crm\security\RequestGroups::$guestRequest;
        }

    }

}
    