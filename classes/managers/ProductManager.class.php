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
    use crm\exceptions\InsufficientProductException;
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

        public function setProductHidden($id, $hidden) {
            $this->mapper->updateField($id, 'hidden', $hidden);
        }

        public function setProductQtyChecked($id, $qty_checked) {
            $this->mapper->updateField($id, 'qty_checked', $qty_checked);
        }

        public function getMostSimilarProduct($name) {
            $products = ProductManager::getInstance()->selectAll();
            $maxPercent = 0;
            $selectedProduct = null;
            $allProductSortBySimilatity = [];
            foreach ($products as $product) {
                $percent = 0;
                similar_text($product->getName(), $name, $percent);
                if (!isset($allProductSortBySimilatity[$percent])) {
                    $allProductSortBySimilatity[$percent] = [];
                }
                $allProductSortBySimilatity[$percent][] = $product;
                if ($percent > $maxPercent) {
                    $maxPercent = $percent;
                    $selectedProduct = $product;
                }
            }

            krsort($allProductSortBySimilatity);
            if ($maxPercent > 78) {
                return [$selectedProduct, $this->array_flatten($allProductSortBySimilatity)];
            }
            return [null, $this->array_flatten($allProductSortBySimilatity)];
        }

        private function array_flatten($array) {
            if (!is_array($array)) {
                return FALSE;
            }
            $result = array();
            foreach ($array as $value) {
                if (is_array($value)) {
                    $result = array_merge($result, $this->array_flatten($value));
                } else {
                    $result[] = $value;
                }
            }
            return $result;
        }

        public function findAndSetProoductImageFromPurseOrders($product, $allOrders) {
            $maxPercent = 0;
            $productImage = "";
            foreach ($allOrders as $order) {
                $percent = 0;
                similar_text($product->getName(), $order->getProductName(), $percent);
                if ($percent > $maxPercent) {
                    $maxPercent = $percent;
                    $productImage = $order->getImageUrl();
                }
            }
            if ($maxPercent > 78) {
                $this->updateField($product->getId(), 'image_url', $productImage);
            }
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

        public function updateProductWeight($productId, $weight) {
            return $this->mapper->updateField($productId, 'unit_weight', $weight);
        }

        public function createProduct($name, $model, $manufacturerId = 1, $uomId = 1, $weight = 0.1) {
            $dto = $this->createDto();
            $dto->setName($name);
            $dto->setModel($model);
            $dto->setManufacturerId($manufacturerId);
            $dto->setUomId($uomId);
            $dto->setUnitWeight($weight);
            $id = $this->insertDto($dto);
            $dto->setId($id);
            $this->findAndSetProoductImageFromPurseOrders($dto, PurseOrderManager::getInstance()->selectAdvance(['id', 'product_name', 'image_url'], [], [], "", null, null, false, "", 'GROUP BY product_name'));
            return $id;
        }

        public function updateProduct($id, $name, $model, $manufacturerId = 1, $uomId = 1, $weight = 0) {
            $dto = $this->selectByPk($id);
            if ($dto) {
                $dto->setName($name);
                $dto->setModel($model);
                $dto->setManufacturerId($manufacturerId);
                $dto->setUomId($uomId);
                $dto->setUnitWeight($weight);
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
            $ret = [];
            foreach ($rows as $row) {
                $ret[$row->getId()] = $row;
            }
            return $ret;
        }

        public function calculateProductQuantityInStock($productId) {
            $productPurchaseOrderLines = PurchaseOrderLineManager::getInstance()->getNonCancelledProductPurchaseOrders($productId);
            $productSoldCount = floatval(SaleOrderLineManager::getInstance()->getProductCountInNonCancelledSaleOrders($productId));
            $ret = 0;
            foreach ($productPurchaseOrderLines as $productPurchaseOrderLine) {
                $ret += floatval($productPurchaseOrderLine->getQuantity());
            }
            $ret -= $productSoldCount;
            return $ret;
        }

        public function calculateProductCost($productId, $productSaleQty, $saleOrderId = 0, $includePartnerWarehase = False, $ignoreInsufficientProduct = false) {
            $date = null;
            if ($saleOrderId > 0) {
                $so = SaleOrderManager::getInstance()->selectByPk($saleOrderId);
                if (!$so) {
                    throw new NgsErrorException('Sale Order does not exist! id: ' . $saleOrderId);
                }
                $date = $so->getOrderDate();
            }
            $this->calculationProductId = $productId;
            $excludePartnerIds = '0';
            if ($includePartnerWarehase) {
                $excludePartnerIds = SettingManager::getInstance()->getSetting('warehouse_partners');
            }
            $productPurchaseOrderLines = PurchaseOrderLineManager::getInstance()->getNonCancelledProductPurchaseOrders($productId, $date, $excludePartnerIds);
            $productPurchaseOrderLines = $this->mapDtosById($productPurchaseOrderLines);
            $productSaleOrderLines = SaleOrderLineManager::getInstance()->getNonCancelledProductSaleOrders($productId, $saleOrderId, $date, $excludePartnerIds);
            $productPurchaseOrderLines = $this->subtracPurchaseOrderLinesByProductSaleOrders($productPurchaseOrderLines, $productSaleOrderLines, $ignoreInsufficientProduct);
            $ret = $this->removePurchaseOrderLinesQuantityByProductSale($productPurchaseOrderLines, $productSaleQty, $date);
            return $ret;
        }

        public function updateProductCostForOneUnit($productId) {
            $productUnitCostInBaseCurrency = ProductManager::getInstance()->calculateProductCost($productId, 1, 0);
            $productDto = ProductManager::getInstance()->selectByPk($productId);
            $productDto->setUnitCost($this->calculateProductTotalCost($productUnitCostInBaseCurrency));
            ProductManager::getInstance()->updateByPK($productDto);
        }

        private function removePurchaseOrderLinesQuantityByProductSale($productPurchaseOrderLines, $productSoldCount, $date) {
            $ret = [];
            if (empty($productPurchaseOrderLines)) {
                return 0;
            }
            $profit_calculation_method = SettingManager::getInstance()->getSetting('profit_calculation_method');
            while (true) {
                if ($profit_calculation_method == 'max') {
                    $lineId = $this->findMaxProductPriceLineId($productPurchaseOrderLines, $date);
                } elseif ($profit_calculation_method == 'min') {
                    $lineId = $this->findMinProductPriceLineId($productPurchaseOrderLines);
                } elseif ($profit_calculation_method == 'average') {
                    $lineId = $this->findAverageProductPriceLineId($productPurchaseOrderLines);
                } else {
                    $lineId = $this->findFirstNonZeroQuantityLineId($productPurchaseOrderLines);
                }

                if ($lineId == 0) {
                    return $ret;
                }
                $pol = $productPurchaseOrderLines[$lineId];
                $quantity = floatval($pol->getQuantity());
                if ($quantity >= $productSoldCount) {
                    $pol->setQuantity(round($quantity - $productSoldCount, 2));
                    $ret[] = [round($productSoldCount, 2), round($pol->getUnitPrice() * $pol->getCurrencyRate(), 2)];
                    break;
                } else {
                    $ret[] = [round($quantity, 2), round($pol->getUnitPrice() * $pol->getCurrencyRate(), 2)];
                    $pol->setQuantity(0);
                    $productSoldCount = round($productSoldCount - $quantity, 2);
                }
            }
            return $ret;
        }

        public function calculateProductTotalCost($productUnitCostInBaseCurrency) {
            $ret = 0;
            if (empty($productUnitCostInBaseCurrency)) {
                return 0;
            }
            $totalQty = 0;
            foreach ($productUnitCostInBaseCurrency as $pair) {
                $qty = floatval($pair[0]);
                $unitPrice = floatval($pair[1]);
                $ret += $qty * $unitPrice;
                $totalQty += $qty;
            }
            return $ret / $totalQty;
        }

        private function subtracPurchaseOrderLinesByProductSaleOrders($productPurchaseOrderLines, $productSaleOrderLines, $ignoreInsufficientProduct = False) {
            if (empty($productSaleOrderLines)) {
                return $productPurchaseOrderLines;
            }
            $profit_calculation_method = SettingManager::getInstance()->getSetting('profit_calculation_method');
            foreach ($productSaleOrderLines as $productSaleOrderLine) {
                $productSaleOrderLineQty = $productSaleOrderLine->getQuantity();
                while ($productSaleOrderLineQty > 0) {
                    if ($profit_calculation_method == 'max') {
                        $lineId = $this->findMaxProductPriceLineId($productPurchaseOrderLines, $productSaleOrderLine->getOrderDate());
                    } elseif ($profit_calculation_method == 'min') {
                        $lineId = $this->findMinProductPriceLineId($productPurchaseOrderLines, $productSaleOrderLine->getOrderDate());
                    } elseif ($profit_calculation_method == 'average') {
                        $lineId = $this->findAverageProductPriceLineId($productPurchaseOrderLines, $productSaleOrderLine->getOrderDate());
                    } else {
                        $lineId = $this->findFirstNonZeroQuantityLineId($productPurchaseOrderLines);
                    }
                    if ($lineId == 0) {
                        if (!$ignoreInsufficientProduct) {
                            throw new InsufficientProductException($this->calculationProductId);
                        }
                        var_dump($this->calculationProductId);
                        $productSaleOrderLineQty -= $quantity;
                        continue;
                    }
                    $pol = $productPurchaseOrderLines[$lineId];
                    $quantity = floatval($pol->getQuantity());
                    if ($quantity >= $productSaleOrderLineQty) {
                        $pol->setQuantity($quantity - $productSaleOrderLineQty);
                    } else {
                        $pol->setQuantity(0);
                    }
                    $productSaleOrderLineQty -= $quantity;
                }
            }
            $ret = [];
            foreach ($productPurchaseOrderLines as $productPurchaseOrderLine) {
                if ($productPurchaseOrderLine->getQuantity() > 0) {
                    $ret [] = $productPurchaseOrderLine;
                }
            }

            return $this->mapDtosById($ret);
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

        private function findMaxProductPriceLineId($productPurchaseOrderLines, $beforeDate = null) {
            $maxProductPrice = 0;
            $maxProductPriceLineId = 0;
            foreach ($productPurchaseOrderLines as $lineId => $dto) {
                if ($dto->getQuantity() == 0 || (!empty($beforeDate) && $dto->getOrderDate() > $beforeDate)) {
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
            return $maxProductPriceLineId;
        }

        private function findMinProductPriceLineId($productPurchaseOrderLines, $beforeDate = null) {
            $minProductPrice = PHP_INT_MAX;
            $minProductPriceLineId = 0;
            foreach ($productPurchaseOrderLines as $lineId => $dto) {
                if ($dto->getQuantity() == 0 || (!empty($beforeDate) && $dto->getOrderDate() > $beforeDate)) {
                    continue;
                }
                $unitPrice = floatval($dto->getUnitPrice());
                $currencyRate = floatval($dto->getCurrencyRate());
                $productPriceInMainCurrency = $unitPrice * $currencyRate;
                if ($productPriceInMainCurrency < $minProductPrice) {
                    $minProductPrice = $productPriceInMainCurrency;
                    $minProductPriceLineId = $lineId;
                }
            }
            return $minProductPriceLineId;
        }

        private function calcAveragePrice($productPurchaseOrderLines, $beforeDate = null) {
            $sum = 0;
            $qty = 0;
            foreach ($productPurchaseOrderLines as $lineId => $dto) {
                if ($dto->getQuantity() == 0 || (!empty($beforeDate) && $dto->getOrderDate() > $beforeDate)) {
                    continue;
                }
                $unitPrice = floatval($dto->getUnitPrice());
                $currencyRate = floatval($dto->getCurrencyRate());
                $productPriceInMainCurrency = $unitPrice * $currencyRate;
                $sum += floatval($productPriceInMainCurrency);
                $qty += floatval($dto->getQuantity());
            }
            return $sum / $qty;
        }

        private function findAverageProductPriceLineId($productPurchaseOrderLines, $beforeDate = null) {
            $averagePrice = $this->calcAveragePrice($productPurchaseOrderLines, $beforeDate);
            $minProductPriceLineId = 0;
            $minDiff = $averagePrice;
            foreach ($productPurchaseOrderLines as $lineId => $dto) {
                if ($dto->getQuantity() == 0 || (!empty($beforeDate) && $dto->getOrderDate() > $beforeDate)) {
                    continue;
                }
                $unitPrice = floatval($dto->getUnitPrice());
                $currencyRate = floatval($dto->getCurrencyRate());
                $productPriceInMainCurrency = $unitPrice * $currencyRate;
                if (abs($productPriceInMainCurrency - $averagePrice) < $minDiff) {
                    $minDiff = abs($productPriceInMainCurrency - $averagePrice);
                    $minProductPriceLineId = $lineId;
                }
            }
            if ($minProductPriceLineId == 0) {
                return $this->findMinProductPriceLineId($productPurchaseOrderLines, $beforeDate);
            }
            return $minProductPriceLineId;
        }

    }

}
    