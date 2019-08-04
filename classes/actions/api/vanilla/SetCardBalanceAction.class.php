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
            $id = intval(NGS()->args()->card_id);
            $message = trim(NGS()->args()->message);

            if (!empty($message) && strpos($message, 'valid')) {
                $card = VanillaCardsManager::getInstance()->selectByPK($id);
                $manager = new \naffiq\telegram\channel\Manager($telegramToken, $telegramCrmChannelId);
                $manager->postMessage('****' . substr($card->getNumber(), -6) . ' is invalid!');
                VanillaCardsManager::getInstance()->updateField($id, 'updated_at', date('Y-m-d H:i:s'));
                $this->addParam('success', true);
                return;
            }

            if (!empty($message) && strpos($message, 'issue with your account')) {
                $card = VanillaCardsManager::getInstance()->selectByPK($id);
                $tryCount = intval($card->getTryCount());
                if ($tryCount < 10) {
                    VanillaCardsManager::getInstance()->updateField($id, 'try_count', $tryCount + 1);
                    $this->addParam('success', true);
                    return;
                }
                if ($card->getClosed() == 0) {
                    $manager = new \naffiq\telegram\channel\Manager($telegramToken, $telegramCrmChannelId);
                    $note = trim($card->getNote());
                    if (!empty($note)) {
                        $note = ' note: ' . $note;
                    }
                    $manager->postMessage('****' . substr($card->getNumber(), -6) . ' is closed! last available balance was: $' . $card->getBalance() . ' initial balance was: ' . $card->getInitialBalance() . $note . ' card supplied at: ' . $card->getCreatedAt());
                    VanillaCardsManager::getInstance()->updateField($id, 'closed', 1);
                    VanillaCardsManager::getInstance()->updateField($id, 'updated_at', date('Y-m-d H:i:s'));
                    return;
                }
            }

            $balance = floatval(trim(trim(NGS()->args()->balance),'$'));
            $transaction_history = trim(urldecode(trim(NGS()->args()->transaction_history)));
            $transaction_history = preg_replace('/\s+/', ' ', $transaction_history);
            $transaction_history = preg_replace('^\\d{1,2}/\\d{2}/\\d{4}^', "\r\n", $transaction_history);
            VanillaCardsManager::getInstance()->updateField($id, 'updated_at', date('Y-m-d H:i:s'));
            $card = VanillaCardsManager::getInstance()->selectByPK($id);
            if ($balance >= $vanilla_telegram_notification_min_balance) {
                $manager = new \naffiq\telegram\channel\Manager($telegramToken, $telegramCrmChannelId);
                $note = trim($card->getNote());
                if (!empty($note)) {
                    $note = ' note: ' . $note;
                }
                $manager->postMessage('****' . substr($card->getNumber(), -6) . ' balance is: $' . $balance . $note . ' card supplied at: ' . $card->getCreatedAt());
            }
            if ($card->getInitialBalance() == null) {
                VanillaCardsManager::getInstance()->updateField($id, 'initial_balance', $balance);
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
    