<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\purchase {

    use crm\loads\NgsLoad;
    use crm\managers\PartnerManager;
    use crm\managers\PaymentMethodManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\SettingManager;
    use crm\security\RequestGroups;
    use NGS;

    class UpdateLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $id = intval(NGS()->args()->id);
            $purchaseOrder = PurchaseOrderManager::getInstance()->selectByPK($id);
            if ($purchaseOrder) {
                if (!isset($_SESSION['action_request'])) {
                    $_SESSION['action_request'] = ['order_date' => $purchaseOrder->getOrderDate(), 'partnerId' => $purchaseOrder->getPartnerId(), 'note' => $purchaseOrder->getNote()];
                }
                $this->addParam("purchaseOrder", $purchaseOrder);
                $this->addParam('req', $_SESSION['action_request']);
                unset($_SESSION['action_request']);
                $this->addParam('payment_methods', PaymentMethodManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
                $this->addParam('partners', PartnerManager::getInstance()->selectAdvance('*', [], ['name']));
                $this->addParam('defaultPaymentMethodId', SettingManager::getInstance()->getSetting('default_payment_method_id'));
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/purchase/update.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
