<?php
namespace ngs\framework {
	abstract class AbstractAction extends \ngs\framework\AbstractRequest {
		private $params = array();

		public function initialize() {
			parent::initialize();
		}

		public function service() {
//			$this->service();
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function addParam($name, $value) {
			$this->params[$name] = $value;
		}
		
		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function addParams($paramsArr) {
			foreach($paramsArr as $name=>$value){
				$this->params[$name] = $value;
			}
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function getParams() {
			return $this->params;
		}

	}

}
