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

    use crm\dal\mappers\CheckoutUsersMapper;
    use crm\managers\AdvancedAbstractManager;
    use crm\managers\CheckoutUsersManager;

    class CheckoutUsersManager extends AdvancedAbstractManager {

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
                self::$instance = new CheckoutUsersManager(CheckoutUsersMapper::getInstance());
            }
            return self::$instance;
        }

        public function insertOrUpdateUser($checkoutUserObject) {
            $dto = $this->createDto();
            $dto->setUserId($checkoutUserObject->id);
            $dto->setFullName($checkoutUserObject->first_name. ' '. $checkoutUserObject->last_name);
            $dto->setReferrerId($checkoutUserObject->referrer_id);
            $dto->setReferrerEmail($checkoutUserObject->referrer_email);
            $dto->setDiscount($checkoutUserObject->discount);
            $dto->setDisabled($checkoutUserObject->disabled);
        }
        

    }

}
