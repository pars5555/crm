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

    class DeleteRecipientOrderAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $recipientOrderId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Order ID is missing';
                $this->redirect('rorder/list');
            }
            $recipientOrderManager = RecipientOrderManager::getInstance();
            $recipientOrderManager->delete($recipientOrderId);
            $_SESSION['success_message'] = 'Order Successfully deleted!';
            if (strpos($_SERVER['HTTP_REFERER'], 'rorder/list') === false) {
                $this->redirect('rorder/list');
            } else {
                $this->redirectToReferer();
            }
        }

    }

}
