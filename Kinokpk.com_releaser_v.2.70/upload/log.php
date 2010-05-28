<?

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

require_once "include/bittorrent.php";

dbconn();
getlang('log');
loggedinorreturn();

if (get_user_class() < UC_MODERATOR) stderr($tracker_lang['error'],$tracker_lang['access_denied']);
// delete items older than a week
$secs = 7 * 86400;
stdhead($tracker_lang['logs']);
$type = htmlspecialchars((string)$_GET["type"]);
if(!$type || $type == 'simp') $type = "tracker";
print("<p align=center>"  .
($type == tracker || !$type ? $tracker_lang['tracker'] : "<a href=log.php?type=tracker>".$tracker_lang['tracker']."</a>") . " | " .
($type == bans ? "<b>".$tracker_lang['bans']."</b>" : "<a href=log.php?type=bans>".$tracker_lang['bans']."</a>") . " | " .
($type == release ? "<b>".$tracker_lang['release']."</b>" : "<a href=log.php?type=release>".$tracker_lang['release']."</a>") . " | " .
($type == exchange ? "<b>".$tracker_lang['exchange']."</b>" : "<a href=log.php?type=exchange>".$tracker_lang['exchange']."</a>") . " | " .
($type == torrent ? "<b>".$tracker_lang['torrent']."</b>" : "<a href=log.php?type=torrent>".$tracker_lang['torrent']."</a>") . " | " .
($type == error ? "<b>".$tracker_lang['errors']."</b>" : "<a href=log.php?type=error>".$tracker_lang['errors']."</a>") . "</p>\n");

if (($type == 'speed' || $type == 'error') && $CURUSER['class'] < 4) {
	stdmsg($tracker_lang['error'],$tracker_lang['access_closed']);
	stdfoot();
	die();
}

sql_query("DELETE FROM sitelog WHERE " . time() . " - added > $secs") or sqlerr(__FILE__, __LINE__);
$limit = ($type == 'announce' ? "LIMIT 1000" : "");
$res = sql_query("SELECT txt, added, color FROM `sitelog` WHERE type = ".sqlesc($type)." ORDER BY `added` DESC $limit") or sqlerr(__FILE__, __LINE__);
print("<h1>".$tracker_lang['logs']."</h1>\n");
if (mysql_num_rows($res) == 0)
print("<b>".$tracker_lang['log_file_empty']."</b>\n");
else
{
	print("<table border=1 cellspacing=0 cellpadding=5>\n");
	print("<tr><td class=colhead align=left>".$tracker_lang['time']."</td><td class=colhead align=left>".$tracker_lang['event']."</td></tr>\n");
	while ($arr = mysql_fetch_assoc($res))
	{
		$time = mkprettytime($arr['added']);
		print("<tr style=\"background-color: $arr[color]\"><td>$time</td><td align=left>".makesafe($arr[txt])."</td></tr>\n");
	}
	print("</table>");
}
stdfoot();
?>