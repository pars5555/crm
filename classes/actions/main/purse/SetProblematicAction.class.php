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

    class SetProblematicAction extends BaseAction {

        public function service() {
            $id = intval(NGS()->args()->id);
            $row = PurseOrderManager::getInstance()->selectByPK($id);
            if ($row->getProblematic() == 1) {
                PurseOrderManager::getInstance()->updateField($id, 'problematic', 0);
                $this->addParam('problematic', 0);
            } else {
                PurseOrderManager::getInstance()->updateField($id, 'problematic', 1);
                $this->addParam('problematic', 1);
            }
            $this->addParam('id', $id);
        }

    }

}
