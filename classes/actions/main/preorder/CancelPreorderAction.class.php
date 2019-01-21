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
    use crm\exceptions\InsufficientProductException;
    use crm\managers\ProductManager;
    use crm\managers\PreorderManager;
    use NGS;

    class CancelPreorderAction extends BaseAction {

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
            if ($preorderDto->getCancelled() == 1) {
                $_SESSION['error_message'] = 'Preorder Order with ID ' . NGS()->args()->id . ' is already cancelled.';
                $this->redirect('preorder/list');
            }
            $note = NGS()->args()->note;
            $preorderManager->cancelPreorder($preorderId, $note);
            try {
                $_SESSION['success_message'] = 'Preorder Order Successfully cancelled!';
            } catch (InsufficientProductException $exc) {
                $preorderManager->restorePreorder($preorderId);
                $productDto = ProductManager::getInstance()->selectByPk($exc->getProductId());
                $_SESSION['error_message'] = $productDto->getName() . ' product insufficient!';
            }
            $this->redirectToReferer();
        }

    }

}
