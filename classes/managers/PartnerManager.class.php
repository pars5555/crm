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

    use crm\dal\mappers\PartnerMapper;

    class PartnerManager extends AdvancedAbstractManager {

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
                self::$instance = new PartnerManager(PartnerMapper::getInstance());
            }
            return self::$instance;
        }

        public function createPartner($name, $email, $address) {
            $dto = $this->createDto();
            $dto->setName($name);
            $dto->setEmail($email);
            $dto->setAddress($address);
            $dto->setCreateDate(date('Y-m-d H:i:s'));
            return $this->insertDto($dto);
        }

       

    }

}
