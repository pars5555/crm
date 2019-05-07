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

    class PurseOrderDto extends AbstractDto {

        const CHECKOUT_ORDER_STATUSES = [0 => 'draft', 5 => 'confirmed', 10 => 'processing', 15 => 'purchased', 20 => 'cancelled', 25 => 'done'];

        private $histores = [];
        private $checkoutObjectData = null;
        // Map of DB value to Field value
        private $mapArray = array("id" => "id", "order_number" => "orderNumber", "amazon_order_number" => "amazonOrderNumber",
            "delivery_date" => "deliveryDate", "carrier_delivery_date" => "carrierDeliveryDate", "unit_address" => "unitAddress",
            "carrier_tracking_status" => "carrierTrackingStatus", "tracking_number" => "trackingNumber", "supposed_purchase_price" => "supposedPurchasePrice",
            "amazon_total" => "amazonTotal", "buyer_name" => "buyerName", "problematic" => "problematic", "hidden" => "hidden",
            "amazon_primary_status_text" => 'amazonPrimaryStatusText', 'problem_solved' => 'problemSolved', 'shipping_type' => 'shipping_type',
            "discount" => "discount", "serial_number" => "serial_number", "btc_rate" => "btcRate", "recipient_name" => "recipientName", "product_name" => "productName",
            "quantity" => "quantity", "cancelled_at" => "cancelledAt", "image_url" => "imageUrl", "shipping_carrier" => "shippingCarrier", "status" => "status", "note" => 'note', "unread_messages" => 'unreadMessages',
            "account_name" => "accountName", "created_at" => "createdAt", "updated_at" => "updatedAt", "hidden_at" => "hiddenAt", 'meta' => 'meta',
            'external' => 'external',
            'product_id' => 'productId',
            'checkout_customer_unit_address' => 'checkoutCustomerUnitAddress',
            'checkout_order_id' => 'checkoutOrderId',
            'checkout_customer_name' => 'checkoutCustomerName',
            'checkout_order_status' => 'checkoutOrderStatus',
            'checkout_order_metadata' => 'checkoutOrderMetadata',
            'external_merchant_name' => 'externalMerchantName',
            'external_product_number' => 'externalProductNumber'
        );

        // returns map array
        public function getMapArray() {
            return $this->mapArray;
        }

        public function isDelayed() {
            return $this->getProblemSolved() == 0 && $this->getExternal() == 1 && $this->getCreateDateDiffWithNow() > intval(\crm\managers\SettingManager::getInstance()->getSetting('external_products_days_diff_for_created_date'));
        }

        private function getCreateDateDiffWithNow() {
            return round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($this->getCreatedAt())) / 86400);
        }

        public function getLocalCarrierName() {
            $carrierFirst2Letter = substr(strtolower($this->getUnitAddress()), 0, 2);
                if ($carrierFirst2Letter == 'nv') {
                    return "nova";
                }
                if ($carrierFirst2Letter == 'ar') {
                    return "onex";
                }
                return "globbing";
        }
        
        public function getCarrierTrackingUrl() {
            $trackingNumber = $this->getTrackingNumber();
            if (strpos(strtolower($this->getShippingCarrier()), 'usps') !== false) {
                return 'https://tools.usps.com/go/TrackConfirmAction?qtc_tLabels1=' . $trackingNumber;
            }
            if (strpos(strtolower($this->getShippingCarrier()), 'fedex') !== false) {
                return "https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=$trackingNumber&cntry_code=us&locale=en_US";
            }
            if (strpos(strtolower($this->getShippingCarrier()), 'ups') !== false) {
                return "https://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=$trackingNumber&loc=en_am";
            }
            return false;
        }

        function getStatusHistoryText() {
            $ret = [];
            foreach ($this->histores as $history) {
                $ret[] = $history->getStatus() . ' (' . $history->getCreatedAt() . ')';
            }
            return implode('&#013;', $ret);
        }

        function getAmazonOrderNumberText() {
            $ret = [];
            foreach ($this->histores as $history) {
                $ret[] = $history->getAmazonOrderNumber() . ' (' . $history->getCreatedAt() . ')';
            }
            return implode('&#013;', $ret);
        }

        function getCheckoutOrderProductLink() {

            switch (strtolower($this->getCheckoutOrderMetadataProperty('external_website_name', ''))) {
                case 'newegg':
                    return "https://www.newegg.com/Product/Product.aspx?Item=" . $this->external_product_number;
                default:
                    return "https://amazon.com/dp/" . $this->external_product_number . '?th=1&psc=1';
            }
        }

        function getCheckoutOrderMetadataProperty($propertyName, $defaultValue = "") {
            $checkoutOrderObject = $this->getCheckoutOrderObject();
            if (empty($checkoutOrderObject)) {
                return $defaultValue;
            }
            $perpetyList = explode('->', $propertyName);
            foreach ($perpetyList as $pName) {
                if (!isset($checkoutOrderObject->$pName))
                {
                    return $defaultValue;
                }
                $checkoutOrderObject = $checkoutOrderObject->$pName;
            }
            return $checkoutOrderObject;
        }

        private function getCheckoutOrderObject() {
            if (empty($this->checkoutObjectData)) {
                if (!empty($this->checkout_order_metadata)) {
                    $this->checkoutObjectData = json_decode($this->checkout_order_metadata);
                }
            }
            return $this->checkoutObjectData;
        }

        function hasMoreThanOneAmazonOrder() {
            if (empty($this->histores)) {
                return false;
            }
            $amazonOrderNumber = $this->getAmazonOrderNumber();
            foreach ($this->histores as $history) {
                $aon = trim($history->getAmazonOrderNumber());
                if (!empty($amazonOrderNumber) && $amazonOrderNumber !== $aon) {
                    return true;
                }
                if (!empty($aon)) {
                    $amazonOrderNumber = $history->getAmazonOrderNumber();
                }
            }
            return false;
        }

    }

}
