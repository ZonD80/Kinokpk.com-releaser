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
getlang('viewcensoredtorrents');
gzip();


loggedinorreturn();


if ($_GET["delt"])
{
	if (get_user_class() >= UC_MODERATOR) {
		if (empty($_GET["delt"]))
		stderr($tracker_lang['error'],$tracker_lang['no_fields_blank']);
		sql_query("DELETE FROM censoredtorrents WHERE id IN (" . implode(", ", array_map("sqlesc", $_GET["delt"])) . ")");

		$CACHE->clearGroupCache("block-cen");
		stderr($tracker_lang['success'], $tracker_lang['ban_uninstalled']);
		stdfoot();
		die;
	}
	else  {
		stderr($tracker_lang['error'], $tracker_lang['not_permission_prohibitions']);
		stdfoot();
		die;
	}
}


stdhead($tracker_lang['ban_releases']);

if (get_user_class() >= UC_MODERATOR) $moder=1;

print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\" >");
print("<tr><td class=\"colhead\" align=\"center\" colspan=\"15\">".$tracker_lang['banned_releases']."</td></tr>");
$res = sql_query("SELECT * FROM censoredtorrents ORDER BY id DESC");
print ("<tr>");
print("<tr><td class=\"colhead\" align=\"center\">".$tracker_lang['title']."</td><td class=\"colhead\" align=\"center\">".$tracker_lang['reason_ban']."</td></tr>");
while ($row = mysql_fetch_array($res)) {
	if ($moder) $name = $row['name'] . "<br /><a href=\"censoredtorrents.php?id=".$row['id']."\">".$tracker_lang['more']."</a>"; else $name = $row['name'];


	print("<tr><td align=\"center\">".$name."</td><td align=\"center\">".format_comment($row['reason'])."</td></tr>");
}

print("</table>");
if ($moder)
print("<div align=\"center\">[<a href=censoredtorrents.php?action=new><b>".$tracker_lang['add_ban']."</b></a>]</div>");
stdfoot();

?>