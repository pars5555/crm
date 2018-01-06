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

    use crm\dal\mappers\PartnerDebtCacheMapper;

    class PartnerDebtCacheManager extends AdvancedAbstractManager {

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
                self::$instance = new PartnerDebtCacheManager(PartnerDebtCacheMapper::getInstance());
            }
            return self::$instance;
        }

        public function setPartnerDebtCache($partnerId, $debts) {
            foreach ($debts as $currencyId => $amount) {
                $rows = $this->selectAdvance('*', ['partner_id', '=', $partnerId, 'and', 'currency_id', '=', $currencyId]);
                if (!empty($rows)) {
                    $row= $rows[0];
                    $row ->setAmount(floatval($amount));
                    $this->mapper->updateByPK($row);
                }else
                {
                    $dto = new \crm\dal\dto\PartnerDebtCacheDto();
                    $dto ->setPartnerId($partnerId);
                    $dto ->setAmount(floatval($amount));
                    $dto ->setCurrencyId($currencyId);
                    $this->mapper->insertDto($dto);
                    
                }
            }
        }

    }

}
