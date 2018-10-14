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

    use crm\dal\mappers\PurseOrderHistoryMapper;

    class PurseOrderHistoryManager extends AdvancedAbstractManager {

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
                self::$instance = new PurseOrderHistoryManager(PurseOrderHistoryMapper::getInstance());
            }
            return self::$instance;
        }
        public function addRow($orderId, $status, $meta) {
            $dto = $this->createDto();
            $dto->setOrderId($orderId);
            $dto->setStatus($status);
            $dto->setMeta($meta);
            $dto->setCreatedAt(date('Y-m-d H:i:s'));
            return $this->insertDto($dto);
        }

    }

}
