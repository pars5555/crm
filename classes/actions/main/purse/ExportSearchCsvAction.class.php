<?php

namespace crm\actions\main\purse {

    use crm\actions\BaseAction;

    class ExportSearchCsvAction extends BaseAction {

        public function service() {
            $trackingNumbersStr = trim(NGS()->args()->trackingNumbers);
            $trackingNumbersStr = preg_replace('/\s+/', ';', $trackingNumbersStr);
            $trackingNumbersStr = str_replace(',', ';', $trackingNumbersStr);
            $trackingNumbersArray = explode(";", $trackingNumbersStr);

            $rows = \crm\managers\PurseOrderManager::getInstance()->findByTrackingNumbers($trackingNumbersArray);
            $this->exportCsv($rows);
        }

        public function exportCsv($rows) {
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=export.csv');
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