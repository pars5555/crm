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

    use crm\loads\NgsLoad;
    use crm\managers\CurrencyManager;
    use crm\managers\ProductManager;
    use crm\managers\SaleOrderLineManager;
    use crm\managers\SaleOrderLineSerialNumberManager;
    use crm\managers\SaleOrderManager;
    use crm\security\RequestGroups;
    use NGS;

    class OpenLoad extends NgsLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $this->addParam('products', ProductManager::getInstance()->selectAdvance('*', [], ['name']));
            $this->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
            $paymentId = NGS()->args()->id;
            $saleOrders = SaleOrderManager::getInstance()->getSaleOrdersFull(['id', '=', $paymentId]);
            if (!empty($saleOrders)) {
                $saleOrder = $saleOrders[0];
                $this->addParam('saleOrder', $saleOrder);
            }

            $polSerialNumbersDtos = [];
            $saleOrderLineIds = $this->getSaleOrderLineIds($saleOrders);
            if (!empty($saleOrderLineIds)) {
                $saleOrderLineIdsSql = '(' . implode(',', $saleOrderLineIds) . ')';
                $polSerialNumbersDtos = SaleOrderLineSerialNumberManager::getInstance()->selectAdvance('*', ['line_id', 'IN', $saleOrderLineIdsSql]);
                $polSerialNumbersDtos = $this->mapDtosByLineId($polSerialNumbersDtos);
            }
            $this->addParam("polSerialNumbersDtos", $polSerialNumbersDtos);
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

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

    }

}
