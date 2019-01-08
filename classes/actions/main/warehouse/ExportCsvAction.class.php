<?php

namespace crm\actions\main\warehouse {

    use crm\actions\BaseAction;
    use crm\managers\CurrencyRateManager;
    use crm\managers\ProductManager;
    use crm\managers\WarehouseManager;

    class ExportCsvAction extends BaseAction {

        public function service() {

            $productsQuantity = WarehouseManager::getInstance()->getAllProductsQuantity();
            $productsPrice = WarehouseManager::getInstance()->getAllProductsPrice(array_keys($productsQuantity));
            $products = ProductManager::getInstance()->getProductListFull([], 'category_id', 'ASC');
            $usdRate = CurrencyRateManager::getInstance()->getCurrencyRate(1);
            $this->addParam('products', $products);
            $this->addParam('usd_rate', $usdRate);
            $this->addParam('productsQuantity', $productsQuantity);
            $this->addParam('productsPrice', $productsPrice);
            $total = 0;
            foreach ($productsQuantity as $pId => $qty) {
                $total += floatval($productsPrice[$pId]) * floatval($qty);
            }

            $this->exportCsv($products, $usdRate, $productsQuantity, $productsPrice, $total);
        }

        public function exportCsv($products, $usdRate, $productsQuantity, $productsPrice, $total) {
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=export.csv');
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($output, ['Item Name', 'Model', 'Uom', 'Quantity', 'Price', 'Stock Price']);
            fputcsv($output, ['']);
            foreach ($products as $product) {
                if (isset($productsQuantity[$product->getId()]) && $productsQuantity[$product->getId()] > 0) {
                    $row = [$product->getName(), $product->getModel(), $product->getUomDto()->getName(),
                        $productsQuantity[$product->getId()] ?: 0, round($productsPrice[$product->getId()], 2), $product->getStockPrice()];
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