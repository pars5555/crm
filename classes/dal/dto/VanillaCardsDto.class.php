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

    class VanillaCardsDto extends AbstractDto {

        // constructs class instance
        public function __construct() {
            
        }

        // Map of DB value to Field value
        private $mapArray = array("id" => "id", 
            "number" => "number",
            "month" => "month",
            "year" => "year",
            "cvv" => "cvv",            
            "initial_balance" => "initialBalance",
            "balance" => "balance",
            "external_orders_ids" => "external_orders_ids",
            "note" => "note",
            "closed" => "closed",
            "updated_at" => "updatedAt",
            "created_at" => "createdAt"
            );

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

    }

}
