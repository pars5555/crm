<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\sale\warranty {

    use crm\loads\AdminLoad;
    use crm\managers\CurrencyManager;
    use crm\managers\ProductManager;
    use crm\managers\SaleOrderLineManager;
    use crm\managers\SaleOrderLineSerialNumberManager;
    use crm\managers\SaleOrderManager;
    use crm\security\RequestGroups;
    use NGS;

    class OpenLoad  extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $this->addParam('products', ProductManager::getInstance()->selectAdvance('*', [], ['name']));
            $this->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
            $soId = NGS()->args()->id;
            $saleOrders = SaleOrderManager::getInstance()->getSaleOrdersFull(['id', '=', $soId]);
            if (!empty($saleOrders)) {
                $saleOrder = $saleOrders[0];
                $this->addParam('saleOrder', $saleOrder);
            }

            $solSerialNumbersDtos = [];
            $saleOrderLineIds = $this->getSaleOrderLineIds($saleOrders);
            if (!empty($saleOrderLineIds)) {
                $saleOrderLineIdsSql = '(' . implode(',', $saleOrderLineIds) . ')';
                $solSerialNumbersDtos = SaleOrderLineSerialNumberManager::getInstance()->selectAdvance('*', ['line_id', 'IN', $saleOrderLineIdsSql]);
                $solSerialNumbersDtos = $this->mapDtosByLineId($solSerialNumbersDtos);
            }
            $this->addParam("polSerialNumbersDtos", $solSerialNumbersDtos);
        }

        private function mapDtosByLineId($polSerialNumbersDtos) {
            $ret = [];
            foreach ($polSerialNumbersDtos as $polSerialNumbersDto) {
                $ret[$polSerialNumbersDto->getLineId()][] = $polSerialNumbersDto;
            }
            return $ret;
        }

        private function getSaleOrderLineIds($saleOrders) {
            $lineIds = [];
            foreach ($saleOrders as $saleOrder) {
                $saleOrderLineIds = SaleOrderLineManager::getDtosIdsArray($saleOrder->getSaleOrderLinesDtos());
                $lineIds = array_merge($lineIds, $saleOrderLineIds);
            }
            return $lineIds;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/sale/warranty/open.tpl";
        }


    }

}
