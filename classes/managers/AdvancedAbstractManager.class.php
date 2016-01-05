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
        private $lastSelectAdvanceWhere = null;

        function __construct($mapper) {
            $this->mapper = $mapper;
        }

        public function startTransaction() {
            $this->mapper->startTransaction();
        }

        /**
         * Commits the current transaction
         */
        public function commitTransaction() {
            $this->mapper->commitTransaction();
        }

        /**
         * Rollback the current transaction
         */
        public function rollbackTransaction() {
            $this->mapper->rollbackTransaction();
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
            $dtos = $this->mapper->selectByPKs($pks);
            if ($mapByIds) {
                return $this->mapDtosById($dtos);
            }
            return $dtos;
        }

        public static function mapDtosById($dtos) {
            $mappedDtos = array();
            foreach ($dtos as $dto) {
                $mappedDtos[$dto->getId()] = $dto;
            }
            return $mappedDtos;
        }

        public static function getDtosIdsArray($dtos) {
            $idsArray = array();
            foreach ($dtos as $dto) {
                $idsArray[] = $dto->getId();
            }
            return $idsArray;
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

        public function deleteAdvance($filters = null) {
            $where = $this->getWhereSubQueryByFilters($filters);
            return $this->mapper->deleteAdvance($where);
        }

        public function countAdvance($filters = null) {
            $where = $this->getWhereSubQueryByFilters($filters);
            return $this->mapper->countAdvance($where);
        }
        public function selectAdvance($fieldsArray = '*', $filters = null, $orderByFieldsArray = null, $orderByAscDesc = "ASC", $offset = null, $limit = null, $mapByIds = False) {

            $where = $this->getWhereSubQueryByFilters($filters);
            $fields = $fieldsArray;
            if (is_array($fieldsArray)) {
                $fields = '`' . implode('`, `', $fieldsArray) . '`';
            }
            $order = "";
            if (!in_array(strtoupper($orderByAscDesc), ['ASC', 'DESC'])) {
                $orderByAscDesc = 'ASC';
            }

            if (!empty($orderByFieldsArray)) {
                $order = $orderByFieldsArray;
                if (is_array($orderByFieldsArray)) {
                    $order = implode('`, `', $orderByFieldsArray);
                }
                $order = 'ORDER BY `' . $order . '` ' . $orderByAscDesc;
            }
            $this->lastSelectAdvanceWhere = $where;
            $ret = $this->mapper->selectAdvance($fields, $where, $order, $offset, $limit);
            if ($mapByIds) {
                return $this->mapDtosById($ret);
            }
            return $ret;
        }

        public function getLastSelectAdvanceRowsCount() {
            if (!isset($this->lastSelectAdvanceWhere)) {
                return 0;
            }
            return intval($this->mapper->selectAdvanceCount($this->lastSelectAdvanceWhere));
        }

        protected function getWhereSubQueryByFilters($filters) {
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
