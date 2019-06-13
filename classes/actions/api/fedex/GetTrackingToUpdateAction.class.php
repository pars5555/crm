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

namespace crm\actions\api\fedex {

    use crm\actions\BaseAction;
    use crm\security\RequestGroups;

    class GetTrackingToUpdateAction extends BaseAction {

        public function service() {
            $trackingNumber = \crm\managers\PurseOrderManager::getInstance()->getFedexTrackingsToCheck();
            if (!empty($trackingNumber)) {
                $this->addParam('tracking_number', $trackingNumber);
            }
            $this->addParam('success', true);
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
    