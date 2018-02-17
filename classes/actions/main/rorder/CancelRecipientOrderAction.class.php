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

namespace crm\actions\main\rorder {

    use crm\actions\BaseAction;
    use crm\exceptions\InsufficientProductException;
    use crm\managers\ProductManager;
    use crm\managers\RecipientOrderManager;
    use NGS;

    class CancelRecipientOrderAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $recipientOrderId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Recipient Order ID is missing';
                $this->redirect('rorder/list');
            }
            $recipientOrderManager = RecipientOrderManager::getInstance();
            $recipientOrderDto = $recipientOrderManager->selectByPK($recipientOrderId);
            if (!isset($recipientOrderDto)) {
                $_SESSION['error_message'] = 'Recipient Order with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('rorder/list');
            }
            if ($recipientOrderDto->getCancelled() == 1) {
                $_SESSION['error_message'] = 'Recipient Order with ID ' . NGS()->args()->id . ' is already cancelled.';
                $this->redirect('rorder/list');
            }
            $note = NGS()->args()->note;
            $recipientOrderManager->cancelRecipientOrder($recipientOrderId, $note);
            try {
                RecipientOrderManager::getInstance()->updateAllDependingSaleOrderLines($recipientOrderId);
                $_SESSION['success_message'] = 'Recipient Order Successfully cancelled!';
            } catch (InsufficientProductException $exc) {
                $recipientOrderManager->restoreRecipientOrder($recipientOrderId);
                $productDto = ProductManager::getInstance()->selectByPK($exc->getProductId());
                $_SESSION['error_message'] = $productDto->getName() . ' product insufficient!';
            }
            $this->redirectToReferer();
        }

    }

}
