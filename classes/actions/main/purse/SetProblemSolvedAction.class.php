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

    class SetProblemSolvedAction extends BaseAction {

        public function service() {
            $id = intval(NGS()->args()->id);
            PurseOrderManager::getInstance()->updateField($id, 'problem_solved', 1);
             $this->addParam('id', $id);
        }

    }

}
