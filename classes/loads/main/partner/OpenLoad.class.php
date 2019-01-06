<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\partner {

    use crm\loads\AdminLoad;
    use crm\managers\AttachmentManager;
    use crm\managers\CalculationManager;
    use crm\managers\CurrencyManager;
    use crm\managers\PartnerInitialDebtManager;
    use crm\managers\PartnerManager;
    use crm\managers\PaymentTransactionManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\SaleOrderManager;
    use NGS;

    class OpenLoad extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $partnerId = intval(NGS()->args()->id);
            $partner = PartnerManager::getInstance()->getPartnerFull($partnerId);
            if ($partner) {
                $this->addParam('partner', $partner);
                $partnerSaleOrders = SaleOrderManager::getInstance()->getPartnerSaleOrders($partnerId);
                $partnerPurchaseOrders = PurchaseOrderManager::getInstance()->getPartnerPurchaseOrders($partnerId);
                $partnerPaymentTransactions = PaymentTransactionManager::getInstance()->getPartnerPaymentTransactions($partnerId);
                $partnerBillingTransactions = PaymentTransactionManager::getInstance()->getPartnerBillingTransactions($partnerId);
                $partnerInitialDebt = PartnerInitialDebtManager::getInstance()->getPartnerInitialDebt($partnerId);
                $this->addParam('partnerSaleOrders', $partnerSaleOrders);
                $this->addParam('partnerPurchaseOrders', $partnerPurchaseOrders);
                $this->addParam('partnerPaymentTransactions', $partnerPaymentTransactions);
                $this->addParam('partnerBillingTransactions', $partnerBillingTransactions);
                $debt = CalculationManager::getInstance()->calculatePartnerDebtBySalePurchaseAndPaymentTransations(
                        $partnerSaleOrders, $partnerPurchaseOrders, $partnerPaymentTransactions, $partnerBillingTransactions, $partnerInitialDebt);
                $this->addParam('partnerDebt', $debt);
                $currencyManager = CurrencyManager::getInstance();
                $this->addParam('currencies', $currencyManager->mapDtosById($currencyManager->selectAdvance('*', ['active', '=', 1], ['name'])));
                $attachments = AttachmentManager::getInstance()->getPartnerAttachments($partnerId);
                $this->addParam('attachments', $attachments);
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/partner/open.tpl";
        }

    }

}
