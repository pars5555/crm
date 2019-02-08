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

    use crm\loads\AdminLoad;
    use crm\managers\PurchaseOrderLineManager;
    use crm\managers\PurchaseOrderLineSerialNumberManager;
    use crm\managers\PurchaseOrderManager;
    use crm\managers\SaleOrderLineManager;
    use crm\managers\SaleOrderLineSerialNumberManager;
    use crm\managers\SaleOrderManager;
    use crm\security\RequestGroups;
    use NGS;

    class ContentLoad extends AdminLoad {

        public function load() {
            $searchText = $this->initFilters();
            $searchWhereFilter = [];
            if ($searchText !== false) {
                $searchWhereFilter = ['serial_number', 'like', "'%$searchText%'"];
            }
            $sol_searial_numbers = SaleOrderLineSerialNumberManager::getInstance()->selectAdvance('*', $searchWhereFilter, null, null, 0, 1000);
            if (!empty($sol_searial_numbers)){
            $saleOrdersDateMappedBySN = $this->getSaleOrdersDatesMappedBySN($sol_searial_numbers);
            }
            $pol_searial_numbers = PurchaseOrderLineSerialNumberManager::getInstance()->selectAdvance('*', $searchWhereFilter, null, null, 0, 1000);
            $purchaseOrdersDateMappedBySN = [];
            if (!empty($pol_searial_numbers)){
                $purchaseOrdersDateMappedBySN = $this->getPurchaseOrdersDatesMappedBySN($pol_searial_numbers);
            }
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
            $soIdsMappedBySolId = $this->getSoIdsMappedBySolIds($solDtos);
            if (!empty($soIdsMappedBySolId)) {
                $soDtos = SaleOrderManager::getInstance()->getSaleOrdersFull($where = ['id', 'in', '(' . implode(',', array_unique(array_values($soIdsMappedBySolId))), ')']);
                $soDtosMappedByID = SaleOrderManager::mapDtosById($soDtos);
            }
            $ret = [];
            foreach ($soIdsMappedBySolId as $solId => $soId) {
                foreach ($snMappedBySolIds[$solId] as $sn) {
                    if (isset($soDtosMappedByID[$soId])) {
                        $ret[$sn] = [$soId, $soDtosMappedByID[$soId]->getOrderDate(), $soDtosMappedByID[$soId]->getPartnerDto()->getName()];
                    }
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
            $poDtos = PurchaseOrderManager::getInstance()->getPurchaseOrdersFull($where = ['id', 'in', '(' . implode(',', array_unique(array_values($poIdsMappedByPolId))), ')']);
            $poDtosMappedByID = SaleOrderManager::mapDtosById($poDtos);
            $ret = [];
            foreach ($poIdsMappedByPolId as $polId => $poId) {
                foreach ($snMappedByPolIds[$polId] as $sn) {
                    if (isset($poDtosMappedByID[$poId])) {
                        $ret[$sn] = [$poId, $poDtosMappedByID[$poId]->getOrderDate(), $poDtosMappedByID[$poId]->getPartnerDto()->getName()];
                    }
                }
            }
            return $ret;
        }

    }

}
