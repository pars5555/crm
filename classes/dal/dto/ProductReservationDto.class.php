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

    class ProductReservationDto extends AbstractDto {

        // Map of DB value to Field value
        protected $mapArray = array("id" => "id",
            "product_id" => "productId", "quantity" => "quantity",
            "phone_number" => "phoneNumber", "start_at" => "startAt",
            "hours" => "hours", "note" => "note", "created_at" => "created_at"
        );

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

    }

}
