<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main {

    use crm\loads\AdminLoad;
    use crm\managers\CurrencyRateManager;
    use crm\managers\PartnerManager;
    use crm\managers\ProductCategoryManager;
    use crm\managers\ProductManager;
    use crm\managers\ProductReservationManager;
    use crm\managers\PurchaseOrderLineManager;
    use crm\managers\PurseOrderManager;
    use crm\managers\SaleOrderLineManager;
    use crm\managers\SettingManager;
    use crm\managers\WarehouseManager;
    use crm\security\RequestGroups;
    use NGS;

    class WarehouseLoad extends AdminLoad {

        public function load() {
            $pwarehousesProductsQuantity = $this->loadPartnersWarehouses();
            $reservations = ProductReservationManager::getInstance()->getReservedProducts();
            $this->addParam('reservations', $reservations);
            $this->loadProductModelsAndBrands();




            $categories = ProductCategoryManager::getInstance()->selectAll();
            $categoriesMappedById = [];
            foreach ($categories as $category) {
                $categoriesMappedById[$category->getId()] = $category->getName();
            }
            $productsQuantity = WarehouseManager::getInstance()->getAllProductsQuantity(true);
            $productsPrice = WarehouseManager::getInstance()->getAllProductsPrice(array_keys($productsQuantity));
            $productsMappedById = ProductManager::getInstance()->selectAdvance('*', [], 'category_id', 'ASC', null, null, true);
            $productIds = array_keys($productsMappedById);

            $days = SettingManager::getInstance()->getSetting('new_items_days');
            $newProductIds = PurchaseOrderLineManager::getInstance()->getLastDaysPurchases($days);

            $productsPurchaseOrders = PurchaseOrderLineManager::getInstance()->getProductsPurchaseOrders($productIds);
            $productsSaleOrders = SaleOrderLineManager::getInstance()->getProductsSaleOrders($productIds);
            $partnerIds = [];
            foreach ($productsSaleOrders as $sos) {
                foreach ($sos as $so) {
                    $partnerIds[] = intval($so->getPartnerId());
                }
            }
            foreach ($productsPurchaseOrders as $pos) {
                foreach ($pos as $po) {
                    $partnerIds[] = intval($po->getPartnerId());
                }
            }
            $partnerIdsSql = implode(',', array_unique($partnerIds));
            $partnersMappedByIds = PartnerManager::getInstance()->selectAdvance(['name', 'id'], ['id', 'in', "($partnerIdsSql)"], null, null, null, null, true);
            $usdRate = CurrencyRateManager::getInstance()->getCurrencyRate(1);

            $products = $this->initSorting($productsMappedById, $productsSaleOrders, $productsPurchaseOrders);
            //$loadOrdersPuposedToNotReceivedToDestinationCounty = $this->loadOrdersPuposedToNotReceivedToDestinationCounty($products);

            //$this->addParam('productsNotReceivedToDestinationCounty', $loadOrdersPuposedToNotReceivedToDestinationCounty);
            $this->addParam('categoriesMappedById', $categoriesMappedById);
            $this->addParam('pwarehousesProductsQuantity', $pwarehousesProductsQuantity);
            $this->addParam('products', $products);
            $this->addParam('newProductIds', $newProductIds);
            $this->addParam('usd_rate', $usdRate);
            $this->addParam('productsQuantity', $productsQuantity);
            $this->addParam('productsPrice', $productsPrice);
            $this->addParam('productsPurchaseOrder', $productsPurchaseOrders);
            $this->addParam('productsSaleOrder', $productsSaleOrders);
            $this->addParam('partnersMappedByIds', $partnersMappedByIds);
            $total = 0;
            $totalStock = 0;
            foreach ($productsQuantity as $pId => $qty) {
                $total += floatval($productsPrice[$pId]) * floatval($qty);
                $productStockPrice = $productsMappedById[$pId]->getStockPrice();
                if ($productStockPrice <= 0.01) {
                    $productStockPrice = floatval($productsPrice[$pId]);
                }
                $totalStock += floatval($productStockPrice) * floatval($qty);
            }
            $this->addParam('total', $total);
            $this->addParam('total_stock', $totalStock);
            $this->addParam('showprofit', isset($_COOKIE['showprofit']) ? $_COOKIE['showprofit'] : 0);
            $this->addParam('vahagn_cookie', isset($_COOKIE['vahagn']) ? $_COOKIE['vahagn'] : 0);
        }

        public static function getSortByFields() {
            return ['sale_date' => 'Sale Date', 'purchase_date' => 'Purchase Date'];
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/warehouse.tpl";
        }

        private function loadProductModelsAndBrands() {
            list($models, $brands) = ProductManager::getInstance()->getBrandsAndModels();
            $this->addParam('models', $models);
            $this->addParam('brands', $brands);
        }

        private function loadPartnersWarehouses() {
            $warehouse_partners = SettingManager::getInstance()->getSetting('warehouse_partners');
            $warehouse_partners_array = explode(',', $warehouse_partners);
            $productIds = [];
            $pwarehousesProductsQuantity = [];
            foreach ($warehouse_partners_array as $partnerId) {
                $productsQuantity = WarehouseManager::getInstance()->getWarehousePartnerProductsQuantity($partnerId);
                foreach ($productsQuantity as $productId => $qty) {
                    if (!isset($pwarehousesProductsQuantity[$productId])) {
                        $pwarehousesProductsQuantity[$productId] = 0;
                    }
                    $pwarehousesProductsQuantity[$productId] += $qty;
                }
                $productIds = array_merge(array_keys($productsQuantity));
            }
            return $pwarehousesProductsQuantity;
        }

        public function initSorting($productsMappedById, $productsSaleOrders, $productsPurchaseOrders) {
            $sortByFields = self::getSortByFields();
            $this->addParam('sortFields', $sortByFields);
            $selectedFilterSortBy = 'none';
            if (isset(NGS()->args()->srt)) {
                if (array_key_exists(NGS()->args()->srt, $sortByFields)) {
                    $selectedFilterSortBy = NGS()->args()->srt;
                }
            }
            $selectedFilterSortByAscDesc = 'DESC';
            if (isset(NGS()->args()->ascdesc)) {
                if (in_array(strtoupper(NGS()->args()->ascdesc), ['ASC', 'DESC'])) {
                    $selectedFilterSortByAscDesc = strtoupper(NGS()->args()->ascdesc);
                }
            }
            if ($selectedFilterSortBy === 'purchase_date') {
                $this->sortProductsBySaleOrderDate($productsMappedById, $productsPurchaseOrders, $selectedFilterSortByAscDesc);
            }
            if ($selectedFilterSortBy === 'sale_date') {
                $this->sortProductsBySaleOrderDate($productsMappedById, $productsSaleOrders, $selectedFilterSortByAscDesc);
            }
            $this->addParam('selectedFilterSortByAscDesc', $selectedFilterSortByAscDesc);
            $this->addParam('selectedFilterSortBy', $selectedFilterSortBy);
            return $productsMappedById;
        }

        private function sortProductsBySaleOrderDate(&$productsMappedById, $productsPorchaseOrSaleOrdersMappedByProductId, $selectedFilterSortByAscDesc) {
            $warehouse_partners = SettingManager::getInstance()->getSetting('warehouse_partners');
            $warehouse_partners_ids_array = explode(',', $warehouse_partners);
            $productsMappedByLastSaleOrderDate = [];
            foreach ($productsPorchaseOrSaleOrdersMappedByProductId as $productId => $sos) {
                $maxSaleOrderDate = "";
                foreach ($sos as $so) {
                    if (!in_array($so->getPartnerId(), $warehouse_partners_ids_array) && $so->getOrderDate() >= $maxSaleOrderDate) {
                        $maxSaleOrderDate = $so->getOrderDate();
                    }
                }
                $productsMappedByLastSaleOrderDate[$maxSaleOrderDate] = $productsMappedById[$productId];
            }
            if (strtolower($selectedFilterSortByAscDesc) === 'asc') {
                ksort($productsMappedByLastSaleOrderDate);
            } else {
                krsort($productsMappedByLastSaleOrderDate);
            }
            $productsMappedById = array_values($productsMappedByLastSaleOrderDate);
        }

    }

}
