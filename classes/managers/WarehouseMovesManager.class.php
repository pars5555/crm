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

    use crm\dal\mappers\WarehouseMoveMapper;

    class WarehouseMovesManager extends AdvancedAbstractManager {

        /**
         * @var $instance
         */
        public static $instance;

        /**
         * Returns an singleton instance of this class
         * @param object $config
         * @param object $args
         * @return
         */
        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new WarehouseMovesManager(WarehouseMoveMapper::getInstance());
            }
            return self::$instance;
        }

        public function getWarehouseProducts($wId = 0) {
            if (!empty($wId)){
                $rows = $this->mapper->selectByField('warehouse_id', $wId);
            }else
            {
                $rows = $this->mapper->selectAll();
            }
            $porductsQty = [];
            foreach ($rows as $row) {
                if (!isset($porductsQty[intval($row->getProductId())])) {
                    $porductsQty[intval($row->getProductId())]  = 0;
                }
                $porductsQty[intval($row->getProductId())] += floatval($row->getQuantity());
            }
            $productDtos = ProductManager::getInstance()->selectByPKs(array_keys($porductsQty), true);
            $ret = [];
            foreach ($porductsQty as $productId => $qty) {
                $ret[$productId] = ["qty" => $qty, 'product'=>$productDtos[$productId]];
            }
            
            return $ret;
        }

    }

}
