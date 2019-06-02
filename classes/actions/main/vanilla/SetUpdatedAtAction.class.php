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

    class SetUpdatedAtAction extends BaseAction {

        public function service() {
            $id = intval(NGS()->args()->id);
            $days_ago = date('Y-m-d', strtotime("-10 days"));
            VanillaCardsManager::getInstance()->updateField($id, 'updated_at', $days_ago);
        }

    }

}
