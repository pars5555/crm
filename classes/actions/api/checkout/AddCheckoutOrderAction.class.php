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

namespace crm\actions\api\checkout {

    use crm\actions\BaseAction;
    use crm\managers\PurseOrderManager;
    use NGS;

    class AddCheckoutOrderAction extends BaseAction {

        public function service() {
            $orderId = intval(NGS()->args()->order_id);
            $customer_name = trim(NGS()->args()->customer_name);
            $shipping_carrier = trim(NGS()->args()->shipping_carrier);
            $unitAddress = trim(NGS()->args()->unit_address);
            $asin = trim(NGS()->args()->asin);
            $productName = trim(NGS()->args()->title);
            $qty = max(intval(NGS()->args()->qty), 1);
            $price = floatval(NGS()->args()->price);
            $imageUrl = trim(NGS()->args()->image_url);
            PurseOrderManager::getInstance()->addCheckoutOrder($orderId, $shipping_carrier, $customer_name, $asin, $productName, $qty, $price, $unitAddress, $imageUrl, 0);
        }

        public function getRequestGroup() {
            return \crm\security\RequestGroups::$guestRequest;
        }

    }

}
    