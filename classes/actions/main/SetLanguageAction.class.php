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

    class SetLanguageAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->lang) && in_array(NGS()->args()->lang, ['am', 'en', 'ru'])) {
                setcookie('ul', NGS()->args()->lang, time() + 60 * 60 * 24 * 365, "/", HTTP_HOST);
            }
            header("location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }

    }

}
