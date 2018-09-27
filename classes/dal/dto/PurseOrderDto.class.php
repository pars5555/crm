<?php

/**
 * TracksDto mapper class
 * setter and getter generator
 * for ilyov_tracks table
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2015
 * @package dal.dto.tracks
 * @version 6.0
 *
 */

namespace crm\dal\dto {

    use \ngs\framework\dal\dto\AbstractDto;

    class PurseOrderDto extends AbstractDto {

        private $histores = [];
        // Map of DB value to Field value
        private $mapArray = array("id" => "id", "order_number" => "orderNumber", "amazon_order_number" => "amazonOrderNumber",
            "delivery_date" => "deliveryDate", "carrier_delivery_date" => "carrierDeliveryDate",
            "carrier_tracking_status" => "carrierTrackingStatus", "tracking_number" => "trackingNumber",
            "amazon_total" => "amazonTotal", "buyer_name" => "buyerName", "problematic" => "problematic",
            "discount" => "discount", "serial_number" => "serial_number", "btc_rate" => "btcRate", "recipient_name" => "recipientName", "product_name" => "productName",
            "quantity" => "quantity", "image_url" => "imageUrl", "shipping_carrier" => "shippingCarrier", "status" => "status", "note" => 'note', "unread_messages" => 'unreadMessages',
            "account_name" => "accountName", "created_at" => "createdAt", "updated_at" => "updatedAt", 'meta' => 'meta');

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

        public function getDeliveryDateDiffToNow() {
            if ($this->getDeliveryDate() <= 0) {
                return 0;
            }

            $ddate = $this->getDeliveryDate();
            $now = date('Y-m-d');
            if ($this->getDeliveryDate() >= $now) {
                return 0;
            }
            $date1 = date_create($ddate);
            $date2 = date_create($now);
            $diff = date_diff($date1, $date2);
            return $diff->days;
        }

        public function getCarrierTrackingUrl() {
            $trackingNumber = $this->getTrackingNumber();
            if (strpos(strtolower($this->getShippingCarrier()), 'usps') !== false) {
                return 'https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=' . $trackingNumber;
            }
            if (strpos(strtolower($this->getShippingCarrier()), 'fedex') !== false) {
                return "https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=$trackingNumber&cntry_code=us&locale=en_US";
            }
            if (strpos(strtolower($this->getShippingCarrier()), 'ups') !== false) {
                return "https://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=$trackingNumber&loc=en_am";
            }
            return false;
        }

        function getStatusHistoryText() {
            $ret = [];
            foreach ($this->histores as $history) {
                $ret[] = $history->getStatus() . ' (' . $history->getCreatedAt() . ')';
            }
            return implode('&#013;', $ret);
        }

        function getAmazonOrderNumberText() {
            $ret = [];
            foreach ($this->histores as $history) {
                $ret[] = $history->getAmazonOrderNumber() . ' (' . $history->getCreatedAt() . ')';
            }
            return implode('&#013;', $ret);
        }

        function hasMoreThanOneAmazonOrder() {
            if (empty($this->histores)) {
                return false;
            }
            $amazonOrderNumber = $this->getAmazonOrderNumber();
            foreach ($this->histores as $history) {
                $aon = trim($history->getAmazonOrderNumber());
                if (!empty($amazonOrderNumber) && $amazonOrderNumber !== $aon) {
                    return true;
                }
                if (!empty($aon)) {
                    $amazonOrderNumber = $history->getAmazonOrderNumber();
                }
            }
            return false;
        }

    }

}
