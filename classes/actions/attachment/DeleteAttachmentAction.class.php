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

namespace crm\actions\attachment {

    use crm\actions\BaseAction;

    class DeleteAttachmentAction extends BaseAction {

        public function service() {
            $id = NGS()->args()->id;
            $attachment = \crm\managers\AttachmentManager::getInstance()->selectByPk($id);
            if (empty($attachment)) {
                die("File not found");
            }
            if ($attachment->getEntityName() === 'checkout') {
                $order = \crm\managers\PurseOrderManager::getInstance()->selectByPk($attachment->getEntityId());
                $checkoutOrderId = $order->getCheckoutOrderId();
                if (empty($checkoutOrderId)) {
                    return;
                }
                $file = CHECKOUT_DATA_DIR . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . $checkoutOrderId . DIRECTORY_SEPARATOR . 'pictures' . DIRECTORY_SEPARATOR . $attachment->getFileName();
            } else {
                $file = DATA_DIR . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $attachment->getEntityName() . DIRECTORY_SEPARATOR . $attachment->getFileName();
            }
            unlink($file);
            \crm\managers\AttachmentManager::getInstance()->deleteByPk($id);
        }

    }

}
