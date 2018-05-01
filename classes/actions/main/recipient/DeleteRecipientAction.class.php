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

namespace crm\actions\main\recipient {

use crm\actions\BaseAction;
use crm\managers\RecipientManager;
use NGS;

    class DeleteRecipientAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $recipientId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Recipient ID is missing';
                $this->redirect('recipient/list');
            }
            $recipientManager = RecipientManager::getInstance();
            $recipientDto = $recipientManager->selectByPK($recipientId);
            if (!isset($recipientDto)) {
                $_SESSION['error_message'] = 'Recipient with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('recipient/list');
            }
            $recipientManager->deleteRecipientFull($recipientId);
            $_SESSION['success_message'] = 'Recipient Successfully deleted!';
            $this->redirect('recipient/list');
        }

    }

}
