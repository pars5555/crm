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

    class UpdateAllOrdersAction extends BaseAction {

        public function service() {
            set_time_limit ( 120);
            $updateAllSaleOrderLinesCurrencyRates = SaleOrderManager::getInstance()->updateAllLinesCurrencyRates();
            $updateAllPurchaseOrderLinesCurrencyRates = PurchaseOrderManager::getInstance()->updateAllLinesCurrencyRates();
            $updateAllPaymentOrdersCurrencyRates = PaymentTransactionManager::getInstance()->updateAllOrdersCurrencyRates();
            SaleOrderManager::getInstance()->updateAllOrderLines();
            PurchaseOrderManager::getInstance()->updateAllOrderLines();
            $this->addParam('status', true);
            $this->addParam('total_sale_orders', $updateAllSaleOrderLinesCurrencyRates);
            $this->addParam('total_purchase_orders', $updateAllPurchaseOrderLinesCurrencyRates);
            $this->addParam('total_payment_orders', $updateAllPaymentOrdersCurrencyRates);
        }

    }

}
