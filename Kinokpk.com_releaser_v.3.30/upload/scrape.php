<?php
/**
 * Scraper
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

define ('IN_ANNOUNCE', true);

define("ROOT_PATH",dirname(__FILE__).'/');
require_once(ROOT_PATH . 'include/functions_announce.php');
require_once(ROOT_PATH. 'include/benc.php');

dbconn(false);

$r = "d" . benc_str("files") . "d";


if (!isset($_GET["info_hash"]))
$query = "SELECT info_hash, times_completed, SUM(seeder) AS seeders, SUM(1) AS peers FROM torrents LEFT JOIN peers ON torrents.id=peers.torrent GROUP BY torrents.id ORDER BY info_hash";
else {
	$hash = bin2hex($_GET["info_hash"]);
	if (strlen($_GET["info_hash"]) != 20)
	err("Invalid info-hash (".strlen($_GET["info_hash"]).")");
	$query = "SELECT torrents.id, info_hash, times_completed, SUM(seeder) AS seeders, SUM(1) AS peers FROM torrents LEFT JOIN peers ON torrents.id=peers.torrent WHERE info_hash = " . sqlesc($hash)." GROUP BY torrents.id";
}

$res = mysql_query($query) or sqlerr(__FILE__,__LINE__);

while ($row = mysql_fetch_assoc($res)) {
	$id = $row['id'];
	$seeders=(int)$row['seeders'];
	$leechers = intval($row['peers']-$row["seeders"]);
	$row["seeders"] = (int)$row["seeders"];
	$r .= "20:" . pack("H*", ($row["info_hash"])) . "d" .
	benc_str("complete") . "i" . $seeders . "e" .
	benc_str("downloaded") . "i" . $row["times_completed"] . "e" .
	benc_str("incomplete") . "i" . ($leechers) . "e" .
		"e";
	$okay = true;
}

if ($id) mysql_query("UPDATE trackers SET seeders={$seeders}, leechers=$leechers, lastchecked=".time().", state='ok_local' WHERE torrent={$id} AND tracker='localhost'") or sqlerr(__FILE__,__LINE__);

$r .= "ee";

if (!$okay) err('Data does not found');
benc_resp_raw($r);

?>