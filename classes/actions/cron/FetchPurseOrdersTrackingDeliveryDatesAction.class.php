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
    use DOMDocument;
    use DOMXPath;

    class FetchPurseOrdersTrackingDeliveryDatesAction extends BaseAction {

        public function service() {
            set_time_limit(0);
            $rows = PurseOrderManager::getInstance()->getNotDeliveredToWarehouseOrdersThatHasNotTrackingNumber();
            $updated = 0;
            foreach ($rows as $row) {
                $aon = $row->getAmazonOrderNumber();
                $url = "https://www.amazon.com/progress-tracker/package/ref=oh_aui_hz_st_btn?_encoding=UTF8&itemId=jpmklqnukqppon&orderId=$aon";
                $content = file_get_contents($url);

                libxml_use_internal_errors(true);
                $xmlDoc = new DOMDocument();
                $xmlDoc->loadHTML($content);
                $finder = new DOMXPath($xmlDoc);
                $ordersRows = $finder->query("//*[contains(@class, 'cardContainer')]");

                libxml_clear_errors();

                $trackingNumber = false;
                for ($i = 0; $i < $ordersRows->length; $i++) {
                    $el = $ordersRows->item($i);
                    $trackingLinks = $el->getElementsByTagName('a');
                    if ($trackingLinks->length > 0) {
                        $trackingNumber = $trackingLinks->item(0)->nodeValue;
                        $trackingNumber = trim(str_replace('Tracking ID', '', $trackingNumber));
                        $shippingCarrierName = $this->getShippingCarrierName($el);
                        break;
                    }
                }
                if (!empty($trackingNumber)) {
                    $updated += 1;
                    PurseOrderManager::getInstance()->updateField($row->getId(), 'tracking_number', $trackingNumber);
                    PurseOrderManager::getInstance()->updateField($row->getId(), 'shipping_carrier', $shippingCarrierName);
                    PurseOrderManager::getInstance()->updateField($row->getId(), 'updated_at', date('Y-m-d H:i:s'));
                }
                sleep(1);
            }
            $this->addParam('updated', $updated);
        }

        private function getShippingCarrierName($el) {
            $trackingHeadlineEl = $el->getElementsByTagName('h1');
            if ($trackingHeadlineEl->length > 0) {
                for ($index = 0; $index < $trackingHeadlineEl->length; $index++) {
                    $trackingHeadline = $trackingHeadlineEl->item($index)->nodeValue;
                    if (strpos($trackingHeadline, 'Shipped with') !== false) {
                        $trackingHeadline = trim(str_replace('Shipped with', '', $trackingHeadline));
                        return trim($trackingHeadline);
                    }
                }
            }
            return 'N/A';
        }

    }

}
    