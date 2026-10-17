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
    use crm\security\RequestGroups;
    use NGS;

    class SetLanguageAction extends BaseAction {

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

        public function service() {
            $lang = NGS()->args()->lang ?? null;
            if ($lang && in_array($lang, ['am', 'en', 'ru'])) {
                setcookie('ul', $lang, time() + 60 * 60 * 24 * 365, "/");
                $_COOKIE['ul'] = $lang;
            }
            $redirectUrl = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
            header("Location: " . $redirectUrl);
            exit;
        }

    }

}
