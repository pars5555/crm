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
    use crm\managers\PaymentTransactionManager;
    use crm\managers\ProductManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\PurseOrderManager;
    use crm\managers\RecipientManager;
    use crm\managers\SaleOrderManager;
    use crm\managers\SettingManager;
    use crm\managers\TranslationManager;
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
                case 'settings':
                case 'settings_name':
                    $manager = SettingManager::getInstance();
                    break;
                case 'btc':
                    $manager = PurseOrderManager::getInstance();
                    break;
                case 'recipient':
                    $manager = RecipientManager::getInstance();
                    break;
            }
            if ($objectType === 'settings_name'){
                $manager->setSetting($fieldName, $fieldValue);
                $this->addParam('value', $fieldValue);
                $capitalData = json_decode(SettingManager::getInstance()->getSetting('capital_data', '{}'), true);
                $capitalData[$fieldName] = $fieldValue;
                return;
            }
            $manager->updateField($id, $fieldName, $fieldValue);
            $valueAfterSave = $manager->selectByPk($id);
            $this->addParam('value', $valueAfterSave->$fieldName);
        }

    }

}
