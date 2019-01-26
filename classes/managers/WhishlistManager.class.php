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

    use crm\dal\mappers\WhishlistMapper;
    use crm\managers\AdvancedAbstractManager;
    use crm\managers\WhishlistManager;

    class WhishlistManager extends AdvancedAbstractManager {

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
                self::$instance = new WhishlistManager(WhishlistMapper::getInstance());
            }
            return self::$instance;
        }

        public function updateRow($id, $name, $asinList, $targetPrice) {
            $dto = $this->selectByPk($id);
            $dto->setName($name);
            $dto->setAsinList($asinList);
            $dto->setTargetPrice($targetPrice);
            $dto->setCurrentMinPrice(0);
            $dto->setCurrentMinPriceAsin('');
            $dto->setUpdatedAt(date('Y-m-d H:i:s'));
            return $this->updateByPk($dto);
            
        }
        
        public function addRow($name, $asinList, $targetPrice) {
            $dto = $this->createDto();
            $dto->setName($name);
            $dto->setAsinList($asinList);
            $dto->setTargetPrice($targetPrice);
            $dto->setUpdatedAt(date('Y-m-d H:i:s'));
            return $this->insertDto($dto);
        }

    }

}
