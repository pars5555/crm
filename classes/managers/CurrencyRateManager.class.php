<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package managers
 * @version 6.0
 *
 */

namespace crm\managers {

    use crm\dal\mappers\CurrencyRateMapper;
    use crm\managers\AdvancedAbstractManager;
    use crm\managers\CurrencyRateManager;
    use crm\managers\SettingManager;
    use DateTime;

    class CurrencyRateManager extends AdvancedAbstractManager {

        /**
         * @var $instance
         */
        public static $instance;

        /**
         * Returns an singleton instance of this class
         *
         * @param object $config
         * @param object $args
         * @return
         */
        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new CurrencyRateManager(CurrencyRateMapper::getInstance());
            }
            return self::$instance;
        }

        public function addRow($iso, $amount, $rate, $date) {
            $dto = $this->createDto();
            $dto->setIso($iso);
            $dto->setAmount($amount);
            $dto->setRate($rate);
            $dto->setDate($date);
            return $this->insertDto($dto);
        }

        public function getCurrencyRate($currencyId) {
            $date = date('Y-m-d');
            return $this->getCurrencyRateByDate($date, $currencyId);
        }

        public function getCurrencyRateByDate($date, $currencyId) {
            $currencyIso = CurrencyManager::getInstance()->selectByPk($currencyId)->getIso();
            if (strtolower($currencyIso) == strtolower(SettingManager::getInstance()->getSetting('main_currency_iso'))) {
                return 1;
            }
            $oDate = new DateTime($date);
            $sDate = $oDate->format("Y-m-d");
            $sqlStartDate = "DATE_SUB('$sDate',INTERVAL 15 DAY)";
            $sqlEndDate = "DATE_ADD('$sDate',INTERVAL 1 DAY)";
            $sqlCurrencyIso = "'" . strtoupper($currencyIso) . "'";
            $rows = $this->selectAdvance('*', ['date', '>=', $sqlStartDate, 'AND', 'date', '<=', $sqlEndDate, 'AND', 'iso', '=', $sqlCurrencyIso], 'date', 'DESC');
            if (!empty($rows)) {
                $dto = $rows[0];
                return floatval($dto->getRate()) / floatval($dto->getAmount());
            }
            $rows = $this->selectAdvance('*', ['iso', '=', $sqlCurrencyIso], 'date', 'DESC');
            if (!empty($rows)) {
                $dto = $rows[0];
                return floatval($dto->getRate()) / floatval($dto->getAmount());
            }
            return null;
        }

    }

}
