<?php
/**
 * NgsErrorException exceptions class extends from Exception
 * handle ngs errors
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @package framework.exceptions
 * @version 2.0.0
 * @year 2010-2014
 */
namespace ngs\framework\exceptions {
	class NgsErrorException extends \Exception {

		public function __construct() {
			$argv = func_get_args();
			switch( func_num_args() ) {
				default :
					return;
					break;
				case 1 :
					$code = -1;
					$msg = $argv[0];
					break;
				case 2 :
					$code = $argv[0];
					$msg = $argv[1];
					break;
			}
			NGS()->getTemplateEngine()->assignJson("status", "error");
			NGS()->getTemplateEngine()->assignJson("code", $code);
			NGS()->getTemplateEngine()->assignJson("msg", $msg);
                        var_dump($msg);exit;
			NGS()->getTemplateEngine()->display();exit;
		}
	}
}