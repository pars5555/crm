<?php
/**
 * Base ngs class
 * for static function that will
 * vissible from any classes
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @year 2014-2015
 * @package framework
 * @version 2.0.0
 * @copyright Naghashyan Solutions LLC
 *
 */

use ngs\framework\exception\DebugException;
class NGS {

  public static $TOP = "";
  public $NGSVERSION = "2.0.0";
  public $DEFAULT_NS = "ngs";
  public $FRAMEWORK_NS = "ngs";

  private static $instance = null;
  private $ngsConfig = null;
  private $config = array();
  private $args = array();
  private $loadMapper = null;
  private $routesEngine = null;
  private $moduleRoutesEngine = null;
  private $sessionManager = null;
  private $tplEngine = null;
  private $fileUtils = null;
  private $jsBuilder = null;
  private $cssBuilder = null;
  private $requestParams = null;
  private $moduleName = "";
  private $isModuleEnable = false;

  public function __construct() {
    if (defined("DEFAULT_NS")) {
      $this -> DEFAULT_NS = DEFAULT_NS;
    }
    if (defined("MODULES_ENABLE") && MODULES_ENABLE == true) {
      $this -> isModuleEnable = MODULES_ENABLE;
    }
    $this -> registerAutoload();
  }

  /**
   * Returns an singleton instance of this class
   *
   * @return object NGS
   */
  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new NGS();
    }
    return self::$instance;
  }

  public function getContext() {
    return NgsModule::getInstance();
  }

  public function isModuleEnable() {
    return $this -> isModuleEnable;
  }

  public function setModuleName($module) {
    $this -> moduleName = $module;
  }

  public function getModuleName() {
    return $this -> moduleName;
  }

  /**
   * this method calculate dir conencted with module
   *
   * @static
   * @return String rootDir
   */
  public function getRootDirByModule($modulePrefix = "") {
    if ($modulePrefix == "" && $this -> getModuleName() != null) {
      return NGS_ROOT . "/" . MODULES_DIR . "/" . $this -> getModuleName();
    }
    if ($modulePrefix == "" || $modulePrefix == $this -> FRAMEWORK_NS || $this -> DEFAULT_NS == $modulePrefix) {
      return NGS_ROOT;
    }
    if (($modulePrefix == $this -> getModuleName() || is_dir(NGS_ROOT . "/" . MODULES_DIR . "/" . $modulePrefix))) {
      return NGS_ROOT . "/" . MODULES_DIR . "/" . $modulePrefix;
    }
  }

  /**
   * this method return global ngs root config file
   *
   *
   * @return array config
   */
  public function getNgsConfig() {
    if (isset($this -> ngsConfig)) {
      return $this -> ngsConfig;
    }
    return $this -> ngsConfig = json_decode(file_get_contents($this -> getRootDirByModule($this -> DEFAULT_NS) . "/" . CONF_DIR . "/config_" . $this -> getShortEnvironment() . ".json"));
  }

  /**
   * static function that return ngs
   * global config
   *
   *
   * @return array config
   */
  public function getConfig($prefix = null) {
    if ($this -> getModuleName() == null) {
      return $this -> getNgsConfig();
    }
    if ($prefix == null) {
      $_prefix = $this -> getModuleName();
    } else {
      $_prefix = $prefix;
    }
    if (isset($this -> config[$_prefix])) {
      return $this -> config[$_prefix];
    }
    return $this -> config[$_prefix] = json_decode(file_get_contents($this -> getRootDirByModule($_prefix) . "/" . CONF_DIR . "/config_" . $this -> getShortEnvironment() . ".json"));
  }

  /**
   * static function that return ngs
   * global url args
   *
   *
   * @return array config
   */
  public function getArgs() {
    return $this -> args;
  }

  /**
   * static function that set ngs
   * url args
   *
   *
   * @return array config
   */
  public function setArgs($args) {
    if (!is_array($args)) {
      return false;
    }
    $this -> requestParams = null;
    $this -> args = array_merge($this -> args, $args);
  }

  public function args($trim = false) {
    if ($this -> requestParams != null) {
      return $this -> requestParams;
    }
    $this -> requestParams = $this -> createObjectFromArray(array_merge((array)$this -> getArgs(), $_REQUEST), $trim);
    return $this -> requestParams;
  }

  public function getConfigDir($ns = "") {
    if ($ns == "") {
      if ($this -> getModuleName() != null) {
        $ns = $this -> getModuleName();
      }
    }
    return $this -> getRootDirByModule($ns) . "/" . CONF_DIR;
  }

  public function getTemplateDir($ns = null) {
    if ($ns == "") {
      if ($this -> getModuleName() != null) {
        $ns = $this -> getModuleName();
      }
    }
    return $this -> getRootDirByModule($ns) . "/" . TEMPLATES_DIR;

  }

  public function getPublicDir($ns = null) {
    if ($ns == "") {
      if ($this -> getModuleName() != null) {
        $ns = $this -> getModuleName();
      }
    }
    return $this -> getRootDirByModule($ns) . "/" . PUBLIC_DIR;

  }

  public function getClassesDir($ns = "") {
    if ($ns == "") {
      if ($this -> getModuleName() != null) {
        $ns = $this -> getModuleName();
      }
    }
    return $this -> getRootDirByModule($ns) . "/" . CLASSES_DIR;
  }

  public function getNgsRoutesFile() {
    return json_decode(file_get_contents($this -> getRootDirByModule($_prefix) . "/" . CONF_DIR . "/config_" . $this -> getShortEnvironment() . ".json"));
  }

  /**
   * this method  return imusic.am
   * sessiomanager if defined by user it return it if not
   * return imusic.am default sessiomanager
   *
   * @static
   * @return Object loadMapper
   */
  public function getSessionManager() {

    if ($this -> sessionManager != null) {
      return $this -> sessionManager;
    }
    $ns = $this -> FRAMEWORK_NS . "\\framework\\session\\NgsSessionManager";
    try {
      if (defined("USE_SESSION_MANAGER")) {
        $ns = USE_SESSION_MANAGER;
      }
      $this -> sessionManager = new $ns();
    } catch(Exception $e) {
      throw new DebugException("SESSION MANAGER NOT FOUND, please check in constants.php SESSION_MANAGER variable");
    }
    return $this -> sessionManager;
  }

  /**
   * static function that return imusic.am
   * fileutils if defined by user it return it if not
   * return imusic.am default fileutils
   *
   * @return Object fileUtils
   */
  public function getRoutesEngine() {
    if ($this -> routesEngine != null) {
      return $this -> routesEngine;
    }
    $ns = $this -> FRAMEWORK_NS . "\\framework\\routes\\NgsRoutes";
    try {
      if (defined("ROUTES_ENGINE")) {
        $ns = ROUTES_ENGINE;
      }
      $this -> routesEngine = new $ns();
    } catch(Exception $e) {
      throw new DebugException("ROUTES ENGINE NOT FOUND, please check in constants.php ROUTES_ENGINE variable");
    }
    return $this -> routesEngine;
  }

  /**
   * this function that return imusic.am
   * fileutils if defined by user it return it if not
   * return imusic.am default fileutils
   *
   * @return Object fileUtils
   */
  public function getModulesRoutesEngine() {
    if ($this -> moduleRoutesEngine != null) {
      return $this -> moduleRoutesEngine;
    }
    $ns = $this -> FRAMEWORK_NS . "\\framework\\routes\\NgsModuleRoutes";
    try {
      if (defined("MODULES_ROUTES_ENGINE")) {
        $ns = MODULES_ROUTES_ENGINE;
      }
      $this -> moduleRoutesEngine = new $ns();
    } catch(Exception $e) {
      throw new DebugException("ROUTES ENGINE NOT FOUND, please check in constants.php ROUTES_ENGINE variable");
    }
    return $this -> moduleRoutesEngine;
  }

  /**
   * static function that return imusic.am
   * loadmapper if defined by user it return it if not
   * return imusic.am default loadmapper
   *
   * @return Object loadMapper
   */
  public function getLoadMapper() {
    if ($this -> loadMapper != null) {
      return $this -> loadMapper;
    }
    $ns = $this -> FRAMEWORK_NS . "\\framework\\routes\\NgsLoadMapper";
    try {
      if (defined("LOAD_MAPPER")) {
        $ns = LOAD_MAPPER;
      }
      $this -> loadMapper = new $ns();
    } catch(Exception $e) {
      throw new DebugException("LOAD MAPPER NOT FOUND, please check in constants.php LOAD_MAPPER variable");
    }
    return $this -> loadMapper;
  }

  /**
   * static function that return imusic.am
   * loadmapper if defined by user it return it if not
   * return imusic.am default loadmapper
   *
   * @return Object loadMapper
   */
  public function getTemplateEngine() {
    if ($this -> tplEngine != null) {
      return $this -> tplEngine;
    }
    $ns = $this -> FRAMEWORK_NS . "\\framework\\templater\\NgsTemplater";
    try {
      if (defined("TEMPLATE_ENGINE")) {
        $ns = "\\" . TEMPLATE_ENGINE;
      }
      $this -> tplEngine = new $ns();
    } catch(Exception $e) {
      throw new DebugException("TEMPLATE ENGINE NOT FOUND, please check in constants.php TEMPLATE_ENGINE variable");
    }
    return $this -> tplEngine;
  }

  /**
   * static function that return ngs
   * fileutils if defined by user it return it if not
   * return ngs default fileutils
   *
   * @return Object fileUtils
   */
  public function getFileUtils() {
    if ($this -> fileUtils != null) {
      return $this -> fileUtils;
    }
    $ns = $this -> FRAMEWORK_NS . "\\framework\\util\\FileUtils";
    try {
      if (defined("FILE_UTILS")) {
        $ns = FILE_UTILS;
      }
      $this -> fileUtils = new $ns();
    } catch(Exception $e) {
      throw new DebugException("FILE UTILS NOT FOUND, please check in constants.php FILE_UTILS variable");
    }
    return $this -> fileUtils;
  }

  /**
   * static function that return ngs
   * fileutils if defined by user it return it if not
   * return ngs default fileutils
   *
   * @return Object fileUtils
   */
  public function getJsBuilder() {
    if ($this -> jsBuilder != null) {
      return $this -> jsBuilder;
    }
    $ns = $this -> FRAMEWORK_NS . "\\framework\\util\\JsBuilder";
    try {
      if (defined("JS_BUILDER")) {
        $ns = JS_BUILDER;
      }
      $this -> jsBuilder = new $ns();
    } catch(Exception $e) {
      throw new DebugException("JS UTILS NOT FOUND, please check in constants.php JS_BUILDER variable");
    }
    return $this -> jsBuilder;
  }

  /**
   * static function that return ngs
   * fileutils if defined by user it return it if not
   * return ngs default fileutils
   *
   * @return Object fileUtils
   */
  public function getCssBuilder() {
    if ($this -> cssBuilder != null) {
      return $this -> cssBuilder;
    }
    $ns = $this -> FRAMEWORK_NS . "\\framework\\util\\CssBuilder";
    try {
      if (defined("CSS_BUILDER")) {
        $ns = CSS_BUILDER;
      }
      $this -> cssBuilder = new $ns();
    } catch(Exception $e) {
      throw new DebugException("CSS UTILS NOT FOUND, please check in constants.php CSS_BUILDER variable");
    }
    return $this -> cssBuilder;
  }

  public function getFileStreamerByType($fileType) {
    switch ($fileType) {
      case 'js' :
        return $this -> getJsBuilder();
        break;
      case 'css' :
        return $this -> getCssBuilder();
        break;
      default :
        return false;
    }
  }

  public function getLoadsPackage() {
    $loadsPackage = "loads";
    if (defined("LOADS_DIR")) {
      $loadsPackage = LOADS_DIR;
    }
    return $loadsPackage;
  }

  public function getActionPackage() {
    $actionPackage = "actions";
    if (defined("ACTIONS_DIR")) {
      $actionPackage = ACTIONS_DIR;
    }
    return $actionPackage;
  }

  /**
   * return project prefix
   * @static
   * @access
   * @return String $namespace
   */
  public function getEnvironment() {
    $env = "production";
    if (defined("ENVIRONMENT")) {
      $env = ENVIRONMENT;
    }
    return $env;
  }

  /**
   * return short env prefix
   * @static
   * @access
   * @return String $env
   */
  public function getShortEnvironment() {
    $env = "prod";
    if (defined("ENVIRONMENT") && ENVIRONMENT == "development") {
      $env = "dev";
    }
    return $env;
  }

  public function getNGSVersion() {
    return $this -> NGSVERSION;
  }

  /**
   * check if ngs js framework enable
   *
   * @return bool $_status
   */
  public function isJsFrameworkEnable() {
    $_status = false;
    if (defined("JS_FRAMEWORK_ENABLE") && is_bool(JS_FRAMEWORK_ENABLE)) {
      $_status = JS_FRAMEWORK_ENABLE;
    }
    return $_status;
  }

  public function getDynObject() {
    return new \ngs\framework\util\NgsDynamic();
  }

  /**
   * register file autload event for namespace use include
   */
  private function registerAutoload() {
    spl_autoload_register(function($class) {
      $class = str_replace('\\', '/', $class);
      $ngsPrefix = substr($class, 0, strpos($class, "/"));
      $class = substr($class, strpos($class, "/") + 1);
      $classPath = NGS() -> getRootDirByModule($ngsPrefix) . "/" . CLASSES_DIR;
      $filePath = $classPath . '/' . $class . '.class.php';
      if (file_exists($filePath)) {
        require_once ($filePath);
      }
    });
  }

  /**
   * detect if request call from ajax or not
   * @static
   * @access
   * @return bool|true|false
   */
  public function isAjaxRequest() {
    if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest") {
      return true;
    }
    return false;
  }

  public function getUniqueId() {
    return md5(uniqid() . time() . rand(0, 100000) . microtime(true));
  }

  public function createObjectFromArray($arr, $trim = false) {

    $stdObj = $this -> getDynObject();
    foreach ($arr as $key => $value) {
      $last = substr(strrchr($key, '.'), 1);
      if (!$last)
        $last = $key;
      $node = $stdObj;
      foreach (explode('.', $key) as $key2) {
        if (!isset($node -> $key2)) {
          $node -> $key2 = new stdclass;
        }
        if ($key2 == $last) {
          if (is_string($value)) {
            if ($trim == true) {
              $node -> $key2 = trim(htmlspecialchars(strip_tags($value)));
            } else {
              $node -> $key2 = $value;
            }
          } else {
            $node -> $key2 = $value;
          }

        } else {
          $node = $node -> $key2;
        }
      }
    }
    return $stdObj;
  }

  public function isJson($string) {
    if (is_numeric($string)) {
      return false;
    }
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
  }

  public function getNotFoundException() {
    return new \ngs\framework\exceptions\NotFoundException();
  }

  /**
   * Return a thingie based on $paramie
   * @abstract
   * @access
   * @param boolean $paramie
   * @return integer|babyclass
   */
  public function redirect($url) {
    header("location: //" . HTTP_HOST . "/$url");
  }

  public function getHost() {
    $array = explode(".", HTTP_HOST);
    return (array_key_exists(count($array) - 2, $array) ? $array[count($array) - 2] : "") . "." . $array[count($array) - 1];
  }

}

function NGS() {
  return NGS::getInstance();
}

NGS();
