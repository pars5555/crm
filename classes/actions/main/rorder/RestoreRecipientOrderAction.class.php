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
use crm\managers\RecipientOrderManager;
use NGS;

    class RestoreRecipientOrderAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $recipientOrderId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Order ID is missing';
                $this->redirect('rorder/list');
            }
            $recipientOrderManager = RecipientOrderManager::getInstance();
            $recipientOrderDto = $recipientOrderManager->selectByPK($recipientOrderId);
            if (!isset($recipientOrderDto)) {
                $_SESSION['error_message'] = 'Order with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('rorder/list');
            }
            if ($recipientOrderDto->getCancelled() == 0) {
                $_SESSION['error_message'] = 'Order with ID ' . NGS()->args()->id . ' is not cancelled.';
                $this->redirect('rorder/list');
            }
            $recipientOrderManager->restoreRecipientOrder($recipientOrderId);
            $_SESSION['success_message'] = 'Order Successfully restored!';
            $this->redirectToReferer();
        }

    }

}
