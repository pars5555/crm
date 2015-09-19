<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2012-2014
 */

namespace crm\exceptions {

    use Exception;

    class InsufficientProductException extends Exception {

        private $productId;

        public function __construct($productId) {
            $this->productId = $productId;
        }

        function getProductId() {
            return $this->productId;
        }

    }

}
