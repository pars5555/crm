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

namespace crm\actions\main\purchase {

    use crm\actions\BaseAction;
    use crm\exceptions\InsufficientProductException;
    use crm\managers\ProductManager;
    use crm\managers\PurchaseOrderManager;
    use NGS;

    class CancelPurchaseOrderAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $purchaseOrderId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Purchase Order ID is missing';
                $this->redirect('purchase/list');
            }
            $purchaseOrderManager = PurchaseOrderManager::getInstance();
            $purchaseOrderDto = $purchaseOrderManager->selectByPK($purchaseOrderId);
            if (!isset($purchaseOrderDto)) {
                $_SESSION['error_message'] = 'Purchase Order with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('purchase/list');
            }
            if ($purchaseOrderDto->getCancelled() == 1) {
                $_SESSION['error_message'] = 'Purchase Order with ID ' . NGS()->args()->id . ' is already cancelled.';
                $this->redirect('purchase/list');
            }
            $note = NGS()->args()->note;
            $purchaseOrderManager->cancelPurchaseOrder($purchaseOrderId, $note);
            try {
                PurchaseOrderManager::getInstance()->updateAllDependingSaleOrderLines($purchaseOrderId);
                $_SESSION['success_message'] = 'Purchase Order Successfully cancelled!';
            } catch (InsufficientProductException $exc) {
                $purchaseOrderManager->restorePurchaseOrder($purchaseOrderId);
                $productDto = ProductManager::getInstance()->selectByPK($exc->getProductId());
                $_SESSION['error_message'] = $productDto->getName() . ' product insufficient!';
            }
            $this->redirectToReferer();
        }

    }

}
