<?php
namespace ngs\framework {
	abstract class  AbstractLoadMapper {

		private $params;
		private $smarty;

		public function __construct() {

		}

		public function setSmarty($smarty) {
			$this->smarty = $smarty;
		}

		
		
		public function getParam($name) {
			$param = null;
			if (!$this->params) {
				$ns = $this->smarty->get_template_vars("ns");
				$param = $ns[$name];
			} else {
				$param = $this->params[$name];
			}

			return $param;
		}

		public function setParam($name, $value) {
			$ns = $this->smarty->get_template_vars("ns");
			return $ns[$name];
		}

		public function setParams($params) {
			$this->params = $params;
		}

	}

}
?>