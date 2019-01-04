<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\recipient {

    use crm\loads\AdminLoad;
    use crm\managers\AttachmentManager;
    use crm\managers\CurrencyManager;
    use crm\managers\RecipientManager;
    use crm\managers\RecipientOrderManager;
    use NGS;

    class OpenLoad extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $recipientId = intval(NGS()->args()->id);
            $recipient = RecipientManager::getInstance()->getRecipientFull($recipientId);
            $currencyManager = CurrencyManager::getInstance();
            if ($recipient) {
                $this->addParam('recipient', $recipient);
                $recipientOrders = RecipientOrderManager::getInstance()->getRecipientOrders($recipientId);
                $this->addParam('recipientOrders', $recipientOrders);
                $this->addParam('currencies', $currencyManager->mapDtosById($currencyManager->selectAdvance('*', ['active', '=', 1], ['name'])));

                $attachments = AttachmentManager::getInstance()->getEntityAttachments($recipientId, 'recipient');
                $this->addParam('attachments', $attachments);
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/recipient/open.tpl";
        }

    }

}
