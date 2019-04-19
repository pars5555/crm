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

    class PurchaseOrderDto extends AbstractDto {

        private $debt;
        // Map of DB value to Field value
        private $mapArray = array("id" => "id", "order_date" => "orderDate", "partner_id" => "partnerId", "note" => "note",
            "cancelled" => "cancelled","deleted" => "deleted", "cancel_note" => "cancelNote", "payment_deadline" => "paymentDeadline", "checked" => "checked",
            "paid" => "paid", "paid_at" => "paidAt", "purse_order_id" => "purseOrderId");
        private $productPrices;

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
        
        function setProductPrice($productId, $price) {
            $this->productPrices[$productId] = $price;
            
        }
        
        function getProductPrice($productId) {
            return $this->productPrices[$productId];
        }

    }

}
