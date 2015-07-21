<?php
/**
 * BrowserUtils is framework util class
 * that get info about request browser
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @year 2013-2015
 * @copyright Naghashyan Solutions LLC
 */
namespace ngs\framework\util {
	class BrowserUtils {

		/**
		 * getBrowserInfo get return
		 *
		 * @param string $str
		 * @param bool $retMsg
		 * @return string or bool
		 */
		public static function getBrowserInfo() {

			$u_agent = $_SERVER['HTTP_USER_AGENT'];
			$bname = 'Unknown';
			$platform = 'Unknown';
			$version = "";

			if (preg_match('/linux/i', $u_agent)) {
				$platform = 'linux';
			} elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
				$platform = 'mac';
			} elseif (preg_match('/windows|win32/i', $u_agent)) {
				$platform = 'windows';
			}
			$bInfo = $this->getBrowserInfoByUAgent($u_agent);
			$known = array('Version', $bInfo["ub"], 'other');
			$pattern = '#(?<browser>'.join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

			$i = count($matches['browser']);
			if ($i != 1) {
				if (strripos($u_agent, "Version") < strripos($u_agent, $bInfo["ub"])) {
					$version = $matches['version'][0];
				} else {
					$version = $matches['version'][1];
				}
			} else {
				$version = $matches['version'][0];
			}
			if ($version == null || $version == "") {
				$version = "?";
			}
			$stdclass = new stdclass;
			$stdclass->userAgent = $u_agent;
			$stdclass->name = $bInfo["bname"];
			$stdclass->version = $version;
			$stdclass->platform = $platform;
			$stdclass->short_name = strtolower($bInfo["ub"]);
			return $stdclass;
		}

		private function getBrowserInfoByUAgent($uAgent) {
			if (preg_match('/MSIE/i', $uAgent) && !preg_match('/Opera/i', $uAgent)) {
				$bname = 'Internet Explorer';
				$ub = "MSIE";
			} elseif (preg_match('/Firefox/i', $uAgent)) {
				$bname = 'Mozilla Firefox';
				$ub = "Firefox";
			} elseif (preg_match('/Chrome/i', $uAgent)) {
				$bname = 'Google Chrome';
				$ub = "Chrome";
			} elseif (preg_match('/Safari/i', $uAgent)) {
				$bname = 'Apple Safari';
				$ub = "Safari";
			} elseif (preg_match('/Opera/i', $uAgent)) {
				$bname = 'Opera';
				$ub = "Opera";
			} elseif (preg_match('/Netscape/i', $uAgent)) {
				$bname = 'Netscape';
				$ub = "Netscape";
			}
			return array("bname" => $bname, "ub" => $ub);
		}

	}

}
