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
    use crm\exceptions\InsufficientProductException;
    use crm\managers\SaleOrderLineManager;
    use crm\managers\SaleOrderManager;
    use NGS;

    class SaveSaleOrderLinesAction extends BaseAction {

        public function service() {
             ini_set('memory_limit','3G');
            set_time_limit(0);
            if (!isset(NGS()->args()->sale_order_id)) {
                $_SESSION['error_message'] = 'Sale Order ID is missing';
                $this->redirect('sale/list');
            }
            $saleOrderId = intval(NGS()->args()->sale_order_id);
            $createdFromPurchaseOrder = false;
            if (isset(NGS()->args()->poid) && intval(NGS()->args()->poid) > 1) {
                $createdFromPurchaseOrder = true;
            }
            try {
                SaleOrderLineManager::getInstance()->startTransaction();
                if (isset(NGS()->args()->lines)) {
                    $jsonLinesArray = NGS()->args()->lines;
                    $linesIdsToNotDelete = [];
                    if (!empty($jsonLinesArray)) {
                        foreach ($jsonLinesArray as $jsonLine) {
                            $line = json_decode($jsonLine);
                            if (isset($line->line_id) && !$createdFromPurchaseOrder) {
                                $linesIdsToNotDelete[] = $line->line_id;
                                SaleOrderLineManager::getInstance()->updateSaleOrderLine($saleOrderId, $line->line_id, $line->product_id, $line->quantity, $line->unit_price, $line->currency_id);
                            } else {
                                $newLineId = SaleOrderLineManager::getInstance()->createSaleOrderLine($saleOrderId, $line->product_id, $line->quantity, $line->unit_price, $line->currency_id);
                                $linesIdsToNotDelete[] = $newLineId;
                            }
                        }
                    }
                    SaleOrderLineManager::getInstance()->deleteWhereIdNotIdIds($saleOrderId, $linesIdsToNotDelete);
                } else {
                    SaleOrderLineManager::getInstance()->deleteByField('sale_order_id', $saleOrderId);
                }
                //SaleOrderManager::getInstance()->updateAllDependingSaleOrderLines($saleOrderId);
                SaleOrderManager::getInstance()->commitTransaction();
            } catch (InsufficientProductException $exc) {
                SaleOrderManager::getInstance()->rollbackTransaction();
                $product = \crm\managers\ProductManager::getInstance()->selectByPk($exc->getProductId());
                $productInfo = $product->getId();
                if (isset($product)) {
                    $productInfo = $product->getName() . " (" . $product->getId() . ")";
                }
                $_SESSION['error_message'] = "Insufficient Product: " . $productInfo;
                $this->redirect('sale/' . $saleOrderId);
            } catch (Exception $exc) {
                SaleOrderManager::getInstance()->rollbackTransaction();
                $_SESSION['error_message'] = $exc->getMessage();
                $this->redirect('sale/' . $saleOrderId);
            }
            $this->redirect('sale/warranty/' . $saleOrderId);
        }

    }

}
