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

    use crm\dal\dto\SolSerialNumberDto;

    class SolSerialNumbersMapper extends AdvancedAbstractMysqlMapper {

        private static $instance;
        public $tableName = "sol_serial_numbers";

        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new SolSerialNumbersMapper();
            }
            return self::$instance;
        }

        public function createDto() {
            return new SolSerialNumberDto();
        }

        public function getPKFieldName() {
            return "id";
        }

        public function getTableName() {
            return $this->tableName;
        }

    }

}
