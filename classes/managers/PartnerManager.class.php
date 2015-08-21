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

    use crm\dal\mappers\PartnerMapper;

    class PartnerManager extends AdvancedAbstractManager {

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
                self::$instance = new PartnerManager(PartnerMapper::getInstance());
            }
            return self::$instance;
        }

        public function deletePartnerFull($partnerId) {
            $saleOrderDtosMappedById = SaleOrderManager::getInstance()->selectAdvance('id', ['partner_id', '=', $partnerId], null, null, null, null, true);
            $purchaseOrderDtosMappedById = PurchaseOrderManager::getInstance()->selectAdvance('id', ['partner_id', '=', $partnerId], null, null, null, null, true);
            if (!empty($saleOrderDtosMappedById)) {
                $sqlSaleOrderIds = '(' . implode(',', array_keys($saleOrderDtosMappedById)) . ')';
                SaleOrderLineManager::getInstance()->deleteAdvance(['sale_order_id', 'in', $sqlSaleOrderIds]);
            }
            if (!empty($purchaseOrderDtosMappedById)) {
                $sqlPurchaseOrderIds = '(' . implode(',', array_keys($purchaseOrderDtosMappedById)) . ')';
                PurchaseOrderLineManager::getInstance()->deleteAdvance(['purchase_order_id', 'in', $sqlPurchaseOrderIds]);
            }
            SaleOrderManager::getInstance()->deleteByField('partner_id', $partnerId);
            PurchaseOrderManager::getInstance()->deleteByField('partner_id', $partnerId);
            PaymentTransactionManager::getInstance()->deleteByField('partner_id', $partnerId);
            PartnerManager::getInstance()->deleteByPK($partnerId);
            return true;
        }

        public function createPartner($name, $email, $address, $phone) {
            $dto = $this->createDto();
            $dto->setName($name);
            $dto->setEmail($email);
            $dto->setAddress($address);
            $dto->setPhone($phone);
            $dto->setCreateDate(date('Y-m-d H:i:s'));
            return $this->insertDto($dto);
        }

        public function updatePartner($id, $name, $email, $address, $phone) {
            $dto = $this->selectByPK($id);
            if (isset($dto)) {
                $dto->setName($name);
                $dto->setEmail($email);
                $dto->setAddress($address);
                $dto->setPhone($phone);
                $dto->setCreateDate(date('Y-m-d H:i:s'));
                return $this->updateByPk($dto);
            }
            return false;
        }

        public function calculatePartnerDeptBySalePurchaseAndPaymentTransations($id) {
            $partnerSaleOrders = SaleOrderManager::getInstance()->getPartnerSaleOrders($id);
            $partnerPurchaseOrders = PurchaseOrderManager::getInstance()->getPartnerPurchaseOrders($id);
            $partnerPaymentTransactions = PaymentTransactionManager::getInstance()->getPartnerPaymentTransactions($id);
            $partnerBillingTransactions = PaymentTransactionManager::getInstance()->getPartnerBillingTransactions($id);
            return CalculationManager::getInstance()->calculatePartnerDeptBySalePurchaseAndPaymentTransations(
                            $partnerSaleOrders, $partnerPurchaseOrders, $partnerPaymentTransactions, $partnerBillingTransactions);
        }

    }

}
