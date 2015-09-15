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

use crm\dal\mappers\ProductMapper;
use ngs\framework\exceptions\NgsErrorException;

    class ProductManager extends AdvancedAbstractManager {

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
                self::$instance = new ProductManager(ProductMapper::getInstance());
            }
            return self::$instance;
        }

        public function safeDeleteProduct($productId) {
            $solDtos = SaleOrderLineManager::getInstance()->selectByField('product_id', $productId);
            if (!empty($solDtos)) {
                return false;
            }
            $polDtos = PurchaseOrderLineManager::getInstance()->selectByField('product_id', $productId);
            if (!empty($polDtos)) {
                return false;
            }
            $this->deleteByPK($productId);
            return True;
        }

        public function createProduct($name, $model, $manufacturerId, $uomId) {
            $dto = $this->createDto();
            $dto->setName($name);
            $dto->setModel($model);
            $dto->setManufacturerId($manufacturerId);
            $dto->setUomId($uomId);
            return $this->insertDto($dto);
        }

        public function updateProduct($id, $name, $model, $manufacturerId, $uomId) {
            $dto = $this->selectByPK($id);
            if ($dto) {
                $dto->setName($name);
                $dto->setModel($model);
                $dto->setManufacturerId($manufacturerId);
                $dto->setUomId($uomId);
                return $this->updateByPk($dto);
            }
            return false;
        }

        public function getProductListFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            $rows = $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit);
            $manufacturerIds = array();
            $uomIds = array();
            foreach ($rows as $row) {
                $manufacturerIds[] = $row->getManufacturerId();
                $uomIds[] = $row->getUomId();
            }
            $manufacturerIds = array_unique($manufacturerIds);
            $uomIds = array_unique($uomIds);
            $manufacturerDtos = ManufacturerManager::getInstance()->selectByPKs($manufacturerIds, true);
            $uomDtos = UomManager::getInstance()->selectByPKs($uomIds, true);
            foreach ($rows as $row) {
                $row->setUomDto($uomDtos[$row->getUomId()]);
                $row->setManufacturerDto($manufacturerDtos[$row->getManufacturerId()]);
            }
            return $rows;
        }

        public function calculateProductQuantityInStock($productId) {
            $productPurchaseOrderLines = PurchaseOrderLineManager::getInstance()->getNonCancelledProductPurchaseOrders($productId);
            $productSoldCount = intval(SaleOrderLineManager::getInstance()->getProductCountInNonCancelledSaleOrders($productId));
            $ret = 0;
            foreach ($productPurchaseOrderLines as $productPurchaseOrderLine) {
                $ret += floatval($productPurchaseOrderLine->getQuantity());
            }
            $ret -= $productSoldCount;
            return $ret;
        }

        public function calculateProductsCost($productIds) {
            $productsPurchaseOrderLines = PurchaseOrderLineManager::getInstance()->getNonCancelledProductsPurchaseOrders($productIds);
            $productsSoldCount = SaleOrderLineManager::getInstance()->getProductsCountInNonCancelledSaleOrders($productIds);
            foreach ($productIds as $productId) {
                $productPurchaseOrderLines = array_key_exists($productId, $productsPurchaseOrderLines) ? $productsPurchaseOrderLines[$productId] : [];
                $productPurchaseOrderLines = $this->mapDtosById($productPurchaseOrderLines);
                $productSoldCount = array_key_exists($productId, $productsSoldCount) ? $productsSoldCount[$productId] : 0;
                $this->calculationProductId = $productId;
                $notSoldProductsPurchaseOrderLines[$productId] = $this->subtracPurchaseOrderLinesQuantityByProductSoldCount($productPurchaseOrderLines, $productSoldCount);
            }
            $profit_calculation_method = SettingManager::getInstance()->getSetting('profit_calculation_method');
            $ret = [];
            switch ($profit_calculation_method) {
                case 'max':
                    foreach ($productIds as $productId) {
                        $ret[$productId] = $this->findMaxProductPriceLineId($notSoldProductsPurchaseOrderLines[$productId]);
                    }
                    break;
                default:
                    foreach ($productIds as $productId) {
                        $ret[$productId] = $this->calculateAverageProductPriceinPurchaseOrderLines($notSoldProductsPurchaseOrderLines[$productId]);
                    }
                    break;
            }
            return $ret;
        }

        public function calculateProductCost($productId, $productSaleQty, $saleOrderId = 0) {
            $date = null;
            if ($saleOrderId > 0) {
                $so = SaleOrderManager::getInstance()->selectByPK($saleOrderId);
                if (!$so) {
                    throw new NgsErrorException('Sale Order does not exist! id: ' . $saleOrderId);
                }
                $date = $so->getOrderDate();
            }
            $this->calculationProductId = $productId;
            $productPurchaseOrderLines = PurchaseOrderLineManager::getInstance()->getNonCancelledProductPurchaseOrders($productId, $date);
            $productPurchaseOrderLines = $this->mapDtosById($productPurchaseOrderLines);
            $productSoldCount = intval(SaleOrderLineManager::getInstance()->getProductCountInNonCancelledSaleOrders($productId, $saleOrderId, $date));
            
            $this->subtracPurchaseOrderLinesQuantityByProductSoldCount($productPurchaseOrderLines, $productSoldCount);
            $ret = $this->removePurchaseOrderLinesQuantityByProductSale($productPurchaseOrderLines, $productSaleQty);
            return $ret;
        }

        private function removePurchaseOrderLinesQuantityByProductSale($productPurchaseOrderLines, $productSoldCount, $profit_calculation_method) {
            $ret = [];
            $profit_calculation_method = SettingManager::getInstance()->getSetting('profit_calculation_method');
            while (true) {
                if ($profit_calculation_method == 'max') {
                    $lineId = $this->findMaxProductPriceLineId($productPurchaseOrderLines);
                } else {
                    $lineId = $this->findFirstNonZeroQuantityLineId($productPurchaseOrderLines);
                }
                if ($lineId == 0) {
                    throw new NgsErrorException('Insuficient product! function removePurchaseOrderLinesQuantityByProductSale');
                }
                $pol = $productPurchaseOrderLines[$lineId];
                $quantity = floatval($pol->getQuantity());
                if ($quantity >= $productSoldCount) {
                    $pol->setQuantity($quantity - $productSoldCount);
                    $ret[] = [$productSoldCount, $pol->getUnitPrice() * $pol->getCurrencyRate()];
                    break;
                } else {
                    $ret[] = [$quantity, $pol->getUnitPrice() * $pol->getCurrencyRate()];
                    $pol->setQuantity(0);
                    $productSoldCount -= $quantity;
                }
            }
            return $ret;
        }

        private function subtracPurchaseOrderLinesQuantityByProductSoldCount($productPurchaseOrderLines, $productSoldCount) {
            if ($productSoldCount == 0) {
                return $productPurchaseOrderLines;
            }
            $profit_calculation_method = SettingManager::getInstance()->getSetting('profit_calculation_method');
            while (true) {

                if ($profit_calculation_method == 'max') {
                    $lineId = $this->findMaxProductPriceLineId($productPurchaseOrderLines);
                } else {
                    $lineId = $this->findFirstNonZeroQuantityLineId($productPurchaseOrderLines);
                }
                if ($lineId == 0) {
                    throw new NgsErrorException('Insuficient product! function subtracPurchaseOrderLinesQuantityByProductSoldCount sold count:' . $productSoldCount);
                }
                $pol = $productPurchaseOrderLines[$lineId];
                $quantity = floatval($pol->getQuantity());
                if ($quantity >= $productSoldCount) {
                    $pol->setQuantity($quantity - $productSoldCount);
                    break;
                } else {
                    $pol->setQuantity(0);
                    $productSoldCount -= $quantity;
                }
            }
            $ret = [];
            foreach ($productPurchaseOrderLines as $productPurchaseOrderLine) {
                if ($productPurchaseOrderLine->getQuantity() > 0) {
                    $ret [] = $productPurchaseOrderLine;
                }
            }
            return $ret;
        }

        private function findFirstNonZeroQuantityLineId($productPurchaseOrderLines) {
            foreach ($productPurchaseOrderLines as $lineId => $dto) {
                if ($dto->getQuantity() == 0) {
                    continue;
                }
                return $lineId;
            }
            throw new NgsErrorException('Insuficient product (function findFirstNonZeroQuantityLineId)!');
        }

        private function findMaxProductPriceLineId($productPurchaseOrderLines) {
            $maxProductPrice = 0;
            $maxProductPriceLineId = 0;
            foreach ($productPurchaseOrderLines as $lineId => $dto) {
                if ($dto->getQuantity() == 0) {
                    continue;
                }
                $unitPrice = floatval($dto->getUnitPrice());
                $currencyRate = floatval($dto->getCurrencyRate());
                $productPriceInMainCurrency = $unitPrice * $currencyRate;
                if ($productPriceInMainCurrency > $maxProductPrice) {
                    $maxProductPrice = $productPriceInMainCurrency;
                    $maxProductPriceLineId = $lineId;
                }
            }
            if ($maxProductPriceLineId == 0) {
                $product = ProductManager::getInstance()->selectByPK($this->calculationProductId);
                throw new NgsErrorException('Insuficient product (function findMaxProductPriceLineId)! product: ' . $product->getName() . " (id: $this->calculationProductId)");
            }
            return $maxProductPriceLineId;
        }

    }

}
    