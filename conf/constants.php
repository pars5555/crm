<?php
/**
 * default constants 
 * in this file should be store all constants
 *
 * @author Levon Naghashyan
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2015
 * @version 2.0.0
 * @copyright Naghashyan Solutions LLC
 *
 */
/*
|--------------------------------------------------------------------------
| DEFINNING VARIABLES IF SCRIPT RUNNING FROM COMMAND LINE
|--------------------------------------------------------------------------
*/
date_default_timezone_set("Asia/Yerevan");
if(php_sapi_name() == "cli"){
	$args = substr($argv[1], strpos($argv[1], "?")+1);
	$uri = substr($argv[1], 0, strpos($argv[1], "?"));
	$_SERVER["REQUEST_URI"] = $uri;
	if(isset($args)){
		$queryArgsArr = explode("&", $args);
		foreach ($queryArgsArr as $value) {
			$_arg = explode("=", $value);
			$_REQUEST[$_arg[0]] = $_arg[1];
			$_GET[$_arg[0]] = $_arg[1];
		}
	}
	if(isset($argv[2])){
		$_SERVER["ENVIRONMENT"] = $argv[2];
	}
	$_SERVER["HTTP_HOST"] = "";
} 
/*
|--------------------------------------------------------------------------
| DEFINNING ENVIRONMENT VARIABLES
|--------------------------------------------------------------------------
*/
$environment = "development";
if (isset($_SERVER["ENVIRONMENT"])) {
	$environment = $_SERVER["ENVIRONMENT"];
}
define("ENVIRONMENT", $environment);
//define error show status
if (ENVIRONMENT != "production") {
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

//defaining ngs namespace
define("DEFAULT_NS", "crm");

//defaining ngs namespace
define("JS_FRAMEWORK_ENABLE", true);

/*
|--------------------------------------------------------------------------
| DEFINNING DEFAULT DIRS
|--------------------------------------------------------------------------
*/
//---defining document root
if(strpos(getcwd(), "/htdocs") == false && strpos(getcwd(), "\htdocs") == false){
	throw new Exception("please change doc root to htdocs");
}
//---defining ngs root
if(strpos(getcwd(), "/htdocs") !== false){
	define("NGS_ROOT", substr(getcwd(), 0, strrpos(getcwd(), "/htdocs")));
}else{
	define("NGS_ROOT", substr(getcwd(), 0, strrpos(getcwd(), "\htdocs")));
}
/*
|--------------------------------------------------------------------------
| DEFINNING DEFAULTS PACKAGES DIRS
|--------------------------------------------------------------------------
*/

//---defining classes dir
define("CLASSES_DIR", "classes");


//---defining public dir
define("PUBLIC_DIR", "htdocs");

//---defining config  dir
define("CONF_DIR", "conf");

//---defining data dir 
define("DATA_DIR", "data");

//---defining data bin path
define("BIN_DIR", "bin");

//---defining temp folder path
define("TEMP_DIR", "temp");

//defining load and action directories
define("LOADS_DIR", "loads");
define("ACTIONS_DIR", "actions");

//---defining classes paths
define("CLASSES_PATH", "classes");

//defining routs file path
define("NGS_ROUTS", NGS_ROOT."/conf/routes.json");
//defining database connector class path
define("USE_DBMS", "util\db\ImprovedDBMS");
//defining load mapper path
define("USE_LOAD_MAPPER", "loads\LoadMapper");
//defining session manager path
define("USE_SESSION_MANAGER", "crm\managers\SessionManager");
//defining session manager path
define("USE_TEMPLATE_ENGINE", "util\ImTemplater");

/*
|--------------------------------------------------------------------------
| DEFINNING NGS MODULES
|--------------------------------------------------------------------------
*/
//---defining if modules enabled
define("MODULES_ENABLE", false);
//---defining modules dir
define("MODULES_DIR", "modules");
//---defining modules routing file
define("NGS_MODULS_ROUTS", NGS_ROOT."/conf/modules.json");

/*
|--------------------------------------------------------------------------
| DEFINNING SMARTY DIRS
|--------------------------------------------------------------------------
*/
//---defining smarty root
define("SMARTY_DIR", NGS_ROOT."/classes/lib/smarty/");
//---defining smarty paths
define("TEMPLATES_DIR", "templates");
define("SMARTY_CACHE_DIR", "cache");
define("SMARTY_COMPILE_DIR", "compile");


/*
|--------------------------------------------------------------------------
| DEFINNING HOST VARIABLES
|--------------------------------------------------------------------------
*/
//defaining default host
define("HTTP_HOST", $_SERVER["HTTP_HOST"]);
define("SITE_PATH", "//".$_SERVER["HTTP_HOST"]);
$array = explode(".", HTTP_HOST);
define("NGS_HTTP_HOST", (array_key_exists(count($array) - 2, $array) ? $array[count($array) - 2] : "").".".$array[count($array) - 1]);
define("NGS_SITE_PATH", "//".NGS_HTTP_HOST);
