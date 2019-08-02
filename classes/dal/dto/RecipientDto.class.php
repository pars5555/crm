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

    class RecipientDto extends AbstractDto {

        // constructs class instance
        public function __construct() {
            
        }

        // Map of DB value to Field value
        private $mapArray = array("id" => "id", 
            "first_name" => "firstName", "last_name"=>"lastName", 
            "email" => "email", "document_number" => "documentNumber",  "document_type" => "documentType", 
            "express_unit_address" => "expressUnitAddress",
            "express_unit_address_1" => "expressUnitAddress_1",
            
            "standard_unit_address" => "standardUnitAddress", 
            "onex_express_unit" => "onexExpressUnit", 
            "onex_express_unit_1" => "onexExpressUnit_1", 
            "onex_standard_unit" => "onexStandardUnit", 
            "nova_express_unit" => "novaExpressUnit",
            "nova_express_unit_1" => "novaExpressUnit_1",
            "nova_standard_unit" => "novaStandardUnit", 
            "shipex_express_unit" => "shipexExpressUnit", 
            "shipex_express_unit_1" => "shipexExpressUnit_1", 
            "shipex_standard_unit" => "shipexStandardUnit", 
            "cheapex_express_unit" => "cheapexExpressUnit",
            "cheapex_express_unit_1" => "cheapexExpressUnit_1",
            "cheapex_standard_unit" => "cheapexStandardUnit", 
            "ssid" => "ssid", "meta" => "meta", "address" => "address", 
            "district" => "district", "note" => "note",  "checked" => "checked",
            "phone_number" => "phoneNumber", "favorite" => "favorite", "deleted" => "deleted");

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

    }

}
