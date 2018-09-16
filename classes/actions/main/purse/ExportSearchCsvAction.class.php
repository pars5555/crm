<?php

namespace crm\actions\main\purse {

    use crm\actions\BaseAction;

    class ExportSearchCsvAction extends BaseAction {

        public function service() {
            $strackingNumbersStr = trim(NGS()->args()->trackingNumbers);
            $strackingNumbersStr = preg_replace('/\s+/', ';', $strackingNumbersStr);
            $strackingNumbersStr = str_replace(',', ';', $strackingNumbersStr);
            $strackingNumbersArray = explode(";", $strackingNumbersStr);

            $rows = \crm\managers\PurseOrderManager::getInstance()->findByTrackingNumbers($strackingNumbersArray);
            $this->exportCsv($rows);
        }

        public function exportCsv($rows) {
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=observers.csv');
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
           
            fputcsv($output, ['Given Tracking Number', 'Order Tracking Number', 'Recipient', 'Qty', 'Product', 'Total $']);
            foreach ($rows as $tr => $row) {
                fputcsv($output, ['"'.$tr.'"','"'. $row->getTrackingNumber().'"', $row->getRecipientName(), $row->getQuantity(), $row->getProductName(), $row->getAmazonTotal()]);
            }
            exit;
        }

    }

}