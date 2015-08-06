<?php

/**
 * main site action for all ngs site's actions
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2014
 * @package actions.site
 * @version 6.0
 *
 */

namespace crm\actions\cron {

    use crm\actions\BaseAction;
    use crm\managers\CurrencyRateManager;
    use SoapClient;
    use SoapFault;

    class GetCurrencyRatesAction extends BaseAction {

        public function service() {
            $rates = $this->getCbaRates();
            if ($rates !== false) {
                $date = $rates[1];
                $currencyRateManager = CurrencyRateManager::getInstance();
                $selectByField = $currencyRateManager->selectByField('date', $date);
                if (empty($selectByField)) {
                    foreach ($rates[0] as $rate) {
                        $iso = $rate[0];
                        $amount = $rate[1];
                        $rate = $rate[2];
                        $currencyRateManager->addRow($iso, $amount, $rate, $date);
                    }
                }
            }
        }

        /**
         * 
         * @return array(array(array(iso, amount, rate),...), date) or FALSE
         */
        private function getCbaRates() {
            $soapClient = new SoapClient("http://api.cba.am/exchangerates.asmx?wsdl");
            $ret = array();
            try {
                $info = $soapClient->ExchangeRatesLatest();
                if (!isset($info->ExchangeRatesLatestResult) || !isset($info->ExchangeRatesLatestResult->Rates) || !isset($info->ExchangeRatesLatestResult->Rates->ExchangeRate)) {
                    return false;
                }
                foreach ($info->ExchangeRatesLatestResult->Rates->ExchangeRate as $dto) {
                    $ret[] = array($dto->ISO, $dto->Amount, $dto->Rate);
                }
                $currentDate = $info->ExchangeRatesLatestResult->CurrentDate;
                $date = date_create_from_format('Y-m-d\TH:i:s', $currentDate);
                if (!$date) {
                    return false;
                }
                $dateStr = $date->format('Y-m-d');
                return array($ret, $dateStr);
            } catch (SoapFault $fault) {
                return false;
            }
            unset($soapClient);
        }

    }

}
