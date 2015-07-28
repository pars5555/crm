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

    use crm\dal\mappers\ProductMapper;

    class ProductManager extends AdvancedAbstractManager {

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
                self::$instance = new ProductManager(ProductMapper::getInstance());
            }
            return self::$instance;
        }

        public function createProduct($name, $model, $manufacturerId, $uomId) {
            $dto = $this->createDto();
            $dto ->setName($name);
            $dto ->setModel($model);
            $dto ->setManufacturerId($manufacturerId);
            $dto ->setUomId($uomId);
            return $this->insertDto($dto);
        }

        public function getProductListFull($where = [], $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            $rows = $this->selectAdvance('*', $where, $orderByFieldsArray, $orderByAscDesc, $offset, $limit);
            $manufacturerIds = array();
            $uomIds = array();
            foreach ($rows as $row) {
                $manufacturerIds[] = $row->getManufacturerId();
                $uomIds[] = $row->getUomId();
            }
            $manufacturerIds = array_unique($manufacturerIds);
            $uomIds = array_unique($uomIds);
            $manufacturerDtos = ManufacturerManager::getInstance()->selectByPKs($manufacturerIds, true);
            $uomDtos = UomManager::getInstance()->selectByPKs($uomIds, true);
            foreach ($rows as $row) {
                $row->setUomDto($uomDtos[$row->getUomId()]);
                $row->setManufacturerDto($manufacturerDtos[$row->getManufacturerId()]);
            }
            return $rows;
        }

    }

}
