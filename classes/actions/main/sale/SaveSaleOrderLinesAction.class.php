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

namespace crm\actions\main\sale {

    use crm\actions\BaseAction;
    use crm\managers\SaleOrderLineManager;
    use crm\managers\SaleOrderLineSerialNumberManager;
    use NGS;

    class SaveSaleOrderLinesAction extends BaseAction {

        public function service() {
            if (!isset(NGS()->args()->sale_order_id)) {
                $_SESSION['error_message'] = 'Sale Order ID is missing';
                $this->redirect('sale/list');
            }
            $saleOrderId = intval(NGS()->args()->sale_order_id);
            if (isset(NGS()->args()->lines)) {
                $jsonLinesArray = NGS()->args()->lines;
                SaleOrderLineManager::getInstance()->deleteByField('sale_order_id', $saleOrderId);
                if (!empty($jsonLinesArray)) {
                    foreach ($jsonLinesArray as $jsonLine) {
                        $line = json_decode($jsonLine);
                        $newLineId = SaleOrderLineManager::getInstance()->createSaleOrderLine($saleOrderId, $line->product_id, $line->quantity, $line->unit_price, $line->currency_id);
                        if (isset($line->line_id)) {
                            SaleOrderLineSerialNumberManager::getInstance()->replaceLineId($line->line_id, $newLineId);
                        }
                    }
                }
            } else {
                SaleOrderLineManager::getInstance()->deleteByField('sale_order_id', $saleOrderId);
            }
            $_SESSION['success_message'] = 'Sale Order lines successfully saved.';
            $this->redirect('sale/warranty/' . $saleOrderId);
        }

    }

}
