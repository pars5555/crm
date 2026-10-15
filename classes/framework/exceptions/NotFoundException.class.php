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
      header("HTTP/1.0 404 Not Found");
      // browsers get the friendly page; ajax/API callers keep the old plain-text answer
      $page = (isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '') . '/errors/404.html';
      $wantsHtml = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'text/html') !== false;
      if ($wantsHtml && !isset($_SERVER['HTTP_X_REQUESTED_WITH']) && is_file($page)) {
        header('Content-Type: text/html; charset=utf-8');
        readfile($page);
      } else {
        echo "404 page :)";
      }
      exit;
    }

  }

}
