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
            $productSoldCount = floatval(SaleOrderLineManager::getInstance()->getProductCountInNonCancelledSaleOrders($productId));
            $ret = 0;
            foreach ($productPurchaseOrderLines as $productPurchaseOrderLine) {
                $ret += floatval($productPurchaseOrderLine->getQuantity());
            }
            $ret -= $productSoldCount;
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
            $productSaleOrderLines = SaleOrderLineManager::getInstance()->getNonCancelledProductSaleOrders($productId, $saleOrderId, $date);
            $productPurchaseOrderLines = $this->subtracPurchaseOrderLinesByProductSaleOrders($productPurchaseOrderLines, $productSaleOrderLines);
            $ret = $this->removePurchaseOrderLinesQuantityByProductSale($productPurchaseOrderLines, $productSaleQty, $date);
            return $ret;
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

        public function updateProductCostForOneUnit($productId) {
            $productUnitCostInBaseCurrency = ProductManager::getInstance()->calculateProductCost($productId, 1, 0);
            $productDto = ProductManager::getInstance()->selectByPK($productId);
            $productDto->setUnitCost($this->calculateProductTotalCost($productUnitCostInBaseCurrency));
            ProductManager::getInstance()->updateByPK($productDto);
        }

        public function calculateProductTotalCost($productUnitCostInBaseCurrency) {
            $ret = 0;
            if (empty($productUnitCostInBaseCurrency)) {
                return 0;
            }
            foreach ($productUnitCostInBaseCurrency as $pair) {
                $qty = floatval($pair[0]);
                $unitPrice = floatval($pair[1]);
                $ret +=$qty * $unitPrice;
            }
            return $ret;
        }

        private function subtracPurchaseOrderLinesByProductSaleOrders($productPurchaseOrderLines, $productSaleOrderLines) {
            if (empty($productSaleOrderLines)) {
                return $productPurchaseOrderLines;
            }
            $profit_calculation_method = SettingManager::getInstance()->getSetting('profit_calculation_method');
            foreach ($productSaleOrderLines as $productSaleOrderLine) {
                $productSaleOrderLineQty = $productSaleOrderLine->getQuantity();
                while ($productSaleOrderLineQty > 0) {
                    if ($profit_calculation_method == 'max') {
                        $lineId = $this->findMaxProductPriceLineId($productPurchaseOrderLines, $productSaleOrderLine->getOrderDate());
                    } else {
                        $lineId = $this->findFirstNonZeroQuantityLineId($productPurchaseOrderLines);
                    }
                    if ($lineId == 0) {
                        throw new NgsErrorException('Insuficient product! function subtracPurchaseOrderLinesQuantityByProductSoldCount product id:' . $this->calculationProductId);
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

    }

}
    