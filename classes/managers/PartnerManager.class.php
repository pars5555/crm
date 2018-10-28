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

        public function getPartnerFull($partnerId) {
            $partners = $this->getPartnersFull(['id', '=', $partnerId]);
            if (!empty($partners)) {
                return $partners [0];
            }
            return null;
        }
        
        public function setPartnerIncludedInCapital($id, $hidden) {
            $this->mapper->updateField($id, 'included_in_capital', $hidden);
            
        }
        
        public function setPartnerHidden($id, $hidden) {
            $this->mapper->updateField($id, 'hidden', $hidden);
            
        }
        public function getPartnersFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            $rows = $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit);
            $partnerIds = array();
            foreach ($rows as $row) {
                $partnerIds[] = intval($row->getId());
            }
            $partnerIds = array_unique($partnerIds);
            $partnerInitialDebtDtos = PartnerInitialDebtManager::getInstance()->getInitialDebtsFull(['partner_id', 'in', '(' . implode(',', $partnerIds) . ')'], 'datetime', 'DESC');
            $partnerInitialDebtDtos = $this->mapByPartnerId($partnerInitialDebtDtos);
            foreach ($rows as $row) {
                $partnerId = intval($row->getId());
                if (!array_key_exists($partnerId, $partnerInitialDebtDtos)) {
                    $partnerInitialDebtDtos[$partnerId] = [];
                }
                $row->setPartnerInitialDebtDtos($partnerInitialDebtDtos[$partnerId]);
            }
            return $rows;
        }

        public function deletePartnerFull($partnerId) {
            $saleOrderDtos = SaleOrderManager::getInstance()->selectAdvance('id', ['partner_id', '=', $partnerId]);
            $purchaseOrderDtos= PurchaseOrderManager::getInstance()->selectAdvance('id', ['partner_id', '=', $partnerId]);
            $paymentOrderDtos = PaymentTransactionManager::getInstance()->selectAdvance('id', ['partner_id', '=', $partnerId]);
            if (!empty($saleOrderDtos)) {
                throw new \ngs\framework\exceptions\NgsErrorException('partner has sale orders related!');
            }
            if (!empty($purchaseOrderDtos)) {
                throw new \ngs\framework\exceptions\NgsErrorException('partner has purchase orders related!');
            }
            if (!empty($paymentOrderDtos)) {
                throw new \ngs\framework\exceptions\NgsErrorException('partner has payment orders related!');
            }
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

        public function updatePartner($id, $name, $email, $address, $phone, $initialDebts) {
            $dto = $this->selectByPK($id);
            if (isset($dto)) {
                $dto->setName($name);
                $dto->setEmail($email);
                $dto->setAddress($address);
                $dto->setPhone($phone);
                $dto->setCreateDate(date('Y-m-d H:i:s'));
                $ret = $this->updateByPk($dto);
                PartnerInitialDebtManager::getInstance()->deleteByField('partner_id', $id);
                if (!empty($initialDebts)) {
                    foreach ($initialDebts as $initialDebt) {
                        PartnerInitialDebtManager::getInstance()->addRow($id, $initialDebt->amount, $initialDebt->currency_id, $initialDebt->note);
                    }
                }
                return $ret;
            }
            return false;
        }

        public function calculatePartnerDebtBySalePurchaseAndPaymentTransations($id) {
            $partnerSaleOrders = SaleOrderManager::getInstance()->getPartnerSaleOrders($id);
            $partnerPurchaseOrders = PurchaseOrderManager::getInstance()->getPartnerPurchaseOrders($id);
            $partnerPaymentTransactions = PaymentTransactionManager::getInstance()->getPartnerPaymentTransactions($id);
            $partnerBillingTransactions = PaymentTransactionManager::getInstance()->getPartnerBillingTransactions($id);
            $partnerInitialDebt = PartnerInitialDebtManager::getInstance()->getPartnerInitialDebt($id);
            return CalculationManager::getInstance()->calculatePartnerDebtBySalePurchaseAndPaymentTransations(
                            $partnerSaleOrders, $partnerPurchaseOrders, $partnerPaymentTransactions, $partnerBillingTransactions, $partnerInitialDebt);
        }

        private function mapByPartnerId($partnerInitialDebtDtos) {
            $ret = [];
            foreach ($partnerInitialDebtDtos as $partnerInitialDebtDto) {
                $ret[$partnerInitialDebtDto->getPartnerId()][] = $partnerInitialDebtDto;
            }
            return $ret;
        }

    }

}
