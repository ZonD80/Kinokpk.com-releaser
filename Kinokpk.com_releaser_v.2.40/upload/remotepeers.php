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
require_once("include/bittorrent.php");
require_once(ROOT_PATH.'include/benc.php');
dbconn();
getlang('remotepeers');
if ($CURUSER) {
	$ss_a = @mysql_fetch_array(@sql_query("SELECT uri FROM stylesheets WHERE id = " . $CURUSER["stylesheet"]));
	if ($ss_a)
	$ss_uri = $ss_a["uri"];
	else
	$ss_uri = $CACHEARRAY['default_theme'];
} else
$ss_uri = $CACHEARRAY['default_theme'];

print('<head><link rel="stylesheet" href="themes/'.$ss_uri.'/'.$ss_uri.'.css" type="text/css"><style type="text/css">body {background: none;}</style></head>');

if (!is_valid_id($_GET['id'])) 			die($tracker_lang['error'].": ".$tracker_lang['invalid_id']);
$id = (int) $_GET["id"];

$pregstring = ("details\.php\?id=$id");

if (!preg_match("/$pregstring/",$_SERVER['HTTP_REFERER'])) die($tracker_lang['error']."".$tracker_lang['invalid_result']."");

print('<div align="center">'.$tracker_lang['del_peers'].'</div>');
$fn = ROOT_PATH."/torrents/$id.torrent";
if (!is_readable($fn)) die ($tracker_lang['invaled_passed']);
$dict = bdec_file($fn, (1024*1024));
list($info) = dict_check($dict, "info");
if (!$dict['value']['announce']) die($tracker_lang['distr_our_tracker']);

$anarray = get_announce_urls($dict);
foreach ($anarray as $url) {
	$peers = get_remote_peers($url, sha1($info["string"]), false);

	print($peers['tracker']." : ".(($peers['state'] == 'false')?"".$tracker_lang['unable_peers']."":($peers['seeders']." ".$tracker_lang['seeders_l'].", ".$peers['leechers']." ".$tracker_lang['leechers_l']." = ".($peers['seeders']+$peers['leechers'])." ".$tracker_lang['peers_l'])));
}
?>