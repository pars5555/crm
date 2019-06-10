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

        private $order_amounts = [];
        private $succeed_order_amounts = [];
        // Map of DB value to Field value
        private $mapArray = array("id" => "id",
            "number" => "number",
            "month" => "month",
            "year" => "year",
            "cvv" => "cvv",
            "initial_balance" => "initialBalance",
            "balance" => "balance",
            "external_orders_ids" => "external_orders_ids",
            "attention" => "attention",
            "sold_amount" => "soldAmount",
            "note" => "note",
            "transaction_history" => "transactionHistory",
            "closed" => "closed",
            "invalid" => "invalid",
            "deleted" => "deleted",
            "updated_at" => "updatedAt",
            "created_at" => "createdAt"
        );

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

        public function addOrderAmount($orderAmount) {
            $this->order_amounts[] = $orderAmount;
        }

        public function getOrdersAmountsText() {
            if (!empty($this->order_amounts)) {
                if (count($this->order_amounts) === 1) {
                    return '$' . implode(' ; $', $this->order_amounts);
                }
                return '$' . implode(' ; $', $this->order_amounts) . '  <br> total: $' . array_sum($this->order_amounts);
            }
            return "";
        }

        public function addSucceedAmountsText($orderAmount) {
            $this->succeed_order_amounts[] = $orderAmount;
        }

        public function getTransactionHistoryText() {
            //$0.00
            $ths = explode("\r\n", $this->transaction_history);
            if (count($ths) > 2) {
                $ths = array_slice($ths, 1, -1);
                $ret = [];
                $merchantAmountMap = [];
                foreach (array_reverse($ths) as &$th) {
                    //12:08 PM WINN-DIXIE #03 7024 BER - $12.84 
                    $_th = substr($th, 9);
                    //WINN-DIXIE #03 7024 BER - $12.84 
                    $parts = explode('-', $_th);
                    $amount = trim(trim($parts[count($parts) - 1]), '$');
                    //12.84 
                    $merchant = trim(explode('-', $_th)[0]);
                    //WINN-DIXIE #03 7024 BER
                    if ($amount === '0.00' || isset($merchantAmountMap[$merchant . '_' . $amount])) {
                        $merchantAmountMap[$merchant . '_' . $amount] = 1;
                        $ret[] = '<span style="color:red">' . $th . '</span>';
                    } else {
                        $ret[] = $th;
                        $merchantAmountMap[$merchant . '_' . $amount] = 1;
                    }
                }
                return implode("\r\n", array_reverse($ret));
            }

            return implode("\r\n", $ths);
        }

        public function getSucceedAmountsText() {
            return implode(' ; ', $this->succeed_order_amounts);
        }

    }

}
