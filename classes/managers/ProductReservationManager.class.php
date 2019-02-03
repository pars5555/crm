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

    use crm\dal\mappers\ProductReservationMapper;
    use crm\managers\AdvancedAbstractManager;
    use crm\managers\ProductReservationManager;

    class ProductReservationManager extends AdvancedAbstractManager {

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
                self::$instance = new ProductReservationManager(ProductReservationMapper::getInstance());
            }
            return self::$instance;
        }

        public function getReservedProducts() {
            $rows = $this->mapper->getReservedProducts();
            $ret = [];
            foreach ($rows as $row) {
                if (!isset($ret[$row->getProductId()])) {
                    $ret[$row->getProductId()] = [];
                }
                $ret[$row->getProductId()][] = $row;
            }
            return $ret;
        }

        public function addRow($productId, $quantity, $phone_number, $hours, $note) {
            $dto = $this->createDto();
            $dto->setProductId($productId);
            $dto->setQuantity($quantity);
            $dto->setPhoneNumber($phone_number);
            $dto->setStartAt(date('Y-m-d H:i:s'));
            $dto->setHours($hours);
            $dto->setNote($note);
            $dto->setCreatedAt(date('Y-m-d H:i:s'));
            return $this->insertDto($dto);
        }

    }

}
