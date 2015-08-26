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

    use crm\managers\TranslationManager;
    use ngs\framework\AbstractLoad;

    /**
     * General parent for a ngs demo loads
     */
    abstract class NgsLoad extends AbstractLoad {

        /**
         * Initializes translations array for selected language.
         *
         * @return
         */
        public function __construct() {
            if (isset($_COOKIE['showprofit']) && $_COOKIE['showprofit'] == 1) {
                $this->addParam('showprofit', 1);
            } else {
                $this->addParam('showprofit', 0);
            }
        }

        //! A constructor.
        public function initialize() {
            parent::initialize();
            $lm = TranslationManager::getInstance();
            $this->addParam("lm", $lm);
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

    }

}
