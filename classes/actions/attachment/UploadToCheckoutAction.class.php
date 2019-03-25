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

    class UploadToCheckoutAction extends BaseAction {

        public function service() {
            $partnerId = intval(NGS()->args()->partner_id);
            $entityName = trim(NGS()->args()->entity_name);
            $entityId = intval(NGS()->args()->entity_id);
            
            $order = \crm\managers\PurseOrderManager::getInstance()->selectByPk($entityId);
            $checkoutOrderId = $order->getCheckoutOrderId();
            if (empty($checkoutOrderId)){
                return;
            }

            $filePath = $_FILES['file']['tmp_name'];
            $fileName = mb_strtolower($_FILES['file']['name']);
            $attachmentDirectory = CHECKOUT_DATA_DIR . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . $checkoutOrderId . DIRECTORY_SEPARATOR . 'pictures';
            if (!is_dir(CHECKOUT_DATA_DIR . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . $checkoutOrderId)) {
                mkdir(CHECKOUT_DATA_DIR . DIRECTORY_SEPARATOR . 'orders' . DIRECTORY_SEPARATOR . $checkoutOrderId, 0777);
            }
            if (!is_dir($attachmentDirectory)) {
                mkdir($attachmentDirectory, 0777);
            }
            $array = explode('.', $fileName);
            $extension = end($array);
            $fname = str_replace('.', '', uniqid('chk_', true)) . '.' . $extension;

            $rowId = \crm\managers\AttachmentManager::getInstance()->addRow($partnerId, $entityName, $entityId, $fileName, $fname);
            $fileFullPathToMove = $attachmentDirectory . DIRECTORY_SEPARATOR . $fname;
            $jsonData = '{"attachment_id":' . $rowId . '}';
            echo "<script>parent.attachmentUploadTarget($jsonData);</script>";
            move_uploaded_file($filePath, $fileFullPathToMove);
            exit;
        }

    }

}
