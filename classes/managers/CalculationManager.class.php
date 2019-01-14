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

        public function calculatePartnerDebtBySalePurchaseAndPaymentTransations($partnersSaleOrders, $partnersPurchaseOrders, $partnerPaymentTransactions, $partnerBillingTransactions, $partnerInitialDebt) {
            $partnerDebt = [];
            foreach ($partnersSaleOrders as $saleOrder) {
                if ($saleOrder->getCancelled() == 1) {
                    continue;
                }
                $totalAmount = $saleOrder->getTotalAmount();
                foreach ($totalAmount as $currencyId => $amount) {
                    if (!array_key_exists($currencyId, $partnerDebt)) {
                        $partnerDebt[$currencyId] = 0;
                    }
                    $partnerDebt[$currencyId] += $amount;
                }
            }
            foreach ($partnersPurchaseOrders as $purchaseOrder) {
                if ($purchaseOrder->getCancelled() == 1) {
                    continue;
                }
                $totalAmount = $purchaseOrder->getTotalAmount();
                foreach ($totalAmount as $currencyId => $amount) {
                    if (!array_key_exists($currencyId, $partnerDebt)) {
                        $partnerDebt[$currencyId] = 0;
                    }
                    $partnerDebt[$currencyId] -= $amount;
                }
            }
            foreach ($partnerPaymentTransactions as $transaction) {
                if ($transaction->getCancelled() == 1) {
                    continue;
                }
                $currencyId = $transaction->getCurrencyId();
                $amount = floatval($transaction->getAmount());
                if (!array_key_exists($currencyId, $partnerDebt)) {
                    $partnerDebt[$currencyId] = 0;
                }
                $partnerDebt[$currencyId] += $amount;
            }
            foreach ($partnerBillingTransactions as $transaction) {
                if ($transaction->getCancelled() == 1) {
                    continue;
                }
                $currencyId = $transaction->getCurrencyId();
                $amount = floatval($transaction->getAmount());
                if (!array_key_exists($currencyId, $partnerDebt)) {
                    $partnerDebt[$currencyId] = 0;
                }
                $partnerDebt[$currencyId] += $amount;
            }
            if (!empty($partnerInitialDebt)) {
                foreach ($partnerInitialDebt as $currencyId => $amount) {
                    if (!array_key_exists($currencyId, $partnerDebt)) {
                        $partnerDebt[$currencyId] = 0;
                    }
                    $partnerDebt[$currencyId] += $amount;
                }
            }
            return $partnerDebt;
        }

        public function calculatePartnerAllDealesDebtHistory($partnerId, $allDeals) {

            $partnerDebt = PartnerInitialDebtManager::getInstance()->getPartnerInitialDebt($partnerId);

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

        public function calculatePartnersDebtBySalePurchaseAndPaymentTransations($partnersSaleOrdersMappedByPartnerId, $partnersPurchaseOrdersMappedByPartnerId, $partnersPaymentTransactionsMappedByPartnerId, $partnersBillingTransactionsMappedByPartnerId, $partnersInitialDebt) {
            $partnersDebt = [];
            foreach ($partnersSaleOrdersMappedByPartnerId as $partnerId => $saleOrders) {
                foreach ($saleOrders as $saleOrder) {
                    if ($saleOrder->getCancelled() == 1) {
                        continue;
                    }
                    $totalAmount = $saleOrder->getTotalAmount();
                    foreach ($totalAmount as $currencyId => $amount) {
                        if (!array_key_exists($partnerId, $partnersDebt)) {
                            $partnersDebt[$partnerId] = [];
                        }
                        if (!array_key_exists($currencyId, $partnersDebt[$partnerId])) {
                            $partnersDebt[$partnerId][$currencyId] = 0;
                        }
                        $partnersDebt[$partnerId][$currencyId] += $amount;
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
                        if (!array_key_exists($partnerId, $partnersDebt)) {
                            $partnersDebt[$partnerId] = [];
                        }
                        if (!array_key_exists($currencyId, $partnersDebt[$partnerId])) {
                            $partnersDebt[$partnerId][$currencyId] = 0;
                        }
                        $partnersDebt[$partnerId][$currencyId] -= $amount;
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
                    if (!array_key_exists($partnerId, $partnersDebt)) {
                        $partnersDebt[$partnerId] = [];
                    }
                    if (!array_key_exists($currencyId, $partnersDebt[$partnerId])) {
                        $partnersDebt[$partnerId][$currencyId] = 0;
                    }
                    $partnersDebt[$partnerId][$currencyId] += $unitPrice;
                }
            }

            foreach ($partnersBillingTransactionsMappedByPartnerId as $partnerId => $transactions) {
                foreach ($transactions as $transaction) {
                    if ($transaction->getCancelled() == 1) {
                        continue;
                    }
                    $currencyId = $transaction->getCurrencyId();
                    $unitPrice = floatval($transaction->getAmount());
                    if (!array_key_exists($partnerId, $partnersDebt)) {
                        $partnersDebt[$partnerId] = [];
                    }
                    if (!array_key_exists($currencyId, $partnersDebt[$partnerId])) {
                        $partnersDebt[$partnerId][$currencyId] = 0;
                    }
                    $partnersDebt[$partnerId][$currencyId] += $unitPrice;
                }
            }
            //rounding
            foreach ($partnersDebt as $partnerId => $partnerDebt) {
                foreach ($partnerDebt as $currencyId => &$debt) {
                    $partnersDebt[$partnerId][$currencyId] = round($debt, 2);
                    //this case is for -0 only to show it as 0
                    if ($partnersDebt[$partnerId][$currencyId] == -$partnersDebt[$partnerId][$currencyId]) {
                        $partnersDebt[$partnerId][$currencyId] = 0;
                    }
                }
            }
            if (!empty($partnersInitialDebt)) {
                foreach ($partnersInitialDebt as $partnerId => $partnerInitialDebt) {
                    foreach ($partnerInitialDebt as $currencyId => $amount) {
                        if (!isset($partnersDebt[$partnerId])) {
                            $partnersDebt[$partnerId] = [];
                        }
                        if (!isset($partnersDebt[$partnerId][$currencyId])) {
                            $partnersDebt[$partnerId][$currencyId] = 0;
                        }
                        $partnersDebt[$partnerId][$currencyId] += $amount;
                    }
                }
            }
            foreach ($partnersDebt as $partnerId => $partnerDebt) {
                $zeroDebt = 1;
                foreach ($partnerDebt as $currencyId => &$debt) {
                    $partnersDebt[$partnerId][$currencyId] = round($debt, 2);
                    //this case is for -0 only to show it as 0
                    if ($partnersDebt[$partnerId][$currencyId] == -$partnersDebt[$partnerId][$currencyId]) {
                        $partnersDebt[$partnerId][$currencyId] = 0;
                    }
                    if (abs($partnersDebt[$partnerId][$currencyId]) >= 0.1) {
                        $zeroDebt = 0;
                    }
                }
                $partnersZeroDebt[$partnerId] = $zeroDebt;
            }
            return [$partnersDebt, $partnersZeroDebt];
        }

    }

}
