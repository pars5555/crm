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
    use crm\managers\RecipientManager;
    use NGS;

    class RefreshRecipientAction extends BaseAction {

        public function service() {
            $id = intval(NGS()->args()->id);
            $order = PurseOrderManager::getInstance()->selectByPk($id);
            $recipient = RecipientManager::getInstance()->getRecipientByUnitAddress($order->getUnitAddress());
            if (!empty($recipient)) {
                PurseOrderManager::getInstance()->updateField(
                        $order->getId(), 'recipient_name', $recipient->getFirstName() . ' ' . $recipient->getLastName());
            }
            $this->addParam('id', $id);
        }

    }

}
