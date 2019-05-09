<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\vanilla {

    use crm\loads\AdminLoad;
    use crm\managers\PartnerManager;
    use crm\managers\SettingManager;
    use crm\managers\VanillaCardsManager;
    use NGS;

    class ListLoad extends AdminLoad {

        public function load() {
            self::initLoad($this);
        }
        
        public static function initLoad($load){
            $limit = 100;
            list($offset, $balance) = self::initFilters($limit, $load);
            $where = ['1', '=', '1'];
            //$where = array_merge($where, ['AND','deleted', '=', '0']);
            if ($balance > 0) {
                $where = array_merge($where, ['AND', 'balance', '>=', $balance]);
            }
            $barney_partner_id = intval(SettingManager::getInstance()->getSetting('barney_partner_id'));
            $debt = PartnerManager::getInstance()->calculatePartnerDebtBySalePurchaseAndPaymentTransations($barney_partner_id);
            $dollarDebt = 0;
            if (isset($debt[1])) {
                $dollarDebt = floatval($debt[1]);
            }
            $totalSuccess = VanillaCardsManager::getInstance()->getAllDeliveredTotal();
            $rows = VanillaCardsManager::getInstance()->selectAdvance('*', $where, 'id', 'desc', $offset, $limit);
            $count = VanillaCardsManager::getInstance()->getLastSelectAdvanceRowsCount();
            $pagesCount = ceil($count / $limit);
            $load->addParam('pagesCount', $pagesCount);
            $load->addParam('rows', $rows);
            $load->addParam('totalSuccess', $totalSuccess);
            $load->addParam('debt', $dollarDebt);

            $externalOrderIdsArray = [];
            foreach ($rows as $row) {
                $externalOrdersIds = intval($row->getExternalOrdersIds());
                if (!empty($externalOrdersIds)) {
                    $externalOrderIdsArray = array_merge($externalOrderIdsArray, explode(',', $externalOrdersIds));
                }
            }
            $exOrdersMappedById = \crm\managers\PurseOrderManager::getInstance()->selectByPKs($externalOrderIdsArray, true);

            self::addOrdersInfoToRows($rows, $exOrdersMappedById);
        }

        private static function initFilters($limit, $load) {
            //pageing
            $selectedFilterPage = 1;
            if (isset(NGS()->args()->pg)) {
                $selectedFilterPage = intval(NGS()->args()->pg);
            }
            $load->addParam('selectedFilterPage', $selectedFilterPage);
            $offset = 0;
            if ($selectedFilterPage > 1) {
                $offset = ($selectedFilterPage - 1) * intval($limit);
            }

            $balance = 0;
            if (isset(NGS()->args()->bal)) {
                $balance = floatval(NGS()->args()->bal);
            }

            $load->addParam('balance', $balance);
            return [$offset, $balance];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/vanilla/list.tpl";
        }

        private static function addOrdersInfoToRows($rows, $exOrdersMappedById) {
            foreach ($rows as $row) {
                $externalOrdersIds = $row->getExternalOrdersIds();
                if (!empty($externalOrdersIds)) {
                    $orderIds = explode(',', $externalOrdersIds);
                    foreach ($orderIds as $orderId) {
                        if (!isset($exOrdersMappedById[$orderId])) {
                            continue;
                        }
                        $amazonTotal = $exOrdersMappedById[$orderId]->getAmazonTotal();
                        $delivered = $exOrdersMappedById[$orderId]->getDelivered();
                        $row->addOrderAmount($amazonTotal);
                        if ($delivered == 1) {
                            $row->addSucceedAmountsText($amazonTotal);
                        }
                    }
                }
            }
        }

    }

}
