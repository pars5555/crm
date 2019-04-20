<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\sale {

    use crm\loads\AdminLoad;
    use crm\managers\AttachmentManager;
    use crm\managers\CurrencyManager;
    use crm\managers\ProductManager;
    use crm\managers\SaleOrderManager;
    use NGS;

    class OpenLoad extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $poId = 0;
            if (isset(NGS()->args()->poid)) {
                $poId = intval(NGS()->args()->poid);
            }
            $this->addParam('purchase_order_id', $poId);
            $this->addParam('products', ProductManager::getInstance()->selectAdvance('*', [], ['name']));
            $this->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
            $saleorderId = NGS()->args()->id;
            if ($poId > 0) {
                $saleOrders = [SaleOrderManager::getInstance()->createSaleOrderLinesFromPurchaseOrder($poId, $saleorderId)];
            } else {
                $saleOrders = SaleOrderManager::getInstance()->getSaleOrdersFull(['id', '=', $saleorderId]);
            }
            
            $attachments = AttachmentManager::getInstance()->getEntityAttachments($saleorderId, 'sale_order');
            if (!empty($saleOrders)) {
                $saleOrder = $saleOrders[0];
                $this->addParam('saleOrder', $saleOrder);
                $this->addParam('attachments', $attachments);
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/sale/open.tpl";
        }

    }

}
