<?php

namespace crm\actions\main\partner {

    use crm\actions\BaseAction;
    use crm\loads\main\partner\AllDealsLoad;
    use crm\managers\CurrencyManager;

    class ExportPartnerAllDealsCsvAction extends BaseAction {

        public function service() {
            $allDeals = AllDealsLoad::loadParams();
            $this->exportCsv($allDeals);
        }

        public function exportCsv($allDeals) {
            header('Content-type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=export.csv');
            $output = fopen('php://output', 'w');
            fputs($output, chr(0xEF) . chr(0xBB) . chr(0xBF));
            $headerFieldNames = ['Type', 'Date', 'Amount', 'Balance', 'Note'];
            fputcsv($output, $headerFieldNames);
            fputcsv($output, []);
            foreach ($allDeals as $deal) {
                $type = $deal[0];
                $deal = $deal[1];
                $amoutText = $this->getAmoutText($type, $deal);
                $debtText = $this->getDebtText($deal);
                $date = ($type == 'sale' || $type == 'purchase') ? $deal->getOrderDate() : $deal->getDate();
                fputcsv($output, [ $type, '="' . $date . '"', '="' . $amoutText . '"', '="' . $debtText . '"', '="' . $deal->getNote() . '"']);
            }
            exit;
        }

        private function getDebtText($deal) {
            $currencies = CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], null, null, null, null, true);
            $ret = "";
            foreach ($deal->getDebt() as $currencyId => $amount) {
                $currencyDto = $currencies[$currencyId];
                if ($currencyDto->getSymbolPosition() == 'left') {
                    $ret .= $currencyDto->getTemplateChar();
                }
                $ret .= $amount;
                if ($currencyDto->getSymbolPosition() == 'right') {
                    $ret .= $currencyDto->getTemplateChar();
                }
            }
            return $ret;
        }

        private function getAmoutText($type, $deal) {
            $currencies = CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], null, null, null, null, true);
            $ret = "";
            if ($type == 'sale' || $type == 'purchase') {
                $totalAmount = $deal->getTotalAmount();

                foreach ($totalAmount as $currencyId => $amount) {
                    $currencyDto = $currencies[$currencyId];
                    if ($currencyDto->getSymbolPosition() == 'left') {
                        $ret .= $currencyDto->getTemplateChar();
                    }
                    $ret .= $amount;
                    if ($currencyDto->getSymbolPosition() == 'right') {
                        $ret .= $currencyDto->getTemplateChar();
                    }
                }
            } else {
                $currencyDto = $currencies[$deal->getCurrencyId()];
                if ($currencyDto->getSymbolPosition() == 'left') {
                    $ret .= $currencyDto->getTemplateChar();
                }
                $ret .= $deal->getAmount();
                if ($currencyDto->getSymbolPosition() == 'right') {
                    $ret .= $currencyDto->getTemplateChar();
                }
            }
            return $ret;
        }

    }

}
    