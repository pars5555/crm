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
            "email" => "email", "document_number" => "documentNumber",  "document_type" => "documentType", "express_unit_address" => "expressUnitAddress",
            "standard_unit_address" => "standardUnitAddress", "meta" => "meta", "address" => "address", "district" => "district", 
            "phone_number" => "phoneNumber", "favorite" => "favorite", "deleted" => "deleted");

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

    }

}
