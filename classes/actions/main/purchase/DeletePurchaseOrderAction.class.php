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
    use crm\managers\PurchaseOrderManager;
    use NGS;

    class DeletePurchaseOrderAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $purchaseOrderId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Purchase Order ID is missing';
                $this->redirect('purchase/list');
            }
            $purchaseOrderManager = PurchaseOrderManager::getInstance();
            $purchaseOrderManager->deleteByPK($purchaseOrderId);
            $_SESSION['success_message'] = 'Purchase Order Successfully deleted!';
            $this->redirect('purchase/list');
        }

    }

}
