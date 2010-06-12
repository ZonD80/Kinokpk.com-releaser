<?php
// Database info

include("include/bittorrent.php");

if (!$_GET['test']) die('fail');
$dbhost = $mysql_host;
$dbuser = $mysql_user;
$dbpass = $mysql_pass;
$dbname = $mysql_db;

//---------------

header('Content-type: text/plain');

$dbconn = mysql_connect($dbhost, $dbuser, $dbpass) or die( mysql_error() );
$db = mysql_select_db($dbname) or die( mysql_error() );

mysql_query("SET NAMES cp1251");

$sql = "ALTER DATABASE `".$dbname."` DEFAULT CHARACTER SET cp1251 COLLATE cp1251_general_ci";
$result = mysql_query($sql) or die( mysql_error() );
print "Database changed to cp1251.\n";
flush();
$sql = 'SHOW TABLES';
$result = mysql_query($sql) or die( mysql_error() );

while ( $row = mysql_fetch_row($result) )
{
$table = mysql_real_escape_string($row[0]);
$sql = "ALTER TABLE $table DEFAULT CHARACTER SET cp1251 COLLATE cp1251_general_ci, CONVERT TO CHARACTER SET cp1251 COLLATE cp1251_general_ci";
mysql_query($sql) or die( mysql_error() );
print "$table changed to cp1251.\n";
flush();
}

mysql_close($dbconn);
?>
