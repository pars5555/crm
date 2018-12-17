<?php

namespace crm\actions\main\sale {

    use crm\actions\BaseAction;

    class ExportCsvAction extends BaseAction {

        public function service() {
            $startDate = NGS()->args()->startDate;
            $endDate = NGS()->args()->endDate;
            $partnerId = intval(NGS()->args()->partner_id);
            $where = ['cancelled', '=', 0, 'and', 'order_date', '>=', "'$startDate'", 'and', 'order_date', '<=', "'$endDate 23:59:59'"];
            $partnerDto = null;
            if ($partnerId > 0) {
                $partnerDto = \crm\managers\PartnerManager::getInstance()->selectByPk($partnerId);
                $where = array_merge($where, ['and', 'partner_id', '=', $partnerId]);
            }
            $rows = \crm\managers\SaleOrderManager::getInstance()->getSaleOrdersFull($where, 'order_date', "DESC");
            $this->exportCsv($rows, $startDate, $endDate, $partnerDto);
        }

        public function exportCsv($rows, $startDate, $endDate, $partnerDto = null) {
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=export.csv');
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
            $totalSalesProfitAMD = 0;
            $totalSalesAMD = 0;
            foreach ($rows as $row) {
                $totalSalesAMD+= $row->getTotalAmountInMainCurrency();
                $totalSalesProfitAMD+=$row->getTotalProfit();
            }
            fputcsv($output, ['Star Date', 'End Date', 'Total Amount AMD', 'Total Profit AMD']);
            fputcsv($output, [$startDate, $endDate,$totalSalesAMD ,$totalSalesProfitAMD]);
            fputcsv($output, ['']);
            fputcsv($output, ['']);
            fputcsv($output, ['']);
            fputcsv($output, ['']);
            fputcsv($output, ['']);
            

            fputcsv($output, ['Date', 'Partner', 'Total Amount AMD', 'Profit AMD']);
            $currencyDtosMappedById = \crm\managers\CurrencyManager::getInstance()->selectAdvance('*', [], null, null, null, null, true);

            foreach ($rows as $row) {
                fputcsv($output, ['']);
                fputcsv($output, [$row->getOrderDate(), $row->getPartnerDto()->getName(), $row->getTotalAmountInMainCurrency(), $row->getTotalProfit()]);
                $saleOrderLinesDtos = $row->getSaleOrderLinesDtos();
                fputcsv($output, ['', 'Product', 'Qty', 'Amount']);
                foreach ($saleOrderLinesDtos as $saleOrderLineDto) {
                    $currencyChar = $saleOrderLineDto->getCurrencyDto()->getTemplateChar();

                    fputcsv($output, ['', $saleOrderLineDto->getProductDto()->getName(), $saleOrderLineDto->getQuantity(), $saleOrderLineDto->getUnitPrice() . ' ' . $currencyChar]);
                }
                $totalAmounts = $row->getTotalAmount();
                $totalAmountsTextsArray = [];
                foreach ($totalAmounts as $currencyId => $ta) {
                    $currencyDto = $currencyDtosMappedById[$currencyId];
                    $templateChar = $currencyDto->getTemplateChar();
                    $totalAmountsTextsArray [] = $ta . ' ' . $templateChar;
                }
                fputcsv($output, ['', '', 'total', implode('   ;', $totalAmountsTextsArray)]);
            }
            exit;
        }

    }

}