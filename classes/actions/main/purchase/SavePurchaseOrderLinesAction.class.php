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
    use crm\managers\PurchaseOrderLineManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\SaleOrderManager;
    use NGS;

    class SavePurchaseOrderLinesAction extends BaseAction {

        public function service() {
             ini_set('memory_limit','3G');
            set_time_limit(0);
            if (!isset(NGS()->args()->purchase_order_id)) {
                $_SESSION['error_message'] = 'Purchase Order ID is missing';
                $this->redirect('purchase/list');
            }
            $purchaseOrderId = intval(NGS()->args()->purchase_order_id);
            try {
                if (isset(NGS()->args()->lines)) {
                    $jsonLinesArray = NGS()->args()->lines;
                    $linesIdsToNotDelete = [];
                    if (!empty($jsonLinesArray)) {
                        foreach ($jsonLinesArray as $jsonLine) {
                            $line = json_decode($jsonLine);
                            if (isset($line->line_id)) {
                                $linesIdsToNotDelete[] = $line->line_id;
                                PurchaseOrderLineManager::getInstance()->updatePurchaseOrderLine($purchaseOrderId, $line->line_id, $line->product_id, $line->quantity, $line->unit_price, $line->currency_id);
                            } else {
                                $newLineId = PurchaseOrderLineManager::getInstance()->createPurchaseOrderLine($purchaseOrderId, $line->product_id, $line->quantity, $line->unit_price, $line->currency_id);
                                $linesIdsToNotDelete[] = $newLineId;
                            }
                        }
                    }
                    PurchaseOrderLineManager::getInstance()->deleteWhereIdNotIdIds($purchaseOrderId, $linesIdsToNotDelete);
                } else {
                    PurchaseOrderLineManager::getInstance()->deleteByField('purchase_order_id', $purchaseOrderId);
                }
            } catch (InsufficientProductException $exc) {
                $product = \crm\managers\ProductManager::getInstance()->selectByPk($exc->getProductId());
                $productInfo = $product->getId();
                if (isset($product)) {
                    $productInfo = $product->getName() . " (" . $product->getId() . ")";
                }
                $_SESSION['error_message'] = "Insufficient Product: " . $productInfo;
                $this->redirect('purchase/' . $purchaseOrderId);
            } catch (Exception $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $this->redirect('purchase/' . $purchaseOrderId);
            }

            $_SESSION['success_message'] = 'Purchase Order lines successfully saved.';
            $this->redirect('purchase/warranty/' . $purchaseOrderId);
        }

    }

}
