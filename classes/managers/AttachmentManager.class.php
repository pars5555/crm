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

    use crm\dal\mappers\AttachmentMapper;
    use crm\managers\AdvancedAbstractManager;
    use crm\managers\AttachmentManager;

    class AttachmentManager extends AdvancedAbstractManager {

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
                self::$instance = new AttachmentManager(AttachmentMapper::getInstance());
            }
            return self::$instance;
        }

        public function getPartnerRelatedAttachments($partnerObjects) {
            $partnerIds = [];
            foreach ($partnerObjects as $partnerObject) {
                $partnerIds [] = $partnerObject->getId();
            }
            $partnerIdsSql = '(' . implode(',', $partnerIds) . ')';
            $attachments = $this->selectAdvance('*', ['partner_id', 'in', $partnerIdsSql]);
            $ret = [];
            foreach ($attachments as $attachment) {
                if (!isset($ret[$attachment->getPartnerId()]))
                {
                    $ret[$attachment->getPartnerId()] = [];
                }
                 $ret[$attachment->getPartnerId()][] = $attachment;
            }
            return $ret;
        }

        public function getEntitiesAttachments($entityObjects, $entityName) {
            $entityIds = [];
            foreach ($entityObjects as $entityObject) {
                $entityIds [] = $entityObject->getId();
            }
            if (empty($entityIds))
            {
                return [];
            }
            $entityIdsSql = '(' . implode(',', $entityIds) . ')';
            $attachments = $this->selectAdvance('*', ['entity_id', 'in', $entityIdsSql, 'AND', 'entity_name', '=', "'$entityName'"]);
            $ret = [];
            foreach ($attachments as $attachment) {
                if (!isset($ret[$attachment->getEntityId()]))
                {
                    $ret[$attachment->getEntityId()] = [];
                }
                 $ret[$attachment->getEntityId()][] = $attachment;
            }
            return $ret;
        }

        public function getEntityAttachments($entityId, $entityName) {
            return $this->selectAdvance('*', ['entity_id', '=', $entityId, 'AND', 'entity_name', '=', "'$entityName'"]);
        }

        public function getPartnerAttachments($partnerId) {
            return $this->selectAdvance('*', ['partner_id', '=', $partnerId]);
        }

        public function addRow($partnerId, $entityName, $entityId, $uploadedFileName, $fileName) {
            $dto = $this->createDto();
            $dto->setPartnerId($partnerId);
            $dto->setEntityName($entityName);
            $dto->setEntityId($entityId);
            $dto->setFileName($fileName);
            $dto->setUploadedFileName($uploadedFileName);
            $dto->setCreatedAt(date('Y-m-d H:i:s'));
            return $this->insertDto($dto);
        }

    }

}
