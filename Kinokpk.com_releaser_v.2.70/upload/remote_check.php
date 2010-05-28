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


@set_time_limit(0);
@ignore_user_abort(1);
date_default_timezone_set('UTC');

define ("IN_ANNOUNCE",true);
define ("ROOT_PATH",dirname(__FILE__).'/');
require_once(ROOT_PATH.'include/secrets.php');
// connection closed
$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
or die ('Not connected : ' . mysql_error());
mysql_select_db ($mysql_db, $db);

$cronrow = mysql_query("SELECT * FROM cron WHERE cron_name IN ('remotecheck_disabled','in_remotecheck')");
while ($cronres = mysql_fetch_array($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];

if ($CRON['in_remotecheck'] || $CRON['remotecheck_disabled']) die('Remote check is already running or disabled by SYSOP');


require_once(ROOT_PATH."include/benc.php");
$torrents_total=0;

$cronrow = mysql_query("SELECT * FROM cron WHERE cron_name IN ('remotecheck_disabled','remotepeers_cleantime','in_remotecheck','remote_torrents','remote_lastchecked')");
while ($cronres = mysql_fetch_array($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];

$CRON['remote_lastchecked'] = (int)$CRON['remote_lastchecked'];
$CRON['remote_torrents'] = (int)$CRON['remote_torrents'];

mysql_query("UPDATE cron SET cron_value=1 WHERE cron_name='in_remotecheck'");

$minid = (int)@mysql_result(mysql_query("SELECT MIN(id) FROM torrents"),0);

$res = mysql_query("SELECT id,info_hash,announce_urls FROM torrents WHERE announce_urls <> '' ORDER BY id DESC LIMIT {$CRON['remote_lastchecked']}, ".($CRON['remote_lastchecked']+$CRON['remote_torrents']));

// print "SELECT id,info_hash,announce_urls FROM torrents WHERE announce_urls <> '' ORDER BY id DESC LIMIT {$CRON['remote_lastchecked']}, ".($CRON['remote_lastchecked']+$CRON['remote_torrents'])."<hr>";
$torrents_ch=1;
while (list($id,$hash,$announce_urls) = mysql_fetch_array($res)) {

	$anarray = explode(",",$announce_urls);

	$r_seeders=$r_leechers=0;
	foreach ($anarray as $url) {
		$peers = get_remote_peers($url, $hash);

		if ($peers['state']!='false') {
			$r_seeders+=$peers['seeders'];
			$r_leechers+=$peers['leechers'];
		}
	}

	mysql_query("UPDATE LOW_PRIORITY torrents SET remote_seeders=$r_seeders, remote_leechers=$r_leechers WHERE id=$id");
	$torrents_total++;

	//print("$id updated, $torrents_total total, $torrents_ch in a circle, MIN ID $minid <br/>");
	//flush();
	if ($torrents_ch>=$CRON['remote_torrents']) {
		mysql_query("UPDATE cron SET cron_value=$torrents_total WHERE cron_name='remote_lastchecked'");

		break;
	}
	if ($id==$minid) { mysql_query("UPDATE cron SET cron_value=0 WHERE cron_name='remote_lastchecked'"); $torrents_total=0; }
	$torrents_ch++;
	$r_seeders=$r_leechers=0;
}
mysql_query("UPDATE cron SET cron_value=".time()." WHERE cron_name='last_remotecheck'");
mysql_query("UPDATE cron SET cron_value=cron_value+1 WHERE cron_name='num_checked'");
mysql_query("UPDATE cron SET cron_value=0 WHERE cron_name='in_remotecheck'");
?>