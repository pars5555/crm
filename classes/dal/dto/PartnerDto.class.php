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

    class PartnerDto extends AbstractDto {

        // constructs class instance
        public function __construct() {
            
        }

        // Map of DB value to Field value
        private $mapArray = array("id" => "id", "name" =>
            "name", "email" => "email", "address" => "address","slug" => "slug",
            "phone" => "phone", "hidden" => "hidden", "included_in_capital" => "includedInCapital", "create_date" => "createDate");

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

    }

}
