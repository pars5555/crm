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

        public function confirmOrder($checkoutOrderId) {
            $host = self::CHECKOUT_HOST;
            if (\crm\util\Util::isWindows()) {
                $host = "http://api.checkoutdev.am";
            }
            $urlParams = ['order_id' => $checkoutOrderId];
            $arrContextOptions = array(
                "ssl" => array(
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ),
            );

            $stream = stream_context_create($arrContextOptions);
            $url = $host . '/' . self::CHECKOUT_CONFIRM_ACTION_PATH . '?'. http_build_query($urlParams);
            $res = @file_get_contents($url, false, $stream);
            $data = json_decode($res, true);
            if (isset($data['success']) && $data['success'] === true) {
                return true;
            }
            if (isset($data['message'])) {
                return ($data['message']);
            }
            return $data;
        }

    }

}
