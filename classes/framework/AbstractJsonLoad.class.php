<?php
/**
 * NGS abstract load all loads that response is json should extends from this class
 * this class extends from AbstractRequest class
 * this class class content base functions that will help to
 * initialize loads
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @year 2014-2015
 * @package framework
 * @version 2.0.0
 * @copyright Naghashyan Solutions LLC
 *
 */
namespace ngs\framework {
	use \ngs\framework\exception\NoAccessException;
	abstract class AbstractJsonLoad extends AbstractLoad {

		protected $params = array();

		
		protected function setNestedLoadParams($namespace, $fileNs, $loadObj) {
			$this->params["inc"][$namespace] = $loadObj->getParams();
		}

		public final function getLoadType(){
			return "json";
		}
		
	}

}
