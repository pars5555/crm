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

    class SetPaidAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $preorderId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Preorder Order ID is missing';
                $this->redirect('preorder/list');
            }
            if (isset(NGS()->args()->paid)) {
                $paid = NGS()->args()->paid;
            } else {
                $_SESSION['error_message'] = 'Paid parameter is missing';
                $this->redirect('preorder/' . NGS()->args()->id);
            }
            $preorderManager = PreorderManager::getInstance();
            $preorderDto = $preorderManager->selectByPk($preorderId);
            if (!isset($preorderDto)) {
                $_SESSION['error_message'] = 'Preorder Order with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('preorder/list');
            }
            $preorderManager->setPaid($preorderId, $paid);
        }

    }

}
