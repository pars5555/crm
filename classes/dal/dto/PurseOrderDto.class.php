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
            "delivery_date" => "deliveryDate", "tracking_number" => "trackingNumber", "amazon_total" => "amazonTotal", "buyer_name" => "buyerName",
            "discount" => "discount", "serial_number" => "serial_number", "btc_rate" => "btcRate", "recipient_name" => "recipientName", "product_name" => "productName",
            "quantity" => "quantity", "image_url" => "imageUrl", "shipping_carrier" => "shippingCarrier", "status" => "status", "note" => 'note', "unread_messages" => 'unreadMessages',
            "account_name" => "accountName", "created_at" => "createdAt", "updated_at" => "updatedAt", 'meta' => 'meta');

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

        public function getDeliveryDateDiffToNow() {
            $delDate = new \DateTime($this->getDeliveryDate());
            if ($this->getDeliveryDate() > 0) {
                return 0;
            }
            $now = new \DateTime();
            return intval($now->diff($delDate)->format("%a"));
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
