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

    use crm\dal\mappers\StickyNoteMapper;
    use crm\managers\AdvancedAbstractManager;
    use crm\managers\StickyNoteManager;

    class StickyNoteManager extends AdvancedAbstractManager {

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
                self::$instance = new StickyNoteManager(StickyNoteMapper::getInstance());
            }
            return self::$instance;
        }

        public function setPageContent($pageName, $userId, $note) {
            $rows = $this->selectAdvance('*', ['admin_id', "=", $userId, 'AND', 'page_name', '=', "'$pageName'", 'AND', 'datetime', '>=', "DATE_SUB(NOW(),INTERVAL 1 HOUR)"], 'id', 'desc', 0, 1);
            if (empty($rows)){
            }else{
                $dto = $rows[0];
                $dto->setContent($note);
                return $this->updateByPk($dto);
            }
            $dto = $this->createDto();
            $dto->setPageName($pageName);
            $dto->setAdminId($userId);
            $dto->setContent($note);
            $dto->setDatetime(date('Y-m-d H:i:s'));
            return $this->insertDto($dto);
        }

        public function getPageContent($page, $adminId) {
            $rows = $this->selectAdvance('*', ['admin_id', "=", $adminId, 'AND', 'page_name', '=', "'$page'"], 'id', 'desc', 0, 1);
            if (!empty($rows)) {
                return $rows[0]->getContent();
            }
            return "";
        }

    }

}
