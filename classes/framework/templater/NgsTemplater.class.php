<?php
/**
 * Smarty util class extends from main smarty class
 * provides extra features for ngs
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @package uril
 * @version 2.0.0
 * @year 2010-2015
 * @copyright Naghashyan Solutions LLC
 */
namespace ngs\framework\templater {
  use ngs\framework\templater\AbstractTemplater;
  class NgsTemplater extends AbstractTemplater {

    /**
     * constructor
     * reading Smarty config and setting up smarty environment accordingly
     */
    private $smarty = null;
    private $template = null;
    private $params = array();
    private $permalink = null;
    private $smartyParams = array();
    public function __construct() {
    }

    public function smartyInitialize() {
      require_once (SMARTY_DIR . "/Smarty.class.php");
      $this->smarty = new \Smarty();
      $this->smarty->template_dir = NGS()->getTemplateDir();
      $this->smarty->setCompileDir($this->getSmartyCompileDir());
      $this->smarty->config_dir = $this->getSmartyConfigDir();
      $this->smarty->cache_dir = $this->getSmartyCacheDir();
      $this->smarty->compile_check = true;
      $this->smarty->registerPlugin("function", "getTemplateDir", array($this, "getSmartyTemplateDir"));

      $this->smarty->assign("TEMPLATE_DIR", TEMPLATES_DIR);
      $this->smarty->assign("pm", NGS()->getLoadMapper());

      $protocol = "//";

      // register the outputfilter
      $this->smarty->registerFilter("output", array($this, "add_dyn_header"));

      $staticPath = $protocol . $_SERVER["HTTP_HOST"];
      if (isset(NGS()->getConfig()->static_path) && NGS()->getConfig()->static_path != null) {
        $staticPath = $protocol . NGS()->getConfig()->static_path;
      }
      $version = NGS()->getNGSVersion();
      if (isset(NGS()->getConfig()->version)) {
        $version = NGS()->getConfig()->version;
      }

      $this->assign("SITE_URL", $_SERVER["HTTP_HOST"]);
      $this->assign("SITE_PATH", $protocol . $_SERVER["HTTP_HOST"]);
      $this->assign("STATIC_PATH", $staticPath);
      $this->assign("TEMPLATE_DIR", TEMPLATES_DIR);
      $this->assign("ENVIRONMENT", ENVIRONMENT);
      $this->assign("VERSION", $version);
      foreach ($this->smartyParams as $key => $value) {
        $this->smarty->assign($key, $value);
      }
    }

    public function assign($key, $value) {
      $this->smartyParams[$key] = $value;
    }

    public function assignJson($key, $value) {
      $this->params[$key] = $value;
    }

    /**
     * set template
     *
     * @param String $template
     *
     */
    public function setTemplate($template) {
      $this->template = $template;
    }

    /**
     * Return a template
     *
     * @return String $template|null
     */
    public function getTemplate() {
      return $this->template;
    }

    /**
     * set template
     *
     * @param String $template
     *
     */
    public function setPermalink($permalink) {
      $this->permalink = $permalink;
    }

    /**
     * Return a template
     *
     * @return String $template|null
     */
    public function getPermalink() {
      return $this->permalink;
    }

    public function display() {
      if ($this->getTemplate() == null) {
        $this->diplayJSONResuls();
        return;
      }
      $this->smartyInitialize();
      if (NGS()->isAjaxRequest() && NGS()->isJsFrameworkEnable()) {
        $this->assignJson("html", $this->smarty->fetch($this->getTemplate()));
        $this->assignJson("nl", NGS()->getLoadMapper()->getNestedLoads());
        $this->assignJson("pl", $this->getPermalink());
        $this->diplayJSONResuls();
        return true;
      } elseif ($this->getTemplate() != null) {
        $this->smarty->display($this->getTemplate());
      }
    }

    /**
     * Return a thingie based on $paramie
     * @abstract
     * @access
     * @param boolean $paramie
     * @return integer|babyclass
     */
    private function createJSON($arr) {
      $jsonArr = array();
      if (!isset($arr["status"])) {
        $arr["status"] = "ok";
      }
      if ($arr["status"] == "error") {
        header("HTTP/1.0 400 BAD REQUEST");
        if (isset($arr["code"])) {
          $jsonArr["code"] = $arr["code"];
        }
        if (isset($arr["msg"])) {
          $jsonArr["msg"] = $arr["msg"];
        }
        if (isset($arr["params"])) {
          $jsonArr["params"] = $arr["params"];
        }
      } else {
        if (isset($arr["_params_"])) {
          $jsonArr = array_merge($jsonArr, $arr["_params_"]);
          unset($arr["_params_"]);
        }

        $jsonArr = array_merge($jsonArr, $arr);
      }
      return json_encode($jsonArr);
    }

    /**
     * Return a thingie based on $paramie
     * @abstract
     * @access
     * @param boolean $paramie
     * @return integer|babyclass
     */
    private function diplayJSONResuls() {
      try {
        header('Content-Type: application/json; charset=utf-8');
        echo $this->createJSON($this->params);
      } catch (Exception $ex) {
        echo $ex->getMessage();
      }

    }

    public function add_dyn_header($tpl_output, $template) {

      $jsString = "";
      $jsString = '<meta name="generator" content="Naghashyan Framework ' . NGS()->getNGSVersion() . '" />';
      if (!defined("JS_FRAMEWORK_ENABLE") || JS_FRAMEWORK_ENABLE === false) {
        $tpl_output = str_replace('</head>', $jsString, $tpl_output) . "\n";
        return $tpl_output;
      }
      $jsString .= '<script type="text/javascript">';
      $jsString .= "NGS.setInitialLoad('" . NGS()->getRoutesEngine()->getContentLoad() . "', '" . json_encode($this->params) . "');";
      $jsString .= 'NGS.setModule("' .NGS()->getModuleName() . '");';
      $jsString .= 'NGS.setTmst("' . time() . '");';
      $jsString .= 'var NGS_URL = "' . HTTP_HOST . '";';
      $jsString .= 'var NGS_PATH = "//' . HTTP_HOST . '";';
      foreach ($this->getCustomJsParams() as $key => $value) {
        $jsString .= $key . " = '" . $value . "';";
      }
      $jsString .= '</script>';
      $jsString .= '</head>';
      $tpl_output = str_replace('</head>', $jsString, $tpl_output);
      if (ENVIRONMENT == "production") {
        $tpl_output = preg_replace('![\t ]*[\r\n]+[\t ]*!', '', $tpl_output);
      }
      return $tpl_output;
    }

    protected function getCustomJsParams() {
      return array();
    }

    public function getSmartyTemplateDir($params, &$smarty) {
      $module = null;
      if (!empty($params["module"])) {
        $module = $params["module"];
      }
      return NGS()->getTemplateDir($module);
    }

    protected function getSmartyCompileDir() {
      $compileDir = "compile";
      if (defined("SMARTY_COMPILE_DIR")) {
        $compileDir = SMARTY_COMPILE_DIR;
      }
      return NGS()->getTemplateDir() . "/" . $compileDir;
    }

    protected function getSmartyCacheDir() {
      $cacheDir = "cache";
      if (defined("SMARTY_CACHE_DIR")) {
        $cacheDir = SMARTY_CACHE_DIR;
      }
      return NGS()->getTemplateDir() . "/" . $cacheDir;
    }

    protected function getSmartyConfigDir() {
      $configDir = "config";
      if (defined("SMARTY_CONFIG_DIR")) {
        $configDir = SMARTY_CONFIG_DIR;
      }
      return NGS()->getTemplateDir() . "/" . $configDir;
    }

  }

}
