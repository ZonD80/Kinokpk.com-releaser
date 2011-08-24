<?php
class REL_DB {
	public $query, $conntection;
	
	
	/**
	 * Sets mode to non-gui debug. Query times and errors will be printed directly to page.
	 */
	function debug() {
		$this->debug = true;
		$this->ttime = 0;
	}
	function __construct($db) {
		$this->connection = @mysql_connect($db['host'], $db['user'], $db['pass']);
		if (!$this->connection)
		die("Error " . mysql_errno() . " aka " . mysql_error().". Failed to estabilish connection to SQL server");
		mysql_select_db($db['db'])
		or die("Cannot select database {$db['db']}: " + mysql_error());

		$this->my_set_charset($db['charset']);
		$this->query = array();
		register_shutdown_function("mysql_close");
		//$this->query[0] = array("seconds" => 0, "query" => 'TOTAL');
	}

	/**
	 * Sets charset to database connection.
	 * @param string $charset Charset to be set
	 * @return void
	 */
	function my_set_charset($charset) {
		if (!function_exists("mysql_set_charset") || !mysql_set_charset($charset)) mysql_query("SET NAMES $charset");
		return;
	}

	/**
	 * Preforms a sql query and writes query and time to statistics
	 * @param string $query Query to be performed
	 * @return resource Mysql resource
	 */
	function query($query) {

		$query_start_time = microtime(true); // Start time
		$result = mysql_query($query);
		$query_end_time = microtime(true); // End time
		$query_time = ($query_end_time - $query_start_time);
		if ($this->debug) {
		$this->ttime = $this->ttime + $query_time;
		print (mysql_errno()?"ERROR: ".mysql_errno()." - ".mysql_error().'<br/>':'')."$query<br/>took $query_time, total {$this->ttime}<hr/>";
			
		}
		$this->query[] = array("seconds" => $query_time, "query" => $query);
		return $result;
	}

	/**
	 * Preforms a sql query, returning a results
	 * @param string $query query to be executed
	 * @param string $type Type of returned data, assoc (default) - associative array, array - array, object - object
	 * @return mixed
	 */
	function query_return($query,$type='assoc') {
		$res = $this->query($query);
		if ($res) {
		if ($type=='assoc')
		while ($row = mysql_fetch_assoc($res)) {
			$return[] = $row;
		} 
		elseif ($type=='array')
		while ($row = mysql_fetch_array($res)) {
			$return[] = $row;
		} 
		elseif ($type=='object')
		while ($row = mysql_fetch_assoc($res)) {
			$return[] = $row;
		}
		return $return;
		} else return false;
	}
	
	/**
	 * Preforms an sql query, returns first row
	 * @param string $query query to be executed
	 * @param string $type Type of returned data, assoc (default) - associative array, array - array, object - object
	 * @return mixed
	 */
	function query_row($query,$type='assoc') {
		$result = $this->query_return($query);
		if (!$result) return false;
		return array_shift($result);
	}

}