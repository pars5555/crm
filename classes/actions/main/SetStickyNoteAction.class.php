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
    use crm\managers\StickyNoteManager;
    use NGS;

    class SetStickyNoteAction extends BaseAction {

        public function service() {

            $note = NGS()->args()->note;
            $page_name = NGS()->args()->page_name;
            $userId = NGS()->getSessionManager()->getUserId();
            StickyNoteManager::getInstance()->setPageContent($page_name, $userId, $note);
            $note = StickyNoteManager::getInstance()->getPageContent($page_name, $userId);
            $this->addParam('note', $note);
        }

    }

}
