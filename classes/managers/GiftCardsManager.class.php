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

    use crm\dal\mappers\GiftCardsMapper;
    use crm\managers\AdvancedAbstractManager;
    use crm\managers\GiftCardsManager;

    class GiftCardsManager extends AdvancedAbstractManager {

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
                self::$instance = new GiftCardsManager(GiftCardsMapper::getInstance());
            }
            return self::$instance;
        }

        public function addRow($partnerId) {
            $dto = $this->createDto();
            $dto->setCreatedAt(date('Y-m-d H:i:s'));
            $dto->setPartnerId($partnerId);
            return $this->insertDto($dto);
        }
        
    }

}
