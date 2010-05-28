<?php

/*
 Project: Kinokpk.com releaser
 This file is part of Kinokpk.com releaser.
 Kinokpk.com releaser is based on TBDev,
 originally by RedBeard of TorrentBits, extensively modified by
 Gartenzwerg and Yuna Scatari.
 Kinokpk.com releaser is free software;
 you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 Kinokpk.com is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Kinokpk.com releaser; if not, write to the
 Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 MA  02111-1307  USA
 Do not remove above lines!
 */

define ('IN_ANNOUNCE', true);
define("ROOT_PATH",dirname(__FILE__).'/');
require_once(ROOT_PATH.'include/core_announce.php');

dbconn();

gzip();

foreach (array('passkey','info_hash','peer_id','event','ip','localip') as $x) {
	if(isset($_GET[$x]))
	$GLOBALS[$x] = '' . $_GET[$x];
}

foreach (array('port','downloaded','uploaded','left') as $x)
$GLOBALS[$x] = (float) $_GET[$x];

if (strpos($passkey, '?')) {
	$tmp = substr($passkey, strpos($passkey, '?'));
	$passkey = substr($passkey, 0, strpos($passkey, '?'));
	$tmpname = substr($tmp, 1, strpos($tmp, '=')-1);
	$tmpvalue = substr($tmp, strpos($tmp, '=')+1);
	$GLOBALS[$tmpname] = $tmpvalue;
}

if (get_magic_quotes_gpc()) {
	$info_hash = stripslashes($info_hash);
	$peer_id = stripslashes($peer_id);
}

foreach (array('passkey','info_hash','peer_id','port','downloaded','uploaded','left') as $x)
if (!isset($x)) err('Missing key: '.$x);
foreach (array('info_hash','peer_id') as $x)
if (strlen($GLOBALS[$x]) != 20)
err('Неверный '.$x.' (' . strlen($GLOBALS[$x]) . ' - ' . urlencode($GLOBALS[$x]) . ')');
if (strlen($passkey) != 32)
err('Неверный (' . strlen($passkey) . ' - пасскей)');
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

$port =(int) $_GET['port'];

if (!$port || $port > 0xffff) err("Неверный порт");

if (!isset($event))
$event = '';
$seeder = ($left == 0) ? 'yes' : 'no';

checkclient($peer_id);

mysql_query("SELECT id FROM users WHERE passkey = " . sqlesc($passkey)) or err(mysql_error());
if (mysql_affected_rows() == 0)
err('Неверный пасскей. Пожалуйста, перекачайте торрент с '.$CACHEARRAY['defaultbaseurl']);
$hash = bin2hex($info_hash);
$res = mysql_query('SELECT id, banned, free, seeders + leechers AS numpeers, UNIX_TIMESTAMP(added) AS ts FROM torrents WHERE info_hash = "'.$hash.'"') or err(mysql_error());
$torrent = mysql_fetch_array($res);
if (!$torrent)
err('Этот торрент не зарегестрирован на трекере, возможно он обновился. Пожалуйста, проверьте наши новинки.');
$torrentid = $torrent['id'];
if ($torrent['banned'] == 'yes') err('Этот торрент забанен.');
$fields = 'seeder, peer_id, ip, port, uploaded, downloaded, userid, last_action, UNIX_TIMESTAMP(NOW()) AS nowts, UNIX_TIMESTAMP(prev_action) AS prevts';
$numpeers = $torrent['numpeers'];
$limit = '';
if ($numpeers > $rsize)
$limit = 'ORDER BY RAND() LIMIT '.$rsize;
$res = mysql_query('SELECT '.$fields.' FROM peers WHERE torrent = '.$torrentid.' '.$limit) or err(mysql_error());
$resp = 'd' . benc_str('interval') . 'i' . $CACHEARRAY['announce_interval']*60 . 'e' . benc_str('peers') . (($compact = ((int)$_GET['compact'] == 1)) ? '' : 'l');
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
	$res = mysql_query('SELECT '.$fields.' FROM peers WHERE '.$selfwhere) or err(mysql_error());
	$row = mysql_fetch_array($res);
	if ($row) {
		$userid = $row['userid'];
		$self = $row;
	}
}

