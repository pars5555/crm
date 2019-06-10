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

    use crm\dal\mappers\VanillaCardsMapper;
    use crm\managers\AdvancedAbstractManager;
    use crm\managers\VanillaCardsManager;

    class VanillaCardsManager extends AdvancedAbstractManager {

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
                self::$instance = new VanillaCardsManager(VanillaCardsMapper::getInstance());
            }
            return self::$instance;
        }

        public function getAllDeliveredTotal() {
            return $this->mapper->getAllDeliveredTotal();
        }
        
        public function getTotalBalance($ignoreLessThan = 10) {
            return $this->mapper->getTotalBalance($ignoreLessThan) ;
        }
        
        public function addRow() {
            $dto = $this->createDto();
            $dto->setCreatedAt(date('Y-m-d H:i:s'));
            return $this->insertDto($dto);
        }
        
        public function getBtcRate() {
            return $this->selectAdvance('*', ['name', '=', "'BTC'"], 'id', 'DESC', 0, 1)[0]->getRate();
        }

    }

}
