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
    use crm\managers\PurchaseOrderLineManager;
    use NGS;

    class SavePurchaseOrderLinesAction extends BaseAction {

        public function service() {
            if (!isset(NGS()->args()->purchase_order_id)) {
                $_SESSION['error_message'] = 'Purchase Order ID is missing';
                $this->redirect('purchase/list');
            }
            $purchaseOrderId = intval(NGS()->args()->purchase_order_id);
            if (isset(NGS()->args()->lines)) {
                $jsonLinesArray = NGS()->args()->lines;
                PurchaseOrderLineManager::getInstance()->deleteByField('purchase_order_id', $purchaseOrderId);
                if (!empty($jsonLinesArray)) {
                    foreach ($jsonLinesArray as $jsonLine) {
                        $line = json_decode($jsonLine);
                        PurchaseOrderLineManager::getInstance()->createPurchaseOrderLine($purchaseOrderId, $line->product_id, $line->quantity, $line->unit_price, $line->currency_id);
                    }
                }
            } else {
                PurchaseOrderLineManager::getInstance()->deleteByField('purchase_order_id', $purchaseOrderId);
            }
            $_SESSION['success_message'] = 'Purchase Order lines successfully saved.';
            $this->redirect('purchase/' . $purchaseOrderId);
        }

    }

}