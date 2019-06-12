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
            $categoryNamesMappedById = [];
            foreach ($categories as $category) {
                $categoryNamesMappedById[$category->getId()] = $category->getName();
            }
            $this->exportCsv($products, $productsQuantity, $categoryNamesMappedById);
        }

        public function exportCsv($products, $productsQuantity, $categoryNamesMappedById) {

            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=export.csv');
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            //fputcsv($output, ['Item Name', 'Model', 'Quantity','Sale Price']);
            fputcsv($output, ['']);
            $catId = -1;
            $categories = ProductCategoryManager::getInstance()->selectAll();
            $categoriesMappedById = [];
            foreach ($categories as $category) {
                $categoriesMappedById[$category->getId()] = $category->getName();
            }
            foreach ($products as $product) {
                if ($catId != $product->getCategoryId()) {
                    $catId = $product->getCategoryId();
                    fputcsv($output, []);
                    fputcsv($output, [$categoryNamesMappedById[$product->getCategoryId()], '', '', '']);
                }

                if (isset($productsQuantity[$product->getId()]) && $productsQuantity[$product->getId()] > 0) {
                    $categoryId = $product->getCategoryId();
                    $row = [$product->getId(), $product->getName(), $product->getModel(), $productsQuantity[$product->getId()] ?: 0, isset($categoriesMappedById[$categoryId]) ?$categoriesMappedById[$categoryId]->getWarrantyMonths(): 0];
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