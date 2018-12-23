<?php

namespace crm\actions\main\purse {

    use crm\actions\BaseAction;

    class ExportSearchCsvAction extends BaseAction {

        public function service() {
            $trackingNumbersStr = trim(NGS()->args()->trackingNumbers);
            $trackingNumbersStr = preg_replace('/\s+/', ';', $trackingNumbersStr);
            $trackingNumbersStr = str_replace(',', ';', $trackingNumbersStr);
            $trackingNumbersArray = explode(";", $trackingNumbersStr);

            $orders = \crm\managers\PurseOrderManager::getInstance()->findByTrackingNumbers($trackingNumbersArray);
            $this->exportCsv($orders);
        }

        public function exportCsv($orders) {
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=export.csv');
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
           
            fputcsv($output, ['Given Tracking Number', 'Order Tracking Number', 'Recipient', 'Qty', 'Product', 'Total $']);
            
            foreach ($orders as $tr => $order) {
                $row = [$tr,$order->getTrackingNumber(), $order->getRecipientName(), $order->getQuantity(), $order->getProductName(), $order->getAmazonTotal()];
                $row = array_map(function(&$el){
                    return '="'.$el .'"';
                }, $row);
                fputcsv($output, $row);
            }
            exit;
        }

    }

}