<?php
namespace ngs\framework\exceptions {
	class NotFoundException extends \Exception {

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function __construct() {
			echo "404 page :)";
			exit;

		}

		private function jsonOutput($msg) {
			header("HTTP/1.0 404 Not Found");
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($msg);
		}

	}

}
