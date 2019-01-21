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

namespace crm\actions\main\preorder {

    use crm\actions\BaseAction;
    use crm\exceptions\InsufficientProductException;
    use crm\managers\ProductManager;
    use crm\managers\PreorderLineManager;
    use crm\managers\PreorderManager;
    use crm\managers\SaleOrderManager;
    use NGS;

    class SavePreorderLinesAction extends BaseAction {

        public function service() {
            if (!isset(NGS()->args()->preorder_id)) {
                $_SESSION['error_message'] = 'Preorder Order ID is missing';
                $this->redirect('preorder/list');
            }
            $preorderId = intval(NGS()->args()->preorder_id);
            try {
                if (isset(NGS()->args()->lines)) {
                    $jsonLinesArray = NGS()->args()->lines;
                    $linesIdsToNotDelete = [];
                    if (!empty($jsonLinesArray)) {
                        foreach ($jsonLinesArray as $jsonLine) {
                            $line = json_decode($jsonLine);
                            if (isset($line->line_id)) {
                                $linesIdsToNotDelete[] = $line->line_id;
                                PreorderLineManager::getInstance()->updatePreorderLine($preorderId, $line->line_id, $line->product_id, $line->quantity, $line->unit_price, $line->currency_id);
                            } else {
                                $newLineId = PreorderLineManager::getInstance()->createPreorderLine($preorderId, $line->product_id, $line->quantity, $line->unit_price, $line->currency_id);
                                $linesIdsToNotDelete[] = $newLineId;
                            }
                        }
                    }
                    PreorderLineManager::getInstance()->deleteWhereIdNotIdIds($preorderId, $linesIdsToNotDelete);
                } else {
                    PreorderLineManager::getInstance()->deleteByField('preorder_id', $preorderId);
                }
            } catch (InsufficientProductException $exc) {
                $product = \crm\managers\ProductManager::getInstance()->selectByPk($exc->getProductId());
                $productInfo = $product->getId();
                if (isset($product)) {
                    $productInfo = $product->getName() . " (" . $product->getId() . ")";
                }
                $_SESSION['error_message'] = "Insufficient Product: " . $productInfo;
                $this->redirect('preorder/' . $preorderId);
            } catch (Exception $exc) {
                $_SESSION['error_message'] = $exc->getMessage();
                $this->redirect('preorder/' . $preorderId);
            }

            $_SESSION['success_message'] = 'Preorder Order lines successfully saved.';
            $this->redirect('preorder/list?srt=order_date&ascdesc=DESC');
        }

    }

}
