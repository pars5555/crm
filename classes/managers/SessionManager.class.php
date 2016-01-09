<?php

/**
 *
 * NGS custom session manager
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2015
 * @package managers
 * @version 6.0
 *
 */

namespace crm\managers {

    use crm\security\RequestGroups;
    use crm\security\UserGroups;
    use crm\security\users\GuestUser;
    use crm\security\users\NgsAdminUser;
    use crm\security\users\NgsModeratorUser;
    use crm\security\users\NgsObserverUser;
    use NGS;
    use ngs\framework\exceptions\InvalidUserException;
    use ngs\framework\session\NgsSessionManager;

    class SessionManager extends NgsSessionManager {

        private $user = null;

        public function __construct() {
            session_start();
        }

        /**
         * return ngs current logined or not
         * user object
         *
         * @return userObject
         */
        public function getUser() {
            if ($this->user != null) {
                return $this->user;
            }
            try {
                $user = $this->getUserByLevel(isset($_COOKIE["crmut"])? : null);
                $user->validate();
            } catch (InvalidUserException $ex) {
                $this->deleteUser($user, false);
                NGS()->redirect(substr($_SERVER["REQUEST_URI"], 1));
                exit;
            }
            $this->user = $user;
            return $this->user;
        }

        protected function getUserHashKey() {
            return 'crmuh';
        }

        protected function getUserIdKey() {
            return 'crmud';
        }

        protected function getUserTypeKey() {
            return 'crmut';
        }

        /**
         * login ngs customer to system
         * set user hash and update user cookies
         *
         * @param userDto
         *
         * @return boolean
         */
        public function login($type, $id) {
            $user = $this->getUserByType($type);
            $user->login($id);
            $this->setUser($user, true, false);
            return true;
        }

        public function logout() {
            $this->deleteUser($this->getUser(), false);
            return true;
        }

        /**
         * create and return user object by user level       
         * @return userObject
         */
        public function getUserByLevel($ut) {
            switch ($ut) {
                case UserGroups::$ADMIN :
                    return new NgsAdminUser();
                case UserGroups::$MODERATOR :
                    return new NgsModeratorUser();
                case UserGroups::$OBSERVER:
                    return new NgsObserverUser();
                default :
                    return new GuestUser();
            }
        }

        /**
         * create and return user object by user level text
         *
         * @param string $userType
         *
         * @return userObject
         */
        public function getUserByType($userType) {
            switch ($userType) {
                case UserGroups::$ADMIN:
                    return new NgsAdminUser();
                case UserGroups::$MODERATOR:
                    return new NgsModeratorUser();
                case UserGroups::$OBSERVER:
                    return new NgsObserverUser();
                case UserGroups::$GUEST :
                    return new GuestUser();
                default :
                    throw new InvalidUserException("user not found");
            }
        }

        public function getUserId() {
            if ($this->getUser() == null) {
                return null;
            }
            return $this->getUser()->getId();
        }

        public function getUserType() {
            if ($this->getUser() == null) {
                return null;
            }
            return $this->getUser()->getLevel();
        }

        /**
         * validate all ngs request
         * by user
         *
         * @param object $request
         *
         * @return userObject
         */
        public function validateRequest($request, $user = null) {
            if ($user == null) {
                $user = $this->getUser();
            }
            switch ($request->getRequestGroup()) {
                case RequestGroups::$adminRequest :
                    if ($user->getLevel() == UserGroups::$ADMIN) {
                        return true;
                    }
                    break;
                case RequestGroups::$guestRequest:
                    return true;
            }
            return false;
        }

        private function updateUserHash($uId) {
            $customerManager = UserManager::getInstance($this->config, null);
            return $customerManager->updateUserHash($uId);
        }

    }

}
