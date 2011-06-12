<?php

/*
 * @todo NEED TO BE REWRITED
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


require_once("include/bittorrent.php");
INIT();
loggedinorreturn();

$condition = '';
$countcond = '';

$view = (string)$_GET['view'];
if (!in_array($view, array('seeders','leechers',''))) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Invalid type of statisitcs'));
if ($view == 'leechers') {$condition = "AND p.seeder = 0"; $countcond = " WHERE seeder = 0";}
if ($view == 'seeders') {$condition = "AND p.seeder = 1"; $countcond = " WHERE seeder = 1"; }


$count = @mysql_result(sql_query("SELECT SUM(1) FROM peers".$countcond),0);

if (!$count) stderr($REL_LANG->say_by_key('error'), "Нет статистики, удовлетворяющей выбранным фильтрам.");
$torrentsperpage = $REL_CONFIG['torrentsperpage'];


$limit = "LIMIT 50";


$cheaters = sql_query("SELECT p.torrent AS tid, t.name AS tname, p.ip, p.port, p.seeder, p.peer_id, p.userid, u.username, u.class, u.enabled, u.warned, u.donor, u.ratingsum FROM peers AS p INNER JOIN users AS u ON u.id = p.userid INNER JOIN torrents AS t ON t.id = p.torrent WHERE u.enabled = 1 ".$condition." ORDER BY p.last_action DESC $limit") or sqlerr(__FILE__,__LINE__);

$REL_TPL->stdhead("Статистика пиров");

print("<table cellpadding=\"3\" cellspacing=\"0\" width=\"100%\">");
if (get_user_class() >= UC_MODERATOR)
print("<tr><td class=\"colhead\">Юзер</td><td class=\"colhead\">Торрент</td><td class=\"colhead\">IP&nbsp;/&nbsp;Порт</td><td class=\"colhead\">Сид</td><td class=\"colhead\">Peer_id</td></tr>");
else
print("<tr><td class=\"colhead\">Юзер</td><td class=\"colhead\">Торрент</td><td class=\"colhead\">Сид</td></tr>");
while ($cheater = mysql_fetch_array($cheaters)) {
	list($tid, $tname, $ip, $port, $seeder, $peer_id, $userid, $username, $class, $enabled, $warned, $donor) = $cheater;
	if ($seeder)
	$is_seed = "<span style=\"color: green\">".$REL_LANG->say_by_key('yes')."</span>";
	else
	$is_seed = "<span style=\"color: red\">".$REL_LANG->say_by_key('no')."</span>";
	if (mb_strlen($tname) > 50)
	$tname = substr($tname, 0, 50)."...";
	$peer_id = substr($peer_id, 0, 7);

	if (get_user_class() >= UC_MODERATOR)
	print("<tr><td><nobr><a href=\"".$REL_SEO->make_link('userdetails','id',$userid,'username',translit($username))."\">".get_user_class_color($class, $username)."</a>".get_user_icons(array("enabled" => $enabled, "donor" => $donor, "warned" => $warned))."</nobr></td><td><nobr><a href=\"".$REL_SEO->make_link('details','id',$tid,'name',translit($tname))."\">$tname</a></nobr></td><td>$ip:$port</td><td align=\"center\">$is_seed</td><td>$peer_id</td></tr>");
	else
	print("<tr><td><nobr><a href=\"".$REL_SEO->make_link('userdetails','id',$userid,'username',translit($username))."\">".get_user_class_color($class, $username)."</a>".get_user_icons(array("enabled" => $enabled, "donor" => $donor, "warned" => $warned))."</nobr></td><td><nobr><a href=\"".$REL_SEO->make_link('details','id',$tid,'name',translit($tname))."\">$tname</a></nobr></td><td align=\"center\">$is_seed</td></tr>");
}
print("</table>");

$REL_TPL->stdfoot();
 */
?>