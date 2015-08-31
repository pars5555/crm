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
    use crm\managers\PurchaseOrderLineManager;
    use crm\managers\PurchaseOrderLineSerialNumberManager;
    use crm\managers\PurchaseOrderManager;
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
            $saleOrdersDateMappedBySN = $this->getSaleOrdersDatesMappedBySN($sol_searial_numbers);
            $pol_searial_numbers = PurchaseOrderLineSerialNumberManager::getInstance()->selectAdvance('*', $searchWhereFilter, null, null, 0, 1000);
            $purchaseOrdersDateMappedBySN = $this->getPurchaseOrdersDatesMappedBySN($pol_searial_numbers);
            $combinePolAndSolSerialNumbers = $this->combinePolAndSolSerialNumbers($sol_searial_numbers, $pol_searial_numbers);
            $this->addParam('searial_numbers', $combinePolAndSolSerialNumbers);
            $this->addParam('saleOrdersDateMappedBySN', $saleOrdersDateMappedBySN);
            $this->addParam('purchaseOrdersDateMappedBySN', $purchaseOrdersDateMappedBySN);
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

        private function getSoIdsMappedBySolIds($solDtos) {
            $ret = [];
            foreach ($solDtos as $solDto) {
                $ret[$solDto->getId()] = $solDto->getSaleOrderId();
            }
            return $ret;
        }

        private function getSaleOrdersDatesMappedBySN($sol_searial_numbers) {
            $snMappedBySolIds = [];
            foreach ($sol_searial_numbers as $sol_searial_number) {
                $snMappedBySolIds[$sol_searial_number->getLineId()][] = $sol_searial_number->getSerialNumber();
            }
            $solDtos = SaleOrderLineManager::getInstance()->selectByPKs(array_keys($snMappedBySolIds));
            $solIdsMappedBySoId = $this->getSoIdsMappedBySolIds($solDtos);
            $soDtosMappedByID = SaleOrderManager::getInstance()->selectByPKs(array_unique(array_values($solIdsMappedBySoId)), true);
            $ret = [];
            foreach ($solIdsMappedBySoId as $solId => $soId) {
                foreach ($snMappedBySolIds[$solId] as $sn) {
                    $ret[$sn] = $soDtosMappedByID[$soId]->getOrderDate();
                    
                }
            }
            return $ret;
        }

        private function getPoIdsMappedByPolIds($solDtos) {
            $ret = [];
            foreach ($solDtos as $solDto) {
                $ret[$solDto->getId()] = $solDto->getPurchaseOrderId();
            }
            return $ret;
        }

        private function getPurchaseOrdersDatesMappedBySN($pol_searial_numbers) {
            $snMappedByPolIds = [];
            foreach ($pol_searial_numbers as $pol_searial_number) {
                $snMappedByPolIds[$pol_searial_number->getLineId()][] = $pol_searial_number->getSerialNumber();
            }
            $polDtos = PurchaseOrderLineManager::getInstance()->selectByPKs(array_keys($snMappedByPolIds));
            $poIdsMappedByPolId = $this->getPoIdsMappedByPolIds($polDtos);
            $poDtosMappedByID = PurchaseOrderManager::getInstance()->selectByPKs(array_unique(array_values($poIdsMappedByPolId)), true);
            $ret = [];
            foreach ($poIdsMappedByPolId as $polId => $poId) {
                foreach ($snMappedByPolIds[$polId] as $sn) {
                    $ret[$sn] = $poDtosMappedByID[$poId]->getOrderDate();
                    
                }
            }
            return $ret;
        }

    }

}
