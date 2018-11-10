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
    use crm\managers\PurseOrderManager;
    use NGS;

    class AddExternalOrderAction extends BaseAction {

        public function service() {
            $unitAddress = trim(NGS()->args()->unit_address);
            $url = trim(NGS()->args()->url);
            $qty = intval(NGS()->args()->qty);
            $price = floatval(NGS()->args()->price);
            $html = $this->curl_get_contents($url);
            $productName = $this->get_title($html);
            if (empty($productName)) {
                $productName = $url;
            }
            $imageUrl = $this->extractImagesFromWebPage($html);
            PurseOrderManager::getInstance()->addExternalOrder($productName, $qty, $price, $unitAddress, $imageUrl);
        }

        private function curl_get_contents($url, $headers = [], $cookie = '') {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_REFERER, 'https://crm.pc.am');
            if (!empty($headers)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }
            if (!empty($cookie)) {
                curl_setopt($ch, CURLOPT_COOKIE, $cookie);
            }
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }

        function extractImagesFromWebPage($html) {
            $dom = new \DOMDocument();
            if (@$dom->loadHTML($html)) {
                $element = $dom->getElementById('landingImage');
                if (!empty($element)) {
                    return key(json_decode($element->getAttribute('data-a-dynamic-image')));
                }
                $finder = new \DOMXPath($dom);
                $ordersRows = $finder->query("//*[contains(@class, 'mainSlide')]");
                if ($ordersRows->length > 0) {
                    return $ordersRows->item(0)->getElementsByTagName('img')->item(0)->getAttribute('src');
                }
            }
            return false;
        }

        public function get_title($content) {

            $dom = new \DOMDocument();
            if (@$dom->loadHTML($content)) {
                $element = $dom->getElementById('productTitle');
                if (!empty($element)) {
                    return $this->DOMinnerHTML($element);
                }
            }
            
            $finder = new \DOMXPath($dom);
            $ordersRows = $finder->query("//*[contains(@itemprop, 'name')]");
            if ($ordersRows ->length > 0)
            {
                return $this->DOMinnerHTML($ordersRows[0]);
            }
            if (strlen($content) > 0) {
                $content = trim(preg_replace('/\s+/', ' ', $content)); // supports line breaks inside <title>
                $res = preg_match("/<title>(.*)\<\/title>/siU", $content, $title); // ignore case
                if (!$res) {
                    return 'No title';
                }
                return $title[1];
            }
        }

        private function DOMinnerHTML(\DOMElement $element) {
            $innerHTML = "";
            $children = $element->childNodes;

            foreach ($children as $child) {
                $innerHTML .= $element->ownerDocument->saveHTML($child);
            }

            return $innerHTML;
        }

    }

}
    