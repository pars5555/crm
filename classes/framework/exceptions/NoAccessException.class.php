<?php
namespace ngs\framework\exceptions {
	class NoAccessException extends \Exception {

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function __construct($message) {
			parent::__construct($message, 0);
		}
	}
}
?>