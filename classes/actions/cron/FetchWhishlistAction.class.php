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
    use crm\managers\WhishlistManager;
    use crm\security\RequestGroups;

    class FetchWhishlistAction extends BaseAction {

        public function service() {
            set_time_limit(0);
            $rows = WhishlistManager::getInstance()->selectAdvance('*', [], 'current_min_price');
            foreach ($rows as $row) {
                $asinList = $row->getAsinList();
                if (!empty($asinList)) {
                    $asinListArray = explode(',', $asinList);
                }
                echo 'id: ' . $row->getId() . ' updateing...' . "\r\n";
                $minPrice = PHP_INT_MAX;
                $minMinAsin = "";
                foreach ($asinListArray as $asin) {
                    $price = PurseOrderManager::getInstance()->getItemPriceByAsin($asin);
                    $currentMinPrice = floatval($row->getCurrentMinPrice());
                    echo 'asin: ' . $asin . ', old price: ' . $currentMinPrice . ' , new price: ' . $price . "\r\n";
                    if ($price < $minPrice) {
                        $minPrice = $price;
                        $minMinAsin = $asin;
                    }
                    sleep(4);
                }
                WhishlistManager::getInstance()->updateField($row->getId(), 'current_min_price', $minPrice);
                WhishlistManager::getInstance()->updateField($row->getId(), 'current_min_price_asin', $minMinAsin);
                echo 'id: ' . $row->getId() . ' finished' . "\r\n";
            }
            echo 'Finished' . "\r\n" . "\r\n" . "\r\n" . "\r\n" . "\r\n";

            exit;
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
    