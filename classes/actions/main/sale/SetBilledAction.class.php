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

    class SetBilledAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $saleOrderId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Sale Order ID is missing';
                $this->redirect('sale/list');
            }
            if (isset(NGS()->args()->billed)) {
                $billed = NGS()->args()->billed;
            } else {
                $_SESSION['error_message'] = 'Billed parameter is missing';
                $this->redirect('sale/' . NGS()->args()->id);
            }
            $saleOrderManager = SaleOrderManager::getInstance();
            $saleOrderDto = $saleOrderManager->selectByPK($saleOrderId);
            if (!isset($saleOrderDto)) {
                $_SESSION['error_message'] = 'Sale Order with ID ' . NGS()->args()->id . ' does not exists.';
                $this->redirect('sale/list');
            }
            $saleOrderManager->setBilled($saleOrderId, $billed);
        }

    }

}
