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

    use crm\dal\mappers\CreditCardsMapper;
    use crm\managers\AdvancedAbstractManager;
    use crm\managers\CreditCardsManager;

    class CreditCardsManager extends AdvancedAbstractManager {

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
                self::$instance = new CreditCardsManager(CreditCardsMapper::getInstance());
            }
            return self::$instance;
        }
        
        public function addRow() {
            $dto = $this->createDto();
            $dto->setDescription('N/A');
            
            return $this->insertDto($dto);
        }
        

    }

}
