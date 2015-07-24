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

    use \ngs\framework\AbstractManager;

    abstract class AdvancedAbstractManager extends AbstractManager {

        protected $mapper;
        private $lastSelectAdvanceParams = null;

        function __construct($mapper) {
            $this->mapper = $mapper;
        }

        public function selectByField($fieldName, $fieldValue) {
            return $this->mapper->selectByField($fieldName, $fieldValue);
        }

        public function deleteByField($fieldName, $fieldValue) {
            return $this->mapper->deleteByField($fieldName, $fieldValue);
        }

        public function selectByPK($pk) {
            return $this->mapper->selectByPK($pk);
        }

        public function selectByPKs($pks, $mapByIds = False) {
            if (empty($pks)) {
                return [];
            }
            $ret = $this->mapper->selectByPKs($pks);
            if ($mapByIds) {
                $mappedRet = array();
                foreach ($ret as $r) {
                    $mappedRet[$r->getId()] = $r;
                }
                return $mappedRet;
            }
            return $ret;
        }

        public function createDto() {
            return $this->mapper->createDto();
        }

        public function insertDto($dto) {
            return $this->mapper->insertDto($dto);
        }

        public function updateByPk($dto, $esc = true) {
            return $this->mapper->updateByPk($dto, $esc);
        }

        public function updateField($id, $fieldName, $fieldValue) {
            return $this->mapper->updateField($id, $fieldName, $fieldValue);
        }

        public function deleteByPK($id) {
            return $this->mapper->deleteByPK($id);
        }

        public function selectAll() {
            return $this->mapper->selectAll();
        }

        public function selectAdvance($fieldsArray = '*', $filters = null, $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null) {
            $where = $this->getWhereSubQueryByFilters($filters);
            $fields = $fieldsArray;
            if (is_array($fieldsArray)) {
                $fields = '`' . implode('`, `', $fieldsArray) . '`';
            }
            $order = $orderByFieldsArray;
            if (is_array($orderByFieldsArray)) {
                $order = 'ORDER BY `' . implode('`, `', $orderByFieldsArray) . '` ' . $orderByAscDesc;
            }
            $this->lastSelectAdvanceWhere = $where;
            return $this->mapper->selectAdvance($fields, $where, $order, $offset, $limit);
        }

        public function getLastSelectAdvanceRowsCount() {
            if (!isset($this->lastSelectAdvanceWhere)) {
                return 0;
            }
            return intval($this->mapper->selectAdvanceCount($this->lastSelectAdvanceWhere));
        }

        private function getWhereSubQueryByFilters($filters) {
            if (empty($filters)) {
                return "";
            }
            $where = "WHERE ";
            foreach ($filters as $filter) {
                $strToLoqerFilter = strtolower($filter);
                if (in_array($strToLoqerFilter, [')', '(', 'and', 'or', '<', '<=', '=', '>', '>=', 'is', 'null', 'not'])) {
                    $where .= ' ' . strtoupper($strToLoqerFilter) . ' ';
                } else {
                    $where .= ' ' . $filter . ' ';
                }
            }
            return $where;
        }

    }

}
