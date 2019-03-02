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
            $unitAddress = trim(NGS()->args()->unit_address);
            $asin = trim(NGS()->args()->asin);
            $productName = trim(NGS()->args()->title);
            $qty = intval(NGS()->args()->qty);
            $price = floatval(NGS()->args()->price);
            $imageUrl = trim(NGS()->args()->image_url);
            PurseOrderManager::getInstance()->addManualOrder($productName, $qty, $price, $unitAddress, $imageUrl, 0);
        }

        public function getRequestGroup() {
            return \crm\security\RequestGroups::$guestRequest;
        }

    }

}
    