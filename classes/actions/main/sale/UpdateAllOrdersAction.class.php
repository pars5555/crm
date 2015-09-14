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

namespace crm\actions\main\sale {

    use crm\actions\BaseAction;
    use crm\managers\SaleOrderManager;

    class UpdateAllOrdersAction extends BaseAction {

        public function service() {
            $updateAllSaleOrderLinesCurrencyRates = SaleOrderManager::getInstance()->updateAllSaleOrderLinesCurrencyRates();
            SaleOrderManager::getInstance()->updateAllSaleOrdersProfit();
            $this->addParam('status', true);
            $this->addParam('total_sale_orders', $updateAllSaleOrderLinesCurrencyRates);
        }

    }

}
