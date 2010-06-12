<?php
/**
 * Annouce of tracker
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

define ('IN_ANNOUNCE', true);
define("ROOT_PATH",dirname(__FILE__).'/');
@error_reporting(E_ALL & ~E_NOTICE);
@ini_set('error_reporting', E_ALL & ~E_NOTICE);
@ini_set('display_errors', '1');
@ini_set('display_startup_errors', '0');
@ini_set('ignore_repeated_errors', '1');
@ignore_user_abort(1);
@set_time_limit(0);
date_default_timezone_set('UTC');
require_once(ROOT_PATH . 'include/classes.php');
require_once(ROOT_PATH . 'include/benc.php');
require_once(ROOT_PATH . 'include/functions_announce.php');
gzip();
$time=time();
foreach (array('passkey','info_hash','peer_id','event','ip','localip') as $x) {
	if(isset($_GET[$x]))
	$GLOBALS[$x] = (string)$_GET[$x];
}

foreach (array('port','downloaded','uploaded','left') as $x)
$GLOBALS[$x] = (float)$_GET[$x];

foreach (array('passkey','info_hash','peer_id','port','downloaded','uploaded','left') as $x)
if (!isset($x)) err('Missing key: '.$x);
foreach (array('info_hash','peer_id') as $x)
if (strlen($GLOBALS[$x]) != 20)
err('Invalid '.$x.' (' . strlen($GLOBALS[$x]) . ' - ' . urlencode($GLOBALS[$x]) . ')');
if (strlen($passkey) != 32)
err('Invalid passkey (' . strlen($passkey) . " - $passkey)");
$ip = getip();
$rsize = 50;

foreach(array('num want', 'numwant', 'num_want') as $k) {
	if (isset($_GET[$k]))
	{
		$rsize = (int) $_GET[$k];
		break;
	}
}

$agent = $_SERVER['HTTP_USER_AGENT'];

if (!$port || $port > 0xffff)
err("Invalid port");
if (!isset($event))
$event = '';
$seeder = ($left == 0) ? 1: 0;

checkclient($peer_id);

dbconn();

$ucls = mysql_query("SELECT id,class FROM users WHERE passkey = " . sqlesc($passkey)) or sqlerr(__FILE__,__LINE__);
if (mysql_affected_rows() == 0)
err('Invalid passkey! Re-download the .torrent from '.$CACHEARRAY['defaultbaseurl']);
list($userid,$userclass) = mysql_fetch_array($ucls);
$hash = bin2hex($info_hash);
$res = mysql_query('SELECT torrents.id, visible, banned, free, freefor, (trackers.seeders + trackers.leechers) AS numpeers, added AS ts FROM torrents LEFT JOIN trackers ON torrents.id=trackers.torrent WHERE info_hash = "'.$hash.'" AND tracker="localhost"') or sqlerr(__FILE__,__LINE__);
$torrent = mysql_fetch_array($res);
if (!$torrent)
err('Torrent not registered with this tracker.');
$torrentid = $torrent['id'];
$fields = 'seeder, peer_id, ip, port, uploaded, downloaded, userid, last_action, '.$time.' AS nowts, prev_action AS prevts';
$numpeers = $torrent['numpeers'];
$limit = '';
$cronrow = mysql_query("SELECT cron_value FROM cron WHERE cron_name = 'announce_interval'") or sqlerr(__FILE__,__LINE__);

$announce_interval = mysql_result($cronrow,0)*60;
if ($numpeers > $rsize)
$limit = 'ORDER BY RAND() LIMIT '.$rsize;
$res = mysql_query('SELECT '.$fields.' FROM peers WHERE torrent = '.$torrentid.' '.$limit)  or sqlerr(__FILE__,__LINE__);
$resp = 'd' . benc_str('interval') . 'i' . $announce_interval . 'e' . benc_str('peers') . (($compact = (((int)$_GET['compact'] == 1) || $CACHEARRAY['announce_packed'])) ? '' : 'l');
$no_peer_id = ((int)$_GET['no_peer_id'] == 1);
unset($self);
while ($row = mysql_fetch_array($res)) {
	if ($row['peer_id'] == $peer_id) {
		$userid = $row['userid'];
		$self = $row;
		continue;
	}
	if($compact) {
		$peer_ip = explode('.', $row["ip"]);
		$plist .= pack("C*", $peer_ip[0], $peer_ip[1], $peer_ip[2], $peer_ip[3]). pack("n*", (int) $row["port"]);
	} else {
		$resp .= 'd' .
		benc_str('ip') . benc_str($row['ip']) .
		(!$no_peer_id ? benc_str("peer id") . benc_str($row["peer_id"]) : '') .
		benc_str('port') . 'i' . $row['port'] . 'e' . 'e';
	}
}
$resp .= ($compact ? benc_str($plist) : '') . (substr($peer_id, 0, 4) == '-BC0' ? "e7:privatei1ee" : "ee");
$selfwhere = 'torrent = '.$torrentid.' AND peer_id = '.sqlesc($peer_id);
if (!isset($self)) {
	$res = mysql_query('SELECT '.$fields.' FROM peers WHERE '.$selfwhere) or sqlerr(__FILE__,__LINE__);
	$row = mysql_fetch_array($res);
	if ($row) {
		$userid = $row['userid'];
		$self = $row;
	}
}

$announce_wait = 10;
if (isset($self) && ($self['prevts'] > ($self['nowts'] - $announce_wait )) )
err('There is a minimum announce time of ' . $announce_wait . ' seconds');
if (!isset($self)) {
	$rz = mysql_query('SELECT id, uploaded, downloaded, enabled, passkey_ip FROM users WHERE passkey = '.sqlesc($passkey)) or sqlerr(__FILE__,__LINE__);
	if (mysql_num_rows($rz) == 0)
	err('Unknown passkey. Please redownload the torrent from '.$CACHEARRAY['defaultbaseurl']);
	$az = mysql_fetch_array($rz);
	if (!$az['enabled'])
	err('This account is disabled.');
	$userid = $az['id'];
	if ($userclass != UC_VIP && !$seeder) {
		if ($CACHEARRAY['use_wait']) {
			$gigs = $az['uploaded'] / (1024*1024*1024);
			$elapsed = floor(($time - $torrent['ts']) / 3600);
			$ratio = (($az['downloaded'] > 0) ? ($az['uploaded'] / $az['downloaded']) : 1);
			if ($ratio < 0.5 || $gigs < 5)
			$wait = 48;
			elseif ($ratio < 0.65 || $gigs < 6.5)
			$wait = 24;
			elseif ($ratio < 0.8 || $gigs < 8)
			$wait = 12;
			elseif ($ratio < 0.95 || $gigs < 9.5)
			$wait = 6;
			else
			$wait = 0;
			if ($elapsed < $wait)
			err('Not authorized, wait please (' . ($wait - $elapsed) . 'h)');
		}
	}
	$passkey_ip = $az['passkey_ip'];
	if ($passkey_ip != '' && getip() != $passkey_ip)
	err('Unauthorized IP for this passkey!');
} else {
	$upthis = max(0, $uploaded - $self['uploaded']);
	$downthis = ($torrent['free'] || @in_array($userid,@explode(',',$torrent['freefor']))) ? max(0, $downloaded - $self['downloaded']) : 0;
	if ($upthis > 0 || $downthis > 0)
	mysql_query('UPDATE LOW_PRIORITY users SET uploaded = uploaded + '.$upthis.', downloaded = downloaded + '.$downthis.' WHERE id='.$userid) or sqlerr(__FILE__,__LINE__);
}
$dt = $time;
$updateset = array();
$snatch_updateset = array();
if ($event == 'stopped') {
	if (isset($self)) {
		mysql_query('DELETE FROM peers WHERE '.$selfwhere) or sqlerr(__FILE__,__LINE__);
		if (mysql_affected_rows()) {
			if ($self['seeder'])
			$trupdateset[] = 'seeders = IF(seeders > 0, seeders - 1, 0)';
			else
			$trupdateset[] = 'leechers = IF(leechers > 0, leechers - 1, 0)';
		}
	}
} else {
	if ($event == 'completed') {
		$snatch_updateset[] = "finished = 1";
		$snatch_updateset[] = "completedat = $dt";
		$updateset[] = 'times_completed = times_completed + 1';
	}
	if (isset($self)) {
		$downloaded2 = max(0, $downloaded - $self['downloaded']);
		$uploaded2 = max(0, $uploaded - $self['uploaded']);
		if ($downloaded2 > 0 || $uploaded2 > 0) {
			$snatch_updateset[] = "uploaded = uploaded + $uploaded2";
			$snatch_updateset[] = "downloaded = downloaded + $downloaded2";

		}
		$prev_action = $self['last_action'];
		mysql_query("UPDATE LOW_PRIORITY peers SET uploaded = $uploaded, downloaded = $downloaded, uploadoffset = $uploaded2, downloadoffset = $downloaded2, to_go = $left, last_action = ".$time.", prev_action = $prev_action, seeder = $seeder"
		. ($seeder&& $self["seeder"] != $seeder ? ", finishedat = " . $time : "") . ", agent = ".sqlesc($agent)." WHERE $selfwhere") or sqlerr(__FILE__,__LINE__);
		if (mysql_affected_rows() && $self['seeder'] != $seeder) {
			if ($seeder) {
				$trupdateset[] = 'seeders = seeders + 1';
				$trupdateset[] = 'leechers = IF(leechers > 0, leechers - 1, 0)';
			} else {
				$trupdateset[] = 'leechers = leechers + 1';
				$trupdateset[] = 'seeders = IF(seeders > 0, seeders - 1, 0)';
			}
		}
	} else {
		if (portblacklisted($port))
		err('Port '.$port.' is blacklisted.');
		else {
			$sockres = @fsockopen($ip, $port, $errno, $errstr, 5);
			if (!$sockres) {
				$connectable = 0;
				if ($CACHEARRAY['nc'])
				err('Your client is not connectable! Check your Port-configuration or search on forums.');
			}else {
				$connectable = 1;
				@fclose($sockres);
			}
		}

		$res = mysql_query('SELECT finished, completedat FROM snatched WHERE torrent = '.$torrentid.' AND userid = '.$userid) or sqlerr(__FILE__,__LINE__);
		$SN = mysql_fetch_assoc($res);
		if (!$SN)
		mysql_query("INSERT LOW_PRIORITY INTO snatched (torrent, userid, startedat) VALUES ($torrentid, $userid, $dt)") or sqlerr(__FILE__,__LINE__);
		$ret = mysql_query("INSERT LOW_PRIORITY INTO peers (connectable, torrent, peer_id, ip, port, uploaded, downloaded, to_go, started, last_action, seeder, userid, agent, uploadoffset, downloadoffset, passkey) VALUES ($connectable, $torrentid, " . sqlesc($peer_id) . ", " . sqlesc($ip) . ", $port, $uploaded, $downloaded, $left, $time, $time, $seeder, $userid, " . sqlesc($agent) . ", $uploaded, $downloaded, " . sqlesc($passkey) . ")");// or sqlerr(__FILE__,__LINE__);
		if ($ret) {
			if ($seeder)
			$trupdateset[] = 'seeders = seeders + 1';
			else
			$trupdateset[] = 'leechers = leechers + 1';
		}
	}
}
if ($seeder) {
	if (!$torrent['banned'])
	$updateset[] = 'visible = 1';
	$updateset[] = 'last_action = '.$dt;
}
if (count($trupdateset))
mysql_query('UPDATE LOW_PRIORITY trackers SET ' . join(", ", $trupdateset) . ' WHERE torrent = '.$torrentid.' AND tracker="localhost"') or sqlerr(__FILE__,__LINE__);

if (count($updateset))
mysql_query('UPDATE LOW_PRIORITY torrents SET ' . join(", ", $updateset) . ' WHERE id = '.$torrentid) or sqlerr(__FILE__,__LINE__);

if (count($snatch_updateset))
mysql_query('UPDATE LOW_PRIORITY snatched SET ' . join(", ", $snatch_updateset) . ' WHERE torrent = '.$torrentid.' AND userid = '.$userid) or sqlerr(__FILE__,__LINE__);;

if ($_SERVER["HTTP_ACCEPT_ENCODING"] == "gzip" && $CACHEARRAY['use_gzip']) {
	header("Content-Encoding: gzip");
	echo gzencode(benc_resp_raw($resp), 9, FORCE_GZIP);
} else
benc_resp_raw($resp);

?>