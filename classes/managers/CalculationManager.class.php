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

    class CalculationManager {

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
                self::$instance = new CalculationManager();
            }
            return self::$instance;
        }

        public function calculatePartnerDeptBySalePurchaseAndPaymentTransations($partnersSaleOrders, $partnersPurchaseOrders, $partnersTransactions) {
            $partnerDept = [];
            foreach ($partnersSaleOrders as $saleOrder) {
                if ($saleOrder->getCancelled() == 1) {
                    continue;
                }
                $totalAmount = $saleOrder->getTotalAmount();
                foreach ($totalAmount as $currencyId => $amount) {
                    if (!array_key_exists($currencyId, $partnerDept)) {
                        $partnerDept[$currencyId] = 0;
                    }
                    $partnerDept[$currencyId] += $amount;
                }
            }
            foreach ($partnersPurchaseOrders as $purchaseOrder) {
                if ($purchaseOrder->getCancelled() == 1) {
                    continue;
                }
                $totalAmount = $purchaseOrder->getTotalAmount();
                foreach ($totalAmount as $currencyId => $amount) {
                    if (!array_key_exists($currencyId, $partnerDept)) {
                        $partnerDept[$currencyId] = 0;
                    }
                    $partnerDept[$currencyId] += $amount;
                }
            }
            foreach ($partnersTransactions as $transaction) {
                if ($transaction->getCancelled() == 1) {
                    continue;
                }
                $currencyId = $transaction->getCurrencyId();
                $unitPrice = floatval($transaction->getAmount());
                if (!array_key_exists($currencyId, $partnerDept)) {
                    $partnerDept[$currencyId] = 0;
                }
                $partnerDept[$currencyId] += $unitPrice;
            }
            return $partnerDept;
        }

        public function calculatePartnersDeptBySalePurchaseAndPaymentTransations($partnersSaleOrdersMappedByPartnerId, $partnersPurchaseOrdersMappedByPartnerId, $partnersTransactionsMappedByPartnerId) {
            $partnersDept = [];
            foreach ($partnersSaleOrdersMappedByPartnerId as $partnerId => $saleOrders) {
                foreach ($saleOrders as $saleOrder) {
                    if ($saleOrder->getCancelled() == 1) {
                        continue;
                    }
                    $totalAmount = $saleOrder->getTotalAmount();
                    foreach ($totalAmount as $currencyId => $amount) {
                        if (!array_key_exists($partnerId, $partnersDept)) {
                            $partnersDept[$partnerId] = [];
                        }
                        if (!array_key_exists($currencyId, $partnersDept[$partnerId])) {
                            $partnersDept[$partnerId][$currencyId] = 0;
                        }
                        $partnersDept[$partnerId][$currencyId] += $amount;
                    }
                }
            }
            foreach ($partnersPurchaseOrdersMappedByPartnerId as $partnerId => $purchaseOrders) {
                foreach ($purchaseOrders as $purchaseOrder) {
                    if ($purchaseOrder->getCancelled() == 1) {
                        continue;
                    }
                    $totalAmount = $purchaseOrder->getTotalAmount();
                    foreach ($totalAmount as $currencyId => $amount) {
                        if (!array_key_exists($partnerId, $partnersDept)) {
                            $partnersDept[$partnerId] = [];
                        }
                        if (!array_key_exists($currencyId, $partnersDept[$partnerId])) {
                            $partnersDept[$partnerId][$currencyId] = 0;
                        }
                        $partnersDept[$partnerId][$currencyId] -= $amount;
                    }
                }
            }
            foreach ($partnersTransactionsMappedByPartnerId as $partnerId => $transactions) {
                foreach ($transactions as $transaction) {
                    if ($transaction->getCancelled() == 1) {
                        continue;
                    }
                    $currencyId = $transaction->getCurrencyId();
                    $unitPrice = floatval($transaction->getAmount());
                    if (!array_key_exists($partnerId, $partnersDept)) {
                        $partnersDept[$partnerId] = [];
                    }
                    if (!array_key_exists($currencyId, $partnersDept[$partnerId])) {
                        $partnersDept[$partnerId][$currencyId] = 0;
                    }
                    $partnersDept[$partnerId][$currencyId] += $unitPrice;
                }
            }
            return $partnersDept;
        }

    }

}
