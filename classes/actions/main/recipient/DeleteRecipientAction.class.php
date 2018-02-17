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
use crm\managers\PecipientManager;
use NGS;

    class DeletePecipientAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $recipientId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Pecipient ID is missing';
                $this->redirect('recipient/list');
            }
            $recipientManager = PecipientManager::getInstance();
            $recipientDto = $recipientManager->selectByPK($recipientId);
            if (!isset($recipientDto)) {
                $_SESSION['error_message'] = 'Pecipient with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('recipient/list');
            }
            $recipientManager->deletePecipientFull($recipientId);
            $_SESSION['success_message'] = 'Pecipient Successfully deleted!';
            $this->redirect('recipient/list');
        }

    }

}
