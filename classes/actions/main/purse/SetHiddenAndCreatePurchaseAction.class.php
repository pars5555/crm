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

namespace crm\actions\main\purse {

    use crm\actions\BaseAction;
    use crm\managers\ProductManager;
    use crm\managers\PurchaseOrderLineManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\PurseOrderManager;
    use crm\managers\SettingManager;
    use NGS;

    class SetHiddenAndCreatePurchaseAction extends BaseAction {

        public function service() {
            $id = intval(NGS()->args()->id);
            
            $products = json_decode(NGS()->args()->products);
            $partnerId = SettingManager::getInstance()->getSetting('external_supplier_partner_id');
            $external_1kg_shipping_cost = floatval(SettingManager::getInstance()->getSetting('external_1kg_shipping_cost'));
            $poId = false;
            foreach ($products as $product) {
                $productId = intval($product->product_id);
                if ($productId == -1) {
                    continue;
                }
                $tax = floatval($product->tax);
                if (!$poId){
                    $poId = PurchaseOrderManager::getInstance()->createPurchaseOrder(
                        $partnerId, date('Y-m-d H:i:s'), date('Y-m-d'), 'Purchase order for external order id: ' . $id, 1, $id);
                }
                if ($productId == 0) {
                    $productId = ProductManager::getInstance()->createProduct(
                            $product->name, "", "unknown", 1, floatval($product->weight));
                } else {
                    ProductManager::getInstance()->updateProductWeight($productId, floatval($product->weight));
                }
                $shippingPriceForOneUnit = $external_1kg_shipping_cost * floatval($product->weight);
                $unitPrice = floatval($product->price) + $shippingPriceForOneUnit + $tax;
                PurchaseOrderLineManager::getInstance()->createPurchaseOrderLine(
                        $poId, $productId, $product->quantity, $unitPrice, 1);
            }

            PurseOrderManager::getInstance()->updateField($id, 'hidden_at', date('Y-m-d H:i:s'));
            PurseOrderManager::getInstance()->updateField($id, 'hidden', 1);
            $this->addParam('id', $id);
        }

    }

}
