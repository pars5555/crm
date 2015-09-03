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

        public function replaceLineId($oldLineId, $newLineId) {
            $dtos = $this->selectByField('line_id', $oldLineId);
            foreach ($dtos as $dto) {
                $dto->setLineId($newLineId);
                $this->updateByPk($dto);
            }
            return true;
        }

        public function setPurchaseOrderListSerialNumbers($polId, $serialNumbers, $warrantyMonths) {
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
