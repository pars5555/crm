<?php

/**
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2010-2015
 * @package managers
 * @version 6.0
 *
 */

namespace crm\managers {

    use crm\dal\mappers\RecipientMapper;

    class RecipientManager extends AdvancedAbstractManager {

        /**
         * @var $instance
         */
        public static $instance;

        /**
         * Returns an singleton instance of this class
         *
         * @param object $config
         * @param object $args
         * @return
         */
        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new RecipientManager(RecipientMapper::getInstance());
            }
            return self::$instance;
        }

        public function getRecipientFull($partnerId) {
            $partners = $this->getRecipientsFull(['id', '=', $partnerId]);
            if (!empty($partners)) {
                return $partners [0];
            }
            return null;
        }

        public function setRecipientDeleted($id, $deleted) {
            $this->mapper->updateField($id, 'deleted', $deleted);
        }

        public function setRecipientFavorite($id, $favorite) {
            $this->mapper->updateField($id, 'favorite', $favorite);
        }

        public function getRecipientsFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            return $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit);
        }

        public function deleteRecipientFull($partnerId) {
            $saleOrderDtosMappedById = SaleOrderManager::getInstance()->selectAdvance('id', ['partner_id', '=', $partnerId], null, null, null, null, true);
            $orderDtosMappedById = RecipientOrderManager::getInstance()->selectAdvance('id', ['partner_id', '=', $partnerId], null, null, null, null, true);
            if (!empty($saleOrderDtosMappedById)) {
                $sqlSaleOrderIds = '(' . implode(',', array_keys($saleOrderDtosMappedById)) . ')';
                SaleOrderLineManager::getInstance()->deleteAdvance(['sale_order_id', 'in', $sqlSaleOrderIds]);
            }
            if (!empty($orderDtosMappedById)) {
                $sqlOrderIds = '(' . implode(',', array_keys($orderDtosMappedById)) . ')';
                RecipientOrderLineManager::getInstance()->deleteAdvance(['recipient_order_id', 'in', $sqlOrderIds]);
            }
            SaleOrderManager::getInstance()->deleteByField('partner_id', $partnerId);
            RecipientOrderManager::getInstance()->deleteByField('partner_id', $partnerId);
            PaymentTransactionManager::getInstance()->deleteByField('partner_id', $partnerId);
            RecipientManager::getInstance()->deleteByPK($partnerId);
            return true;
        }

        public function createRecipient($name, $email, $meta, $documents, $phone, $isFavorite) {
            $dto = $this->createDto();
            $dto->setName($name);
            $dto->setEmail($email);
            $dto->setMeta($meta);
            $dto->setDocuments($documents);
            $dto->setPhone($phone);
            $dto->setFavorite($isFavorite);
            return $this->insertDto($dto);
        }

        public function updateRecipient($id, $name, $email, $address, $phone, $initialDebts) {
            $dto = $this->selectByPK($id);
            if (isset($dto)) {
                $dto->setName($name);
                $dto->setEmail($email);
                $dto->setAddress($address);
                $dto->setPhone($phone);
                $dto->setCreateDate(date('Y-m-d H:i:s'));
                $ret = $this->updateByPk($dto);
                RecipientInitialDebtManager::getInstance()->deleteByField('partner_id', $id);
                if (!empty($initialDebts)) {
                    foreach ($initialDebts as $initialDebt) {
                        RecipientInitialDebtManager::getInstance()->addRow($id, $initialDebt->amount, $initialDebt->currency_id, $initialDebt->note);
                    }
                }
                return $ret;
            }
            return false;
        }

    }

}
