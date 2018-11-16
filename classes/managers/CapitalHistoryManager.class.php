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

    use crm\dal\mappers\CapitalHistoryMapper;
    use crm\managers\AdvancedAbstractManager;
    use crm\managers\CapitalHistoryManager;

    class CapitalHistoryManager extends AdvancedAbstractManager {

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
                self::$instance = new CapitalHistoryManager(CapitalHistoryMapper::getInstance());
            }
            return self::$instance;
        }

        public function addRow() {
            $capitalData = json_decode(SettingManager::getInstance()->getSetting('capital_data', '{}'), true);
            $warehouseTotal = floatval($capitalData['warehouse_total']);
            $purseTotal = floatval($capitalData['purse_total']);
            $purseBalanceTotal = floatval($capitalData['purse_balance_total']);
            $partnerWarehouseTotal = floatval($capitalData['partner_warehouse_total']);
            $capital_external_debts = floatval($capitalData['capital_external_debts']);
            $capital_external_btc = floatval($capitalData['capital_external_btc']);
            $partnerDebtTotal = floatval($capitalData['partner_debt_total']);
            $cashboxTotal = floatval($capitalData['cashbox_total']);
            $total = round($warehouseTotal + $purseTotal + $purseBalanceTotal + $partnerWarehouseTotal + $partnerDebtTotal + $cashboxTotal + $capital_external_btc - $capital_external_debts, 2);
            $dto = $this->createDto();
            $dto->setAmount($total);
            $dto->setMeta(SettingManager::getInstance()->getSetting('capital_data', '{}'));
            $dto->setCreatedAt(date('Y-m-d H:i:s'));
            return $this->insertDto($dto);
        }
        

    }

}
