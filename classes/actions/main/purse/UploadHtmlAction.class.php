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

namespace crm\actions\main\purse {

    use crm\actions\BaseAction;

    class UploadHtmlAction extends BaseAction {

        public function service() {
            $file_name = $_FILES['list_file']['name'];
            $file_type = $_FILES['list_file']['type'];
            $tmp_name = $_FILES['list_file']['tmp_name'];
            $file_size = $_FILES['list_file']['size'];

            libxml_use_internal_errors(true);
            $xmlDoc = new \DOMDocument();
            $xmlDoc->loadHTMLFile($tmp_name);
            $finder = new \DOMXPath($xmlDoc);

            $ordersRows = $finder->query("//*[contains(@class, 'order-item')]");

            libxml_clear_errors();
            $changedOrNewOrders = [];
            $orderNumbers = [];
            for ($i = 0; $i < $ordersRows->length; $i++) {
                $el = $ordersRows->item($i);
                $img = $el->getElementsByTagName('img')[0];
                $parts = explode("/", $img->getAttribute('src'));
                $imgName = end($parts);
                $parts = explode("/", $img->parentNode->getAttribute('href'));
                $orderNumber = end($parts);
                $orderNumbers[] = $orderNumber;

                $productTitle = $img->getAttribute('title');
                $productImage = $img->getAttribute('src');

                $amazonOrderNumberElements = $el->getElementsByTagName('td')[3]->getElementsByTagName('small');
                $amazonOrderNumber = "";
                if ($amazonOrderNumberElements->length > 0) {
                    $amazonOrderNumber = $amazonOrderNumberElements->item(0)->nodeValue;
                }

                $buyerNameElements = $el->getElementsByTagName('td')[3]->getElementsByTagName('span');
                $buyerName = "";
                if ($buyerNameElements->length > 0) {
                    $buyerName = $buyerNameElements->item(0)->nodeValue;
                }

                $purseTotal = trim($el->getElementsByTagName('td')[4]->getElementsByTagName('span')->item(0)->nodeValue, ' $');
                $orderStatus = $finder->query(".//*[contains(@class, 'status-lg')]", $el)[0]->nodeValue;

                $result = \crm\managers\PurseOrderManager::getInstance()->insertOrUpdateOrder($orderNumber, $productTitle, $productImage, $orderStatus, $imgName, $amazonOrderNumber, $purseTotal, $buyerName);
                if (!empty($result)) {
                    $changedOrNewOrders[] = $orderNumber;
                }
            }
            \crm\managers\PurseOrderManager::getInstance()->archiveIfnotExists($orderNumbers);

            $changedOrNewOrdersText = '"' . implode(';', $changedOrNewOrders) . '"';
            echo "<script>parent.uploadedFileResponse($changedOrNewOrdersText);</script>";
            exit;
        }

    }

}
