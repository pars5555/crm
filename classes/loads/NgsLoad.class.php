<?php

/**
 * main site load for all ngs site's loads
 * this class provide methods
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2015
 * @package loads
 * @version 6.0
 *
 */

namespace crm\loads {

    use crm\managers\SettingManager;
    use crm\managers\TranslationManager;
    use crm\security\RequestGroups;
    use crm\security\UserGroups;
    use NGS;
    use ngs\framework\AbstractLoad;

    abstract class NgsLoad extends AbstractLoad {

        /**
         * Initializes translations array for selected language.
         *
         * @return
         */
        public function __construct() {
            
        }

        //! A constructor.
        public function initialize() {
            parent::initialize();
            $lm = TranslationManager::getInstance();
            $adminId = NGS()->getSessionManager()->getUserId();
            $this->addParam('user', \crm\managers\AdminManager::getInstance()->getById($adminId));
            $this->addParam('userId', NGS()->getSessionManager()->getUserId());
            $this->addParam('userType', NGS()->getSessionManager()->getUserType());
            $this->addParam('userTypeAdmin', UserGroups::$ADMIN);
            $this->addParam('userTypeGuest', UserGroups::$GUEST);
            $this->addParam("lm", $lm);
            $this->addParam("loadName", NGS()->getRoutesEngine()->getPackage());
            $this->addParam('showprofit', isset($_COOKIE['showprofit']) ? $_COOKIE['showprofit'] : 0);
        }

        protected function initErrorMessages() {
            if (isset($_SESSION['error_message'])) {
                $this->addParam('error_message', $_SESSION['error_message']);
                unset($_SESSION['error_message']);
            }
        }

        protected function initSuccessMessages() {
            if (isset($_SESSION['success_message'])) {
                $this->addParam('success_message', $_SESSION['success_message']);
                unset($_SESSION['success_message']);
            }
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

        public function getSetting($varName) {
            return SettingManager::getInstance()->getSetting($varName);
        }

        public function getPhrase($id, $lang = null) {
            return TranslationManager::getInstance()->getPhrase($id, $lang);
        }

    }

}
