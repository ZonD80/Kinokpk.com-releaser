<?php
require_once("include/bittorrent.php");

function bark($msg) {
	genbark($msg, $tracker_lang['error']);
}

dbconn();
$id = $_POST['id'];

$check = mysql_query("SELECT id FROM snached WHERE userid = ".$CURUSER['id']." AND torrent = ".$id." AND finished = 'yes' ");
if(@mysql_result($check,0))
mysql_query("INSERT INTO snatched (userid,torrent,last_action,startdat,completedat,connectable,finished) VALUES (".$CURUSER['id'].",".$id.",'".get_date_time()."','".get_date_time()."','".get_date_time()."','no','yes')");

else bark("¬ы уже скачали/посмотрели этот фильм");

header("Refresh: 1; url=details.php?id=$id");

?>