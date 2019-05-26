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
    use crm\managers\SettingManager;
    use crm\managers\VanillaCardsManager;
    use crm\security\RequestGroups;

    class SetCardBalanceAction extends BaseAction {

        public function service() {
            $telegramToken = SettingManager::getInstance()->getSetting('telegram_bot_token');
            $telegramCrmChannelId = SettingManager::getInstance()->getSetting('telegram_crm_channel_id');
            $vanilla_telegram_notification_min_balance = SettingManager::getInstance()->getSetting('vanilla_telegram_notification_min_balance');

            $closedCardIds = trim(NGS()->args()->closed_cards_ids);
            if (!empty($closedCardIds)) {
                $idsArray = explode(',', $closedCardIds);
                foreach ($idsArray as $ccid) {
                    $card = VanillaCardsManager::getInstance()->selectByPK($ccid);
                    VanillaCardsManager::getInstance()->updateField($ccid, 'updated_at', date('Y-m-d H:i:s'));
                    if ($card->getClosed() == 0) {
                        $manager = new \naffiq\telegram\channel\Manager($telegramToken, $telegramCrmChannelId);
                        $manager->postMessage('****'.substr($card->getNumber(),-6) . ' is closed!');
                        VanillaCardsManager::getInstance()->updateField($ccid, 'closed', 1);
                    }
                }
            }

            $id = intval(NGS()->args()->card_id);
            $balance = floatval(NGS()->args()->balance);
            $transaction_history = trim(urldecode(trim(NGS()->args()->transaction_history)));
            $transaction_history = preg_replace('/\s/', ' ', $transaction_history);
            VanillaCardsManager::getInstance()->updateField($id, 'updated_at', date('Y-m-d H:i:s'));
            if (isset(NGS()->args()->skip) && NGS()->args()->skip == 1) {
                $this->addParam('success', true);
                return;
            }

            if ($balance >= $vanilla_telegram_notification_min_balance) {
                $card = VanillaCardsManager::getInstance()->selectByPK($id);
                $manager = new \naffiq\telegram\channel\Manager($telegramToken, $telegramCrmChannelId);
                $manager->postMessage('****'.substr($card->getNumber(),-6) . ' balance is: $' . $balance);
            }
            VanillaCardsManager::getInstance()->updateField($id, 'balance', $balance);
            VanillaCardsManager::getInstance()->updateField($id, 'transaction_history', $transaction_history);
            $this->addParam('success', true);
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
    