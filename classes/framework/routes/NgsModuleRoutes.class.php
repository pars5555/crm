<?php
/**
 * default ngs modules routing class
 * this class by default used from dispacher
 * for matching url with modules routes
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @year 2015
 * @package ngs.framework.routes
 * @version 2.0.0
 * @copyright Naghashyan Solutions LLC
 *
 */
namespace ngs\framework\routes {
	class NgsModuleRoutes {

		private $routes = null;
		private $package = null;
		private $nestedRoutes = null;
		private $jsonParams = array();
		private $contentLoad = null;
		private $dynContainer = "dyn";

		/**
		 * read from file json routes
		 * and set in private property for cache
		 *
		 * @return json Array
		 */
		private function getRouteConfig() {
			if ($this->routes == null) {
				$this->routes = json_decode(file_get_contents(NGS_MODULS_ROUTS), true);
			}
			return $this->routes;
		}

		/**
		 * this method return pakcage and command from url
		 * check url if set dynamic container return manage using standart routing
		 * if not manage url using routes file if matched succsess return array if not false
		 * this method can be overrided from users for they custom routing scenarios
		 *
		 * @param String $url
		 *
		 * @return array|false
		 */
		public function getModule($domain = null, $uri = null) {

			$module = "ngs";
			if (defined("DEFAULT_NS")) {
				$module = DEFAULT_NS;
			}
			if ($domain == null) {
				$domain = HTTP_HOST;
			}
			$parsedUrl = parse_url($domain);
			$host = explode('.', $parsedUrl['path']);
			$subdomain = null;
			if (count($host) >= 3) {
				$_moduleArr = $this->getModuleBySubDomain($host[0]);
				if ($_moduleArr["ns"] != null) {
					return array("module" => $_moduleArr["ns"], "uri" => $uri);
				}
			}

			if ($uri == null) {
				$uri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "";
				if (strpos($uri, "?") !== false) {
					$uri = substr($uri, 0, strpos($uri, "?"));
				}
			}
			$_moduleArr = $this->getModuleByURI($uri);
			if ($_moduleArr != null) {
				$uriReplacement = $_moduleArr["path"];
				if ($uri[0] == "/") {
					$uriReplacement = "/".$_moduleArr["path"];
				}
				return array("module" => $_moduleArr["ns"], "uri" => str_replace($uriReplacement, "", $uri));
			}
			return array("module" => $module, "uri" => $uri);
		}

		/**
		 * return module by subdomain
		 *
		 * @param String $domain
		 *
		 * @return string
		 */
		private function getModuleBySubDomain($domain) {
			$routes = $this->getRouteConfig();
			if (isset($routes["subdomain"][$domain])) {
				return array("ns"=>$routes["subdomain"][$domain]["dir"], "path"=>$domain);
			}
			return null;
		}

		/**
		 * return module by uri
		 *
		 * @param String $domain
		 *
		 * @return string
		 */
		private function getModuleByURI($uri) {
			$matches = array();
			preg_match_all("/(\/([^\/\?]+))/", $uri, $matches);
			$routes = $this->getRouteConfig();
			if (is_array($matches[2]) && isset($matches[2][0])) {
				if (isset($routes["path"][$matches[2][0]])) {
					return array("ns"=>$routes["path"][$matches[2][0]]["dir"], "path"=>$matches[2][0]);
				}
			}

			return null;
		}

	}

	return __NAMESPACE__;
}
