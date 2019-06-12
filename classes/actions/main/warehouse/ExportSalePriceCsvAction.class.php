<?php

namespace crm\actions\main\warehouse {

    use crm\actions\BaseAction;
    use crm\managers\ProductCategoryManager;
    use crm\managers\ProductManager;
    use crm\managers\WarehouseManager;
    use crm\security\RequestGroups;

    class ExportSalePriceCsvAction extends BaseAction {

        public function service() {

            $productsQuantity = WarehouseManager::getInstance()->getAllProductsQuantity();
            $products = ProductManager::getInstance()->selectAdvance('*', ['include_in_price_xlsx', '=', 1], 'category_id', 'ASC', null, null, true);
            $categories = ProductCategoryManager::getInstance()->selectAll();
            $categoryMappedById = [];
            foreach ($categories as $category) {
                $categoryMappedById[$category->getId()] = $category;
            }
            $this->exportCsv($products, $productsQuantity, $categoryMappedById);
        }

        public function exportCsv($products, $productsQuantity, $categoryMappedById) {

            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=export.csv');
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            //fputcsv($output, ['Item Name', 'Model', 'Quantity','Sale Price']);
            fputcsv($output, ['']);
            $catId = -1;

            foreach ($products as $product) {
                if ($catId != $product->getCategoryId()) {
                    $catId = $product->getCategoryId();
                    fputcsv($output, []);
                    fputcsv($output, [$categoryMappedById[$product->getCategoryId()]->getName(), '', '', '']);
                }

                if (isset($productsQuantity[$product->getId()]) && $productsQuantity[$product->getId()] > 0) {
                    $categoryId = $product->getCategoryId();
                    $row = [$product->getId(), $product->getName(), $product->getModel(), $productsQuantity[$product->getId()] ?: 0, isset($categoryMappedById[$categoryId]) ? $categoryMappedById[$categoryId]->getWarrantyMonths() : 0];
                    $row = array_map(function(&$el) {
                        return '="' . $el . '"';
                    }, $row);
                    fputcsv($output, $row);
                }
            }
            exit;
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}