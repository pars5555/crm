<?php
/**
 * Helper class that works with files
 * have 3 general function
 * 1. send file to user using remote or local file
 * 2. read local file dirs
 * 3. upload files
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @year 2014-2015
 * @package framework.util
 * @version 2.0.0
 * @copyright Naghashyan Solutions LLC
 */
namespace ngs\framework\util {
	class FileUtils {
		
//-----------------------------Streamer Part---------//		
		/**
		 * send file to user
		 * set correct headers and stream file to user
		 * check if file from local or remote
		 * if local using 3 streamer option
		 * standart - php file open and read
		 * xAccelRedirect - nginx streamer
		 * xSendfile - apache file streamer module
		 * if remote
		 * read remote file and stream to user
		 *
		 * @param string $file - full file path
		 * @param array $options - (filename-custom file name, mimeType-custom mime type of file, contentLength-custom file size,
		 * cache true|false, remoteFile-is file remote, streamer - for local files,headers-addition headers)
		 *
		 * @return files bytes
		 */
		public function sendFile($file, $options = array()) {
			if (!is_string($file)){
				throw NGS()->getNotFoundException();
			}
			$defaultOptions = array("filename" => null, "mimeType" => null, "contentLength" => null, "cache" => true, "remoteFile" => false, "streamer" => "standart", "headers" => array());
			$options = array_merge($defaultOptions, $options);
			if ($options["remoteFile"] == false) {
				if (strpos($file, "https://") !== false || strpos($file, "http://") !== false || strpos($file, "ftp://") !== false) {
					$options["remoteFile"] = true;
				}
			}
			if ($options["remoteFile"] == true) {
				$options["remoteFileData"] = get_headers($file, true);
			}
			//check if user set file name than send user's filename if not get from file
			if ($options["filename"] != null) {
				header('Content-Disposition: '.$options["filename"]);
			}
			//check if user set mimetype than send user if not get from file
			if ($options["mimeType"] == null) {
				if ($options["remoteFile"] === false) {
					header('Content-type: '.mime_content_type($file));
				} else {
					header('Content-type: '.$options["remoteFileData"]["Content-Type"]);
				}
			} else {
				header('Content-type: '.$options["mimeType"]);
			}
			//check if content lengh if null and check if we
			//should use php file stream than we should add
			//file size in headers else use user defined file size
			if ($options["contentLength"] == null) {
				if ($options["streamer"] == "standart" && $options["remoteFile"] === false) {
					header('Content-Length: '.filesize($file));
				} else {
					header('Content-Length: '.$options["remoteFileData"]["Content-Length"]);
				}
			} else {
				header('Content-Length: '.$options["contentLength"]);
			}
			
			//send cache headers
			$this->sendCacheHeaders($file, $options);
			header('X-Pad: avoid browser bug');
			header("X-Powered-By: ngs");
			foreach ($options["headers"] as $key => $value) {
				header($value);
			}
			if ($options["remoteFile"] === true) {
				$this->doStreamFromUrl($file);
				return;
			}
			$this->doStreamFile(realpath($file), $options["streamer"]);
		}

		/**
		 * send cache headers
		 *
		 *
		 * @param string $file - full file path
		 * @param array $options - (filename-custom file name, mimeType-custom mime type of file, contentLength-custom file size,
		 * cache true|false, remoteFile-is file remote, streamer - for local files,headers-addition headers)
		 *
		 * @return files bytes
		 */
		private function sendCacheHeaders($file, $options) {
			//if cache is true that check if browser have that file.
			if ($options["cache"]) {
				$etag = md5_file($file);
				if ($options["remoteFile"] == true) {
					$lastModifiedTime = $options["remoteFileData"]["Last-Modified"];
					header("Last-Modified: ".$lastModifiedTime);
					if ($options["remoteFileData"]["Etag"]) {
						$etag = $options["remoteFileData"]["Etag"];
					}
				} else {
					$lastModifiedTime = filemtime($file);
					header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModifiedTime)." GMT");
				}

				header("Etag: ".$etag);
				if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModifiedTime || (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag)) {
					header("HTTP/1.1 304 Not Modified");
					return true;
				}

				header("Cache-Control: private, max-age=10800, pre-check=10800");
				header("Pragma: private");
			} else {
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
				header("Cache-Control: no-store, no-cache, must-revalidate");
				header("Cache-Control: post-check=0, pre-check=0", false);
				header("Pragma: no-cache");
			}

		}

		/**
		 * read and send file bytes
		 *
		 *
		 * @param string $streamFile - full file path
		 * @param string $streamer - streamer mode
		 *
		 * @return files bytes
		 */
		private function doStreamFile($streamFile, $streamer) {
			switch ($streamer) {
				case 'xAccelRedirect' :
					header('X-Accel-Redirect: '.$streamFile);
					break;
				case 'xSendfile' :
					header('X-Sendfile: '.$streamFile);
					break;
				default :
					echo file_get_contents($streamFile);exit;
					$file = @fopen($streamFile, "rb");
					if ($file) {
						while (!feof($file)) {
							print(fread($file, 2048 * 8));
							flush();
							if (connection_status() != 0) {
								@fclose($file);
								die();
							}
						}
						@fclose($file);
					}
					break;
			}

			exit ;
		}

		/**
		 * read and send remote file bytes
		 *
		 *
		 * @param string $url
		 *
		 * @return files bytes
		 */
		private function doStreamFromUrl($url) {
			$file = @fopen($url, "rb");
			if ($file) {
				while (!feof($file)) {
					print(fread($file, 2048 * 8));
					flush();
					if (connection_status() != 0) {
						@fclose($file);
						die();
					}
				}
				@fclose($file);
			}
		}

	}

}
