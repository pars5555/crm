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

namespace crm\actions\main\giftcards {

    use crm\actions\BaseAction;
    use crm\managers\GiftCardsManager;

    class AddAction extends BaseAction {

        public function service() {
            $partnerId = intval(NGS()->args()->partner_id);
            GiftCardsManager::getInstance()->addRow();
            $url = "giftcards/list";
            if ($partnerId > 0) {
                $url .= "?pid=" . $partnerId;
            }
            $this->redirect($url);
        }

    }

}
