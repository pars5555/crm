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
    use NGS;

    class RefreshTrackingAction extends BaseAction {

        public function service() {
            $id = intval(NGS()->args()->id);
            $order = PurseOrderManager::getInstance()->selectByPK($id);
            PurseOrderManager::getInstance()->fetchAndUpdateTrackingDetails($order);
            $order = PurseOrderManager::getInstance()->selectByPK($id);
            
            $this->addParam('id', $id);
            $this->addParam('tracking_number', trim($order->getTrackingNumber()));
            $this->addParam('tracking_url', trim($order->getCarrierTrackingUrl()));
        }

    }

}
