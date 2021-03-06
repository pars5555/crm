<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\general {

    use crm\loads\AdminLoad;
    use crm\managers\SettingManager;
    use NGS;

    class GeneralLoad extends AdminLoad {

        public function load() {
            $adminId = NGS()->getSessionManager()->getUserId();
            if (\crm\managers\AdminManager::getInstance()->getById($adminId)->getType() !== 'root'){
                NGS()->getSessionManager()->logout();
                $this->redirect('login');
                exit;
            }
            $capitalData = json_decode(SettingManager::getInstance()->getSetting('capital_data', '{}'), true);
            $warehouseTotal = $capitalData['warehouse_total'];
            $purseTotal = $capitalData['purse_total'];
            $purseBalanceTotal = $capitalData['purse_balance_total'];
            $capital_external_debts = floatval($capitalData['capital_external_debts']);
            $capital_external_btc = floatval($capitalData['capital_external_btc']);
            $partnerDebtTotal = $capitalData['partner_debt_total'];
            $cashboxTotal = $capitalData['cashbox_total'];
            $this->addParam("cashboxTotal", $cashboxTotal);
            $this->addParam('partnerDebtTotal', $partnerDebtTotal);
            $this->addParam('warehouseTotal', $warehouseTotal);
            $this->addParam('purseTotal', $purseTotal);
            $this->addParam('purseBalanceTotal', $purseBalanceTotal);
            $this->addParam('capital_external_debts', $capital_external_debts);
            $this->addParam('capital_external_btc', $capital_external_btc);
            $this->addParam('capital', $warehouseTotal + $purseTotal + $purseBalanceTotal + $partnerDebtTotal + $cashboxTotal + $capital_external_btc - $capital_external_debts);

            $this->addParam("capital_external_debts", SettingManager::getInstance()->getSetting('capital_external_debts'));
            $this->addParam("capital_external_debts_note", SettingManager::getInstance()->getSetting('capital_external_debts_note'));
            $this->addParam("capital_external_btc", floatval(SettingManager::getInstance()->getSetting('capital_external_btc')));
        }

        public function getDefaultLoads() {
            $loads = array();
            $loads["profitCalculation"]["action"] = "crm.loads.main.general.profit";
            $loads["profitCalculation"]["args"] = array();
            $loads["cashboxCalculation"]["action"] = "crm.loads.main.general.cashbox";
            $loads["cashboxCalculation"]["args"] = array();
            return $loads;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/general/general.tpl";
        }

    }

}
