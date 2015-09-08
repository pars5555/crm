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
                    $date = $billingOrder->getDate();
                    list($billingDateYear, $billingDateMonth, $billingDateDay, $billingTimeHour, $billingTimeMinute) = $this->separateDateTime($date);
                    $_SESSION['action_request'] = [
                        'billingDateYear' => $billingDateYear,
                        'billingDateMonth' => $billingDateMonth,
                        'billingDateDay' => $billingDateDay,
                        'paymentTimeHour' => $billingTimeHour,
                        'paymentTimeMinute' => $billingTimeMinute,
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

        private function separateDateTime($date) {
            if ($date != 0) {
                $d = DateTime::createFromFormat("Y-m-d H:i:s", $date);
                if ($d !== false) {
                    return [$d->format("Y"), $d->format("m"), $d->format("d"), $d->format("H"), $d->format("i")];
                }
            }
            return [null, null, null, 0, 0];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/billing/update.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
