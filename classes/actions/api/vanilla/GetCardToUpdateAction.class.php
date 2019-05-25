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
            $where = ['number', 'like', "'4%'"];
            if (isset(NGS()->args()->mastercard) && NGS()->args()->mastercard === 1) {
                $where = ['number', 'like', "'5%'"];
            }
            $hourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));
            //$where = array_merge($where, ['AND', 'closed', '=', 0,'AND', 'invalid', '=', 0, 'AND','(','updated_at', '<', "'$hourAgo'",'OR','updated_at', 'IS NULL', ')']);
            $where = array_merge($where, ['AND', 'closed', '=', 0,'AND', 'invalid', '=', 0]);
            $rows = VanillaCardsManager::getInstance()->selectAdvance('*', $where, 'updated_at', 'DESC', 0, 1);
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
    