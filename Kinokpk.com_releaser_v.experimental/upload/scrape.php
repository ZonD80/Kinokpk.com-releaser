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

define("ROOT_PATH", dirname(__FILE__) . '/');
require_once(ROOT_PATH . 'include/functions_announce.php');
require_once(ROOT_PATH . 'include/benc.php');

INIT();

//err('Disabled due modifications');
$r = "d" . benc_str("files") . "d";


if (!isset($_GET["info_hash"]))
//$query = "SELECT info_hash, times_completed, SUM(seeder) AS seeders, SUM(1) AS peers FROM torrents LEFT JOIN peers ON torrents.id=peers.torrent GROUP BY torrents.id ORDER BY info_hash";
    $query = "SELECT info_hash,id, completed AS times_completed, seeders, leechers FROM torrents LEFT JOIN xbt_files_users ON torrents.id=fid GROUP BY torrents.id";
else {
    $hash = bin2hex((string)$_GET["info_hash"]);
    if (strlen((string)$_GET["info_hash"]) != 20)
        err("Invalid info-hash length");
    //$query = "SELECT torrents.id, info_hash, times_completed, SUM(seeder) AS seeders, SUM(1) AS peers FROM torrents LEFT JOIN peers ON torrents.id=peers.torrent WHERE info_hash = " . $REL_DB->sqlesc($hash)." GROUP BY torrents.id";
    $query = "SELECT torrents.id, xbt_files.info_hash, completed AS times_completed, torrents.seeders, torrents.leechers FROM torrents LEFT JOIN xbt_files ON torrents.id=xbt_files.fid WHERE torrents.info_hash = " . $REL_DB->sqlesc($hash) . " GROUP BY id";
}

$res = $REL_DB->query($query);

while ($row = mysql_fetch_assoc($res)) {
    $id = $row['id'];
    $seeders = (int)$row['seeders'];
    //$leechers = intval($row['peers']-$row["seeders"]);
    $row["seeders"] = (int)$row["seeders"];
    $r .= "20:" . pack("H*", ($row["info_hash"])) . "d" .
        benc_str("complete") . "i" . $seeders . "e" .
        benc_str("downloaded") . "i" . $row["times_completed"] . "e" .
        benc_str("incomplete") . "i" . ($leechers) . "e" .
        "e";
    $okay = true;
}

if ($id) $REL_DB->query("UPDATE trackers SET seeders={$seeders}, leechers=$leechers, lastchecked=" . TIME . ", state='ok_local' WHERE torrent={$id} AND tracker='localhost'") or sqlerr(__FILE__, __LINE__);

$r .= "ee";

if (!$okay) err('Data does not found');
benc_resp_raw($r);

?>