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
            $closedCardIds = trim(NGS()->args()->closed_cards_ids);
            if (!empty($closedCardIds)) {
                $idsArray = explode(',', $closedCardIds);
                foreach ($idsArray as $ccid) {
                    VanillaCardsManager::getInstance()->updateField($ccid, 'updated_at', date('Y-m-d H:i:s'));
                    VanillaCardsManager::getInstance()->updateField($ccid, 'closed', 1);
                }
            }

            $id = intval(NGS()->args()->card_id);
            $balance = floatval(NGS()->args()->balance);
            VanillaCardsManager::getInstance()->updateField($id, 'updated_at', date('Y-m-d H:i:s'));
            if (isset(NGS()->args()->skip) && NGS()->args()->skip == 1) {
                $this->addParam('success', true);
                return;
            }
            
            VanillaCardsManager::getInstance()->updateField($id, 'balance', $balance);
            $this->addParam('success', true);
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
    