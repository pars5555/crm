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

    class PaymentTransactionDto extends AbstractDto {

        private $partnerDto;
        private $paymentMethodDto;
        private $currencyDto;
        private $debt;

        // constructs class instance
        public function __construct() {
            
        }

        // Map of DB value to Field value
        private $mapArray = array("id" => "id", "date" => "date", "payment_method_id" => "paymentMethodId",
            "partner_id" => "partnerId", "currency_id" => "currencyId", "amount" => "amount", "checked" => "checked"
            , "cancelled" => "cancelled", "cancel_note" => "cancelNote", "note" => "note", "signature" => "signature",
            "is_expense" => "isExpense", "paid" => "paid", "currency_rate" => "currencyRate", "created_at" => "createdAt"
        );

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

        function getPartnerDto() {
            return $this->partnerDto;
        }

        function getPaymentMethodDto() {
            return $this->paymentMethodDto;
        }

        function getCurrencyDto() {
            return $this->currencyDto;
        }

        function setPartnerDto($partnerDto) {
            $this->partnerDto = $partnerDto;
        }

        function setPaymentMethodDto($paymentMethodDto) {
            $this->paymentMethodDto = $paymentMethodDto;
        }

        function setCurrencyDto($currencyDto) {
            $this->currencyDto = $currencyDto;
        }
        
        function getDebt() {
            return $this->debt;
        }

        function setDebt($debt) {
            $this->debt = $debt;
        }

    
    }

}
