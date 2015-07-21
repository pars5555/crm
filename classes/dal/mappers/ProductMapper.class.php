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

    use crm\dal\dto\ProductDto;

    class ProductMapper extends AdvancedAbstractMysqlMapper {

        //! Private members.

        private static $instance;
        public $tableName = "products";

        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new ProductMapper();
            }
            return self::$instance;
        }

        public function createDto() {
            return new ProductDto();
        }

        public function getPKFieldName() {
            return "id";
        }

        public function getTableName() {
            return $this->tableName;
        }

    }

}
