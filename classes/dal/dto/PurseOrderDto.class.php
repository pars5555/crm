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
            "tracking_number" => "trackingNumber", "amazon_total" => "amazonTotal", "buyer_name" => "buyerName",
            "purse_total" => "purseTotal", "btc_rate" => "btcRate", "product_name" => "productName",
            "quantity" => "quantity", "image_url" => "imageUrl", "status" => "status", "hidden" => 'hidden',
            "updated_at" => "updated_at", "created_at" => "created_at");

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

        function getHistores() {
            return $this->histores;
        }

        function setHistores($histores) {
            $this->histores = $histores;
        }

        function addHistory($history) {
            $this->histores[] = $history;
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

    }

}