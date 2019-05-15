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

    class CheckoutUsersDto extends AbstractDto {

        // Map of DB value to Field value
        protected $mapArray = array("id" => "id",
            "user_id" => "userId", "full_name" => "fullName",
            "phone_number" => "phoneNumber", "referrer_id" => "referrerId",
            "referrer_email" => "referrerEmail", "discount" => "discount",
            "disabled" => "disabled"
        );

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

    }

}
