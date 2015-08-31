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

namespace crm\actions\main\sale\warranty {

    use crm\actions\BaseAction;
    use crm\managers\SaleOrderLineSerialNumberManager;
    use NGS;

    class SaveSaleOrderLinesSerialNumbersAction extends BaseAction {

        public function service() {
            if (!isset(NGS()->args()->sale_order_id)) {
                $_SESSION['error_message'] = 'Sale Order ID is missing';
                $this->redirect('sale/list');
            }
            $saleOrderId = intval(NGS()->args()->sale_order_id);

            if (!isset(NGS()->args()->pols_serial_numbers)) {
                $_SESSION['error_message'] = 'Serial Numbers parameter is missing';
                $this->redirect('sale/warranty/' . $saleOrderId);
            }
            $pols_serial_numbers_json = NGS()->args()->pols_serial_numbers;
            $pols_serial_numbers = json_decode($pols_serial_numbers_json);
            foreach ($pols_serial_numbers as $obj) {
                $pol_id = $obj->pol_id;
                $serial_numbers = $obj->serial_numbers;
                $warranty_months = $obj->warranty_months;
                SaleOrderLineSerialNumberManager::getInstance()->setSaleOrderListSerialNumbers($pol_id, $serial_numbers, $warranty_months);
            }

            $_SESSION['success_message'] = 'Serial Numbers successfully saved.';
            $this->redirect('sale/warranty/' . $saleOrderId);
        }

    }

}
