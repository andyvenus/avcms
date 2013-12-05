<?php

namespace AVCMS\Database;

/**
 * Class DatabaseQuery
 * @package AVCMS\Core\Databaseold
 */
class DatabaseQuery {
    // Dependencies
    /**
     * @var \PDO PDO database connection
     */
    protected $PDO;
    
    /**
     * @var string Query String
     */
    protected $query;

    /**
     * @var null|array Parameters Array
     */
    protected $parameters;

    /**
     * @var int
     * @deprecated
     */
    protected $page = 1;

    /**
     * @var
     */
    protected $full_query;

    /**
     * @var
     */
    protected $total_results;

    // constructor
    /**
     * @param $PDO
     */
    public function __construct($PDO) {
	    $this->PDO = $PDO;
    }

    /**
     * @param $query
     */
    public function query($query) {
	    $this->query = $query;
	    $this->run_query();
    }

    /**
     * @param $query
     */
    public function prepare($query) {
	    $this->query = $query;
    }

    /**
     * @param string $data
     */
    public function exec($data = '') {
	    if ($data != '')
	    	$this->parameters = $data;
	    	
	    $this->run_query();
    }

    /**
     *
     */
    protected function run_query() {
    	$this->full_query = $this->prepareQuery();
	    if (isset($this->parameters)) {
	    	try {
    			$this->query_result = $this->PDO->prepare($this->full_query);
    			$this->query_result->execute($this->parameters);
    		}
    		catch(PDOException $err) {
	    		echo $err->getMessage(); 
	    	}
    	}
    	else {
    		try {
	    		$this->query_result = $this->PDO->query($this->full_query);
	    	}
    		catch(PDOException $err) {
	    		$bt = debug_backtrace();
	    		
	    		echo $err->getMessage();
	    		
				echo '<br/> File: '.$bt[2]['file'].' &nbsp;Line: '.$bt[2]['line'];
	    		
	    		exit();
	    	}
    	}
    	$this->query_result->setFetchMode(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $fetch_style
     * @param $cursor_orientation
     * @param int $cursor_offset
     * @return mixed
     */
    public function fetch($fetch_style = \PDO::FETCH_ASSOC, $cursor_orientation = \PDO::FETCH_ORI_NEXT, $cursor_offset = 0) {
    	
    	try {
	    	$result = $this->query_result->fetch($fetch_style, $cursor_orientation, $cursor_offset);
	    }
	    catch(\PDOException $err) {
	    	echo $err->getMessage(); 
	    }

	    return $result;
    }

    /**
     * @param int $column_number
     * @return mixed
     */
    public function fetchColumn($column_number = 0) {
	   	try {
	    	$result = $this->query_result->fetchColumn($column_number);
	    }
	    catch(\PDOException $err) {
	    	echo $err->getMessage(); 
	    }

	    return $result;
    }

    /**
     * @return mixed
     */
    public function totalResults() {
    	if (isset($this->total_results)) {
	    	return $this->total_results;
    	}

    	$query = $this->prepareQuery();
    	if (isset($this->parameters)) {
	    	$total_query = $this->PDO->prepare($query);
	    	$total_query->execute($this->parameters);
	    }
	    else {
		    $total_query = $this->PDO->query($query);
	    }
	    $this->total_results  = $total_query->rowCount();
	    return $this->total_results;
    }

    /**
     * @param bool $limit
     * @return string
     * @throws Exception
     */
    protected function prepareQuery($limit = TRUE) {
    
    	// Manual query has been set, use that
    	if (isset($this->query))
	    	$query = $this->query;
	    // No query set, use data to build query
	    elseif (isset($this->parameters)) {
	    	$where = ' WHERE ';
	    	foreach ($this->parameters as $field => $value) {
		    	$where .= "$field = :$field";
	    	}
	    	$query = "SELECT * FROM $this->table $where";
	    }
	    // No data or query, error
	    else
	    	throw new \Exception('No valid QUERY or DATA');

	    return $query;
    }

    /**
     *
     */
    public function closeCursor() {
	    $this->query_result->closeCursor();
    }

    /**
     * @param $function
     * @return mixed
     */
    public function PDOFunction($function) {
	    $result = $this->query_result->$function();
	    return $result;
    }

}