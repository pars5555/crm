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

    class CancelSaleOrderAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $saleOrderId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Sale Order ID is missing';
                $this->redirect('sale/list');
            }
            $saleOrderManager = SaleOrderManager::getInstance();
            $saleOrderDto = $saleOrderManager->selectByPk($saleOrderId);
            if (!isset($saleOrderDto)) {
                $_SESSION['error_message'] = 'Sale Order with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('sale/list');
            }
            if ($saleOrderDto->getCancelled() == 1) {
                $_SESSION['error_message'] = 'Sale Order with ID ' . NGS()->args()->id . ' is already cancelled.';
                $this->redirect('sale/list');
            }
            $note = NGS()->args()->note;
            $saleOrderManager->cancelSaleOrder($saleOrderId, $note);
            $_SESSION['success_message'] = 'Sale Order Successfully cancelled!';
            $this->redirectToReferer();
        }

    }

}
