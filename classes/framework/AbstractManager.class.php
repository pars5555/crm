<?php
namespace ngs\framework {
	abstract class AbstractManager {

		public function __construct() {
		}

		protected $orderFields = array();

		public function validateMustBeParameters($dataObject, $paramsArray) {
			foreach ($paramsArray as $param) {
				$functionName = "get".ucfirst($param);
				$paramValue = $dataObject->$functionName();
				if ($paramValue == null || $paramValue == "") {
					throw new Exception("The parameter ".$param." is missing.");
				}
			}
			return true;
		}

		public function validateOrderFileld($key) {
			if ($this->orderFields[$key]) {
				return true;
			}
			return false;
		}
		
		/**
		 * Simple hashcode generator
		 *
		 * @return
		 */
		public function generateHashcode() {
			$str = time();
			return md5($str."_".rand(0, 50000));
		}

	}

}
?>