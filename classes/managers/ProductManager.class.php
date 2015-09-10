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
            $notSoldProductPurchaseOrderLines = $this->subtracPurchaseOrderLinesQuantityByProductSoldCount($productPurchaseOrderLines, $productSoldCount);
            $ret = 0;
            foreach ($notSoldProductPurchaseOrderLines as $productPurchaseOrderLine) {
                $ret += floatval($productPurchaseOrderLine->getQuantity());
            }
            return $ret;
        }

        public function calculateProductsCost($productIds) {
            $productsPurchaseOrderLines = PurchaseOrderLineManager::getInstance()->getNonCancelledProductsPurchaseOrders($productIds);
            $productsSoldCount = SaleOrderLineManager::getInstance()->getProductsCountInNonCancelledSaleOrders($productIds);
            foreach ($productIds as $productId) {
                $productPurchaseOrderLines = array_key_exists($productId, $productsPurchaseOrderLines) ? $productsPurchaseOrderLines[$productId] : [];
                $productSoldCount = array_key_exists($productId, $productsSoldCount) ? $productsSoldCount[$productId] : 0;
                $notSoldProductsPurchaseOrderLines[$productId] = $this->subtracPurchaseOrderLinesQuantityByProductSoldCount($productPurchaseOrderLines, $productSoldCount);
            }
            $product_calculation_method = SettingManager::getInstance()->getSetting('product_calculation_method');
            $ret = [];
            switch ($product_calculation_method) {
                case 'max':
                    foreach ($productIds as $productId) {
                        $ret[$productId] = $this->findMaximumProductPriceInPurchaseOrderLines($notSoldProductsPurchaseOrderLines[$productId]);
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

        public function calculateProductCost($productId) {
            $productPurchaseOrderLines = PurchaseOrderLineManager::getInstance()->getNonCancelledProductPurchaseOrders($productId);
            $productSoldCount = intval(SaleOrderLineManager::getInstance()->getProductCountInNonCancelledSaleOrders($productId));
            $notSoldProductPurchaseOrderLines = $this->subtracPurchaseOrderLinesQuantityByProductSoldCount($productPurchaseOrderLines, $productSoldCount);
            $product_calculation_method = SettingManager::getInstance()->getSetting('product_calculation_method');
            switch ($product_calculation_method) {
                case 'max':
                    return $this->findMaximumProductPriceInPurchaseOrderLines($notSoldProductPurchaseOrderLines);
                default:
                    return $this->calculateAverageProductPriceinPurchaseOrderLines($notSoldProductPurchaseOrderLines);
            }
        }

        private function findMaximumProductPriceInPurchaseOrderLines($productPurchaseOrderLines) {
            $maxPrice = 0;
            foreach ($productPurchaseOrderLines as $productPurchaseOrderLine) {
                $unitPrice = floatval($productPurchaseOrderLine->getUnitPrice());
                $currencyRate = floatval($productPurchaseOrderLine->getCurrencyRate());
                if ($unitPrice * $currencyRate > $maxPrice) {
                    $maxPrice = $unitPrice * $currencyRate;
                }
            }
            return $maxPrice;
        }

        private function calculateAverageProductPriceinPurchaseOrderLines($productPurchaseOrderLines) {
            $sum = 0;
            $count = 0;
            foreach ($productPurchaseOrderLines as $productPurchaseOrderLine) {
                $unitPrice = floatval($productPurchaseOrderLine->getUnitPrice());
                $currencyRate = floatval($productPurchaseOrderLine->getCurrencyRate());
                $quantity = floatval($productPurchaseOrderLine->getQuantity());
                $sum += $unitPrice * $currencyRate * $quantity;
                $count += $quantity;
            }
            return $count > 0 ? $sum / $count : 0;
        }

        private function subtracPurchaseOrderLinesQuantityByProductSoldCount($productPurchaseOrderLines, $productSoldCount) {
            foreach ($productPurchaseOrderLines as $productPurchaseOrderLine) {
                $quantity = floatval($productPurchaseOrderLine->getQuantity());
                if ($quantity >= $productSoldCount) {
                    $productPurchaseOrderLine->setQuantity($quantity - $productSoldCount);
                    break;
                } else {
                    $productPurchaseOrderLine->setQuantity(0);
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

    }

}
    