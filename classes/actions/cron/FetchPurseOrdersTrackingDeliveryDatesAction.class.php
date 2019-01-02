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
    use crm\managers\PurseOrderManager;
    use crm\security\RequestGroups;

    class FetchPurseOrdersTrackingDeliveryDatesAction extends BaseAction {

        public function service() {
            set_time_limit(0);
            $rows = PurseOrderManager::getInstance()->getNotDeliveredToWarehouseOrdersThatHasNotTrackingNumber();
            $updated = 0;
            foreach ($rows as $row) {
                PurseOrderManager::getInstance()->fetchAndUpdateTrackingPageDetails($row);
                $updated += 1;
                sleep(1);
            }
            $this->addParam('updated', $updated);
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
    