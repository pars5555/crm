<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\warranty {

    use crm\loads\NgsLoad;
    use crm\managers\PurchaseOrderLineSerialNumberManager;
    use crm\managers\SaleOrderLineManager;
    use crm\managers\SaleOrderLineSerialNumberManager;
    use crm\managers\SaleOrderManager;
    use crm\security\RequestGroups;
    use NGS;

    class ContentLoad extends NgsLoad {

        public function load() {
            $searchText = $this->initFilters();
            $searchWhereFilter = [];
            if ($searchText !== false) {
                $searchWhereFilter = ['serial_number', 'like', "'%$searchText%'"];
            }
            $sol_searial_numbers = SaleOrderLineSerialNumberManager::getInstance()->selectAdvance('*', $searchWhereFilter, null, null, 0, 1000);
            $saleOrdersMappedBySN = $this->getSaleOrders($sol_searial_numbers);
            $pol_searial_numbers = PurchaseOrderLineSerialNumberManager::getInstance()->selectAdvance('*', $searchWhereFilter, null, null, 0, 1000);
            $purchaseOrdersMappedBySN = $this->getPurchaseOrders($pol_searial_numbers);
            $combinePolAndSolSerialNumbers = $this->combinePolAndSolSerialNumbers($sol_searial_numbers, $pol_searial_numbers);
            $this->addParam('searial_numbers', $combinePolAndSolSerialNumbers);
        }

        private function mapBySerialNumbers($serialNnumbersDtos) {
            $ret = [];
            foreach ($serialNnumbersDtos as $serialNnumberDto) {
                $ret[$serialNnumberDto->getSerialNumber()] = $serialNnumberDto;
            }
            return $ret;
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/warranty/content.tpl";
        }

        public function getRequestGroup() {
            return RequestGroups::$guestRequest;
        }

        private function initFilters() {
            $search = False;
            if (isset(NGS()->args()->search) && !empty(NGS()->args()->search)) {
                $search = NGS()->args()->search;
            }
            return $search;
        }

        private function combinePolAndSolSerialNumbers($sol_searial_numbers, $pol_searial_numbers) {
            $ret = [];
            $solSearialNumbersMappedBySN = $this->mapBySerialNumbers($sol_searial_numbers);
            $polSearialNumbersMappedBySN = $this->mapBySerialNumbers($pol_searial_numbers);
            foreach ($polSearialNumbersMappedBySN as $sn => $polSearialNumberDto) {
                $ret[$sn] = [$polSearialNumberDto];
                if (array_key_exists($sn, $solSearialNumbersMappedBySN)) {
                    $ret[$sn][] = $solSearialNumbersMappedBySN[$sn];
                }
            }
            foreach ($solSearialNumbersMappedBySN as $sn => $polSearialNumberDto) {
                if (!array_key_exists($sn, $ret)) {
                    $ret[$sn] = [null, $polSearialNumberDto];
                }
            }
            return $ret;
        }

        private function mapByOrderId($solDtos) {
            $ret = [];
            foreach ($solDtos as $solDto) {
                $ret[$solDto->getId()][] = $solDto->getOrderId();
            }
            return $ret;
        }

        private function getSaleOrders($sol_searial_numbers) {
            $snMappedBySolIds = [];
            foreach ($sol_searial_numbers as $sol_searial_number) {
                $snMappedBySolIds[$sol_searial_number->getLineId()] = $sol_searial_number->getSerialNumberId();
            }
            $solDtos = SaleOrderLineManager::getInstance()->selectByPKs(array_keys($snMappedBySolIds));
            $solIdsMappedBySoId = $this->mapByOrderId($solDtos);
            $soDtosMappedByID = SaleOrderManager::getInstance()->selectByPKs(array_unique(array_values($solIdsMappedBySoId)), true);
            $ret = [];
            foreach ($solIdsMappedBySoId as $solId => $soId) {
                $ret[$snMappedBySolIds[$solId]] = $soDtosMappedByID[$soId]->getOrderDate();
            }
            return $ret;
        }

       
    }

}
