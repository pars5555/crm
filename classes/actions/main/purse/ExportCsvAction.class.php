<?php

namespace crm\actions\main\purse {

    use crm\actions\BaseAction;
    use crm\loads\main\purse\ListLoad;
    use crm\managers\PurseOrderManager;

    class ExportCsvAction extends BaseAction {

        public function service() {
            list($offset, $sortByFieldName, $selectedFilterSortByAscDesc, $where, $words, $searchText,
                    $problematic, $regOrdersInWarehouse) = ListLoad::initFilters();

            if ($problematic == 1) {
                $orders = PurseOrderManager::getInstance()->getProblematicOrders($where);
            } else {
                $orders = PurseOrderManager::getInstance()->getOrders($where, $sortByFieldName, $selectedFilterSortByAscDesc);
            }
            $this->exportCsv($orders);
        }

        public function exportCsv($orders) {
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=export.csv');
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($output, ['Order number', 'Shipping Type', 'Recipient', 'Unit Address', 'Account',
                'Qty', "product Name", "Total", "Status", 'Note', 'order#', "Tracking #"]);
            fputcsv($output, ['']);
            foreach ($orders as $order) {
                $row = [$order->getOrderNumber(), $order->getShippingType(), $order->getRecipientName(), $order->getUnitAddress(),
                    str_replace('purse_', '', $order->getAccountName()), $order->getQuantity(), $order->getProductName(), $order->getAmazonTotal(),
                    $order->getStatus(), $order->getNote(), $order->getAmazonOrderNumber(), $order->getTrackingNumber()];
                $row = array_map(function(&$el){
                    return '="'.$el .'"';
                }, $row);
                fputcsv($output, $row);
            }
            exit;
        }

    }

}