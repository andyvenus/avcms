<?php

namespace AVCMS\Database\MySQL;

use AVCMS\Database\DatabaseQuery;
use AVCMS\Database\QueryBuilder;

class MySQLBuildQuery extends DatabaseQuery {

    public $database_table = '';

    public function setQueryBuilder(QueryBuilder $query_builder) {
        $this->query_data = $query_builder->getQueryData();
        $this->database_table = $query_builder->getTable();
    }

    public function execSelect() {
        $query_data = $this->buildQueryElements();

        $this->query = "SELECT $query_data[columns] FROM {$this->database_table} $query_data[join] $query_data[where] $query_data[limit]";

        if (isset($this->query_data['params'])) {
            $this->parameters = $this->query_data['params'];
        }

        $this->exec();
    }

    protected function buildQueryElements() {
        // COLUMNS //
        if (isset($this->query['columns'])) {
            $q['columns'] = implode(', ', $this->query['columns']);
        }
        else {
            $q['columns'] = '*';
        }

        // JOIN //
        $q['join'] = '';
        if (isset($this->query['join'])) {
            foreach ($this->query['join'] as $join_data) {
                $q['join'] .= "$join_data[0] $join_data[1] ON $join_data[2] $join_data[3] $join_data[4]";
            }
        }

        // WHERE //
        $q['where'] = '';
        if (isset($this->query['where'])) {
            $q['where'] = 'WHERE ';
            foreach ($this->query['where'] as $where_data) {

                // If we have an "AND" or "OR" to append to the query
                if (isset($where_data[4])) {
                    $q['where'] .= $where_data[4].' ';
                }

                if (!$where_data[3]) {
                    $q['where'] .= "$where_data[0] $where_data[1] $where_data[2] ";
                }
                else {
                    $q['where'] .= "$where_data[0] $where_data[1] :$where_data[0] ";
                    $this->params(array($where_data[0]=>$where_data[2]));
                }
            }
        }

        // LIMIT //
        $q['limit'] = '';
        if (isset($this->query['limit'])) {
            $q['limit'] = 'LIMIT ';
            foreach ($this->query['limit'] as $limit_data) {
                if ($limit_data[1]) {
                    $q['limit'] .= $limit_data[0].','.$limit_data[1].' ';
                }
                else {
                    $q['limit'] .= $limit_data[0].' ';
                }
            }
        }

        return $q;
    }
}