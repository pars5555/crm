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
    use DateTime;
    use NGS;

    class UpdateLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $id = intval(NGS()->args()->id);
            $purchaseOrder = PurchaseOrderManager::getInstance()->selectByPK($id);
            if ($purchaseOrder) {
                if (!isset($_SESSION['action_request'])) {
                    $orderDate = $purchaseOrder->getOrderDate();
                    list($purchaseOrderDateYear, $purchaseOrderDateMonth, $purchaseOrderDateDay, $purchaseOrderTimeHour, $purchaseOrderTimeMinute) = $this->separateDateTime($orderDate);
                    $paymentDeadlineDate = $purchaseOrder->getPaymentDeadline();
                    list($paymentDeadlineDateYear, $paymentDeadlineDateMonth, $paymentDeadlineDateDay) = $this->separateDate($paymentDeadlineDate);
                    $_SESSION['action_request'] = [
                        'purchaseOrderDateYear' => $purchaseOrderDateYear,
                        'purchaseOrderDateMonth' => $purchaseOrderDateMonth,
                        'purchaseOrderDateDay' => $purchaseOrderDateDay,
                        'purchaseOrderTimeHour' => $purchaseOrderTimeHour,
                        'purchaseOrderTimeMinute' => $purchaseOrderTimeMinute,
                        'paymentDeadlineDateYear' => $paymentDeadlineDateYear,
                        'paymentDeadlineDateMonth' => $paymentDeadlineDateMonth,
                        'paymentDeadlineDateDay' => $paymentDeadlineDateDay,
                        'partnerId' => $purchaseOrder->getPartnerId(),
                        'note' => $purchaseOrder->getNote(),
                        'isExpense' => $purchaseOrder->getIsExpense()
                    ];
                }
                $this->addParam("purchaseOrder", $purchaseOrder);
                $this->addParam('req', $_SESSION['action_request']);
                unset($_SESSION['action_request']);
                $this->addParam('payment_methods', PaymentMethodManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
                $this->addParam('partners', PartnerManager::getInstance()->selectAdvance('*', [], ['name']));
                $this->addParam('defaultPaymentMethodId', SettingManager::getInstance()->getSetting('default_payment_method_id'));
            }
        }

        private function separateDate($date) {
            if ($date != 0) {
                $d = DateTime::createFromFormat("Y-m-d", $date);
                if ($d !== false) {
                    return [$d->format("Y"), $d->format("m"), $d->format("d")];
                }
            }
            return [null, null, null];
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
            return NGS()->getTemplateDir() . "/main/purchase/update.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
