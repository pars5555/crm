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

        public function calculatePartnerDeptBySalePurchaseAndPaymentTransations($partnersSaleOrders, $partnersPurchaseOrders, $partnerPaymentTransactions, $partnerBillingTransactions, $partnerInitialDept) {
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
                    $partnerDept[$currencyId] -= $amount;
                }
            }
            foreach ($partnerPaymentTransactions as $transaction) {
                if ($transaction->getCancelled() == 1) {
                    continue;
                }
                $currencyId = $transaction->getCurrencyId();
                $amount = floatval($transaction->getAmount());
                if (!array_key_exists($currencyId, $partnerDept)) {
                    $partnerDept[$currencyId] = 0;
                }
                $partnerDept[$currencyId] += $amount;
            }
            foreach ($partnerBillingTransactions as $transaction) {
                if ($transaction->getCancelled() == 1) {
                    continue;
                }
                $currencyId = $transaction->getCurrencyId();
                $amount = floatval($transaction->getAmount());
                if (!array_key_exists($currencyId, $partnerDept)) {
                    $partnerDept[$currencyId] = 0;
                }
                $partnerDept[$currencyId] += $amount;
            }
            if (!empty($partnerInitialDept)) {
                foreach ($partnerInitialDept as $currencyId => $amount) {
                    $partnerDept[$currencyId] += $amount;
                }
            }
            return $partnerDept;
        }

        public function calculatePartnerAllDealesDebtHistory($partnerId, $allDeals) {
             
            $partnerDebt = PartnerInitialDeptManager::getInstance()->getPartnerInitialDept($partnerId);

            foreach ($allDeals as $dealTypeObjectPair) {
                $dealType = $dealTypeObjectPair[0];
                $dealObject = $dealTypeObjectPair[1];
                
                if ($dealObject->getCancelled() == 1) {
                    continue;
                }
                switch ($dealType) {
                    case 'sale':
                        $totalAmount = $dealObject->getTotalAmount();
                        foreach ($totalAmount as $currencyId => $amount) {
                            if (!array_key_exists($currencyId, $partnerDebt)) {
                                $partnerDebt[$currencyId] = 0;
                            }
                            $partnerDebt[$currencyId] += $amount;
                        }
                        break;
                    case 'purchase':
                        $totalAmount = $dealObject->getTotalAmount();
                        foreach ($totalAmount as $currencyId => $amount) {
                            if (!array_key_exists($currencyId, $partnerDebt)) {
                                $partnerDebt[$currencyId] = 0;
                            }
                            $partnerDebt[$currencyId] -= $amount;
                        }
                        break;
                    case 'billing':
                        $currencyId = $dealObject->getCurrencyId();
                        $totalAmount = floatval($dealObject->getAmount());
                        if (!array_key_exists($currencyId, $partnerDebt)) {
                            $partnerDebt[$currencyId] = 0;
                        }
                        $partnerDebt[$currencyId] += $totalAmount;
                        break;
                    case 'payment':
                        $currencyId = $dealObject->getCurrencyId();
                        $totalAmount = floatval($dealObject->getAmount());
                        if (!array_key_exists($currencyId, $partnerDebt)) {
                            $partnerDebt[$currencyId] = 0;
                        }
                        $partnerDebt[$currencyId] += $totalAmount;
                        break;
                }
               $dealObject->setDebt($partnerDebt);
               
            }
        }

        public function calculatePartnersDeptBySalePurchaseAndPaymentTransations($partnersSaleOrdersMappedByPartnerId, $partnersPurchaseOrdersMappedByPartnerId, $partnersPaymentTransactionsMappedByPartnerId, $partnersBillingTransactionsMappedByPartnerId, $partnersInitialDept) {
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
            foreach ($partnersPaymentTransactionsMappedByPartnerId as $partnerId => $transactions) {
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

            foreach ($partnersBillingTransactionsMappedByPartnerId as $partnerId => $transactions) {
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
            //rounding
            foreach ($partnersDept as $partnerId => $partnerDept) {
                foreach ($partnerDept as $currencyId => &$dept) {
                    $partnersDept[$partnerId][$currencyId] = round($dept, 2);
                    //this case is for -0 only to show it as 0
                    if ($partnersDept[$partnerId][$currencyId] == -$partnersDept[$partnerId][$currencyId]) {
                        $partnersDept[$partnerId][$currencyId] = 0;
                    }
                }
            }
            if (!empty($partnersInitialDept)) {
                foreach ($partnersInitialDept as $partnerId => $partnerInitialDept) {
                    foreach ($partnerInitialDept as $currencyId => $amount) {
                        if (!isset($partnersDept[$partnerId])) {
                            $partnersDept[$partnerId] = [];
                        }
                        if (!isset($partnersDept[$partnerId][$currencyId])) {
                            $partnersDept[$partnerId][$currencyId] = 0;
                        }
                        $partnersDept[$partnerId][$currencyId] += $amount;
                    }
                }
            }
            return $partnersDept;
        }

    }

}
