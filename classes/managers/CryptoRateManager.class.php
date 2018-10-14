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

    use crm\dal\mappers\CryptoRateMapper;
    use crm\managers\AdvancedAbstractManager;
    use crm\managers\CryptoRateManager;

    class CryptoRateManager extends AdvancedAbstractManager {

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
                self::$instance = new CryptoRateManager(CryptoRateMapper::getInstance());
            }
            return self::$instance;
        }

        public function addRow($name, $rate) {
            $dto = $this->createDto();
            $dto->setName($name);
            $dto->setRate($rate);
            $dto->setCreatedAt(date('Y-m-d H:i:s'));
            return $this->insertDto($dto);
        }
        
        public function getBtcRate() {
            return $this->selectAdvance('*', ['name', '=', "'BTC'"], 'id', 'DESC', 0, 1)[0]->getRate();
        }

    }

}
