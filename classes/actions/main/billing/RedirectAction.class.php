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

namespace crm\actions\main\billing {

    use crm\actions\BaseAction;

    class RedirectAction extends BaseAction {

        public function service() {
            $_SESSION['action_request'] = $_REQUEST;
            $this->redirect('billing/create');
        }

    }

}
