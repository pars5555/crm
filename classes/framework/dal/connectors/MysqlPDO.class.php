<?php
/**
 * ImprovedDBMS class uses MySQL Improved Extension to access DB.
 * This class provides full transaction support instead of DBMS class.
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @package framework.dal.connectors
 * @version 2.0.0
 * @year 2009-2014
 * @copyright Naghashyan Solutions LLC
 */
namespace ngs\framework\dal\connectors {
	use ngs\framework\exceptions\DebugException;
	class MysqlPDO extends \PDO {

		/**
		 * Singleton instance of class
		 */
		private static $instance = NULL;

		/**
		 * Object which represents the connection to a MySQL Server
		 */
		private $link = null;
		private $stmt = null;
		/**
		 * Tries to connect to a MySQL Server
		 */
		public function __construct($db_host, $db_user, $db_pass, $db_name) {
			parent::__construct('mysql:dbname='.$db_name.';host='.$db_host.';charset=utf8', $db_user, $db_pass);
			$this->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
			$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		}

		/**
		 * Returns an singleton instance of class.
		 *
		 * @return
		 */
		public static function getInstance($db_host, $db_user, $db_pass, $db_name) {
			if (is_null(self::$instance)) {
				self::$instance = new MysqlPDO($db_host, $db_user, $db_pass, $db_name);
			}
			return self::$instance;
		}
                
	}

}
