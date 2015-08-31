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
    use crm\managers\SaleOrderLineSerialNumberManager;
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
            $solSearialNumbersMappedBySN = $this->mapBySerialNumbers($sol_searial_numbers);
            $pol_searial_numbers = PurchaseOrderLineSerialNumberManager::getInstance()->selectAdvance('*', $searchWhereFilter, null, null, 0, 1000);
            $polSearialNumbersMappedBySN = $this->mapBySerialNumbers($pol_searial_numbers);
            $combinePolAndSolSerialNumbers = $this->combinePolAndSolSerialNumbers($solSearialNumbersMappedBySN, $polSearialNumbersMappedBySN);
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

        private function combinePolAndSolSerialNumbers($solSearialNumbersMappedBySN, $polSearialNumbersMappedBySN) {
            $ret = [];
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

    }

}
