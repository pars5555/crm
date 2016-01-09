<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\purchase\warranty {

    use crm\loads\AdminLoad;
    use crm\managers\CurrencyManager;
    use crm\managers\ProductManager;
    use crm\managers\PurchaseOrderLineManager;
    use crm\managers\PurchaseOrderLineSerialNumberManager;
    use crm\managers\PurchaseOrderManager;
    use crm\security\RequestGroups;
    use NGS;

    class OpenLoad  extends AdminLoad {

        public function load() {
            $this->initErrorMessages();
            $this->initSuccessMessages();
            $this->addParam('products', ProductManager::getInstance()->selectAdvance('*', [], ['name']));
            $this->addParam('currencies', CurrencyManager::getInstance()->selectAdvance('*', ['active', '=', 1], ['name']));
            $paymentId = NGS()->args()->id;
            $purchaseOrders = PurchaseOrderManager::getInstance()->getPurchaseOrdersFull(['id', '=', $paymentId]);
            if (!empty($purchaseOrders)) {
                $purchaseOrder = $purchaseOrders[0];
                $this->addParam('purchaseOrder', $purchaseOrder);
            }

            $polSerialNumbersDtos = [];
            $purchaseOrderLineIds = $this->getPurchaseOrderLineIds($purchaseOrders);
            if (!empty($purchaseOrderLineIds)) {
                $purchaseOrderLineIdsSql = '(' . implode(',', $purchaseOrderLineIds) . ')';
                $polSerialNumbersDtos = PurchaseOrderLineSerialNumberManager::getInstance()->selectAdvance('*', ['line_id', 'IN', $purchaseOrderLineIdsSql]);
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

        private function getPurchaseOrderLineIds($purchaseOrders) {
            $lineIds = [];
            foreach ($purchaseOrders as $purchaseOrder) {
                $purchaseOrderLineIds = PurchaseOrderLineManager::getDtosIdsArray($purchaseOrder->getPurchaseOrderLinesDtos());
                $lineIds = array_merge($lineIds, $purchaseOrderLineIds);
            }
            return $lineIds;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/purchase/warranty/open.tpl";
        }

    }

}
