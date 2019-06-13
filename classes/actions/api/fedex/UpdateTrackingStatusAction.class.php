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

    class UpdateTrackingStatusAction extends BaseAction {

        public function service() {
            $tracking_number = trim(NGS()->args()->tracking_number);
            $primary_status = trim(NGS()->args()->primary_status);
            $error = trim(NGS()->args()->error);
            $primary_status_date = trim(NGS()->args()->primary_status_date);
            $travel_history = trim(NGS()->args()->travel_history);
            $shipment_facts = trim(NGS()->args()->shipment_facts);
            var_dump($travel_history);
            exit;
            // $trackingNumber = \crm\managers\PurseOrderManager::getInstance()->setFedexTrackingStatus($tracking_number, );
//            if (!empty($trackingNumber)) {
//                $this->addParam('tracking_number', $trackingNumber);
//            }
//            $this->addParam('success', true);
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
    