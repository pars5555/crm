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

    class CreditCardsDto extends AbstractDto {

        // constructs class instance
        public function __construct() {
            
        }

        // Map of DB value to Field value
        private $mapArray = array("id" => "id",
            "description" => "description",
            "number" => "number",
            "month" => "month",
            "year" => "year",
            "cvv" => "cvv",
            "currency" => "currency",
            "cardholder_name" => "cardholderName",
            "phone" => "phone",
            "ssid" => "ssid",
            "arca" => "arca",
            "pin" => "pin",
            "password" => "password",
            "billing_address" => "billing_address",
            "note" => "note"
        );

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

    }

}
