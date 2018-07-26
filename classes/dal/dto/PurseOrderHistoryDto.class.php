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

    class PurseOrderHistoryDto extends AbstractDto {

        // Map of DB value to Field value
        private $mapArray = array("id" => "id", "order_id" => "order_id", "amazon_order_number" => "amazonOrderNumber",
            "tracking_number" => "trackingNumber",
            "purse_total" => "purseTotal", "btc_rate" => "btcRate", "status" => "status",
            "created_at" => "created_at");

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

    }

}
