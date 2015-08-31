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

    use crm\dal\mappers\SaleOrderLineSerialNumberMapper;

    class SaleOrderLineSerialNumberManager extends AdvancedAbstractManager {

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
                self::$instance = new SaleOrderLineSerialNumberManager(SaleOrderLineSerialNumberMapper::getInstance());
            }
            return self::$instance;
        }

        public function setSaleOrderListSerialNumbers($polId, $serialNumbers, $warrantyMonths) {
            $this->deleteByField("line_id", $polId);
            foreach ($serialNumbers as $key => $serialNumber) {
                $dto = $this->createDto();
                $dto->setLineId($polId);
                $dto->setSerialNumber($serialNumber);
                $dto->setWarrantyMonths($warrantyMonths[$key]);
                $this->insertDto($dto);
            }
            return true;
        }

    }

}
