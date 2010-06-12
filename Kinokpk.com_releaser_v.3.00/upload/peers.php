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
dbconn();
loggedinorreturn();

$condition = '';
$countcond = '';

if ($_GET['view'] == 'leechers') {$condition = "AND p.seeder = 0"; $countcond = " WHERE seeder = 0";}
if ($_GET['view'] == 'seeders') {$condition = "AND p.seeder = 1"; $countcond = " WHERE seeder = 1"; }


$count = @mysql_result(sql_query("SELECT SUM(1) FROM peers".$countcond),0);

if (!$count) stderr($tracker_lang['error'], "Нет статистики, удовлетворяющей выбранным фильтрам.");
$torrentsperpage = $CACHEARRAY['torrentsperpage'];

$addparam = strip_tags($_SERVER['QUERY_STRING']);

if ($addparam != "")
$addparam = $addparam . "&" . $pagerlink;
else
$addparam = $pagerlink;

list($pagertop, $pagerbottom, $limit) = pager($torrentsperpage, $count, "peers.php?" . $addparam);


$cheaters = sql_query("SELECT p.torrent AS tid, t.name AS tname, p.ip, p.port, p.uploaded, p.downloaded, p.to_go, p.seeder, p.agent, p.peer_id, p.userid, u.username, u.class, u.enabled, u.warned, u.donor, (p.uploadoffset / (p.last_action - p.prev_action)) AS upspeed, (p.downloadoffset / (p.last_action - p.prev_action)) AS downspeed FROM peers AS p INNER JOIN users AS u ON u.id = p.userid INNER JOIN torrents AS t ON t.id = p.torrent WHERE u.enabled = 1 ".$condition." ORDER BY upspeed DESC $limit") or sqlerr(__FILE__,__LINE__);

stdhead("Статистика пиров");
print($pagertop);

print("<table cellpadding=\"3\" cellspacing=\"0\" width=\"100%\">");
if (get_user_class() >= UC_MODERATOR)
print("<tr><td class=\"colhead\">Юзер</td><td class=\"colhead\">Торрент</td><td class=\"colhead\">IP&nbsp;/&nbsp;Порт</td><td class=\"colhead\">Раздал</td><td class=\"colhead\">Скачал</td><td class=\"colhead\">Осталось</td><td class=\"colhead\">Раздача</td><td class=\"colhead\">Закачка</td><td class=\"colhead\">Сид</td><td class=\"colhead\">Агент</td><td class=\"colhead\">Peer_id</td></tr>");
else
print("<tr><td class=\"colhead\">Юзер</td><td class=\"colhead\">Торрент</td><td class=\"colhead\">Раздал</td><td class=\"colhead\">Скачал</td><td class=\"colhead\">Осталось</td><td class=\"colhead\">Раздача</td><td class=\"colhead\">Закачка</td><td class=\"colhead\">Сид</td><td class=\"colhead\">Агент</td></tr>");
while ($cheater = mysql_fetch_array($cheaters)) {
	list($tid, $tname, $ip, $port, $uploaded, $downloaded, $left, $seeder, $agent, $peer_id, $userid, $username, $class, $enabled, $warned, $donor, $upspeed, $downspeed) = $cheater;
	list($uploaded, $downloaded, $left, $upspeed, $downspeed) = array_map("mksize", array($uploaded, $downloaded, $left, $upspeed, $downspeed));
	if ($seeder)
	$is_seed = "<span style=\"color: green\">Да</span>";
	else
	$is_seed = "<span style=\"color: red\">Нет</span>";
	if (strlen($tname) > 50)
	$tname = substr($tname, 0, 50)."...";
	$peer_id = substr($peer_id, 0, 7);

	if (get_user_class() >= UC_MODERATOR)
	print("<tr><td><nobr><a href=\"userdetails.php?id=$userid\">".get_user_class_color($class, $username)."</a>".get_user_icons(array("enabled" => $enabled, "donor" => $donor, "warned" => $warned))."</nobr></td><td><nobr><a href=\"details.php?id=$tid\">$tname</a></nobr></td><td>$ip:$port</td><td><nobr>$uploaded</nobr></td><td><nobr>$downloaded</nobr></td><td><nobr>$left</nobr></td><td><nobr>$upspeed/s</nobr></td><td><nobr>$downspeed/s</nobr></td><td align=\"center\">$is_seed</td><td><nobr>$agent</nobr></td><td>$peer_id</td></tr>");
	else
	print("<tr><td><nobr><a href=\"userdetails.php?id=$userid\">".get_user_class_color($class, $username)."</a>".get_user_icons(array("enabled" => $enabled, "donor" => $donor, "warned" => $warned))."</nobr></td><td><nobr><a href=\"details.php?id=$tid\">$tname</a></nobr></td><td><nobr>$uploaded</nobr></td><td><nobr>$downloaded</nobr></td><td><nobr>$left</nobr></td><td><nobr>$upspeed/s</nobr></td><td><nobr>$downspeed/s</nobr></td><td align=\"center\">$is_seed</td><td><nobr>$agent</nobr></td></tr>");
}
print("</table>");

print($pagerbottom);

stdfoot();

?>