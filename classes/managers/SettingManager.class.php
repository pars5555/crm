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

use crm\dal\mappers\SettingMapper;

    class SettingManager extends AdvancedAbstractManager {

        /**
         * @var $instance
         */
        public static $instance;
        private $cache;

        function __construct($mapper) {
            $this->mapper = $mapper;
            $this->cache();
        }

        /**
         * Returns an singleton instance of this class
         *
         * @param object $config
         * @param object $args
         * @return
         */
        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new SettingManager(SettingMapper::getInstance());
            }
            return self::$instance;
        }

        public function getSetting($var) {
            if (array_key_exists($var, $this->cache)) {
                return $this->cache[$var];
            }
            return null;
        }

        private function cache() {
            $allRows = $this->selectAll();
            foreach ($allRows as $row) {
                $this->cache[$row->getVar()] = $row->getValue();
            }
        }

    }

}
