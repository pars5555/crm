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
            $html = file_get_contents($url);
            $productName = $this->get_title($html);
            if (empty($productName)) {
                $productName = $url;
            }
            $imageUrl = $this->extractImagesFromWebPage($html);
            PurseOrderManager::getInstance()->addExternalOrder($productName, $qty, $price, $unitAddress, $imageUrl);
        }

        function extractImagesFromWebPage($html) {
            $dom = new \DOMDocument();
            if (@$dom->loadHTML($html)) {
                // Extracting the specified elements from the web page
                @$elements = $dom->getElementsByTagName('img');
                return $elements[0]->getAttribute('src');
            }
            return FALSE;
        }

        public function get_title($content) {
            
            if (strlen($content) > 0) {
                $content = trim(preg_replace('/\s+/', ' ', $content)); // supports line breaks inside <title>
                preg_match("/\<title\>(.*)\<\/title\>/i", $content, $title); // ignore case
                return $title[1];
            }
        }

    }

}
