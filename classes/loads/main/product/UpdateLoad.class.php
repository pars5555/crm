<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\product {

    use crm\loads\AdminLoad;
    use crm\managers\ManufacturerManager;
    use crm\managers\ProductManager;
    use crm\managers\SettingManager;
    use crm\managers\UomManager;
    use crm\security\RequestGroups;
    use NGS;

    class UpdateLoad  extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $id = intval(NGS()->args()->id);
            $product = ProductManager::getInstance()->selectByPK($id);
            if ($product) {
                $this->addParam('product', $product);
                if (!isset($_SESSION['action_request'])) {
                    $_SESSION['action_request'] = ['name' => $product->getName(), 'model' => $product->getModel(),
                        'manufacturerId' => $product->getManufacturerId(), 'uomId' => $product->getUomId()];
                }
                $this->addParam('req', $_SESSION['action_request']);
                unset($_SESSION['action_request']);
                $this->addParam('uoms', UomManager::getInstance()->selectAdvance('*', [], ['name']));
                $this->addParam('manufacturers', ManufacturerManager::getInstance()->selectAdvance('*', [], ['name']));
                $this->addParam('defaultCurrencyId', SettingManager::getInstance()->getSetting('default_currency_id'));
                $this->addParam('defaultUomId', SettingManager::getInstance()->getSetting('default_uom_id'));
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/product/update.tpl";
        }

    }

}
