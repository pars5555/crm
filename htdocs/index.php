<?php
/**
 * ngs index page
 * for handle all dynamic http calls
 *
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @version 2.1.0
 *
 */
//send conrol origin policy header for non same domain connection
header("Access-Control-Allow-Origin: *");
//do chnage document root if script running from command line
if(php_sapi_name() == "cli"){
	defined('__DIR__') or define('__DIR__', dirname(__FILE__));
	chdir(__DIR__."/");
}
require_once '../vendor/autoload.php';
date_default_timezone_set("Asia/Yerevan");
require_once ("../classes/framework/NGS.class.php");
//$res = \crm\managers\PurseOrderManager::getInstance()->fetchFedexPageDetails('474486664669');
//var_dump($res);exit;
$dispatcher = new ngs\framework\Dispatcher();
