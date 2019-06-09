<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package loads.site.main
 * @version 2.0.0
 */

namespace crm\loads\main\giftcards {

    use crm\loads\AdminLoad;
    use crm\managers\AttachmentManager;
    use crm\managers\GiftCardsManager;
    use crm\managers\PartnerManager;
    use crm\managers\PurseOrderManager;
    use crm\managers\SettingManager;

    class ListLoad extends AdminLoad {

        public function load() {
            self::initLoad($this);
        }

        public static function initLoad($load) {
            $limit = 100;
            list($offset, $searchText, $partnerId) = self::initFilters($limit, $load);
            $where = ['1', '=', 1];
            if ($partnerId > 0) {
                $where = ['partner_id', '=', $partnerId, 'or', 'partner_id', '=', 0, 'or', 'partner_id', 'IS NULL'];
            }
            if (!empty($searchText)) {
                $words = $parts = preg_split('/\s+/', $searchText);
                foreach ($words as $word) {
                    $where = array_merge($where, ['AND', '(', 'code', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'external_order_ids', 'like', "'%$word%'"]);
                    $where = array_merge($where, ['OR', 'note', 'like', "'%$word%'", ')']);
                }
            }

            $debt = PartnerManager::getInstance()->calculatePartnerDebtBySalePurchaseAndPaymentTransations($partnerId);
            $dollarDebt = 0;
            if (isset($debt[1])) {
                $dollarDebt = floatval($debt[1]);
            }
            $rows = GiftCardsManager::getInstance()->selectAdvance('*', $where, 'id', 'desc', $offset, $limit);
            $count = GiftCardsManager::getInstance()->getLastSelectAdvanceRowsCount();
            if (count($rows) === 0) {
                $load->addParam('selectedFilterPage', 1);
                $rows = GiftCardsManager::getInstance()->selectAdvance('*', $where, 'id', 'desc', $offset, 0);
                $count = GiftCardsManager::getInstance()->getLastSelectAdvanceRowsCount();
            }
            $pagesCount = ceil($count / $limit);

            $load->addParam('pagesCount', $pagesCount);
            $load->addParam('rows', $rows);
            $load->addParam('debt', $dollarDebt);


            $externalOrderIdsArray = [];
            foreach ($rows as $row) {
                $externalOrdersIds = intval($row->getExternalOrdersIds());
                if (!empty($externalOrdersIds)) {
                    $externalOrderIdsArray = array_merge($externalOrderIdsArray, explode(',', $externalOrdersIds));
                }
            }
            $exOrdersMappedById = PurseOrderManager::getInstance()->selectByPKs($externalOrderIdsArray, true);

            self::addOrdersInfoToRows($rows, $exOrdersMappedById);


            $attachments = AttachmentManager::getInstance()->getEntitiesAttachments($rows, 'giftcard');
            $load->addParam('attachments', $attachments);
        }

        private static function initFilters($limit, $load) {
            $supplier_partner_ids = explode(',', trim(SettingManager::getInstance()->getSetting('gift_card_supplier_partner_ids')));
            $load->addParam('partner_ids', $supplier_partner_ids);
            $supplierPartners = PartnerManager::getInstance()->selectByPks($supplier_partner_ids, true);
            $load->addParam('partner_ids', $supplier_partner_ids);

            $selectedFilterMerchant = 'all';
            if (isset(NGS()->args()->mrch)) {
                $selectedFilterMerchant = strtolower(NGS()->args()->mrch);
            }
            if (!empty($load)) {
                $all_merchant_names_list = explode(',', SettingManager::getInstance()->getSetting('all_merchant_names_list'));
                $load->addParam('all_merchant_names_list', $all_merchant_names_list);
                $accountNames = PurseOrderManager::getInstance()->getAllAccountNames($selectedFilterMerchant);
                $load->addParam('account_names', $accountNames);
            }

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

            $searchText = '';
            if (isset(NGS()->args()->st)) {
                $searchText = trim(NGS()->args()->st);
            }
            $partner_id = 0;
            if (isset(NGS()->args()->pid)) {
                if (in_array(strtolower(NGS()->args()->pid), $supplier_partner_ids)) {
                    $partner_id = intval(NGS()->args()->pid);
                }
            }
            $load->addParam('searchText', $searchText);
            $load->addParam('selectedFilterPartnerId', $partner_id);
            $load->addParam('supplier_partners_mapped_by_ids', $supplierPartners);

            return [$offset, $searchText, $partner_id];
        }

        public function getTemplate() {
            return NGS()->getTemplateDir() . "/main/giftcards/list.tpl";
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
                        $row->addOrderAmount($amazonTotal);
                        if ($exOrdersMappedById[$orderId]->getStatus() === 'delivered') {
                            $row->addSucceedAmountsText($amazonTotal);
                        }
                    }
                }
            }
        }

    }

}
