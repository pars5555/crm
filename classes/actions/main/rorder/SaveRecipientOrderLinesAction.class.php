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

namespace crm\actions\main\rorder {

    use crm\actions\BaseAction;
    use crm\exceptions\InsufficientProductException;
    use crm\managers\ProductManager;
    use crm\managers\RecipientOrderLineManager;
    use crm\managers\RecipientOrderManager;
    use crm\managers\SaleOrderManager;
    use NGS;

    class SaveRecipientOrderLinesAction extends BaseAction {

        public function service() {
            if (!isset(NGS()->args()->recipient_order_id)) {
                $_SESSION['error_message'] = 'Order ID is missing';
                $this->redirect('rorder/list');
            }
            $recipientOrderId = intval(NGS()->args()->recipient_order_id);
            try {
                if (isset(NGS()->args()->lines)) {
                    $jsonLinesArray = NGS()->args()->lines;
                    $linesIdsToNotDelete = [];
                    if (!empty($jsonLinesArray)) {
                        foreach ($jsonLinesArray as $jsonLine) {
                            $line = json_decode($jsonLine);
                            if (isset($line->line_id)) {
                                $linesIdsToNotDelete[] = $line->line_id;
                                RecipientOrderLineManager::getInstance()->updateRecipientOrderLine($recipientOrderId, $line->line_id, $line->product_id, $line->quantity, $line->unit_price, $line->currency_id);
                            } else {
                                $newLineId = RecipientOrderLineManager::getInstance()->createRecipientOrderLine($recipientOrderId, $line->product_id, $line->quantity, $line->unit_price, $line->currency_id);
                                $linesIdsToNotDelete[] = $newLineId;
                            }
                        }
                    }
                    RecipientOrderLineManager::getInstance()->deleteWhereIdNotIdIds($recipientOrderId, $linesIdsToNotDelete);
                } else {
                    RecipientOrderLineManager::getInstance()->deleteByField('recipient_order_id', $recipientOrderId);
                }
            } catch (InsufficientProductException $exc) {
                $product = \crm\managers\ProductManager::getInstance()->selectByPK($exc->getProductId());
                $productInfo = $product->getId();
                if (isset($product)) {
                    $productInfo = $product->getName() . " (" . $product->getId() . ")";
                }
                $_SESSION['error_message'] = "Insufficient Product: " . $productInfo;
                $this->redirect('rorder/' . $recipientOrderId);
            } catch (Exception $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $this->redirect('rorder/' . $recipientOrderId);
            }

            $_SESSION['success_message'] = 'Order lines successfully saved.';
            RecipientOrderManager::getInstance()->updateAllDependingSaleOrderLines($recipientOrderId);
            $this->redirect('rorder/list/' . $recipientOrderId);
        }

    }

}
