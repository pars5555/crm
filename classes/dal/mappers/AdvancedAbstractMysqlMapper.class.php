<?php

/**
 *
 * Mysql mapper class is extended class from AbstractMysqlMapper.
 * It contatins all read and write functions which are working with its table.
 *
 * @author Levon Naghashyan <levon@naghashyan.com>
 * @site http://naghashyan.com
 * @mail levon@naghashyan.com
 * @year 2009-2015
 * @package crm.dal.mappers
 * @version 2.0.0
 *
 */

namespace crm\dal\mappers {

    use \ngs\framework\dal\mappers\AbstractMysqlMapper;

    abstract class AdvancedAbstractMysqlMapper extends AbstractMysqlMapper {

        public function selectByField($fieldName, $fieldValue) {
            $sqlQuery = sprintf("SELECT * FROM `%s` WHERE `%s` = :value ", $this->getTableName(), $fieldName);
            return $this->fetchRows($sqlQuery, array("value" => $fieldValue));
        }
        
        public function selectOneByField($fieldName, $fieldValue) {
            $sqlQuery = sprintf("SELECT * FROM `%s` WHERE `%s` = :value LIMIT 0, 1", $this->getTableName(), $fieldName);
            $rows = $this->fetchRows($sqlQuery, array("value" => $fieldValue));
            if (!empty($rows))
            {
                return $rows[0];
            }
            return false;
        }

        public function deleteByField($fieldName, $fieldValue) {
            $sqlQuery = sprintf("DELETE FROM `%s` WHERE `%s` = :value ", $this->getTableName(), $fieldName);
            $res = $this->dbms->prepare($sqlQuery);
            if ($res) {
                $res->execute(array("value" => $fieldValue));
                return $res->rowCount();
            }
            return null;
        }

        public function selectByPKs($pks) {
            $sqlQuery = sprintf("SELECT * FROM `%s` WHERE `%s` in (%s) ", $this->getTableName(), $this->getPKFieldName(), implode(',', $pks));
            return $this->fetchRows($sqlQuery);
        }

        public function selectAdvanceCount($where, $join) {
            $sqlQuery = sprintf("SELECT count(*) as `count` FROM `%s` %s  %s ", $this->getTableName(), $join, $where);
            return $this->fetchField($sqlQuery, 'count');
        }

        public function selectAdvance($fields, $where, $order, $offset, $limit, $leftJoin, $groupBy) {
            $sqlQuery = sprintf("SELECT %s FROM `%s` %s %s %s %s ", $fields, $this->getTableName(), $leftJoin, $where, $groupBy, $order);
            if (isset($limit) && $limit > 0) {
                $sqlQuery .= ' LIMIT ' . $offset . ', ' . $limit;
            }
            return $this->fetchRows($sqlQuery);
        }
        
        public function updateAdvance($where, $fieldsValuesMapArray) {
            $where = $this->getWhereSubQueryByFilters($where);
            $subQuerySetValues = "";
            foreach ($fieldsValuesMapArray as $fieldName => $fieldValue) {
                $subQuerySetValues .= "`$fieldName` = :$fieldName ,";
            }
            $subQuerySetValues = trim($subQuerySetValues);
            $subQuerySetValues = trim($subQuerySetValues, ',');
            
            $sqlQuery = sprintf("UPDATE %s SET %s %s ", $this->getTableName(), $subQuerySetValues, $where);
            $res = $this->dbms->prepare($sqlQuery);
            if ($res) {
                $res->execute($fieldsValuesMapArray);
                return $res->rowCount();
            }
            return null;
        }
        
        private function getWhereSubQueryByFilters($filters) {
            if (empty($filters)) {
                return "";
            }
            $where = "WHERE ";
            foreach ($filters as $filter) {
                $strToLowerFilter = strtolower(trim($filter));
                if (in_array($strToLowerFilter, [')', '(', 'and', 'or', '<', '<=', '<>', '=', '>', '>=', 'is', 'null', 'not'])) {
                    $where .= ' ' . strtoupper($strToLowerFilter) . ' ';
                } else {
                    $where .= ' ' . $filter . ' ';
                }
            }
            return $where;
        }

        public function deleteAdvance($where) {
            $sqlQuery = sprintf("DELETE FROM `%s` %s", $this->getTableName(), $where);
            $res = $this->dbms->prepare($sqlQuery);
            if ($res) {
                $res->execute();
                return $res->rowCount();
            }
            return null;
        }

        public function startTransaction() {
            $this->dbms->beginTransaction();
        }

        /**
         * Commits the current transaction
         */
        public function commitTransaction() {
            $this->dbms->commit();
        }

        /**
         * Rollback the current transaction
         */
        public function rollbackTransaction() {
            $this->dbms->rollback();
        }

    }

}
