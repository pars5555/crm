<?php

/**
 * AdminMapper class is extended class from AbstractMapper.
 * It contatins all read and write functions which are working with admin table.
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2015
 * @package admin.dal.mappers
 * @version 1.0.0
 *
 */

namespace crm\dal\mappers {

    use crm\dal\dto\AdminDto;
    use ngs\framework\dal\mappers\AbstractMapper;
    use ngs\framework\dal\mappers\AbstractMysqlMapper;

    class AdminMapper extends AbstractMysqlMapper {

        /**
         * @var table name in DB
         */
        private $tableName;

        /**
         * @var an instance of this class
         */
        private static $instance = null;

        /**
         * Initializes DBMS pointers and table name private class member.
         */
        function __construct() {
            // Initialize the dbms pointer.
            parent::__construct();

            // Initialize table name.
            $this->tableName = "admin";
        }

        /**
         * Returns an singleton instance of this class
         * @return
         */
        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new AdminMapper();
            }
            return self::$instance;
        }

        /**
         * @see AbstractMapper::createDto()
         */
        public function createDto() {
            return new AdminDto();
        }

        /**
         * @see AbstractMapper::getPKFieldName()
         */
        public function getPKFieldName() {
            return "id";
        }

        /**
         * @see AbstractMapper::getTableName()
         */
        public function getTableName() {
            return $this->tableName;
        }

        public function getByUsernamePassword($username, $password) {
            $sqlQuery = sprintf("SELECT * from `%s` WHERE `username` = :username AND `password` = :password", $this->getTableName());
            $result = $this->fetchRow($sqlQuery, array("username" => $username, "password" => $password));
            if (count($result) === 1) {
                return $result;
            }
            return null;
        }

        private $GET_ADMIN_BY_ID_AND_HASH = "SELECT * from `%s` WHERE `id` = :userId AND `hash` = :hash";

        public function validateUser($userId, $hash) {
            $sqlQuery = sprintf($this->GET_ADMIN_BY_ID_AND_HASH, $this->getTableName());
            $result = $this->fetchRow($sqlQuery, array("userId" => $userId, "hash" => $hash));
            if (count($result) === 1) {
                return true;
            }
            return false;
        }

    }

}
