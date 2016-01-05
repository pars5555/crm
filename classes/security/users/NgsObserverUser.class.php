<?php

/**
 *
 * Creates en instance of admin user class and
 * initializes class members necessary for validation.
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2014
 * @package security.users
 * @version 6.0
 *
 */

namespace crm\security\users {

    use crm\managers\ObserverManager;
    use crm\security\UserGroups;
    use ngs\framework\exceptions\InvalidUserException;

    class NgsObserverUser extends AbstractAdminUser {

        public function __construct() {
            parent::__construct();
        }

        /**
         * register guest user
         *
         * @return int userId
         */
        public function login($id) {
            $userHashcode = ObserverManager::getInstance()->loginUser($id);
            $this->setCookieParam("crmut", UserGroups::$MODERATOR);
            $this->setUniqueId($userHashcode);
            $this->setId($id);
            return $id;
        }

        /**
         * Returns user's level
         *
         * @return int
         */
        public function getLevel() {
            return $this->getCookieParam("crmut");
        }

        /**
         * Validates user credentials
         *
         * @return TRUE - if validation passed, and FALSE - otherwise
         */
        public function validate() {
            if (ObserverManager::getInstance()->validate($this->getId(), $this->getUniqueId())) {
                return true;
            }
            throw new InvalidUserException("wrong user");
        }

    }

}