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

  abstract class AbstractPostgreSQLMapper extends AbstractSqlMapper {

    public $dbms;
    private $bulkUpdateQuery;

    /**
     * Initializes DBMS pointer.
     */
    function __construct() {
      $config = NGS()->getConfig();
      if (!isset($config->DB->mysql)) {
        $config = NGS()->getNgsConfig();
      }
      $host = NGS()->getNgsConfig()->DB->pgsql->host;
      $user = NGS()->getNgsConfig()->DB->pgsql->user;
      $pass = NGS()->getNgsConfig()->DB->pgsql->pass;
      $name = NGS()->getNgsConfig()->DB->pgsql->name;
      $port = NGS()->getNgsConfig()->DB->pgsql->port;
      $this->dbms = \ngs\framework\dal\connectors\PgsqlPDO::getInstance($host, $user, $pass, $name,$port);
    }

  }

}
