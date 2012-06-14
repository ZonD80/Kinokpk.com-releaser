<?php
class REL_DB
{
    public $query, $conntection;
    private $debug;


    /**
     * Sets mode to non-gui debug. Query times and errors will be printed directly to page.
     */
    function debug()
    {
        $this->debug = true;
    }

    function __construct($db)
    {
        $this->ttime = 0;
        $this->connection = @mysql_connect($db['host'], $db['user'], $db['pass']);
        if (!$this->connection)
            die("Error " . mysql_errno() . " aka " . mysql_error() . ". Failed to estabilish connection to SQL server");
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
    function my_set_charset($charset)
    {
        if (!function_exists("mysql_set_charset") || !mysql_set_charset($charset)) mysql_query("SET NAMES $charset");
        return;
    }

    /**
     * Escapes value making search query.
     * <code>
     * sqlwildcardesc ('The 120% alcohol');
     * </code>
     * @param string $x Value to be escaped
     * @return string Escaped value
     */
    function sqlwildcardesc($x)
    {
        return str_replace(array("%", "_"), array("\\%", "\\_"), mysql_real_escape_string($x));
    }

    /**
     * Preforms a sql query and writes query and time to statistics
     * @param string $query Query to be performed
     * @return resource Mysql resource
     */
    function query($query)
    {

        $query_start_time = microtime(true); // Start time
        $result = mysql_query($query);
        $query_end_time = microtime(true); // End time
        $query_time = ($query_end_time - $query_start_time);
        $this->ttime = $this->ttime + $query_time;
        if ($this->debug) {
            print "$query<br/>took $query_time, total {$this->ttime}<hr/>";

        }
        if (mysql_errno() && mysql_errno() != 1062) {

            $to_log = "ERROR: " . mysql_errno() . " - " . mysql_error() . "<br/>$query<br/>took $query_time, total {$this->ttime}<br/>Backtrace:<hr/>";
            $to_log .= "<pre>";
            $to_log .= var_export(debug_backtrace(), true);
            $to_log .= "</pre><hr/>";
            //write_log($to_log,'sql_errors');
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
    function sqlesc($value)
    {
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
    function query_return($query, $type = 'assoc')
    {
        $res = $this->query($query);
        if ($res) {
            if ($type == 'assoc')
                while ($row = mysql_fetch_assoc($res)) {
                    $return[] = $row;
                }
            elseif ($type == 'array')
                while ($row = mysql_fetch_array($res)) {
                    $return[] = $row;
                }
            elseif ($type == 'object')
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
    function query_row($query, $type = 'assoc')
    {
        $result = $this->query_return($query);
        if (!$result) return false;
        return array_shift($result);
    }

}