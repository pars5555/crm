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

    use crm\dal\mappers\OnlineShopsMapper;
    use crm\managers\AdvancedAbstractManager;

    class OnlineShopsManager extends AdvancedAbstractManager {

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
                self::$instance = new OnlineShopsManager(OnlineShopsMapper::getInstance());
            }
            return self::$instance;
        }

        public function addRow($name) {
            $dto = $this->createDto();
            $dto->setName($name);
            return $this->insertDto($dto);
        }

        public function getBtcRate() {
            return $this->selectAdvance('*', ['name', '=', "'BTC'"], 'id', 'DESC', 0, 1)[0]->getRate();
        }

    }

}
