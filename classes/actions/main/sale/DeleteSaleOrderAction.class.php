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

    class DeleteSaleOrderAction extends BaseAction {

        public function service() {
            if (isset(NGS()->args()->id)) {
                $saleOrderId = NGS()->args()->id;
            } else {
                $_SESSION['error_message'] = 'Sale Order ID is missing';
                $this->redirect('sale/list');
            }
            $saleOrderManager = SaleOrderManager::getInstance();
            $saleOrderManager->updateField($saleOrderId, 'deleted', 1);
            $_SESSION['success_message'] = 'Sale Order Successfully deleted!';
            if (strpos($_SERVER['HTTP_REFERER'], 'sale/list') === false) {
                $this->redirect('sale/list');
            } else {
                $this->redirectToReferer();
            }
        }

    }

}
