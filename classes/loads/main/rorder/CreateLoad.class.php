<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\rorder {

    use crm\loads\AdminLoad;
    use crm\managers\RecipientManager;
    use crm\managers\PaymentMethodManager;
    use crm\managers\SettingManager;
    use NGS;

    class CreateLoad  extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $this->addParam('req', isset($_SESSION['action_request']) ? $_SESSION['action_request'] : []);
            unset($_SESSION['action_request']);
            $this->addParam('payment_methods', PaymentMethodManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
            $this->addParam('recipients', RecipientManager::getInstance()->selectAdvance('*', [], ['name']));
            
            $this->addParam('defaultPaymentMethodId', SettingManager::getInstance()->getSetting('default_payment_method_id'));
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/rorder/create.tpl";
        }

    }

}
