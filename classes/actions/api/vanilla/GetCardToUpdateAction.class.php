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

namespace crm\actions\api\vanilla {

    use crm\actions\BaseAction;
    use crm\managers\VanillaCardsManager;
    use crm\security\RequestGroups;

    class GetCardToUpdateAction extends BaseAction {

        public function service() {
            $row = VanillaCardsManager::getInstance()->selectAll()[0];
            $this->addParam('card', $row);
            $this->addParam('success', true);
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
    