<?php

namespace crm\actions\main\purchase {

    use crm\actions\BaseAction;
    use crm\managers\CurrencyManager;
    use crm\managers\PartnerManager;
    use crm\managers\PurchaseOrderManager;
    use NGS;

    class ExportCsvAction extends BaseAction {

        public function service() {
            $startDate = NGS()->args()->startDate;
            $endDate = NGS()->args()->endDate;
            $partnerId = intval(NGS()->args()->partner_id);
            $where = ['cancelled', '=', 0, 'and', 'order_date', '>=', "'$startDate'", 'and', 'order_date', '<=', "'$endDate 23:59:59'"];
            $partnerDto = null;
            if ($partnerId > 0) {
                $partnerDto = PartnerManager::getInstance()->selectByPK($partnerId);
                $where = array_merge($where, ['and', 'partner_id', '=', $partnerId]);
            }
            $rows = PurchaseOrderManager::getInstance()->getPurchaseOrdersFull($where, 'order_date', "DESC");
            $this->exportCsv($rows, $startDate, $endDate, $partnerDto);
        }

        public function exportCsv($rows, $startDate, $endDate, $partnerDto = null) {
            $currencyDtosMappedById = CurrencyManager::getInstance()->selectAdvance('*', [], null, null, null, null, true);
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=observers.csv');
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
            $totalPurchases = [1=>0, 2=>0];
            foreach ($rows as $row) {
                $totalAmountMappedByCurrencyId = $row->getTotalAmount();
                foreach ($totalAmountMappedByCurrencyId as $curId => $tAmount) {
                    $totalPurchases[$curId] += $tAmount;
                }
            }

            $totalAmountsTextsArray = [];
            foreach ($totalPurchases as $currencyId => $ta) {
                $currencyDto = $currencyDtosMappedById[$currencyId];
                $templateChar = $currencyDto->getTemplateChar();
                $totalAmountsTextsArray [] = $ta . ' ' . $templateChar;
            }

            fputcsv($output, ['Star Date', 'End Date', 'Total Amount AMD', 'Total Profit AMD']);
            fputcsv($output, [$startDate, $endDate, implode('     ', $totalAmountsTextsArray)]);
            fputcsv($output, ['']);
            fputcsv($output, ['']);
            fputcsv($output, ['']);
            fputcsv($output, ['']);
            fputcsv($output, ['']);


            fputcsv($output, ['Date', 'Partner', 'Total Amount AMD', 'Profit AMD']);


            foreach ($rows as $row) {
                fputcsv($output, ['']);

                $rta = $row->getTotalAmount();
                $totalAmountsTextsArray = [];
                foreach ($rta as $currencyId => $ta) {
                    $currencyDto = $currencyDtosMappedById[$currencyId];
                    $templateChar = $currencyDto->getTemplateChar();
                    $totalAmountsTextsArray [] = $ta . ' ' . $templateChar;
                }
                fputcsv($output, [$row->getOrderDate(), $row->getPartnerDto()->getName(), implode('   ;', $totalAmountsTextsArray)]);
                $purchaseOrderLinesDtos = $row->getPurchaseOrderLinesDtos();
                fputcsv($output, ['', 'Product', 'Qty', 'Amount']);
                foreach ($purchaseOrderLinesDtos as $purchaseOrderLineDto) {
                    $currencyChar = $purchaseOrderLineDto->getCurrencyDto()->getTemplateChar();

                    fputcsv($output, ['', $purchaseOrderLineDto->getProductDto()->getName(), $purchaseOrderLineDto->getQuantity(), $purchaseOrderLineDto->getUnitPrice() . ' ' . $currencyChar]);
                }
            }
            exit;
        }

    }

}