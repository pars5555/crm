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

    use crm\dal\mappers\PurchaseOrderLineSerialNumberMapper;

    class PurchaseOrderLineSerialNumberManager extends AdvancedAbstractManager {

        /**
         * @var $instance
         */
        public static $instance;

        /**
         * Returns an singleton instance of this class
         *
         * @param object $config
         * @param object $args
         * @return
         */
        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new PurchaseOrderLineSerialNumberManager(PurchaseOrderLineSerialNumberMapper::getInstance());
            }
            return self::$instance;
        }

        public function setPurchaseOrderListSerialNumbers($polId, $serialNumbers) {
            $this->deleteByField("line_id", $polId);
            foreach ($serialNumbers as $serialNumber) {
                $dto = $this->createDto();
                $dto->setLineId($polId);
                $dto->setSerialNumber($serialNumber);
                $this->insertDto($dto);
            }
            return true;
        }

    }

}
