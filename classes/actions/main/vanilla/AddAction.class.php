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

namespace crm\actions\main\vanilla {

    use crm\actions\BaseAction;
    use crm\managers\VanillaCardsManager;

    class AddAction extends BaseAction {

        public function service() {
            VanillaCardsManager::getInstance()->addRow();
            $this->redirect('vanilla/list');
        }

    }

}
