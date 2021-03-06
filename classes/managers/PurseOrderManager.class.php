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
        private $fakeRecipientUnitAddressesStr;
        private $globalWhere = [];

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

        public function __construct($mapper) {
            $adminId = NGS()->getSessionManager()->getUserId();
            if ($adminId > 0) {
                $userType = \crm\managers\AdminManager::getInstance()->getById($adminId)->getType();
                if ($userType !== 'root') {
                    $this->globalWhere = [1000 => 'AND', 1001 => 'admin_id', 1002 => '=', 1003 => $adminId];
                }
            }
            parent::__construct($mapper);
            $this->fakeRecipientUnitAddressesStr = RecipientManager::getInstance()->getFakeRecipientUnitAddressesSql();
        }

        public static function getItemAsinFromUrl($url) {
            $pattern = "%/([a-zA-Z0-9]{10})(?:[/?]|$)%";
            $matches = [];
            preg_match($pattern, $url, $matches);
            if ($matches && isset($matches[1])) {
                $asin = $matches[1];
            } else {
                return false;
            }
            return $asin;
        }

        public function getItemPriceByAsin($asin, $maxTryCount = 3) {
            $content = "";
            $tryCount = 0;
            while (empty($content) && $tryCount < $maxTryCount) {
                $tryCount++;
                $content = $this->curl_get_contents('https://api.purse.io/api/v1/instant/item/' . $asin);
                $apiRes = json_decode($content, true);
                if (isset($apiRes['fiat_price'])) {
                    return floatval($apiRes['fiat_price']);
                }
                sleep(3);
            }
            return 0;
        }

        public function setFedexTrackingStatus($tracking_number, $primary_status, $error, $primary_status_date, $travel_history, $shipment_facts) {
            $rows = $this->selectByField('tracking_number', $tracking_number);
            if (!empty($rows)) {
                $row = $rows[0];
                $row->setCarrierTrackingStatus($primary_status);
                $row->setCarrierDeliveryDate($primary_status_date);
                $row->setCarrierTrackingHistory(json_encode($travel_history));
                $row->setCarrierShipmentFacts(json_encode($shipment_facts));
                $row->setTrackingNumberCheckedAt(date('Y-m-d H:i:s'));
                $row->set(json_encode($shipment_facts));
                if ($error == 1) {
                    $row->setProblematic(1);
                }
                $this->updateByPk($row);
            }
        }

        public function getFedexTrackingsToCheck() {
            $rows = $this->selectAdvance('*', ['hidden', '=', 0, 'AND',
                'status', 'not in', "('delivered')", 'AND',
                'shipping_carrier', '=', "'fedex'", 'AND',
                "length(COALESCE(`tracking_number`, ''))", '>', 5
                    ] + $this->globalWhere, 'tracking_number_checked_at', 'ASC', 0, 1);
            if (!empty($rows)) {
                $row = $rows[0];
                $trackingNumber = trim(preg_replace('/[^A-Za-z0-9\-]/', '', $row->getTrackingNumber()));
                $this->updateField($row->getId(), 'tracking_number', $trackingNumber);
                return $trackingNumber;
            }

            return false;
        }

        public function getExpectingProducts($products) {
            $productIds = [];
            foreach ($products as $product) {
                $productIds[] = $product->getId();
            }
            $productIdsSql = "(" . implode(',', $productIds) . ")";
            $orders = $this->selectAdvance('SUM(`quantity`) as qty , SUM(`amazon_total`) as total_amount, `product_id`', [
                'product_id', 'in', $productIdsSql, 'AND',
                'status', 'in', "('shipping', 'shipped', 'feedback', 'finished',  'partially_delivered', 'delivered', 'accepted')", 'AND',
                'hidden', '=', 0] + $this->globalWhere, 'id', 'desc', null, null, false, "", 'GROUP BY product_id');

            $ret = [];
            foreach ($orders as $order) {
                $ret[$order->product_id] = $order->qty;
            }
            return $ret;
        }

        public function getRecipientsRecentOrders($recipientIds) {
            $recipients = RecipientManager::getInstance()->selectByPKs($recipientIds);
            $partnerIdMappedByExpressUnitAddresses = [];
            foreach ($recipients as $recipient) {
                $expressUnitAddress = $recipient->getExpressUnitAddress();
                $expressUnitAddress1 = $recipient->getExpressUnitAddress_1();
                $onexExpressUnit = $recipient->getOnexExpressUnit();
                $onexExpressUnit1 = $recipient->getOnexExpressUnit_1();
                $novaExpressUnit = $recipient->getNovaExpressUnit();
                $novaExpressUnit1 = $recipient->getNovaExpressUnit_1();
                $shipexExpressUnit = $recipient->getShipexExpressUnit();
                $shipexExpressUnit1 = $recipient->getShipexExpressUnit_1();
                $cheapexExpressUnit = $recipient->getCheapexExpressUnit();
                $cheapexExpressUnit1 = $recipient->getCheapexExpressUnit_1();
                if (empty($expressUnitAddress) && empty($onexExpressUnit) && empty($novaExpressUnit) && empty($shipexExpressUnit) && empty($cheapexExpressUnit) &&
                        empty($expressUnitAddress1) && empty($onexExpressUnit1) && empty($novaExpressUnit1) && empty($shipexExpressUnit1) && empty($cheapexExpressUnit1)) {
                    continue;
                }
                if (!empty($expressUnitAddress)) {
                    $partnerIdMappedByExpressUnitAddresses[strtolower($expressUnitAddress)] = $recipient->getId();
                }
                if (!empty($expressUnitAddress1)) {
                    $partnerIdMappedByExpressUnitAddresses[strtolower($expressUnitAddress1)] = $recipient->getId();
                }
                if (!empty($onexExpressUnit)) {
                    $partnerIdMappedByExpressUnitAddresses[strtolower($onexExpressUnit)] = $recipient->getId();
                }
                if (!empty($onexExpressUnit1)) {
                    $partnerIdMappedByExpressUnitAddresses[strtolower($onexExpressUnit1)] = $recipient->getId();
                }
                if (!empty($shipexExpressUnit)) {
                    $partnerIdMappedByExpressUnitAddresses[strtolower($shipexExpressUnit)] = $recipient->getId();
                }
                if (!empty($shipexExpressUnit1)) {
                    $partnerIdMappedByExpressUnitAddresses[strtolower($shipexExpressUnit1)] = $recipient->getId();
                }
                if (!empty($cheapexExpressUnit)) {
                    $partnerIdMappedByExpressUnitAddresses[strtolower($cheapexExpressUnit)] = $recipient->getId();
                }
                if (!empty($cheapexExpressUnit1)) {
                    $partnerIdMappedByExpressUnitAddresses[strtolower($cheapexExpressUnit1)] = $recipient->getId();
                }
                if (!empty($novaExpressUnit)) {
                    $partnerIdMappedByExpressUnitAddresses[strtolower($novaExpressUnit)] = $recipient->getId();
                }
                if (!empty($novaExpressUnit1)) {
                    $partnerIdMappedByExpressUnitAddresses[strtolower($novaExpressUnit1)] = $recipient->getId();
                }
            }
            $unitAddressSql = "('" . implode("','", array_keys($partnerIdMappedByExpressUnitAddresses)) . "')";
            $firstDayOfMonth = date('Y-m-1');
            $orders = $this->selectAdvance('*', ['status', '<>', "'canceled'", 'AND',
                'unit_address', 'in', $unitAddressSql, 'AND',
                'ABS(DATEDIFF(`created_at`, date(now())))', '<=', 50, 'AND',
                '(', 'hidden', '=', 0, 'OR', 'hidden_at', '>=', "'$firstDayOfMonth'", ')'], 'id', 'desc');
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
                    $orders[] = ['created_at' => explode(' ', $order->getCreatedAt())[0], 'status' => $order->getStatus(), 'product_name' => $order->getProductName(),
                        'order_total' => $order->getAmazonTotal(), 'image_url' => $order->getImageUrl()];
                }

                $ret[$recId] = ['total' => $total, 'count' => count($recipientOrders), 'orders' => $orders];
            }
            return $ret;
        }

        public function getNotRegisteredOrdersInWarehouse($registeredTrackingNumbers, $local_carrier_name) {
            $registeredTrackingNumbers = array_map('trim', $registeredTrackingNumbers);
            $ordersThatHasTrackingNumbers = $this->selectAdvance('*', ['hidden', '=', 0, 'AND',
                'status', 'not in', "('open', 'under_balance', 'accepted', 'canceled', 'under_balance.confirming')", 'AND',
                "length(COALESCE(`amazon_order_number`,''))", '>', 5, 'AND',
                "length(COALESCE(`tracking_number`, ''))", '>', 3
                    ] + $this->globalWhere);
            $existingOrdersMappedByTrackingNumbers = [];
            foreach ($ordersThatHasTrackingNumbers as $order) {
                $trackingNumber = $order->getTrackingNumber();
                if (strlen(strval(trim($trackingNumber))) < 5) {
                    continue;
                }
                $carrierFirst2Letter = substr(strtolower($order->getUnitAddress()), 0, 2);
                if ($local_carrier_name === 'globbing' && ($carrierFirst2Letter == 'nv' || $carrierFirst2Letter == 'ar')) {
                    continue;
                }
                if ($local_carrier_name === 'onex' && $carrierFirst2Letter != 'ar') {
                    continue;
                }
                if ($local_carrier_name === 'nova' && $carrierFirst2Letter != 'nv') {
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

        public function confirmCheckoutOrder($id) {
            $dto = $this->selectByPk($id);
            $dto->setStatus('shipping');
            $dto->setCheckoutOrderStatus(5);
            $this->updateByPk($dto);
            return $dto->getExternalProductNumber();
        }

        public function getAllAccountNames($merchant) {
            $where = [];
            if (!empty($merchant) && $merchant !== 'all') {
                $where = ['account_name', 'like', "'%$merchant%'"];
            }
            $rows = $this->selectAdvance(['account_name'], $where, [], "", null, null, false, "", 'GROUP BY account_name');
            $accountNames = [];
            foreach ($rows as $row) {
                $accountNames[] = $row->getAccountName();
            }
            return $accountNames;
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
                'ABS(DATEDIFF(`created_at`, date(now())))', '<=', 200] + $this->globalWhere);
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
            $tracking = strtolower($tracking);
            foreach ($trackingsArray as $key => $tr) {
                $tr = strtolower($tr);
                if (stripos(strval($tracking), strval($tr)) !== false || stripos(strval($tr), strval($tracking)) !== false) {
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
            $content = $this->curl_get_contents($url);
            libxml_use_internal_errors(true);
            $xmlDoc = new \DOMDocument();
            $xmlDoc->loadHTML($content);
            $finder = new \DOMXPath($xmlDoc);
            $exceptionFound = $finder->query("//*[contains(@class, 'errorPage')]");
            if ($exceptionFound->length > 0) {
                $this->updateField($row->getId(), 'amazon_primary_status_text', 'Not found! maybe cancelled');
                $this->updateField($row->getId(), 'problematic', 1);
//                $this->updateField($row->getId(), 'cancelled_at', date('Y-m-d H:i:s'));
                return;
            }

            $ps = $finder->query("//*[@id='primaryStatus']");
            if ($ps->length > 0) {
                $primaryStatusText = trim($ps->item(0)->nodeValue);
                $this->updateField($row->getId(), 'amazon_primary_status_text', $primaryStatusText);
                if (strpos($primaryStatusText, 'cancel') !== false && $row->getExternal() == 1) {
                    $this->updateField($row->getId(), 'status', 'canceled');
                    $this->updateField($row->getId(), 'cancelled_at', date('Y-m-d H:i:s'));
                    $this->updateField($row->getId(), 'hidden', 1);
                }
            }
            $ordersRows = $finder->query("//*[contains(@class, 'cardContainer')]");
            libxml_clear_errors();
            $trackingNumber = false;
            for ($i = 0; $i < $ordersRows->length; $i++) {
                $el = $ordersRows->item($i);
                $trackingLinks = $el->getElementsByTagName('a');
                if ($trackingLinks->length > 0) {
                    $trackingNumber = $trackingLinks->item(0)->nodeValue;
                    $trackingNumber = trim(trim(trim(str_replace('Tracking ID', '', $trackingNumber)), ':'));
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

        public function changeCheckoutOrderStatus($orderId, $status) {
            $dto = $this->selectByField('checkout_order_id', $orderId);
            if (!empty($dto)) {
                $this->updateField($dto[0]->getId(), 'checkout_order_status', $status);
                return true;
            }
            return false;
        }

        public function addCheckoutOrder($orderId, $shipping_carrier, $customer_name, $asin, $productName, $qty, $price, $unitAddress, $imageUrl, $metadata) {
            $carrierUnitAddress = SettingManager::getInstance()->getSetting($shipping_carrier . '_unit_address');
            $id = $this->addManualOrder($productName, 0, $qty, $price, $carrierUnitAddress, $imageUrl, 0, $asin);
            $dto = $this->selectByPk($id);
            $dto->setCheckoutCustomerName($customer_name);
            $dto->setCheckoutOrderId($orderId);
            $dto->setCheckoutOrderMetadata($metadata);
            $dto->setStatus('open');
            $dto->setCheckoutCustomerUnitAddress($unitAddress);
            $this->updateByPk($dto);
        }

        public function addManualOrder($productName, $product_id, $qty, $price, $unitAddress, $imageUrl, $external = 1, $externalProductNumber = "") {
            $adminId = NGS()->getSessionManager()->getUserId();
            $dto = $this->createDto();
            if ($adminId > 0) {
                $dto->setAdminId($adminId);
            }
            $dto->setProductName($productName);
            $dto->setImageUrl($imageUrl);
            $dto->setProductId($product_id);
            $dto->setQuantity($qty);
            $dto->setDiscount(0);
            $dto->setAmazonTotal($price);
            $dto->setAccountName('amazon');
            $dto->setStatus('shipping');
            $dto->setExternal($external);
            $dto->setExternalProductNumber($externalProductNumber);
            $dto->setUnitAddress($unitAddress);
            $fakeRecipientUnitAddressesArray = RecipientManager::getInstance()->getFakeRecipientUnitAddressesArray();
            if (preg_grep("/$unitAddress/i", $fakeRecipientUnitAddressesArray)) {
                $dto->setOriginalUnitAddress($unitAddress);
            }
            $shippingType = RecipientManager::getInstance()->getShippingTypeByUnitAddress($unitAddress);
            $dto->setShippingType($shippingType);
            $recipient = RecipientManager::getInstance()->getRecipientByUnitAddress($unitAddress);
            if (!empty($recipient)) {
                $dto->setRecipientName($recipient->getFirstName() . ' ' . $recipient->getLastName());
            }
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
                $dto->setCancelledAt(date('Y-m-d H:i:s'));
            }
            $productNames = [];
            $totalQty = 0;
            $totalProductNamesLength = 0;
            $productNamesCount = 0;
            foreach ($order['items'] as $item) {
                if (isset($item['quantity']) && isset($item['name'])) {
                    $totalProductNamesLength += strlen($item['name']);
                    $productNamesCount += 1;
                }
            }
            $pordcutNamesMidLength = intval(max(500, $totalProductNamesLength) / $productNamesCount);
            foreach ($order['items'] as $item) {
                if (isset($item['quantity']) && isset($item['name'])) {
                    $productNames[] = $item['quantity'] . ' x ' . mb_strimwidth($item['name'], 0, $pordcutNamesMidLength, "...");
                }
                if (isset($item['quantity'])) {
                    $totalQty += intval($item['quantity']);
                }
            }
            $dto->setProductName(implode('<br><br>', $productNames));
            $dto->setImageUrl($order['items'][0]['images']['small']);
            $dto->setQuantity($totalQty);
            $dto->setAmazonOrderNumber($order['shipping']['purchase_order']);
            $unitAddress = trim($order['shipping']['verbose']['street2']);
            if (empty($dtos)) {
                $dto->setUnitAddress($unitAddress);
                $fakeRecipientUnitAddressesArray = RecipientManager::getInstance()->getFakeRecipientUnitAddressesArray();
                if (preg_grep("/$unitAddress/i", $fakeRecipientUnitAddressesArray)) {
                    $dto->setOriginalUnitAddress($unitAddress);
                }
                $dto->setRecipientName($order['shipping']['verbose']['full_name']);
            }
            $shippingType = RecipientManager::getInstance()->getShippingTypeByUnitAddress($unitAddress);
            $dto->setShippingType($shippingType);
            $dto->setDeliveryDate($order['shipping']['delivery_date']);
            $dto->setUnreadMessages($order['unread_messages']);
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
                            ] + $this->globalWhere);
        }

        public function getTrackingFetchNeededOrders() {
            return $this->selectAdvance(['id', 'amazon_order_number', 'external'], ['hidden', '=', 0, 'AND',
                        'status', 'in', "('shipping', 'shipped', 'feedback', 'finished',  'partially_delivered', 'delivered', 'accepted')", "AND",
                        "length(COALESCE(`amazon_order_number`,''))", '>', 5, 'AND',
                        "length(COALESCE(`tracking_number`, ''))", '<', 3, 'AND',
                        "length(COALESCE(`serial_number`,''))", '<', 5] + $this->globalWhere);
        }

        public function getProblematicOrders($where, $checoutOnly = false) {
            if (!empty($checoutOnly)) {
                $where = array_merge($where, ['AND', '(', 'unit_address', 'in', "($this->fakeRecipientUnitAddressesStr)", 'OR', 'checkout_order_id', '>', 0, ')'] + $this->globalWhere);
            } else {
                $where = array_merge($where, ['AND', '(', 'unit_address', 'not in', "($this->fakeRecipientUnitAddressesStr)", 'OR', 'checkout_order_id', 'IS NULL', ')'] + $this->globalWhere);
            }

            $days = intval(SettingManager::getInstance()->getSetting('btc_products_days_diff_for_delivery_date'));
            return $this->selectAdvance('*', array_merge($where, ['AND', 'problem_solved', '=', 0, 'AND',
                                '(',
                                'problematic', '=', 1, 'OR', 'amazon_primary_status_text', 'like', "'%cancel%'", 'OR', 'amazon_primary_status_text', 'like', "'%Was expected%'",
                                'OR', "length(COALESCE(`unit_address`,''))", '<', 2, 'OR',
                                "`shipping_type`", 'not in', "('express', 'standard')", 'OR',
                                '(',
                                'status', 'in', "('shipping', 'shipped', 'feedback', 'finished',  'partially_delivered', 'delivered', 'accepted')", 'AND',
                                "length(COALESCE(`serial_number`,''))", '<', 2, 'AND', 'ABS(DATEDIFF(`delivery_date`, date(now())))', '>=', $days,
                                ')',
                                ')'
                                    ] + $this->globalWhere));
        }

        public function getOrdersPuposedToNotReceivedToDestinationCounty($checoutOnly = false) {
            if (!empty($checoutOnly)) {
                $where = ['(', 'unit_address', 'in', "($this->fakeRecipientUnitAddressesStr)", 'OR', 'checkout_order_id', '>', 0, ')'];
            } else {
                $where = ['(', 'unit_address', 'not in', "($this->fakeRecipientUnitAddressesStr)", 'OR', 'checkout_order_id', 'IS NULL', ')'];
            }
            $where = array_merge($where, ['AND', 'hidden', '=', 0, 'AND', 'admin_id', '<>', '9', 'AND',
                '(',
                '(',
                'status', 'in', "('shipping', 'shipped', 'accepted')", 'AND',
                "length(COALESCE(`serial_number`,''))", '<', 2,
                //'AND', 'ABS(DATEDIFF(`delivery_date`, date(now())))', '<=', intval(SettingManager::getInstance()->getSetting('btc_products_days_diff_for_delivery_date')),
                ')',
                ')'
                    ] + $this->globalWhere);
            $rows1 = $this->selectAdvance('*', $where);

            //if delivery date in none
            if (!empty($checoutOnly)) {
                $where = ['(', 'unit_address', 'in', "($this->fakeRecipientUnitAddressesStr)", 'OR', 'checkout_order_id', '>', 0, ')'];
            } else {
                $where = ['(', 'unit_address', 'not in', "($this->fakeRecipientUnitAddressesStr)", 'OR', 'checkout_order_id', 'IS NULL', ')'];
            }
            $where = array_merge($where, ['AND', 'hidden', '=', 0, 'AND',
                'status', 'in', "('feedback', 'finished',  'partially_delivered', 'delivered')", 'AND',
                "length(COALESCE(`serial_number`,''))", '<', 2, 'AND',
                'ABS(DATEDIFF(`created_at`, date(now())))', '<=', 30] + $this->globalWhere);
            $rows2 = $this->selectAdvance('*', $where);
            return array_merge($rows1, $rows2);
        }

        public function getOrders($where = [1, '=', 1], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            $where = $where + $this->globalWhere;
            return $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit, true);
        }

        public function getInactiveOrders($token) {
            $headers = $this->getPurseHeader($token);
            $rawData = $this->curl_get_contents('https://api.purse.io/api/v1/orders/me/inactive?limit=2000&offset=0', $headers);
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
            return json_decode($this->curl_get_contents('https://api.purse.io/api/v1/orders/me/active?limit=500&offset=0', $headers), true);
        }

        public function getUserInfo($token, &$raw = '') {
            $headers = $this->getPurseHeader($token);
            $raw = $this->curl_get_contents('https://api.purse.io/api/v1/users/me', $headers);
            return json_decode($raw, true);
        }

        private function curl_get_contents($url, $headers = [], $cookie = '') {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 21);
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch, CURLOPT_COOKIE, 'fdx_cbid=29533490681543402773715880209861; _abck=40750C5D7E7E4A5AF9E6A8A82820200E5F64B535A46100001575FE5B47206F3E~0~L25Xb7VekYfHPlXX4oDG7XUuam8ci9sztN7xoT0oHu0=~-1~-1; siteDC=edc; bm_sz=3E3147750A29A238EE641607A3DC3E8C~QAAQFbVkXxVuDPFnAQAAuM5kGgOO8bsfB80yXKoRa+gm4dF/zI6yFIgzR7aC6Iy5rVP9U+2kz5CJEq/fPI8gFiW/VreyixtDPrj3Y3IkdfHQBErqGsyPfOYC/FaFLNFelmoDRcSmoOOHZqDQ6XW8xmDHB7YhieuosIKy9a85FScVBXu4Q7z6ohs2izMSlA==; ak_bmsc=37E68A74E6EA317AB7D46E68B539AB015F64B5154562000080B72F5C34CF8A33~plXBkfCjGHpOWmPyUbva/olW3C8gyHNf8wtdYDpZtJDI582Q8VVwXZU4PFq0ze8P6hBxpYnSrO/XcZMicN2LR1Cgo4/AeOnJ8pviYosT0kRivqUJhs5k3XLcEiY4C2LpYPzVN2ZN7cunWiUcvAOncYnJa5s1R2FQqHzjUw06SxAJILvXaQPg9TMtf5UbxnIKpnuGFur45ZmplkyVcvEyRM2l4aeqH8qnr/vvMiPfKira8YUQbWakArpRnhklOJ8MmV; AMCVS_1E22171B520E93BF0A490D44%40AdobeOrg=1; AMCV_1E22171B520E93BF0A490D44%40AdobeOrg=817868104%7CMCIDTS%7C17901%7CMCMID%7C51651217412831998371301283322744154880%7CMCAAMLH-1547235845%7C6%7CMCAAMB-1547235845%7CRKhpRz8krg2tLO6pguXWp5olkAcUniQYPHaMWWgdJ3xzPWQmdj0y%7CMCOPTOUT-1546638245s%7CNONE%7CMCAID%7CNONE; fdx_locale=en_US; wdpl_id=29533490681543402773715880209861_1546631384579; tracking_locale=en_US; mbox=session#1546631043829-266982#1546633933; s_pers=%20s_skwcvp%3D%255B%255B%2527O%25253AKeyword%252520Unavailable%2527%252C%25271544777816078%2527%255D%255D%7C1702544216078%3B%20gpv_pageName%3Dus%252Fen%252Ffedex%252Funified%252Ftrackdetailspage%7C1546633511358%3B%20s_nr%3D1546631711372-Repeat%7C1578167711372%3B%20s_invisit%3Dtrue%7C1546633511380%3B%20s_tbm%3D1%7C1546633511386%3B%20s_dfa%3Dfedexeu%252Cfedexglbl%252Cfedexus%7C1546633872569%3B; s_sess=%20s_cm%3DundefinedTyped%252FBookmarkedTyped%252FBookmarkedundefined%3B%20s_cpc%3D0%3B%20s_ppv%3Dus%252Fen%252Ffedex%252Funified%252Ftrackdetailspage%3B%20s_cc%3Dtrue%3B%20s_visit%3D1%3B%20SC_LINKS%3D%3B%20setLink%3D%3B; Nina-nina-fedex-session=%7B%22locale%22%3A%22en_us%22%2C%22lcstat%22%3Afalse%7D; bm_sv=339C99DAD9F13B21F3839F6A51454C44~fxtshoxIGhZ/+g/jDRUORSeeuNl9uPTwCWQJbjtXQuuGTzKggm9EMc+KsGe6xoLb58/b/5IK+QfJ1zuwLN1c7LlsYTY5+ZKNUIX/+FatpPHQOwQJGp0UAFxRyPGZrOCmAp4/nNZkeG69MPUpYKsOaOj5IxJdT+ol0jRpPwhVKIc=');

            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }

        private function getPurseHeader($token) {
            return [
            "authority: api.purse.io",
            "method: GET",
            "scheme: https",
            "accept: application/json, text/javascript, */*; q=0.01",
            "accept-encoding: gzip, deflate, br",
            "accept-language: en-US,en;q=0.9,hy;q=0.8",
            "authorization: JWT $token",
            "origin: https://purse.io",
            "pragma: no-cache",
            "referer: https://purse.io/orders",
            "sec-fetch-dest: empty",
            "sec-fetch-mode: cors",
            "sec-fetch-site: same-site",
            "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36"];
        }
        
        public function fetchFedexPageDetails($trackingNumber) {
//            $url = 'https://www.fedex.com/trackingCal/track';
//            $params = [
//                'data' => '{"TrackPackagesRequest":{"appType":"WTRK","appDeviceType":"DESKTOP","supportHTML":true,"supportCurrentLocation":true,"uniqueKey":"","processingParameters":{},"trackingInfoList":[{"trackNumberInfo":{"trackingNumber":"'.trim($trackingNumber).'","trackingQualifier":"","trackingCarrier":""}}]}}',
//                'action' => 'trackpackages',
//                'locale' => 'en_US',
//                'version' => 1,
//                'format' => 'json'
//            ];
//            return $this->curl_post_contents($url, $params);
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
