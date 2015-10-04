<?php
/**
 * AbstractMapper class is a base class for all mapper lasses.
 * It contains the basic functionality and also DBMS pointer.
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @package framework.dal.mappers
 * @version 2.0.0
 * @year 2009-2015
 * @copyright Naghashyan Solutions LLC
 */
namespace ngs\framework\dal\mappers {
	
	abstract class AbstractMysqlMapper extends AbstractSqlMapper {

		public $dbms;

		/**
		 * Initializes DBMS pointer.
		 */
		function __construct() {
			$config = NGS()->getConfig();	
			if(!isset($config->DB->mysql)){
				$config = NGS()->getNgsConfig();
			}
			$usePdo = true;
			if (isset(NGS()->getConfig()->DB->mysql->driver) && NGS()->getConfig()->DB->mysql->driver == "mysqli") {
				$usePdo = false;
			}
			if ($usePdo) {
				$host = NGS()->getNgsConfig()->DB->mysql->host;
				$user = NGS()->getNgsConfig()->DB->mysql->user;
				$pass = NGS()->getNgsConfig()->DB->mysql->pass;
				$name = NGS()->getNgsConfig()->DB->mysql->name;
				$this->dbms = \ngs\framework\dal\connectors\MysqlPDO::getInstance($host, $user, $pass, $name);
			}
		}
	}
}