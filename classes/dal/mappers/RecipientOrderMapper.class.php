<?php

/**
 *
 * Mysql mapper class is extended class from AbstractMysqlMapper.
 * It contatins all read and write functions which are working with its table.
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2015
 * @package crm.dal.mappers
 * @version 2.0.0
 *
 */

namespace crm\dal\mappers {

    use crm\dal\dto\RecipientOrderDto;

    class RecipientOrderMapper extends AdvancedAbstractMysqlMapper {

        //! Private members.

        private static $instance;
        public $tableName = "recipient_orders";

        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new RecipientOrderMapper();
            }
            return self::$instance;
        }

        public function createDto() {
            return new RecipientOrderDto();
        }

        public function getPKFieldName() {
            return "id";
        }

        public function getTableName() {
            return $this->tableName;
        }

    }

}
