<?php
/**
 * Remote trackers checker
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

header("Content-Type: image/gif");
@set_time_limit(0);
@ignore_user_abort(1);
date_default_timezone_set('UTC');
//	@ini_set('default_socket_timeout', 3);

define ("IN_ANNOUNCE",true);
define ("ROOT_PATH",dirname(__FILE__).'/');
require_once(ROOT_PATH.'include/secrets.php');
// connection closed
$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
or die ('Not connected : ' . mysql_error());
mysql_select_db ($mysql_db, $db);

require_once(ROOT_PATH."include/benc.php");

/**
 * Generates SQL error message sending notification to SYSOP
 * @param string $file File where error begins __FILE__
 * @param string $line Line where error begins __LINE__
 * @return void
 */
function sqlerr($file = '', $line = '') {
	$err = mysql_error();
	$text = ("SQL error, mysql server said: " . $err . ($file != '' && $line != '' ? " file: $file, line: $line" : ""));
	write_log("Remote_check SQL ERROR: $text",'sql_errors');
	err($text);
	return;
}

/**
 * Writes event to sitelog
 * @param stirng $text Message to be writed to log
 * @param string $type Type of log record, default 'tracker'
 * @return void
 */
function write_log($text, $type = "tracker") {
	$type = sqlesc($type);
	$text = sqlesc($text);
	$added = time();
	mysql_query("INSERT INTO sitelog (added, txt, type) VALUES($added, $text, $type)") or sqlerr(__FILE__,__LINE__);
	return;
}
$id=(int)$_GET['id'];

if ($id)  {

	$anarray = mysql_query("SELECT torrents.info_hash, trackers.tracker FROM trackers LEFT JOIN torrents ON torrents.id=trackers.torrent WHERE trackers.torrent=$id AND trackers.tracker<>'localhost'") or sqlerr(__FILE__,__LINE__);

	while (list($infohash,$url) = mysql_fetch_array($anarray)) {
		$peers = get_remote_peers($url, $infohash);
		mysql_query("UPDATE LOW_PRIORITY trackers SET seeders=".(int)$peers['seeders'].", leechers=".(int)$peers['leechers'].", lastchecked=".time().", state='".mysql_real_escape_string($peers['state'])."' WHERE torrent=$id AND tracker='$url'") or sqlerr(__FILE__,__LINE__);
	}
}

$cronrow = mysql_query("SELECT * FROM cron WHERE cron_name IN ('remotecheck_disabled','remotepeers_cleantime','in_remotecheck','remote_trackers')") or sqlerr(__FILE__,__LINE__);
while ($cronres = mysql_fetch_array($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];

if (!$CRON['remotecheck_disabled']) {
	$CRON['remote_lastchecked'] = (int)$CRON['remote_lastchecked'];
	$CRON['remote_trackers'] = (int)$CRON['remote_trackers'];

	mysql_query("UPDATE cron SET cron_value=1 WHERE cron_name='in_remotecheck'") or sqlerr(__FILE__,__LINE__);
	mysql_query("UPDATE cron SET cron_value=".time()." WHERE cron_name='last_remotecheck'") or sqlerr(__FILE__,__LINE__);
	mysql_query("UPDATE cron SET cron_value=cron_value+1 WHERE cron_name='num_checked'") or sqlerr(__FILE__,__LINE__);
	// delete stuck trackers
	mysql_query("UPDATE trackers SET state='' WHERE lastchecked<".(time()-($CRON['remotepeers_cleantime']*2))." AND state = 'in_check'") or sqlerr(__FILE__,__LINE__);
	
	$res = mysql_query("SELECT trackers.id, trackers.tracker, torrents.info_hash FROM trackers LEFT JOIN torrents ON torrents.id=trackers.torrent WHERE ".($CRON['remotepeers_cleantime']?"trackers.lastchecked<".(time()-$CRON['remotepeers_cleantime'])." AND ":'')."trackers.tracker<>'localhost' AND trackers.state<>'in_check' ORDER BY trackers.lastchecked, trackers.id DESC".($CRON['remote_trackers']?" LIMIT {$CRON['remote_trackers']}":'')) or sqlerr(__FILE__,__LINE__);

	//try {
	while ($row = mysql_fetch_assoc($res)) {

		$parray[$row['id']] = array('info_hash'=>$row['info_hash'],'tracker'=>$row['tracker']); }

		if ($parray) {
			mysql_query("UPDATE trackers SET state = 'in_check' WHERE id IN (".implode(',',array_keys($parray)).")") or sqlerr(__FILE__,__LINE__);
			foreach ($parray as $id => $torrent) {
				$hash = $torrent['info_hash'];
				$url = $torrent['tracker'];
				$peers = get_remote_peers($url, $hash);
				if (preg_match('/failed/',$peers['state'])) $fail = true; else $fail = false;
				mysql_query("UPDATE LOW_PRIORITY trackers SET seeders=".(int)$peers['seeders'].", leechers=".(int)$peers['leechers'].", lastchecked=".time().", state='".mysql_real_escape_string($peers['state'])."', num_failed=".($fail?'num_failed+1':'0')." WHERE id=$id") or sqlerr(__FILE__,__LINE__);
			}
			//} catch (Exception $e) mysql_query("UPDATE cron SET cron_value=0 WHERE cron_name='in_remotecheck'");
		}
		mysql_query("UPDATE cron SET cron_value=0 WHERE cron_name='in_remotecheck'") or sqlerr(__FILE__,__LINE__);
}
print base64_decode("R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==");

?>