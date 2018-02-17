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

namespace crm\actions\main\recipient {

    use crm\actions\BaseAction;
    use crm\managers\PecipientManager;
    use NGS;

    class SetPecipientHiddenAction extends BaseAction {

        public function service() {
            $id = intval(NGS()->args()->recipient_id);
            $hidden = NGS()->args()->hidden;
            PecipientManager::getInstance()->setPecipientHidden($id, $hidden);
        }

    }

}
    