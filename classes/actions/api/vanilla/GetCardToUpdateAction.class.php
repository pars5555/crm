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
            $where = ['closed', '=', 0,'AND', 'invalid', '=', 0];
            $rows = VanillaCardsManager::getInstance()->selectAdvance('*', $where, 'updated_at', 'ASC', 0, 1);
            if (empty($rows)){
                $this->addParam('success', true);                
                $this->addParam('finish', true);    
                return;
            }
            $this->addParam('card', $rows[0]);
            $this->addParam('success', true);
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
    