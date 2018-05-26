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
    use crm\managers\PurchaseOrderManager;
    use crm\managers\SaleOrderManager;
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
            }
            $manager->updateField($id, $fieldName, $fieldValue);
            $valueAfterSave = $manager->selectByPk($id);
            $this->addParam('value', $valueAfterSave->$fieldName);
        }

    }

}
