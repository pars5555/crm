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

    class RecipientOrderDto extends AbstractDto {

        private $debt;
        // Map of DB value to Field value
        private $mapArray = array("id" => "id", "order_date" => "orderDate", "recipient_id" => "recipientId", "note" => "note",
            "cancelled" => "cancelled", "cancel_note" => "cancelNote", "payment_deadline" => "paymentDeadline",
            "paid" => "paid", "paid_at" => "paidAt");

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

        function getDebt() {
            return $this->debt;
        }

        function setDebt($debt) {
            $this->debt = $debt;
        }

    }

}
