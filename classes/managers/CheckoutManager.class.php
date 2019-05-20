<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package managers
 * @version 6.0
 *
 */

namespace crm\managers {

    class CheckoutManager {

        /**
         * @var $instance
         */
        public static $instance;

        const CHECKOUT_HOST = "https://api.checkout.am";
        const CHECKOUT_CONFIRM_ACTION_PATH = "/_sys_/orders/Confirm";
        const CHECKOUT_SET_TRACKING_ACTION_PATH = "/_sys_/orders/SetTracking";
        const CHECKOUT_GET_ACTION_PATH = "/entities/get";
        const CHECKOUT_SET_AMAZON_ORDER_NUMBER_ACTION_PATH = "/_sys_/orders/SetAmazonOrderNumber";
        const CHECKOUT_CHANGE_UNIT_ADDRESS_ACTION_PATH = "/_sys_/orders/ChangeUnitAddress";
        const CHECKOUT_CHANGE_ORDER_STATUS_ACTION_PATH = "/_sys_/orders/ChangeStatus";

        /**
         * Returns an singleton instance of this class
         *
         * @param object $config
         * @param object $args
         * @return
         */
        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new CheckoutManager();
            }
            return self::$instance;
        }

        public function changeCheckoutOrderCustomerUnitAddress($checkoutOrderId, $unitAddress) {
            $urlParams = ['order_id' => $checkoutOrderId, 'unit_address' => $unitAddress];
            $actionPath = self::CHECKOUT_CHANGE_UNIT_ADDRESS_ACTION_PATH . '?' . http_build_query($urlParams);
            return $this->returnCheckoutResponse($actionPath);
        }

        public function setCheckoutOrderStatus($checkoutOrderId, $status) {
            $urlParams = ['order_id' => $checkoutOrderId, 'status' => $status];
            $actionPath = self::CHECKOUT_CHANGE_ORDER_STATUS_ACTION_PATH . '?' . http_build_query($urlParams);
            return $this->returnCheckoutResponse($actionPath);
        }

        public function setAmazonOrderNumber($checkoutOrderId, $amazonOrderNumber) {
            $urlParams = ['order_id' => $checkoutOrderId, 'amazon_order_number' => $amazonOrderNumber];
            $actionPath = self::CHECKOUT_SET_AMAZON_ORDER_NUMBER_ACTION_PATH . '?' . http_build_query($urlParams);
            return $this->returnCheckoutResponse($actionPath);
        }

        public function selectAdvance($entityName, $fieldsArray = '*', $filters = [], $groupByFieldsArray = [], $orderByFieldsArray = [], $offset = null, $limit = null, $join = null, $mappedByFieldName = null) {
            $urlParams = [
                'token' => '84ada08f9b43bfd363867ddbd5c78174',
                'fields' => implode(',', $fieldsArray),
                'filters' => implode(',', $filters),
                'group_by' => implode(',', $groupByFieldsArray),
                'order_by' => implode(',', array_keys($orderByFieldsArray)),
                'order_by_asc_desc' => implode(',', array_values($orderByFieldsArray)),
                'offset' => $offset,
                'limit' => $limit,
                'join' => $join,
                'mapped_field_name' => $mappedByFieldName];
            $actionPath = self::CHECKOUT_GET_ACTION_PATH . '/' . ucfirst($entityName);
            $retObject = null;

            
            $success = $this->returnCheckoutResponse($actionPath . '?' . http_build_query($urlParams), $retObject, false);
            if ($success === true) {
                return $retObject;
            }
            return false;
        }

        public function setCheckoutOrderTrackingNumber($checkoutOrderId, $trackingNumber) {
            $urlParams = ['order_id' => $checkoutOrderId, 'tracking_number' => $trackingNumber];
            $actionPath = self::CHECKOUT_SET_TRACKING_ACTION_PATH . '?' . http_build_query($urlParams);
            return $this->returnCheckoutResponse($actionPath);
        }

        public function confirmOrder($checkoutOrderId) {
            $urlParams = ['order_id' => $checkoutOrderId];
            $actionPath = self::CHECKOUT_CONFIRM_ACTION_PATH . '?' . http_build_query($urlParams);
            return $this->returnCheckoutResponse($actionPath);
        }

        private function returnCheckoutResponse($actionPath, &$responseObject = null, $assoc = true) {
            $host = self::CHECKOUT_HOST;
            if (\ngs\framework\util\NgsUtils::isWindows()) {
                $host = "http://api.checkoutdev.am";
            }
            $stream = $this->prepareCheckoutRequest();
            $res = file_get_contents($host . $actionPath, false, $stream);
            $data = json_decode($res, $assoc);
            $responseObject = $data;
            if (isset($data->success) && $data->success === true) {
                return true;
            }
            if (isset($data->message)) {
                return ($data->message);
            }
            if (empty($res)) {
                return "connection problem to checkout.am! please contact administrator!";
            }
            return $data;
        }

        private function prepareCheckoutRequest() {

            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );

            return stream_context_create($arrContextOptions);
        }

    }

}
