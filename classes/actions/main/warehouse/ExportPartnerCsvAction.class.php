<?php

namespace crm\actions\main\warehouse {

    use crm\actions\BaseAction;
    use crm\managers\CurrencyRateManager;
    use crm\managers\ProductManager;
    use crm\managers\SettingManager;
    use crm\managers\WarehouseManager;
    use NGS;

    class ExportPartnerCsvAction extends BaseAction {

        public function service() {
            $warehouse_partners = SettingManager::getInstance()->getSetting('warehouse_partners');
            $warehouse_partners_array = explode(',', $warehouse_partners);
            $partnerId = $warehouse_partners_array[0];
            if (!empty(NGS()->args()->partner_id)) {
                $partnerId = NGS()->args()->partner_id;
            }
            $productsQuantity = WarehouseManager::getInstance()->getWarehousePartnerProductsQuantity($partnerId);
            $productIds = array_keys($productsQuantity);
            $productsPrice = WarehouseManager::getInstance()->getAllProductsPrice($productIds);
            $productIdsSql = '(' . implode(',', $productIds) . ')';
            $products = ProductManager::getInstance()->getProductListFull(['id', 'in', $productIdsSql], 'name', 'ASC');

            $productIds = ProductManager::getDtosIdsArray($products);
            $usdRate = CurrencyRateManager::getInstance()->getCurrencyRate(1);
            $total = 0;
            foreach ($productsQuantity as $pId => $qty) {
                $total += floatval($productsPrice[$pId]) * floatval($qty);
            }
            
            $productLastSellPrice = [];
            $productsSaleOrders = SaleOrderLineManager::getInstance()->getProductsSaleOrders($productIds, $partnerId, $productLastSellPrice);

            $this->exportCsv($products, $usdRate, $productsQuantity, $productsPrice, $total, $productsSaleOrders);
        }

        public function exportCsv($products, $usdRate, $productsQuantity, $productsPrice, $total, $productsSaleOrders) {
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=export.csv');
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($output, ['Item Name', 'Model', 'Uom', 'Quantity', 'Price', 'Stock Price', 'Last Price']);
            fputcsv($output, ['']);
            foreach ($products as $product) {
                if (isset($productsQuantity[$product->getId()]) && $productsQuantity[$product->getId()] > 0) {
                    $row = [$product->getName(), $product->getModel(), $product->getUomDto()->getName(),
                        $productsQuantity[$product->getId()] ?: 0, round($productsPrice[$product->getId()], 2), $product->getStockPrice(),
                        round($productsSaleOrders[$product->getId()], 2)];
                    $row = array_map(function(&$el) {
                        return '="' . $el . '"';
                    }, $row);
                    fputcsv($output, $row);
                }
            }
            exit;
        }

    }

}