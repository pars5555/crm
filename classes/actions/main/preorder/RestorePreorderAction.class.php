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

namespace crm\actions\main\preorder {

use crm\actions\BaseAction;
use crm\managers\PreorderManager;
use NGS;

    class RestorePreorderAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $preorderId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Preorder Order ID is missing';
                $this->redirect('preorder/list');
            }
            $preorderManager = PreorderManager::getInstance();
            $preorderDto = $preorderManager->selectByPk($preorderId);
            if (!isset($preorderDto)) {
                $_SESSION['error_message'] = 'Preorder Order with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('preorder/list');
            }
            if ($preorderDto->getCancelled() == 0) {
                $_SESSION['error_message'] = 'Preorder Order with ID ' . NGS()->args()->id . ' is not cancelled.';
                $this->redirect('preorder/list');
            }
            $preorderManager->restorePreorder($preorderId);
            $_SESSION['success_message'] = 'Preorder Order Successfully restored!';
            $this->redirectToReferer();
        }

    }

}
