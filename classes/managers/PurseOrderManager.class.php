<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package managers
 * @version 6.0
 *
 */

namespace crm\managers {

    use crm\dal\mappers\PurseOrderMapper;

    class PurseOrderManager extends AdvancedAbstractManager {

        /**
         * @var $instance
         */
        public static $instance;

        /**
         * Returns an singleton instance of this class
         *
         * @param object $config
         * @param object $args
         * @return
         */
        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new PurseOrderManager(PurseOrderMapper::getInstance());
            }
            return self::$instance;
        }

        public function getRecipientsRecentOrders($recipientIds) {
            $recipients = RecipientManager::getInstance()->selectByPKs($recipientIds);
            $partnerIdMappedByExpressUnitAddresses = [];
            foreach ($recipients as $recipient) {
                $expressUnitAddress = $recipient->getExpressUnitAddress();
                $onexExpressUnit = $recipient->getOnexExpressUnit();
                $novaExpressUnit = $recipient->getNovaExpressUnit();
                if (empty($expressUnitAddress) && empty($onexExpressUnit) && empty($novaExpressUnit)) {
                    continue;
                }
                if (!empty($expressUnitAddress)) {
                    $partnerIdMappedByExpressUnitAddresses[strtolower($expressUnitAddress)] = $recipient->getId();
                }
                if (!empty($onexExpressUnit)) {
                    $partnerIdMappedByExpressUnitAddresses[strtolower($onexExpressUnit)] = $recipient->getId();
                }
                if (!empty($novaExpressUnit)) {
                    $partnerIdMappedByExpressUnitAddresses[strtolower($novaExpressUnit)] = $recipient->getId();
                }
            }
            $unitAddressSql = "('" . implode("','", array_keys($partnerIdMappedByExpressUnitAddresses)) . "')";
            $firstDayOfMonth = date('Y-m-1');
            $orders = $this->selectAdvance('*', ['status', '<>', "'canceled'", 'AND', 
                'unit_address', 'in', $unitAddressSql, 'AND', 
                'ABS(DATEDIFF(`created_at`, date(now())))', '<=', 50, 'AND', 
                '(','hidden', '=', 0 ,'OR', 'hidden_at', '>=', "'$firstDayOfMonth'", ')'], 'id', 'desc');
            $recipientsRecentOrdersMappedByrecipientId = [];
            foreach ($orders as $order) {
                $expressUnitAddress = $order->getUnitAddress();
                $recipientId = $partnerIdMappedByExpressUnitAddresses[strtolower($expressUnitAddress)];
                if (!array_key_exists($recipientId, $recipientsRecentOrdersMappedByrecipientId)) {
                    $recipientsRecentOrdersMappedByrecipientId[$recipientId] = [];
                }
                $recipientsRecentOrdersMappedByrecipientId[$recipientId][] = $order;
            }
            $ret = [];
            foreach ($recipientsRecentOrdersMappedByrecipientId as $recId => $recipientOrders) {
                $total = 0;
                $orders = [];
                foreach ($recipientOrders as $order) {
                    $total += $order->getAmazonTotal();
                    $orders[] = ['created_at' => explode(' ', $order->getCreatedAt())[0], 'status' => $order->getStatus(), 'order_total' => $order->getAmazonTotal(), 'image_url' => $order->getImageUrl()];
                }

                $ret[$recId] = ['total' => $total, 'count' => count($recipientOrders), 'orders' => $orders];
            }
            return $ret;
        }

        public function getNotRegisteredOrdersInWarehouse($registeredTrackingNumbers) {
            $registeredTrackingNumbers = array_map('trim', $registeredTrackingNumbers);
            $ordersThatHasTrackingNumbers = $this->selectAdvance('*', ['hidden', '=', 0, 'AND',
                'status', 'not in', "('open', 'under_balance', 'accepted', 'canceled', 'under_balance.confirming')", 'AND',
                "length(COALESCE(`amazon_order_number`,''))", '>', 5, 'AND',
                "length(COALESCE(`tracking_number`, ''))", '>', 3
            ]);
            $existingOrdersMappedByTrackingNumbers = [];
            foreach ($ordersThatHasTrackingNumbers as $order) {
                $trackingNumber = $order->getTrackingNumber();
                if (strlen(strval(trim($trackingNumber))) < 5) {
                    continue;
                }
                $existingOrdersMappedByTrackingNumbers [strval($trackingNumber)] = $order;
            }
            $existingOrdersTrackingNumbers = array_keys($existingOrdersMappedByTrackingNumbers);
            $ret = [];
            foreach ($existingOrdersTrackingNumbers as $tracking_number) {
                $index = $this->findTrackingInArray($tracking_number, $registeredTrackingNumbers);
                if ($index === -1) {
                    $order = $existingOrdersMappedByTrackingNumbers [$tracking_number];
                    $ret[] = $order;
                }
            }
            return $ret;
        }

        public function getAccountUpdatedDateString($accountName) {
            $row = $this->selectOneByField('account_name', $accountName);
            if (!empty($row)) {
                $datetime = $row->getUpdatedAt();
                list($date, $time) = explode(' ', $datetime);

                if ($date === date('Y-m-d')) {
                    return 'Today: ' . substr($time, 0, 5);
                }
                if ($date === date('Y-m-d', time() - 60 * 60 * 24)) {
                    return 'Yesteday: ' . substr($time, 0, 5);
                }
                return $date . ' ' . substr($time, 0, 5);
            }
            return false;
        }

        public function findByTrackingNumbers($trackingNumbers, $getNotFounds = true) {
            $trackingNumbers = array_map('trim', $trackingNumbers);
            $notReceivedOrders = $this->selectAdvance(
                    ['id', 'tracking_number', 'recipient_name', 'quantity', 'product_name',
                'amazon_total', 'account_name'], ['hidden', '=', 0, 'AND',
                'ABS(DATEDIFF(`created_at`, date(now())))', '<=', 200]);
            $notReceivedOrdersMappedByTracking = [];
            foreach ($notReceivedOrders as $order) {
                $trackingNumber = $order->getTrackingNumber();
                if (strlen(strval(trim($trackingNumber))) < 5) {
                    continue;
                }
                $notReceivedOrdersMappedByTracking[strval($trackingNumber)] = $order;
            }
            $notReceivedTrackings = array_keys($notReceivedOrdersMappedByTracking);

            $ret = [];
            foreach ($trackingNumbers as $tracking_number) {
                if (empty($tracking_number)) {
                    continue;
                }
                $index = $this->findTrackingInArray($tracking_number, $notReceivedTrackings);
                if ($index >= 0) {
                    $order = $notReceivedOrdersMappedByTracking[$notReceivedTrackings[$index]];
                    $ret[$tracking_number] = $order;
                } else {
                    if ($getNotFounds) {
                        $ret[$tracking_number] = new \crm\dal\dto\PurseOrderDto();
                    }
                }
            }
            return $ret;
        }

        private function findTrackingInArray($tracking, $trackingsArray) {
            foreach ($trackingsArray as $key => $tr) {
                if (stripos(strval($tracking), strval($tr)) !== false or stripos(strval($tr), strval($tracking)) !== false) {
                    return $key;
                }
            }
            return -1;
        }

        private function getShippingCarrierName($el) {
            $trackingHeadlineEl = $el->getElementsByTagName('h1');
            if ($trackingHeadlineEl->length > 0) {
                for ($i = 0; $i < $trackingHeadlineEl->length; $i++) {
                    $trackingHeadline = $trackingHeadlineEl->item($i)->nodeValue;
                    if (strpos($trackingHeadline, 'Shipped with') !== false) {
                        $trackingHeadline = trim(str_replace('Shipped with', '', $trackingHeadline));
                        return trim($trackingHeadline);
                    }
                }
            }
            return 'N/A';
        }

        public function fetchAndUpdateTrackingPageDetails($row) {
            $trackingNumber = $row->getTrackingNumber();
            if (empty($trackingNumber) || strlen($trackingNumber) < 5) {
                return false;
            }

            $shippingCarrier = strtolower($row->getShippingCarrier());
            $deliveryDate = false;
            $trackingStatus = false;
            if (strpos($shippingCarrier, 'usps') !== false) {
                list($deliveryDate, $trackingStatus, $meta) = $this->fetchUspsPageDetails($trackingNumber);
            }
            if (strpos($shippingCarrier, 'fedex') !== false) {
                list($deliveryDate, $trackingStatus, $meta) = $this->fetchFedexPageDetails($trackingNumber);
            }
            if (strpos($shippingCarrier, 'ups') !== false) {
                list($deliveryDate, $trackingStatus, $meta) = $this->fetchUpsPageDetails($trackingNumber);
            }
            if (!empty($deliveryDate)) {
                $this->updateField($row->getId(), 'carrier_delivery_date', $deliveryDate);
                $this->updateField($row->getId(), 'updated_at', date('Y-m-d H:i:s'));
            }
            if (!empty($trackingStatus)) {
                $this->updateField($row->getId(), 'carrier_tracking_status', $trackingStatus);
                $this->updateField($row->getId(), 'updated_at', date('Y-m-d H:i:s'));
            }
        }

        public function fetchAndUpdateTrackingDetails($row) {
            $aon = $row->getAmazonOrderNumber();
            $url = "https://www.amazon.com/progress-tracker/package/ref=oh_aui_hz_st_btn?_encoding=UTF8&itemId=jpmklqnukqppon&orderId=$aon";
            $content = file_get_contents($url);

            libxml_use_internal_errors(true);
            $xmlDoc = new \DOMDocument();
            $xmlDoc->loadHTML($content);
            $finder = new \DOMXPath($xmlDoc);
            $ps = $finder->query("//*[@id='primaryStatus']");
            if ($ps->length > 0) {
                $primaryStatusText = trim($ps->item(0)->nodeValue);
                $this->updateField($row->getId(), 'amazon_primary_status_text', $primaryStatusText);
            }


            $ordersRows = $finder->query("//*[contains(@class, 'cardContainer')]");

            libxml_clear_errors();

            $trackingNumber = false;
            for ($i = 0; $i < $ordersRows->length; $i++) {
                $el = $ordersRows->item($i);
                $trackingLinks = $el->getElementsByTagName('a');
                if ($trackingLinks->length > 0) {
                    $trackingNumber = $trackingLinks->item(0)->nodeValue;
                    $trackingNumber = trim(str_replace('Tracking ID', '', $trackingNumber));
                    if (preg_match('/\\d/', $trackingNumber) === 1) {
                        $shippingCarrierName = $this->getShippingCarrierName($el);
                        break;
                    } else {
                        $trackingNumber = '';
                    }
                }
            }

            if (!empty($trackingNumber)) {
                $this->updateField($row->getId(), 'tracking_number', $trackingNumber);
                $this->updateField($row->getId(), 'shipping_carrier', $shippingCarrierName);
                $this->updateField($row->getId(), 'updated_at', date('Y-m-d H:i:s'));
            }
        }

        public function addExternalOrder($productName, $qty, $price, $unitAddress, $imageUrl) {
            $dto = $this->createDto();
            $dto->setProductName($productName);
            $dto->setImageUrl($imageUrl);
            $dto->setQuantity($qty);
            $dto->setDiscount(0);
            $dto->setAmazonTotal($price);
            $dto->setAccountName('external');            
            $dto->setStatus('shipping');            
            $dto->setExternal(1);            
            $dto->setUnitAddress($unitAddress);
            $shippingType = RecipientManager::getInstance()->getShippingTypeByUnitAddress($unitAddress);
            $recipient = RecipientManager::getInstance()->getRecipientByUnitAddress($unitAddress);
            $dto->setShippingType($shippingType);
            $dto->setRecipientName($recipient->getFirstName().' '. $recipient->getLastName());
            $dto->setCreatedAt(date('Y-m-d H:i:s'));
            return $this->insertDto($dto);
        }
        
        public function insertOrUpdateOrderFromPurseObject($accountName, $order) {
            $dtos = $this->selectByField('order_number', $order['id']);
            $prevStatus = '';
            if (!empty($dtos)) {
                $dto = $dtos[0];
                $prevStatus = $dto->getStatus();
                
            } else {
                $dto = $this->createDto();
            }
            $dto->setOrderNumber($order['id']);
            $dto->setStatus($order['state']);
            if ($order['state'] === 'canceled') {
                $dto->setHidden(1);
            }            
            $dto->setProductName($order['items'][0]['name']);
            $dto->setImageUrl($order['items'][0]['images']['small']);
            $dto->setQuantity($order['items'][0]['quantity']);
            $dto->setAmazonOrderNumber($order['shipping']['purchase_order']);
            $unitAddress = trim($order['shipping']['verbose']['street2']);
            $dto->setUnitAddress($unitAddress);
            $shippingType = RecipientManager::getInstance()->getShippingTypeByUnitAddress($unitAddress);
            $dto->setShippingType($shippingType);            
            $dto->setDeliveryDate($order['shipping']['delivery_date']);
            $dto->setUnreadMessages($order['unread_messages']);
            $dto->setRecipientName($order['shipping']['verbose']['full_name']);
            $dto->setAmazonTotal($order['pricing']['buyer_pays_fiat']);
            $dto->setBtcRate($order['pricing']['market_exchange_rate']['rate']);
            $dto->setDiscount(floatval($order['items'][0]['discount_rate'] * 100));
            $dto->setAccountName($accountName);
            $dto->setMeta(json_encode($order));
            if (isset($order['transaction']) && isset($order['transaction']['buyer'])) {
                $dto->setBuyerName($order['transaction']['buyer']['username']);
            }
            $dto->setCreatedAt(date('Y-m-d H:i:s', $order['created_at']));
            if (!empty($dtos)) {
                $dto->setUpdatedAt(date('Y-m-d H:i:s'));
                $this->updateByPk($dto);
            } else {
                $id = $this->insertDto($dto);
                $dto->setId($id);
            }
            if ($prevStatus != $order['state']) {
                PurseOrderHistoryManager::getInstance()->addRow($dto->getId(), $order['state'], json_encode($order));
            }
        }

        public function getNotDeliveredToWarehouseOrdersThatHasNotTrackingNumber() {
            return $this->selectAdvance('*', ['hidden', '=', 0, 'AND',
                        'status', 'in', "('shipping', 'shipped', 'feedback', 'finished',  'partially_delivered', 'delivered', 'accepted')", 'AND',
                        "length(COALESCE(`amazon_order_number`,''))", '>', 5, 'AND',
                        "length(COALESCE(`tracking_number`, ''))", '<', 3, 'AND',
                        "length(COALESCE(`real_delivery_date`, ''))", '<', 3
            ]);
        }

        public function getProblematicOrders($where) {
            $days = intval(SettingManager::getInstance()->getSetting('btc_products_days_diff_for_delivery_date'));
            return $this->selectAdvance('*', array_merge($where, ['AND', 'problem_solved', '=', '0', 'AND',
                        '(',
                            'problematic', '=', 1, 'OR', 'amazon_primary_status_text', 'like', "'%cancel%'", 'OR', 'amazon_primary_status_text', 'like', "'%Was expected%'",
                            'OR', "length(COALESCE(`unit_address`,''))", '<', 2, 'OR',
                            "`shipping_type`", 'not in', "('express', 'standard')", 'OR',
                            '(',
                                'status', 'in', "('shipping', 'shipped', 'feedback', 'finished',  'partially_delivered', 'delivered', 'accepted')", 'AND',
                                "length(COALESCE(`serial_number`,''))", '<', 2, 'AND', 'ABS(DATEDIFF(`delivery_date`, date(now())))', '>=', $days,
                            ')',
                        ')'
            ]));
        }

        public function getOrdersPuposedToNotReceivedToDestinationCounty() {
            return $this->selectAdvance('*', ['hidden', '=', 0, 'AND',
                        'status', 'in', "('shipping', 'shipped', 'feedback', 'finished',  'partially_delivered', 'delivered', 'accepted')", 'AND',
                        "length(COALESCE(`serial_number`,''))", '<', 2, 'AND',
                        'ABS(DATEDIFF(`delivery_date`, date(now())))', '<=', intval(SettingManager::getInstance()->getSetting('btc_products_days_diff_for_delivery_date'))]);
        }

        public function getTrackingFetchNeededOrders() {
            return $this->selectAdvance(['id', 'amazon_order_number'], ['hidden', '=', 0, 'AND',
                        'status', 'in', "('shipping', 'shipped', 'feedback', 'finished',  'partially_delivered', 'delivered', 'accepted')", "AND",
                        "length(COALESCE(`amazon_order_number`,''))", '>', 5, 'AND',
                        "length(COALESCE(`tracking_number`, ''))", '<', 3, 'AND',
                        "length(COALESCE(`serial_number`,''))", '<', 5]);
        }

        public function getOrders($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            return $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit, true);
        }

        public function getInactiveOrders($token) {
            $headers = $this->getPurseHeader($token);
            $rawData = $this->curl_get_contents('https://api.purse.io/api/v1/orders/me/inactive', $headers);
            $listener = new \JsonStreamingParser\Listener\InMemoryListener();
            $stream = fopen('php://memory', 'r+');
            fwrite($stream, $rawData);
            rewind($stream);
            try {
                $parser = new \JsonStreamingParser\Parser($stream, $listener);
                $parser->parse();
                fclose($stream);
            } catch (Exception $e) {
                fclose($stream);
                throw $e;
            }
            return $listener->getJson();
        }

        public function getActiveOrders($token) {
            $headers = $this->getPurseHeader($token);
            return json_decode($this->curl_get_contents('https://api.purse.io/api/v1/orders/me/active', $headers), true);
        }
        
        public function getUserInfo($token) {
            $headers = $this->getPurseHeader($token);
            return json_decode($this->curl_get_contents('https://api.purse.io/api/v1/users/me', $headers), true);
        }

        private function curl_get_contents($url, $headers = [], $cookie = '') {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch, CURLOPT_REFERER, 'https://purse.io/orders');
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

        private function curl_post_contents($url, $params = []) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }

        private function getPurseHeader($token) {
            return ["authority: api.purse.io",
                "method: GET",
                "scheme: https",
                "accept: application/json, text/javascript, */*; q=0.01",
                "accept-language: en-US,en;q=0.9,hy;q=0.8",
                "authorization: JWT $token",
                "cache-control: no-cache",
                "origin: https://purse.io",
                "pragma: no-cache",
                "referer: https://purse.io/orders",
                "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36"];
        }

        public function fetchFedexPageDetails($trackingNumber) {
            
        }

        public function fetchUpsPageDetails($trackingNumber) {
            
        }

        public function fetchUspsPageDetails($trackingNumber) {
            $uspsApiUserId = SettingManager::getInstance()->getSetting('usps_api_user_id');
            $xml = file_get_contents('http://production.shippingapis.com/ShippingApi.dll?API=TrackV2&XML=%3CTrackFieldRequest%20USERID=%22' . $uspsApiUserId . '%22%3E%20%3CTrackID%20ID=%22' . $trackingNumber . '%22%3E%20%3C/TrackID%3E%20%3C/TrackFieldRequest%3E');
            $object = simplexml_load_string($xml);
            if (!isset($object->TrackInfo) || !isset($object->TrackInfo->TrackSummary)) {
                return [null, null, null];
            }
            $event = $object->TrackInfo->TrackSummary->Event;
            if (strpos(strtolower($event), 'delivered') !== false) {
                $dateStr = $object->TrackInfo->TrackSummary->EventDate;
                $date = \DateTime::createFromFormat("M j, Y", $dateStr)->format('Y-m-d');
                return [$date, $event, json_encode($object)];
            } else {
                return [null, $event, json_encode($object)];
            }
        }

    }

}