$announce_wait = 10;
if (isset($self) && ($self['prevts'] > ($self['nowts'] - $announce_wait )) )
err('Максимальное время обновления статистики - ' . $announce_wait . ' секунд');
if (!isset($self)) {
	$rz = mysql_query('SELECT id, uploaded, downloaded, class, parked, passkey_ip FROM users WHERE passkey='.sqlesc($passkey).' ORDER BY last_access DESC LIMIT 1') or err('Tracker error 2');
	if (mysql_num_rows($rz) == 0)
	err('Неизвестный пасскей. Пожалуйста, свяжитесь с администрацией по поводу этой проблемы, если вы зарегестрированный пользователь '.$CACHEARRAY['defaultbaseurl'].'/staff.php');
	$az = mysql_fetch_array($rz);
	$userid = (int) $az['id'];
	if ($az['class'] < UC_VIP) {
		if ($CACHEARRAY['use_wait']) {
			$gigs = $az['uploaded'] / (1024*1024*1024);
			$elapsed = floor((strtotime(date('Y-m-d H:i:s')) - $torrent['ts']) / 3600);
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
			err('Доступ запрещен, у нас стоят ограничения для личеров (' . ($wait - $elapsed) . 'часов) - подождите пожалуйста!');
		}
	}
	$passkey_ip = $az['passkey_ip'];
	if ($passkey_ip != '' && getip() != $passkey_ip)
	err('Этот IP адрес не авторизован для использования с этим пасскеем');
} else {
	$upthis = max(0, $uploaded - $self['uploaded']);
	$downthis = ($torrent['free'] == 'no') ? max(0, $downloaded - $self['downloaded']) : 0;
	/*$upload_speed = $upthis / max(10, (strtotime($self['last_action']) - $self['prevts']));
	 if ($upload_speed > 5 * 1048576)
		err("Upload speed to high for normal user: ".number_format($upload_speed / 1048576, 2)." MB/s");*/
	if ($upthis > 0 || $downthis > 0)
	mysql_query('UPDATE LOW_PRIORITY users SET uploaded = uploaded + '.$upthis.', downloaded = downloaded + '.$downthis.' WHERE id='.$userid) or err('Произошла ошибка при обновлении users-таблиц трекера, срочно свяжитесь с администрацией '.$CACHEARRAY['defaultbaseurl'].'/contact.php');
}
$dt = sqlesc(date('Y-m-d H:i:s', time()));
$updateset = array();
$snatch_updateset = array();
if ($event == 'stopped') {
	if (isset($self)) {
		mysql_query('UPDATE LOW_PRIORITY snatched SET seeder = "no", connectable = "no" WHERE torrent = '.$torrentid.' AND userid = '.$userid) or err(mysql_error());
		mysql_query('DELETE FROM peers WHERE '.$selfwhere);
		if (mysql_affected_rows()) {
			if ($self['seeder'] == 'yes')
			$updateset[] = 'seeders = seeders - 1';
			else
			$updateset[] = 'leechers = leechers - 1';
		}
	}
} else {
	if ($event == 'completed') {
		$snatch_updateset[] = "finished = 'yes'";
		$snatch_updateset[] = "completedat = $dt";
		$snatch_updateset[] = "seeder = 'yes'";
		$updateset[] = 'times_completed = times_completed + 1';
	}
	if (isset($self)) {
		$res=mysql_query('SELECT uploaded, downloaded FROM snatched WHERE torrent = '.$torrentid.' AND userid = '.$userid) or err(mysql_error());
		$row = mysql_fetch_array($res);
		$downloaded2 = max(0, $downloaded - $self['downloaded']);
		$uploaded2 = max(0, $uploaded - $self['uploaded']);
		if ($downloaded2 > 0 || $uploaded2 > 0) {
			$snatch_updateset[] = "uploaded = uploaded + $uploaded2";
			$snatch_updateset[] = "downloaded = downloaded + $downloaded2";
			$snatch_updateset[] = "to_go = $left";
		}
		$snatch_updateset[] = "port = $port";
		$snatch_updateset[] = "last_action = $dt";
		$snatch_updateset[] = "seeder = '$seeder'";
		$prev_action = $self['last_action'];
		mysql_query("UPDATE LOW_PRIORITY peers SET uploaded = $uploaded, downloaded = $downloaded, uploadoffset = $uploaded2, downloadoffset = $downloaded2, to_go = $left, last_action = NOW(), prev_action = ".sqlesc($prev_action).", seeder = '$seeder'"
		. ($seeder == "yes" && $self["seeder"] != $seeder ? ", finishedat = " . time() : "") . ", agent = ".sqlesc($agent)." WHERE $selfwhere") or err('Произошла ошибка при обновлении peers-таблиц трекера, срочно свяжитесь с администрацией '.$CACHEARRAY['defaultbaseurl'].'/contact.php');
		if (mysql_affected_rows() && $self['seeder'] != $seeder) {
			if ($seeder == 'yes') {
				$updateset[] = 'seeders = seeders + 1';
				$updateset[] = 'leechers = leechers - 1';
			} else {
				$updateset[] = 'seeders = seeders - 1';
				$updateset[] = 'leechers = leechers + 1';
			}
		}
	} else {
		if ($az['parked'] == 'yes')
		err('Error, your account is parked!');
		if (portblacklisted($port))
		err('Port '.$port.' is blacklisted.');
		else {
			$sockres = @fsockopen($ip, $port, $errno, $errstr, 5);
			if (!$sockres) {
				$connectable = 'no';
				if ($CACHEARRAY['nc'] == 'yes')
				err('К вам не возможно подключиться, пожалуйста, проверте порты. Подробнее об этой проблеме читайте в ЧаВо: '.$CACHEARRAY['defaultbaseurl'].'/faq.php');
			}else {
				$connectable = 'yes';
				@fclose($sockres);
			}
		}

		$res = mysql_query('SELECT torrent, userid FROM snatched WHERE torrent = '.$torrentid.' AND userid = '.$userid) or err(mysql_error());
		$check = mysql_fetch_array($res);
		if (!$check)
		mysql_query("INSERT LOW_PRIORITY INTO snatched (torrent, userid, port, startdat, last_action) VALUES ($torrentid, $userid, $port, $dt, $dt)");
		$ret = mysql_query("INSERT LOW_PRIORITY INTO peers (connectable, torrent, peer_id, ip, port, uploaded, downloaded, to_go, started, last_action, seeder, userid, agent, uploadoffset, downloadoffset, passkey) VALUES ('$connectable', $torrentid, " . sqlesc($peer_id) . ", " . sqlesc($ip) . ", $port, $uploaded, $downloaded, $left, NOW(), NOW(), '$seeder', $userid, " . sqlesc($agent) . ", $uploaded, $downloaded, " . sqlesc($passkey) . ")");
		if ($ret) {
			if ($seeder == 'yes')
			$updateset[] = 'seeders = seeders + 1';
			else
			$updateset[] = 'leechers = leechers + 1';
		}
	}
}
if ($seeder == 'yes') {
	if ($torrent['banned'] != 'yes')
	$updateset[] = 'visible = \'yes\'';
	$updateset[] = 'last_action = NOW()';
}
if (count($updateset))
mysql_query('UPDATE LOW_PRIORITY torrents SET ' . join(", ", $updateset) . ' WHERE id = '.$torrentid);

if (count($snatch_updateset))
mysql_query('UPDATE LOW_PRIORITY snatched SET ' . join(", ", $snatch_updateset) . ' WHERE torrent = '.$torrentid.' AND userid = '.$userid) or err(mysql_error()." линия: ".__LINE__);

if ($_SERVER["HTTP_ACCEPT_ENCODING"] == "gzip" && $CACHEARRAY['use_gzip']) {
	header("Content-Encoding: gzip");
	echo gzencode(benc_resp_raw($resp), 9, FORCE_GZIP);
} else
benc_resp_raw($resp);

?>