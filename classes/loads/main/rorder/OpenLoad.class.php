<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\rorder {

    use crm\loads\AdminLoad;
    use crm\managers\CurrencyManager;
    use crm\managers\ProductManager;
    use crm\managers\RecipientOrderManager;
    use NGS;

    class OpenLoad  extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $this->addParam('products', ProductManager::getInstance()->selectAdvance('*', [], ['name']));
            $this->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
            $orderId = NGS()->args()->id;
            $recipientOrders = RecipientOrderManager::getInstance()->getRecipientOrdersFull(['id', '=', $orderId]);
            if (!empty($recipientOrders)) {
                $recipientOrder = $recipientOrders[0];
                $this->addParam('recipientOrder', $recipientOrder);
            }
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/rorder/open.tpl";
        }


    }

}