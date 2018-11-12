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
    use NGS;

    class SetNonProfitAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $saleOrderId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Sale Order ID is missing';
                $this->redirect('sale/list');
            }
            if (isset(NGS()->args()->non_profit)) {
                $nonProfit = NGS()->args()->non_profit;
            } else {
                $_SESSION['error_message'] = 'Non Profit parameter is missing';
                $this->redirect('sale/' . NGS()->args()->id);
            }
            $saleOrderManager = SaleOrderManager::getInstance();
            $saleOrderDto = $saleOrderManager->selectByPk($saleOrderId);
            if (!isset($saleOrderDto)) {
                $_SESSION['error_message'] = 'Sale Order with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('sale/list');
            }
            $saleOrderManager->setNonProfit($saleOrderId, $nonProfit);
        }

    }

}
