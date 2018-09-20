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

        public function findByTrackingNumbers($trackingNumbers, $getNotFounds = true) {
            $trackingNumbers = array_map('trim', $trackingNumbers);
            $notReceivedOrders = $this->selectAdvance(
                    ['id','tracking_number', 'recipient_name', 'quantity', 'product_name',
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
                }else
                {
                    if ($getNotFounds){
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

     
        public function insertOrUpdateOrderFromPurseObject($accountName, $order) {
            $dtos = $this->selectByField('order_number', $order['id']);
            if (!empty($dtos)) {
                $dto = $dtos[0];
            } else {
                $dto = $this->createDto();
            }
            $dto->setOrderNumber($order['id']);
            $dto->setStatus($order['state']);
            $dto->setProductName($order['items'][0]['name']);
            $dto->setImageUrl($order['items'][0]['images']['small']);
            $dto->setQuantity($order['items'][0]['quantity']);
            $dto->setAmazonOrderNumber($order['shipping']['purchase_order']);
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
                $this->insertDto($dto);
            }
        }

        public function getNotDeliveredToWarehouseOrdersThatHasNotTrackingNumber() {
            return $this->selectAdvance('*', ['hidden', '=', 0, 'AND',
                        'status', 'in', "('shipping', 'shipped', 'feedback', 'finished',  'partially_delivered', 'delivered', 'accepted')", 'AND',
                "length(COALESCE(`amazon_order_number`,''))", '>', 5, 'AND',
                "length(COALESCE(`tracking_number`, ''))", '<', 3, 'AND', 
                "length(COALESCE(`real_delivery_date`, ''))", '<', 3, 'AND',
                ]);
        }
        
        public function getOrdersPuposedToNotReceivedToDestinationCounty() {
            return $this->selectAdvance('*', ['hidden', '=', 0, 'AND',
                        'status', 'in', "('shipping', 'shipped', 'feedback', 'finished',  'partially_delivered', 'delivered', 'accepted')", 'AND',
                        "length(COALESCE(`serial_number`,''))", '<', 2 , 'AND',
                        'ABS(DATEDIFF(`delivery_date`, date(now())))', '<=', intval(\crm\managers\SettingManager::getInstance()->getSetting('btc_products_days_diff_for_delivery_date'))]);
        }

        public function getOrders($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            return $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit, true);
        }
        
        public function getTrackingFetchNeededOrders() {
            return $this->selectAdvance(['id', 'amazon_order_number'], 
                    ['hidden', '=', 0, 'AND',
                        'status', 'in', "('shipping', 'shipped', 'feedback', 'finished',  'partially_delivered', 'delivered', 'accepted')", "AND",
                        "length(COALESCE(`amazon_order_number`,''))", '>', 5, 'AND', 
                        "length(COALESCE(`tracking_number`, ''))", '<', 3, 'AND', 
                        "length(COALESCE(`serial_number`,''))", '<', 5]);
        }

        public function getInactiveOrders($token) {
            $headers = $this->getPurseOrdersHeader($token);
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
            $headers = $this->getPurseOrdersHeader($token);
            return json_decode($this->curl_get_contents('https://api.purse.io/api/v1/orders/me/active', $headers), true);
        }

        private function curl_get_contents($url, $headers = [], $cookie = '') {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_ENCODING, "");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
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

        private function getPurseOrdersHeader($token) {
            return ["authority: api.purse.io",
                "method: GET",
                "path: /api/v1/orders/me/active",
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

    }

}
