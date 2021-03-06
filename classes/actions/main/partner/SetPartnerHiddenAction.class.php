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

namespace crm\actions\main\partner {

    use crm\actions\BaseAction;
    use crm\managers\PartnerManager;
    use NGS;

    class SetPartnerHiddenAction extends BaseAction {

        public function service() {
            $id = intval(NGS()->args()->partner_id);
            $hidden = NGS()->args()->hidden;
            PartnerManager::getInstance()->setPartnerHidden($id, $hidden);
        }

    }

}
    