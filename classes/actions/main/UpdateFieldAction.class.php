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

namespace crm\actions\main {

    use crm\actions\BaseAction;
    use crm\managers\CheckoutManager;
    use crm\managers\PaymentTransactionManager;
    use crm\managers\ProductCategoryManager;
    use crm\managers\ProductManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\PurseOrderManager;
    use crm\managers\RecipientManager;
    use crm\managers\SaleOrderManager;
    use crm\managers\SettingManager;
    use crm\managers\TranslationManager;
    use crm\managers\WhishlistManager;

    class UpdateFieldAction extends BaseAction {

        public function service() {

            $id = intval(NGS()->args()->id);
            $fieldName = NGS()->args()->field_name;
            $fieldValue = NGS()->args()->field_value;
            $objectType = NGS()->args()->object_type;
            switch ($objectType) {
                case 'sale':
                    $manager = SaleOrderManager::getInstance();
                    break;
                case 'purchase':
                    $manager = PurchaseOrderManager::getInstance();
                    break;
                case 'payment':
                    $manager = PaymentTransactionManager::getInstance();
                    break;
                case 'billing':
                    $manager = TranslationManager::getInstance();
                    break;
                case 'product':
                    $manager = ProductManager::getInstance();
                    break;
                case 'set_setting':
                case 'settings':
                case 'settings_name':
                    $manager = SettingManager::getInstance();
                    break;
                case 'checkout':
                case 'btc':
                    $manager = PurseOrderManager::getInstance();
                    break;
                case 'recipient':
                    $manager = RecipientManager::getInstance();
                    break;
                case 'whishlist':
                    $manager = WhishlistManager::getInstance();
                    break;
            }
            if ($objectType === 'checkout') {
                $this->handleCheckoutOrdersChanged($manager, $id, $fieldName, $fieldValue);
                return;
            }

            if ($objectType === 'set_setting') {
                $manager->setSetting($fieldName, $fieldValue);
                return;
            }
            if ($objectType === 'settings_name') {
                $manager->setSetting($fieldName, $fieldValue);
                $this->addParam('value', $fieldValue);
                $capitalData = json_decode(SettingManager::getInstance()->getSetting('capital_data', '{}'), true);
                $capitalData[$fieldName] = $fieldValue;
                SettingManager::getInstance()->setSetting('capital_data', json_encode($capitalData));
                return;
            }
            $manager->updateField($id, $fieldName, $fieldValue);
            $valueAfterSave = $manager->selectByPk($id);
            $this->addParam('value', $valueAfterSave->$fieldName);
            if ($fieldName === 'category_id' && $objectType === 'product') {
                $this->addParam('display_value', ProductCategoryManager::getInstance()->selectByPk($fieldValue)->getName());
            }
        }

        public function handleCheckoutOrdersChanged($purseManager, $rowId, $fieldName, $fieldValue) {
            $btcOrder = $valueAfterSave = $purseManager->selectByPk($rowId);
            $checkoutOrderId = $btcOrder->getCheckoutOrderId();
            $ret = false;
            switch ($fieldName) {
                case 'tracking_number':
                    $ret = CheckoutManager::getInstance()->setCheckoutOrderTrackingNumber($checkoutOrderId, $fieldValue);
                    break;
                case 'checkout_customer_unit_address':
                    $ret = CheckoutManager::getInstance()->changeCheckoutOrderCustomerUnitAddress($checkoutOrderId, $fieldValue);
                    break;
                case 'amazon_order_number':
                    $ret = CheckoutManager::getInstance()->setAmazonOrderNumber($checkoutOrderId, $fieldValue);
                    break;
                case 'checkout_order_status':
                    $ret = CheckoutManager::getInstance()->setCheckoutOrderStatus($checkoutOrderId, $fieldValue);
                    $this->addParam('display_value', \crm\dal\dto\PurseOrderDto::CHECKOUT_ORDER_STATUSES[intval($fieldValue)]);
                    break;
                default:
                    break;
            }
            if ($ret === true) {
                $this->addParam('value', $fieldValue);
            } else {
                $this->addParam('success', false);
                $this->addParam('message', print_r($ret, true));
            }
        }

    }

}
