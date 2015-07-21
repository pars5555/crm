<?php
/**
 * default ngs SessionManager class
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @year 2014-2015
 * @package framework.session
 * @version 2.0.0
 * @copyright Naghashyan Solutions LLC
 *
 */
namespace ngs\framework\session {
	class NgsSessionManager extends \ngs\framework\AbstractSessionManager {

		/**
		 * this method for get user from cookies,
		 * Children of the NgsSessionManager class should override this method
		 *
		 * @abstract
		 * @access
		 * @param
		 * @return mixed user Object| $user
		 */
		public function getUser() {
			return null;
		}

		/**
		 * set user info into cookie and session
		 *
		 * @param mixed user Object| $user
		 * @param bool $remember | true
		 * @param bool $useDomain | true
		 *
		 * @return
		 */
		public function setUser($user, $remember = false, $useDomain = true, $useSubdomain = false) {
			$sessionTimeout = $remember ? 2078842581 : null;
			$domain = false;
			if ($useDomain) {
				if ($useSubdomain) {
					$domain = ".".HTTP_HOST;
				} else {
					$domain = ".".NGS()->getHost();
				}
			}
			$cookieParams = $user->getCookieParams();
			foreach ($cookieParams as $key => $value) {
				setcookie($key, $value, $sessionTimeout, "/", $domain);
			}
			$sessionParams = $user->getSessionParams();
			foreach ($sessionParams as $key => $value) {
				$_SESSION[$key] = $value;
			}
		}

		/**
		 * delete user from cookie and session
		 *
		 * @param mixed user Object| $user
		 * @param bool $useDomain | true
		 *
		 * @return
		 */
		public function deleteUser($user, $useDomain = true, $useSubdomain = false) {
			$sessionTimeout = time() - 42000;
			$domain = false;
			if ($useDomain) {
				if ($useSubdomain) {
					$domain = ".".HTTP_HOST;
				} else {
					$domain = ".".NGS()->getHost();
				}
			}
			$cookieParams = $user->getCookieParams();
			foreach ($cookieParams as $key => $value) {
				setcookie($key, '', $sessionTimeout, "/", $domain);
			}
			$sessionParams = $user->getSessionParams();
			foreach ($sessionParams as $key => $value) {
				if (isset($_SESSION[$key])) {
					unset($_SESSION[$key]);
				}
			}
		}

		/**
		 * Update user hash code
		 *
		 * @param mixed user Object| $user
		 *
		 * @return
		 */
		public function updateUserUniqueId($user, $useSubdomain = false) {
			if ($useSubdomain) {
				$domain = ".".HTTP_HOST;
			} else {
				$domain = ".".NGS()->getHost();
			}
			$cookieParams = $user->getCookieParams();
			setcookie("uh", $cookieParams["uh"], null, "/", $domain);
		}

		/**
		 * this method for delete user from cookies,
		 * Children of the NgsSessionManager class should override this method
		 *
		 * @abstract
		 * @param load|action Object $request
		 * @param Object $user | null
		 * @return boolean
		 */
		public function validateRequest($request, $user = null) {
			return null;
		}

	}

}
