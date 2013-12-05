<?php

namespace AVCMS\Database;

class QueryBuilder
{
	protected $query;

	public function __construct($DB = null, $table = null)
    {
        if ($DB) {
            $this->DB = $DB;
            $this->table = $table;
        }
	}

	public function columns($columns)
    {
		if (is_array($columns))
			$this->query['columns'] = $columns;
		else {
			$c = str_replace(', ', ',', $columns);
			$c = explode(',', $c);
			$this->query['columns'] = $c;
		}
		
		return $this;
	}

    /**
     * @param string $parameter1
     * @param string $operator
     * @param string $parameter2
     * @param bool $secure_parameter2
     * @param null $and_or
     * @return $this
     */
    public function where($parameter1, $operator, $parameter2, $secure_parameter2 = true, $and_or = null)
    {
		if (isset($this->query['where']) && $and_or == null)
			$and_or = 'AND';
			
		$this->query['where'][] = array($parameter1, $operator, $parameter2, $secure_parameter2, $and_or);
		
		return $this;
	}
	
	public function or_where($parameter1, $operator, $parameter2)
    {
		$this->where($parameter1, $operator, $parameter2, true, 'OR');
		
		return $this;
	}
	
	public function id($id)
    {
		$this->where('id', '=', $id);
		
		return $this;
	}

    public function slug($slug)
    {
        $this->where('seo_url', '=', $slug);

        return $this;
    }

    public function whereIn($column, $values)
    {

    }
	
	public function limit($limit, $limit2 = null)
    {
		$this->query['limit'][] = array($limit, $limit2);
		
		return $this;
	}
	
	public function params($params)
    {
		if (!isset($this->query['params'])) {
			$this->query['params'] = $params;
		}
		else {
			$this->query['params'] = $this->query['params'] + $params;
		}
		
		return $this;
	}
	
	public function left_join($table, $parameter1, $operator, $parameter2)
    {
		$this->query['join'][] = array('LEFT JOIN', $table, $parameter1, $operator, $parameter2);
		
		return $this;
	}

    public function getQueryData() {
        return $this->query;
    }

    public function fetch()
    {
        if (!isset($this->active_query)) {
            $this->active_query = $this->DB->buildQuery();
            $this->active_query->setQueryBuilder($this);
            $this->active_query->execSelect();
        }

        return $this->active_query->fetch();
    }

    public function getTable() {
        return $this->table;
    }
}