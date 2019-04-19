<?php

namespace crm\actions\main\warehouse {

    use crm\actions\BaseAction;
    use crm\managers\ProductManager;
    use crm\managers\WarehouseManager;

    class ExportSalePriceCsvAction extends BaseAction {

        public function service() {

            $productsQuantity = WarehouseManager::getInstance()->getAllProductsQuantity();
            $products = ProductManager::getInstance()->selectAdvance('*',[], 'category_id', 'ASC', null,null,true);
            $this->exportCsv($products, $productsQuantity);
        }

        public function exportCsv($products, $productsQuantity) {
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=export.csv');
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            //fputcsv($output, ['Item Name', 'Model', 'Quantity','Sale Price']);
            fputcsv($output, ['']);
            foreach ($products as $product) {
                if (isset($productsQuantity[$product->getId()]) && $productsQuantity[$product->getId()] > 0) {
                    $row = [$product->getId(), $product->getName(), $product->getModel(), $productsQuantity[$product->getId()] ?: 0, $product->getSalePrice()?:0];
                    $row = array_map(function(&$el) {
                        return '="' . $el . '"';
                    }, $row);
                    fputcsv($output, $row);
                }
            }
            exit;
        }
        
        public function getRequestGroup() {
            return \crm\security\RequestGroups::$guestRequest;
        }

    }

}