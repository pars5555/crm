<?php

/**
 * main site action for all ngs site's actions
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2014
 * @package actions.site
 * @version 6.0
 *
 */

namespace crm\actions\main {

    use crm\actions\BaseAction;

    class SetProfitVisibilityAction extends BaseAction {

        public function service() {
            if (isset($_COOKIE['showprofit']) && $_COOKIE['showprofit'] == 1) {
                $_COOKIE['showprofit'] = 0;
            } else {
                $_COOKIE['showprofit'] = 1;
            }
            setcookie('showprofit', $_COOKIE['showprofit'], time() + 60 * 60 * 24 * 365, "/", HTTP_HOST);
            header("location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }

    }

}
