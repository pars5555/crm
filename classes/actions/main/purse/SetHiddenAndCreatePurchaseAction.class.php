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
    use crm\managers\PurchaseOrderLineManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\SettingManager;
    use NGS;

    class SetHiddenAndCreatePurchaseAction extends BaseAction {

        public function service() {
            $id = intval(NGS()->args()->id);
            $hide = intval(NGS()->args()->hide);
            $products = json_decode(NGS()->args()->products);
            $partnerId = SettingManager::getInstance()->getSetting('external_supplier_partner_id');
            $external_1kg_shipping_cost = floatval(SettingManager::getInstance()->getSetting('external_1kg_shipping_cost'));
            $poId = PurchaseOrderManager::getInstance()->createPurchaseOrder(
                    $partnerId, date('Y-m-d H:i:s'), date('Y-m-d'), 'Purchase order for external order id: ' . $id);
            foreach ($products as $product) {
                $shippingPriceForOneUnit = $external_1kg_shipping_cost * floatval($product->weight);
                $unitPrice = floatval($product->price) + $shippingPriceForOneUnit;
                PurchaseOrderLineManager::getInstance()->createPurchaseOrderLine(
                        $poId, $product->product_id, $product->quantity, $unitPrice, $currencyId);
            }

            //PurseOrderManager::getInstance()->updateField($id, 'hidden_at', date('Y-m-d H:i:s'));
            //PurseOrderManager::getInstance()->updateField($id, 'hidden', $hide);
        }

    }

}
