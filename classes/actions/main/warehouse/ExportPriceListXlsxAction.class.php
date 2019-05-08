<?php

namespace crm\actions\main\warehouse {

    use crm\actions\BaseAction;
    use crm\managers\ProductCategoryManager;
    use crm\managers\ProductManager;
    use crm\managers\WarehouseManager;
    use crm\security\RequestGroups;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class ExportPriceListXlsxAction extends BaseAction {

        public function service() {

            $productsQuantity = WarehouseManager::getInstance()->getAllProductsQuantity();
            $products = ProductManager::getInstance()->selectAdvance('*', [], 'category_id', 'ASC', null, null, true);
            $categories = ProductCategoryManager::getInstance()->selectAll();
            $categoryNamesMappedById = [];
            foreach ($categories as $category) {
                $categoryNamesMappedById[$category->getId()] = $category->getName();
            }
            $this->exportCsv($products, $productsQuantity, $categoryNamesMappedById);

            
        }

        public function exportCsv($products, $productsQuantity, $categoryNamesMappedById) {

            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="ExportScan.xlsx');
            header('Cache-Control: max-age=0;');
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            
            
            
            $rowIndex = 0;
            $catId = 0;
            foreach ($products as $product) {
                if ($product->getIncludeInPriceXlsx() == 0) {
                    continue;
                }
                if ($catId != $product->getCategoryId()) {
                    $catId = $product->getCategoryId();
                    $rowIndex+=2;
                    $sheet->getStyle("A$rowIndex")->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                    $sheet->getStyle("A$rowIndex")->getFont()->setBold( true );
                    $sheet->getStyle("A$rowIndex")->getFont()->setSize(18);
                    $sheet->setCellValue("A$rowIndex", $categoryNamesMappedById[$product->getCategoryId()]);
                }
                

                if (isset($productsQuantity[$product->getId()]) && $productsQuantity[$product->getId()] > 0) {
                    $rowIndex++;
                    $sheet->setCellValue("A$rowIndex", $product->getName());
                    $sheet->setCellValue("B$rowIndex", $product->getSalePrice());
                }
            }
            $sheet->getColumnDimension('A')->setWidth(100);
            $sheet->getColumnDimension('B')->setWidth(10);
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}