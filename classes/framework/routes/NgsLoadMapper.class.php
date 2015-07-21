<?php
/**
 * default ngs routing class
 * this class by default used from dispacher
 * for matching url with routes
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @year 2014
 * @package framework.routes
 * @version 2.0.0
 * @copyright Naghashyan Solutions LLC
 *
 */
namespace ngs\framework\routes {
	use ngs\framework\AbstractLoadMapper;

	class NgsLoadMapper extends \ngs\framework\AbstractLoadMapper {

		private $nestedLoad = array();

		public function setNestedLoads($parent, $nl, $params) {
			if (!isset($parent)) {
				return;
			}
			$this->nestedLoad[$parent][] = array("load" => $nl, "params" => $params);
		}

		public function getNestedLoads() {
			return $this->nestedLoad;
		}

	}

	return __NAMESPACE__;
}
