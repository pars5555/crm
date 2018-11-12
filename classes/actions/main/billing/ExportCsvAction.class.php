<?php

namespace crm\actions\main\billing {

    use crm\actions\BaseAction;

    class ExportCsvAction extends BaseAction {

        public function service() {
            $startDate = NGS()->args()->startDate;
            $endDate = NGS()->args()->endDate;
            $partnerId = intval(NGS()->args()->partner_id);
            $where = ['amount', '<', 0, 'and', 'cancelled', '=', 0, 'and', 'date', '>=', "'$startDate'", 'and', 'date', '<=', "'$endDate 23:59:59'"];
            $partnerDto = null;
            if ($partnerId > 0) {
                $partnerDto = \crm\managers\PartnerManager::getInstance()->selectByPk($partnerId);
                $where = array_merge($where, ['and', 'partner_id', '=', $partnerId]);
            }
            $rows = \crm\managers\PaymentTransactionManager::getInstance()->getPaymentListFull($where, 'date', "DESC");
            $this->exportCsv($rows, $startDate, $endDate, $partnerDto);
        }

        public function exportCsv($rows, $startDate, $endDate, $partnerDto = null) {
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=observers.csv');
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
            $total = [1=>0, 2=>0];
            foreach ($rows as $row) {
                $total[intval($row->getCurrencyDto()->getId())] += abs($row->getAmount());
                
            }
            $partnerName = isset($partnerDto)?$partnerDto->getName():"All";
            fputcsv($output, ['Start Date','End Date', 'Partner', 'Total $', 'Total AMD']);
            fputcsv($output, [$startDate, $endDate, $partnerName, $total[1],$total[2]]);
            fputcsv($output, []);
            fputcsv($output, []);
            fputcsv($output, []);

            fputcsv($output, ['Date', 'Partner', 'Amount', 'currency', 'Note']);
            foreach ($rows as $row) {
                fputcsv($output, [$row->getDate(), $row->getPartnerDto()->getName(), abs($row->getAmount()), $row->getCurrencyDto()->getTemplateChar(), $row->getNote()]);
            }
            exit;
        }

    }

}