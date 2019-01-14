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

namespace crm\actions\cron {

    use crm\actions\BaseAction;
    use crm\managers\CalculationManager;
    use crm\managers\PartnerDebtCacheManager;
    use crm\managers\PartnerInitialDebtManager;
    use crm\managers\PartnerManager;
    use crm\managers\PaymentTransactionManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\SaleOrderManager;
    use crm\security\RequestGroups;

    class CachePartnerDebtsAction extends BaseAction {

        public function service() {
            $partners = PartnerManager::getInstance()->selectAll();
            $partnerIds = PartnerManager::getInstance()->getDtosIdsArray($partners);
            if (!empty($partnerIds)) {
                $partnersSaleOrdersMappedByPartnerId = SaleOrderManager::getInstance()->getPartnersSaleOrders($partnerIds);
                $partnersPurchaseOrdersMappedByPartnerId = PurchaseOrderManager::getInstance()->getPartnersPurchaseOrders($partnerIds);
                $partnersPaymentTransactionsMappedByPartnerId = PaymentTransactionManager::getInstance()->getPartnersPaymentTransactions($partnerIds);
                $partnersBillingTransactionsMappedByPartnerId = PaymentTransactionManager::getInstance()->getPartnersBillingTransactions($partnerIds);
                $partnersInitialDebt = PartnerInitialDebtManager::getInstance()->getPartnersInitialDebt($partnerIds);
            }
            list($partnersDebt,$partnersZeroDebt) = CalculationManager::getInstance()->calculatePartnersDebtBySalePurchaseAndPaymentTransations($partnersSaleOrdersMappedByPartnerId, $partnersPurchaseOrdersMappedByPartnerId, $partnersPaymentTransactionsMappedByPartnerId, $partnersBillingTransactionsMappedByPartnerId, $partnersInitialDebt);
            foreach ($partnersDebt as $partnerId => $debt) {
                PartnerDebtCacheManager::getInstance()->setPartnerDebtCache($partnerId, $debt);
            }
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
