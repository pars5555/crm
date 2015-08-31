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

namespace crm\actions\main\purchase\warranty {

use crm\actions\BaseAction;
use crm\managers\PurchaseOrderLineSerialNumberManager;
use NGS;

    class SavePurchaseOrderLinesSerialNumbersAction extends BaseAction {

        public function service() {
            if (!isset(NGS()->args()->purchase_order_id)) {
                $_SESSION['error_message'] = 'Purchase Order ID is missing';
                $this->redirect('purchase/list');
            }
            $purchaseOrderId = intval(NGS()->args()->purchase_order_id);
            
            if (!isset(NGS()->args()->pols_serial_numbers))
            {                
                $_SESSION['error_message'] = 'Purchase Order ID is missing';
                $this->redirect('purchase/warranty/' . $purchaseOrderId);
            }
            $pols_serial_numbers_json = NGS()->args()->pols_serial_numbers;
            $pols_serial_numbers = json_decode($pols_serial_numbers_json);
            foreach ($pols_serial_numbers as $obj) {
                $pol_id = $obj->pol_id;
                $serial_numbers =$obj->serial_numbers;
                PurchaseOrderLineSerialNumberManager::getInstance()->setPurchaseOrderListSerialNumbers($pol_id, $serial_numbers);
            }

            $_SESSION['success_message'] = 'Serial Numbers successfully saved.';
            $this->redirect('purchase/warranty/' . $purchaseOrderId);
        }

    }

}
