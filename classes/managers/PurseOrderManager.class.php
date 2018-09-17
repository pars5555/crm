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

        public function findByTrackingNumbers($trackingNumbers) {
            $trackingNumbers = array_map('trim', $trackingNumbers);

            $notReceivedOrders = $this->selectAdvance(
                    ['tracking_number', 'recipient_name', 'quantity', 'product_name',
                'amazon_total', 'account_name'], ['hidden', '=', 0, 'AND',
                'ABS(DATEDIFF(`created_at`, date(now())))', '<=', 200]);
            $notReceivedOrdersMappedByTracking = [];
            foreach ($notReceivedOrders as $order) {
                $trackingNumber = $order->getTrackingNumber();
                if (strlen(trim($trackingNumber)) < 5) {
                    continue;
                }
                $notReceivedOrdersMappedByTracking[$trackingNumber] = $order;
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
                }
            }
            return $ret;
        }

        private function findTrackingInArray($tracking, $trackingsArray) {
            foreach ($trackingsArray as $key => $tr) {
                if (strpos($tracking, $tr) !== false or strpos($tr, $tracking) !== false) {
                    return $key;
                }
            }
            return -1;
        }

        public function emptyAccount($account) {
            $ids = $this->getTrackingFetchNeededOrdersRowIds();
            $idsSql = '(0)';

            if (!empty($ids)) {
                $idsSql = '(' . implode(',', $ids) . ')';
            }
            $this->deleteAdvance(['account_name', '=', "'$account'", 'AND', 'id', 'not in', $idsSql]);
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

        public function getOrdersPuposedToNotReceivedToDestinationCounty() {
            return $this->selectAdvance('*', ['hidden', '=', 0, 'AND',
                        'status', 'in', "('shipping', 'shipped', 'feedback', 'finished',  'partially_delivered', 'delivered', 'accepted')", 'AND',
                        'ABS(DATEDIFF(`delivery_date`, date(now())))', '<=', 13]);
        }

        public function getOrders($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            return $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit, true);
        }

        private function getTrackingFetchNeededOrdersRowIds() {
            $trackingFetchNeededOrders = $this->getTrackingFetchNeededOrders();
            $ids = [];
            foreach ($trackingFetchNeededOrders as $order) {
                $ids [] = $order->getId();
            }
            return $ids;
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
