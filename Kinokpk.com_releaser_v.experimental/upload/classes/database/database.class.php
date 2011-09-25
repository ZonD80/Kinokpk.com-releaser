<?php
class REL_DB {
	public $query, $conntection;


	/**
	 * Sets mode to non-gui debug. Query times and errors will be printed directly to page.
	 */
	function debug() {
		$this->debug = true;
	}
	function __construct($db) {
		$this->ttime = 0;
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
		$this->ttime = $this->ttime + $query_time;
		if ($this->debug) {
			print "$query<br/>took $query_time, total {$this->ttime}<hr/>";

		}
		if (mysql_errno()&&mysql_errno()!=1062) {

			$to_log = "ERROR: ".mysql_errno()." - ".mysql_error()."<br/>$query<br/>took $query_time, total {$this->ttime}<br/>Backtrace:<hr/>";
			$to_log .= "<pre>";
			$to_log .= var_export(debug_backtrace(),true);
			$to_log .= "</pre><hr/>";
			write_log($to_log,'sql_errors');
			print $to_log;
			if (!$this->debug()) die();
		}
		$this->query[] = array("seconds" => $query_time, "query" => $query);
		return $result;
	}
	/**
	 * Escapes value to make safe $REL_DB->query
	 * @param string $value Value to be escaped
	 * @return string Escaped value
	 * @see $REL_DB->query()
	 */
	function sqlesc($value) {
		// Quote if not a number or a numeric string
		if (!is_numeric($value)) {
			$value = "'" . mysql_real_escape_string((string)$value) . "'";
		}
		return $value;
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


	/**
	 * Generates SQL error message sending notification to SYSOP
	 * @param string $file File where error begins __FILE__
	 * @param string $line Line where error begins __LINE__
	 * @return void
	 */
	function sqlerr($file = '', $line = '') {
		global  $queries, $CURUSER, $REL_SEO, $REL_LANG, $REL_DB;
		$err = mysql_error();
		$text = ("<table border=\"0\" bgcolor=\"blue\" align=\"left\" cellspacing=\"0\" cellpadding=\"10\" style=\"background: blue\">" .
	"<tr><td class=\"embedded\"><font color=\"white\"><h1>{$REL_LANG->_('SQL error')}</h1>\n" .
	"<b>{$REL_LANG->_('SQL server response')}: " . $err . ($file != '' && $line != '' ? "<p>{$REL_LANG->_('in')} $file, {$REL_LANG->_('line')} $line</p>" : "") . "<p>{$REL_LANG->_('Query number')}: $queries.</p></b></font></td></tr></table>");
		write_log(make_user_link()." SQL ERROR: $text</font>",'sql_errors');
		print $text;
		return;
	}

}