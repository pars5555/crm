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

namespace crm\actions\main\purse {

    use crm\actions\BaseAction;
    use crm\managers\PurseOrderManager;
    use NGS;

    class SetHiddenAction extends BaseAction {

        public function service() {
            $id = intval(NGS()->args()->id);
            $hide = intval(NGS()->args()->hide);
            PurseOrderManager::getInstance()->updateField($id, 'hidden_at', date('Y-m-d H:i:s'));
            PurseOrderManager::getInstance()->updateField($id, 'hidden', $hide);
        }

    }

}
