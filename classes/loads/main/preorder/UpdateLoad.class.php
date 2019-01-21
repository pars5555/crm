<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\preorder {

    use crm\loads\AdminLoad;
    use crm\managers\PartnerManager;
    use crm\managers\PaymentMethodManager;
    use crm\managers\PreorderManager;
    use crm\managers\SettingManager;
    use crm\security\RequestGroups;
    use DateTime;
    use NGS;

    class UpdateLoad  extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $id = intval(NGS()->args()->id);
            $preorder = PreorderManager::getInstance()->selectByPk($id);
            if ($preorder) {
                if (!isset($_SESSION['action_request'])) {
                    $_SESSION['action_request'] = [
                        'order_date' => $this->cutSecondsFromDateTime($preorder->getOrderDate()),
                        'payment_deadline' => $preorder->getPaymentDeadline(),
                        'partnerId' => $preorder->getPartnerId(),
                        'note' => $preorder->getNote()
                    ];
                }
                $this->addParam("preorder", $preorder);
                $this->addParam('req', $_SESSION['action_request']);
                unset($_SESSION['action_request']);
                $this->addParam('payment_methods', PaymentMethodManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
                $this->addParam('partners', PartnerManager::getInstance()->selectAdvance('*', [], ['name']));
                $this->addParam('defaultPaymentMethodId', SettingManager::getInstance()->getSetting('default_payment_method_id'));
            }
        }

        private function cutSecondsFromDateTime($date) {
            if ($date != 0) {
                $d = DateTime::createFromFormat("Y-m-d H:i:s", $date);
                return $d->format('Y-m-d H:i');
            }
            return null;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/preorder/update.tpl";
        }

    }

}
