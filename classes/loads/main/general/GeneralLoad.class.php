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
            $capitalData = json_decode(SettingManager::getInstance()->getSetting('capital_data', '{}'), true);
            $warehouseTotal = $capitalData['warehouse_total'];
            $purseTotal = $capitalData['purse_total'];
            $purseBalanceTotal = $capitalData['purse_balance_total'];
            $partnerWarehouseTotal = $capitalData['partner_warehouse_total'];
            $partnerDebtTotal = $capitalData['partner_debt_total'];
            $cashboxTotal = $capitalData['cashbox_total'];
            $this->addParam("cashboxTotal", $cashboxTotal);
            $this->addParam('partnerDebtTotal', $partnerDebtTotal);
            $this->addParam('warehouseTotal', $warehouseTotal);
            $this->addParam('purseTotal', $purseTotal);
            $this->addParam('purseBalanceTotal', $purseBalanceTotal);
            $this->addParam('partnerWarehouseTotal', $partnerWarehouseTotal);
            $this->addParam('capital', $warehouseTotal + $purseTotal + $purseBalanceTotal + $partnerWarehouseTotal + $partnerDebtTotal + $cashboxTotal);
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
