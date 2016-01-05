<?php

/**
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2015
 * @package admin.dal.dto
 * @version 1.0.0
 *
 */

namespace crm\dal\dto {

    use ngs\framework\dal\dto\AbstractDto;

    class AdminDto extends AbstractDto {

        // Map of DB value to Field value
        protected $mapArray = array("id" => "id", 
            "username" => "username", "password" => "password", 
            "hash" => "hash", "last_login" => "lastLogin",
            "type" => "type"
            );

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }


    }

}
