<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\billing {

use crm\loads\NgsLoad;
use crm\managers\CurrencyManager;
use crm\managers\PartnerManager;
use crm\managers\PaymentMethodManager;
use crm\managers\PaymentTransactionManager;
use crm\managers\SettingManager;
use crm\security\RequestGroups;
use DateTime;
use NGS;

    class UpdateLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $id = intval(NGS()->args()->id);
            $billingOrder = PaymentTransactionManager::getInstance()->selectByPK($id);
            if ($billingOrder) {
                if (!isset($_SESSION['action_request'])) {
                    $_SESSION['action_request'] = [
                        'date' => $this->cutSecondsFromDateTime($billingOrder->getDate()),
                        'partnerId' => $billingOrder->getPartnerId(),
                        'paymentMethodId' => $billingOrder->getPaymentMethodId(),
                        'currencyId' => $billingOrder->getCurrencyId(),
                        'note' => $billingOrder->getNote(),
                        'signature' => $billingOrder->getSignature(),
                        'amount' => -$billingOrder->getAmount()];
                }
                $this->addParam("billingOrder", $billingOrder);
                $this->addParam('req', $_SESSION['action_request']);
                unset($_SESSION['action_request']);
                $this->addParam('billing_methods', PaymentMethodManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
                $this->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
                $this->addParam('partners', PartnerManager::getInstance()->selectAdvance('*', [], ['name']));
                $this->addParam('defaultCurrencyId', SettingManager::getInstance()->getSetting('default_currency_id'));
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
            return NGS()->getTemplateDir() . "/main/billing/update.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
