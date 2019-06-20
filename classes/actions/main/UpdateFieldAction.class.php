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
use crm\dal\dto\PurseOrderDto;
use crm\managers\CheckoutManager;
use crm\managers\CreditCardsManager;
use crm\managers\GiftCardsManager;
use crm\managers\OnlineShopsManager;
use crm\managers\PaymentTransactionManager;
use crm\managers\ProductCategoryManager;
use crm\managers\ProductManager;
use crm\managers\PurchaseOrderManager;
use crm\managers\PurseOrderManager;
use crm\managers\RecipientManager;
use crm\managers\SaleOrderManager;
use crm\managers\SettingManager;
use crm\managers\TranslationManager;
use crm\managers\VanillaCardsManager;
use crm\managers\VanillaProductsManager;
use crm\managers\WhishlistManager;
use crm\security\RequestGroups;
use NGS;

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
                case 'vproducts':
                    $manager = VanillaProductsManager::getInstance();
                    break;
                case 'checkout':
                case 'btc':
                    $manager = PurseOrderManager::getInstance();
                    break;
                case 'recipient':
                    $manager = RecipientManager::getInstance();
                    break;
                case 'vanilla':
                    $manager = VanillaCardsManager::getInstance();
                    break;
                case 'giftcards':
                    $manager = GiftCardsManager::getInstance();
                    break;
                case 'cc':
                    $manager = CreditCardsManager::getInstance();
                    break;
                case 'online_shop':
                    $manager = OnlineShopsManager::getInstance();
                    break;
                case 'whishlist':
                    $manager = WhishlistManager::getInstance();
                    break;
            }
            if ($objectType === 'checkout') {
                if (!$this->handleCheckoutOrdersChanged($manager, $id, $fieldName, $fieldValue)) {
                    return;
                }
            }

            if ($objectType === 'set_setting') {
                $manager->setSetting($fieldName, $fieldValue);
                return;
            }
            if ($objectType === 'giftcards' && $fieldName === 'code') {
                $fieldValue = strtoupper(preg_replace("/[^a-zA-Z0-9]+/", "", $fieldValue));
            }
            if ($objectType === 'giftcards' && $fieldName === 'discount_percent') {
                $fieldValue = min(100, intval($fieldValue));
                $fieldValue = max(0, intval($fieldValue));
                $row = $manager->selectByPk($id);
                $manager->updateField($id, 'amount_discounted', intval($fieldValue) * floatval($row->getAmount()) / 100);
            }
            if ($objectType === 'giftcards' && $fieldName === 'amount') {
                $row = $manager->selectByPk($id);
                $manager->updateField($id, 'amount_discounted', intval($fieldValue) * intval($row->getDiscountPercent()) / 100);
                
            }
            if ($objectType === 'vanilla' && $fieldName === 'number') {
                $row = $manager->selectByField('number', $fieldValue);
                if (!empty($row) && $row[0]->getId() != $id) {
                    $this->addParam('value', 'Already exists');
                    $this->addParam('message', 'Already exists');
                    $this->addParam('success', false);
                    return;
                }
            }
            if ($objectType === 'settings_name') {
                $manager->setSetting($fieldName, $fieldValue);
                $this->addParam('value', $fieldValue);
                $capitalData = json_decode(SettingManager::getInstance()->getSetting('capital_data', '{}'), true);
                $capitalData[$fieldName] = $fieldValue;
                SettingManager::getInstance()->setSetting('capital_data', json_encode($capitalData));
                return;
            }
            if ($fieldName === 'unit_address' && $objectType === 'checkout') {
                $row = $manager->selectByPk($id);
                if ($fieldValue === 'actual') {
                    $ua = $row->getCheckoutCustomerUnitAddress();
                    $manager->updateField($id, $fieldName, $ua);
                } else {
                    $ua = SettingManager::getInstance()->getSetting($row->getCheckoutOrderMetadataProperty('shipping_carrier') . '_unit_address');
                    $manager->updateField($id, $fieldName, $ua);
                }
                $this->addParam('display_value', $ua);
                $this->addParam('success', true);
                return;
            }
            if ($fieldName === 'unit_address' && $objectType === 'btc') {
                $recipient = RecipientManager::getInstance()->getRecipientByUnitAddress($fieldValue);
                if (!empty($recipient)) {
                    $manager->updateField($id, 'recipient_name', $recipient->getFirstName() . ' ' . $recipient->getLastName());
                } else {
                    $manager->updateField($id, 'recipient_name', 'N/A');
                }
            }
            $manager->updateField($id, $fieldName, $fieldValue);
            $valueAfterSave = $manager->selectByPk($id);
            $this->addParam('value', $valueAfterSave->$fieldName);
            if ($fieldName === 'category_id' && $objectType === 'product') {
                $this->addParam('display_value', ProductCategoryManager::getInstance()->selectByPk($fieldValue)->getName());
            }

            $this->addParam('success', true);
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
                    if ($ret === true && $fieldValue < 10) {
                        $purseManager->updateField($rowId, 'status', 'open');
                    }
                    if ($ret === true && $fieldValue >= 10 && $fieldValue <= 15) {
                        $purseManager->updateField($rowId, 'status', 'shipping');
                    }
                    if ($ret === true && $fieldValue == 25) {
                        $purseManager->updateField($rowId, 'status', 'canceled');
                    }
                    if ($ret === true && $fieldValue > 25) {
                        $purseManager->updateField($rowId, 'status', 'finished');
                    }
                    $this->addParam('display_value', PurseOrderDto::CHECKOUT_ORDER_STATUSES[intval($fieldValue)]);
                    break;
                default:
                    return true;
                    break;
            }
            if ($ret === true) {
                $this->addParam('success', true);
                $this->addParam('value', $fieldValue);
                return true;
            } else {
                $this->addParam('success', false);
                $this->addParam('message', print_r($ret, true));
                return false;
            }
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
