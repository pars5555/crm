<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\giftcards {

    use crm\loads\AdminLoad;
    use crm\managers\AttachmentManager;
    use crm\managers\GiftCardsManager;
    use NGS;

    class OpenLoad extends AdminLoad {

        public function load() {
            $rowId = NGS()->args()->id;
            $gc = GiftCardsManager::getInstance()->selectByPk($rowId);
            $this->addParam('gc', $gc);
            $attachments = AttachmentManager::getInstance()->getEntityAttachments($rowId, 'giftcard');
            $this->addParam('attachments', $attachments);
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/giftcards/open.tpl";
        }

    }

}
