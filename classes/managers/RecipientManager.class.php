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

        public function getRecipientFull($recipientId) {
            $recipients = $this->getRecipientsFull(['id', '=', $recipientId]);
            if (!empty($recipients)) {
                return $recipients [0];
            }
            return null;
        }

        public function setRecipientDeleted($id, $deleted) {
            $this->mapper->updateField($id, 'deleted', $deleted);
        }

        public function setRecipientChecked($id, $checked) {
            $this->mapper->updateField($id, 'checked', $checked);
        }

        public function setRecipientFavorite($id, $favorite) {
            $this->mapper->updateField($id, 'favorite', $favorite);
        }

        public function getRecipientsFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            return $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit);
        }

        public function getRecipientByUnitAddress($unitAddress) {
            if (empty($unitAddress)) {
                return false;
            }
            $rows = $this->selectAdvance('*', [
                'lower', '(', 'express_unit_address', ')', '=', 'lower', '(', "'$unitAddress'", ')', 'OR',
                'lower', '(', 'standard_unit_address', ')', '=', 'lower', '(', "'$unitAddress'", ')', 'OR',
                'lower', '(', 'onex_express_unit', ')', '=', 'lower', '(', "'$unitAddress'", ')', 'OR',
                'lower', '(', 'onex_standard_unit', ')', '=', 'lower', '(', "'$unitAddress'", ')', 'OR',
                'lower', '(', 'shipex_express_unit', ')', '=', 'lower', '(', "'$unitAddress'", ')', 'OR',
                'lower', '(', 'shipex_standard_unit', ')', '=', 'lower', '(', "'$unitAddress'", ')', 'OR',
                'lower', '(', 'cheapex_express_unit', ')', '=', 'lower', '(', "'$unitAddress'", ')', 'OR',
                'lower', '(', 'cheapex_standard_unit', ')', '=', 'lower', '(', "'$unitAddress'", ')', 'OR',
                'lower', '(', 'nova_express_unit', ')', '=', 'lower', '(', "'$unitAddress'", ')', 'OR',
                'lower', '(', 'nova_standard_unit', ')', '=', 'lower', '(', "'$unitAddress'", ')'
            ]);
            if (empty($rows)) {
                return false;
            }
            return $rows[0];
        }

        public function getShippingTypeByUnitAddress($unitAddress) {
            $unitAddress = trim($unitAddress);
            $row = $this->getRecipientByUnitAddress($unitAddress);
            if (empty($row)) {
                return '';
            }
            if (strtolower($row->getExpressUnitAddress()) === strtolower($unitAddress) ||
                    strtolower($row->getOnexExpressUnit()) === strtolower($unitAddress) ||
                    strtolower($row->getShipexExpressUnit()) === strtolower($unitAddress) ||
                    strtolower($row->getCheapexExpressUnit()) === strtolower($unitAddress) ||
                    strtolower($row->getNovaExpressUnit()) === strtolower($unitAddress)) {
                return 'express';
            }
            if (strtolower($row->getStandardUnitAddress()) === strtolower($unitAddress) ||
                    strtolower($row->getOnexStandardUnit()) === strtolower($unitAddress) ||
                    strtolower($row->getShipexStandardUnit()) === strtolower($unitAddress) ||
                    strtolower($row->getCheapexStandardUnit()) === strtolower($unitAddress) ||
                    strtolower($row->getNovaStandardUnit()) === strtolower($unitAddress)) {
                return 'standard';
            }
            return '';
        }

        public function getFakeRecipientUnitAddressesSql() {
            $u1 = SettingManager::getInstance()->getSetting('onex_unit_address');
            $u2 = SettingManager::getInstance()->getSetting('nova_unit_address');
            $u3 = SettingManager::getInstance()->getSetting('globbing_unit_address');
            $u4 = SettingManager::getInstance()->getSetting('shipex_unit_address');
            return "'" . implode("','", [$u1, $u2, $u3, $u4]) . "'";
        }

        public function getRecipientUnitAddresses($recipintId, $sqlReady = false) {
            $recipient = $this->selectByPk($recipintId);
            $res = [$recipient->getExpressUnitAddress(), $recipient->getOnexExpressUnit(), $recipient->getNovaExpressUnit(), $recipient->getShipexExpressUnit(), $recipient->getCheapexExpressUnit(),
                $recipient->getStandardUnitAddress(), $recipient->getOnexStandardUnit(), $recipient->getNovaStandardUnit(), $recipient->getShipexStandardUnit(), $recipient->getCheapexStandardUnit()];
            $res = array_filter($res, function($value) {
                $value = trim($value);
                return !empty($value);
            });
            if (!$sqlReady) {
                return $res;
            }
            return "('" . implode("','", $res) . "')";
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

    }

}
