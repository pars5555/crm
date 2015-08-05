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

        public function getCurrencyRate($currencyIso) {
            $date = date('Y-m-d');
            return $this->getCurrencyRateByDate($date, $currencyIso);
        }

        public function getCurrencyRateByDate($date, $currencyIso) {
            if (strtolower($currencyIso) == strtolower(SettingManager::getInstance()->getSetting('main_currency_iso'))) {
                return 1;
            }
            $sqlStartDate = "DATE_SUB('$date',INTERVAL 15 DAY)";
            $sqlEndDate = "'" . $date . "'";
            $sqlCurrencyIso = "'" . strtoupper($currencyIso) . "'";
            $rows = $this->selectAdvance('*', ['date', '>=', $sqlStartDate, 'AND', 'date', '<=', $sqlEndDate, 'AND', 'iso', '=', $sqlCurrencyIso], 'date', 'DESC');
            if (!empty($rows)) {
                $dto = $rows[0];
                return floatval($dto->getRate()) / floatval($dto->getAmount());
            }
            return null;
        }

    }

}
