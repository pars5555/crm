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

    class SetPaidAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $purchaseOrderId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Purchase Order ID is missing';
                $this->redirect('purchase/list');
            }
            if (isset(NGS()->args()->paid)) {
                $paid = NGS()->args()->paid;
            } else {
                $_SESSION['error_message'] = 'Paid parameter is missing';
                $this->redirect('purchase/' . NGS()->args()->id);
            }
            $purchaseOrderManager = PurchaseOrderManager::getInstance();
            $purchaseOrderDto = $purchaseOrderManager->selectByPk($purchaseOrderId);
            if (!isset($purchaseOrderDto)) {
                $_SESSION['error_message'] = 'Purchase Order with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('purchase/list');
            }
            $purchaseOrderManager->setPaid($purchaseOrderId, $paid);
        }

    }

}
