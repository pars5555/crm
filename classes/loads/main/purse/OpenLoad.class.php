<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\purse {

    use crm\loads\AdminLoad;
    use crm\managers\AttachmentManager;
    use crm\managers\PurseOrderManager;
    use NGS;

    class OpenLoad extends AdminLoad {

        public function load() {
            $orderId = NGS()->args()->id;
            $order = PurseOrderManager::getInstance()->selectByPk($orderId);
            $this->addParam('order', $order);
            $attachments = AttachmentManager::getInstance()->getEntityAttachments($orderId, 'btc');
            $checkoutAttachments = AttachmentManager::getInstance()->getEntityAttachments($orderId, 'checkout');
            $this->addParam('attachments', array_merge($attachments, $checkoutAttachments));
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/purse/open.tpl";
        }

    }

}
