<?php
/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2012-2014
 */
namespace exceptions {
	class JsonException extends \Exception {

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */

		public function __construct($msg, $code = false) {
			NGS()->getTemplateEngine()->assignJson("status", "error");
			if ($code != false) {
				NGS()->getTemplateEngine()->assignJson("code", $code);
			}
			NGS()->getTemplateEngine()->assignJson("msg", $msg);
			NGS()->getTemplateEngine()->display();
			exit ;
		}

	}

}
