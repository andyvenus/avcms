<?php
namespace AVCMS\Database;
use AVCMS\Database\MySQL\MySQLBuildQuery;

/**
 * Class Databaseold
 * @package AVCMS\Core\Databaseold
 */
class Database
{
    /**
     * PDO database connection
     * @var \PDO
     */
    public $PDO;

    /**
     * An array of all queries that have been built/run
     * @var array
     */
    private $queries_log;

    /**
     * The database prefix
     * @var string
     */
    public $prefix;

    /**
     * @param \PDO $PDO
     * @param string $table_prefix
     */
    public function __construct(\PDO $PDO, $table_prefix = '')
    {
        $this->PDO = $PDO;
        $this->prefix = $table_prefix;
    }

    /**
     * @param string $query Query string
     * @param null|array $parameters Query parameters
     * @return DatabaseQuery
     */
    public function query($query, $parameters = null)
    {
    	$queryob = new DatabaseQuery($this->PDO);

    	// No data = standard query
        if (!$parameters) {
            $queryob->query($query);
        }
        // Data found, parametered query
	    else {
	    	// If $data is not an array, make it a single value array
	    	if (!is_array($parameters)) {
		    	$value = $parameters;
				$parameters = array($value);
			}
		    $queryob->prepare($query);
		    $queryob->exec($parameters);
	    }
	    
	    $this->queries_log[] = $query;
	    return $queryob;
    }

    public function buildQuery($table = null) {
        $queryob = new MySQLBuildQuery($this->PDO);

        return $queryob;
    }

    /**
     * @param $table
     * @return QueryBuilder
     */
    public function query_builder($table)
    {
	    return new QueryBuilder($this, $table);
    }

    /**
     * @param $query
     * @return DatabaseQuery
     */
    public function prepare($query)
    {
	    $queryob = new DatabaseQuery($this->PDO);
	    $queryob->prepare($query);
	    
	    $this->queries_log[] = $query;
	    return $queryob;
    }
    
    /**
     * Simple query, just the data, no MySQL syntax
     *
     * @param $type Type, for example "insert" or "update"
     * @param $table
     * @param $parameters
     * @param string $extra
     * @return DatabaseQuery
     */
    public function simple_query($type, $table, $parameters, $extra = '')
    {
    	if ($type == 'insert' || $type == 'INSERT') {
    		// Make list from the data keys (column names)
    		$columns = implode("`, `", array_keys($parameters));
    		// Add first & final apostrophes to list
    		$columns = '`'.$columns.'`';
			
			// Make an list of the placeholders
    		$placeholders = implode(", :", array_keys($parameters));
	    	$generated_query = $this->prepare("INSERT INTO $table ($columns) VALUES (:$placeholders)");
	    }
	    elseif ($type == 'update' || $type == 'UPDATE') {
	    	$query_main = '';
	    	foreach (array_keys($parameters) as $column) {
	    		if (isset($first_done)) 
	    			$query_main .= ', ';
	    		else
	    			$first_done = TRUE;
		    	
		    	$query_main .= '`'.$column.'` = :'.$column;
	    	}
	    	if ($extra != '') {
		    	$query_main .= " $extra";
	    	}
	    	$query = "UPDATE $table SET $query_main";
	    	$generated_query = $this->prepare($query);
	    	
	    }

	    $generated_query->exec($parameters);
	    return $generated_query;
    }
    
    // Perform a simple row count
    /**
     * @param $table
     * @param null $data
     * @param string $extra
     * @return mixed
     */
    public function simple_count($table, $data = null, $extra = '')
    {
	    $query_main = '';
	    // If we have data, get that ready for the query
	    if ($data) {
	    	$query_main .= 'WHERE ';
			foreach (array_keys($data) as $column) {
	    		if (isset($first_done)) 
	    			$query_main .= ' AND ';
				else
	    			$first_done = TRUE;
		    	
				$query_main .= $column.' = :'.$column;
			}
	    }
	    if ($extra != '') {
		   	$query_main .= " $extra";
	    }
	    $query = "SELECT COUNT(*) FROM $table $query_main";
	    $generated_query = $this->prepare($query);
	    $generated_query->exec($data);
	    return $generated_query->fetchColumn();
    }
    
    // Run a PDO function
    /**
     * @param $function
     * @return mixed
     */
    public function PDOFunction($function)
    {
	    $result = $this->PDO->$function();
	    return $result;
    }
    
    // Get the last insert ID
    /**
     * @return mixed
     */
    public function lastInsertId()
    {
	    return $this->PDO->lastInsertId();
    }
    
    // Get the total number of queries run
    /**
     * @return int
     */
    public function total_queries_count()
    {
	    return count($this->queries_log);
    }
    
    // Get a list of all queries run
    /**
     * @return array
     */
    public function query_log()
    {
	    return $this->queries_log;
    }

    /**
     * @param $prefix
     */
    public function set_prefix($prefix)
    {
	    $this->prefix = $prefix;
    }
    
}

?>