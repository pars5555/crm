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

    class DeletePreorderAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $preorderId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Preorder Order ID is missing';
                $this->redirect('preorder/list');
            }
            $preorderManager = PreorderManager::getInstance();
            $preorderManager->deleteByPK($preorderId);
            $_SESSION['success_message'] = 'Preorder Order Successfully deleted!';
            if (strpos($_SERVER['HTTP_REFERER'], 'preorder/list') === false) {
                $this->redirect('preorder/list');
            } else {
                $this->redirectToReferer();
            }
        }

    }

}
