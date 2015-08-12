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

    use crm\dal\mappers\ManufacturerMapper;

    class ManufacturerManager extends AdvancedAbstractManager {

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
                self::$instance = new ManufacturerManager(ManufacturerMapper::getInstance());
            }
            return self::$instance;
        }

        public function createManufacturer($name, $link) {
            $dto = $this->createDto();
            $dto->setName($name);
            $dto->setLink($link);
            return $this->insertDto($dto);
        }

        public function updateManufacturer($id, $name, $link) {
            $dto = $this->selectByPK($id);
            if ($dto) {
                $dto->setName($name);
                $dto->setLink($link);
                return $this->updateByPk($dto);
            }
            return false;
        }

    }

}
