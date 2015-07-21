<?php
/**
 * Sample File 2, phpDocumentor Quickstart
 *
 * This file demonstrates the rich information that can be included in
 * in-code documentation through DocBlocks and tags.
 * @author Zaven Naghashyan <zaven@naghashyan.com>, Levon Naghashyan <levon@naghashyan.com>
 * @version 2.0
 * @package framework
 * @copyright Naghashyan Solutions LLC
 */

namespace ngs\framework {

	abstract class AbstractRequest {

		protected $sessionManager;
		protected $requestGroup;

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function initialize() {
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function setRequestGroup($requestGroup) {
			$this->requestGroup = $requestGroup;
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function getRequestGroup() {
			return $this->requestGroup;
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function setDispatcher($dispatcher) {
			$this->dispatcher = $dispatcher;
		}

		public function redirectToLoad($package, $load, $args, $statusCode = 200) {
			if ($statusCode > 200 && $statusCode < 300) {
				header("HTTP/1.1 $statusCode Exception");
			}
			$this->dispatcher->loadPage($package, $load, $args);
			exit();
		}

		public function toCache() {
			return false;
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		protected function cancel() {
			throw new NoAccessException("Load canceled request ");
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		public function onNoAccess($msg = "") {
			return false;
		}

		/**
		 * Return a thingie based on $paramie
		 * @abstract
		 * @access
		 * @param boolean $paramie
		 * @return integer|babyclass
		 */
		protected function redirect($url, $isSecure = false) {
			$protocol = "http";
			if ($isSecure) {
				$protocol = "https";
			}
			header("location: ".$protocol."://".HTTP_HOST."/$url");
			exit();
		}

	}

}
