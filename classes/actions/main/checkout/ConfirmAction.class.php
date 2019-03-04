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

namespace crm\actions\main\checkout {

    use crm\actions\BaseAction;
    use crm\managers\PurseOrderManager;
    use NGS;

    class ConfirmAction extends BaseAction {

        public function service() {
            $id = intval(NGS()->args()->id);
            $order  = PurseOrderManager::getInstance()->selectByPk($id);
            
            $res = \crm\managers\CheckoutManager::getInstance()->confirmOrder($order->getCheckoutOrderId());
            if ($res === true) {
                $asin = PurseOrderManager::getInstance()->confirmCheckoutOrder($id);
                $this->addParam('success', true);
                $this->addParam('asin', $asin);
                return ;
            }
            $this->addParam('success', false);
            $this->addParam('message', $res);
        }

    }

}
