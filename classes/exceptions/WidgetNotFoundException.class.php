<?php
/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2012-2014
 */
namespace exceptions {
	class WidgetNotFoundException extends \framework\exceptions\NotFoundException {

		private $msg;
		private $errorCode;

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */

		public function __construct($msg, $code = false) {
			$this->msg = $msg;
			if ($code != false) {
				$this->errorCode = $code;
			}
		}

		public function getMsg() {
			$jsonArr = array("status" => "error", "msg" => $this->msg);
			if ($this->errorCode != false) {
				$jsonArr["code"] = $this->errorCode;
			}
			return $jsonArr;
		}

	}

}
