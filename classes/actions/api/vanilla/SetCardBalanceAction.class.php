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

    class SetCardBalanceAction extends BaseAction {

        public function service() {
            $id = intval(NGS()->args()->card_id);
            $closedCardIds = trim(NGS()->args()->closed_cards_ids);
            if (!empty($closedCardIds)) {
                $idsArray = explode(',', $closedCardIds);
                foreach ($idsArray as $id) {
                    VanillaCardsManager::getInstance()->updateField($id, 'updated_at', date('Y-m-d H:i:s'));
                    VanillaCardsManager::getInstance()->updateField($id, 'closed', 1);
                }
            }

            $balance = floatval(NGS()->args()->balance);
            VanillaCardsManager::getInstance()->updateField($id, 'updated_at', date('Y-m-d H:i:s'));
            VanillaCardsManager::getInstance()->updateField($id, 'balance', $balance);
            $this->addParam('success', true);
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
    