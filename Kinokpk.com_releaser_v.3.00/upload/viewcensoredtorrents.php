<?php
/**
 * Censored torrents viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require_once("include/bittorrent.php");

dbconn();
getlang('viewcensoredtorrents');


loggedinorreturn();


if (is_array($_GET["delt"]))
{
	if (get_user_class() >= UC_MODERATOR) {
		if (empty($_GET["delt"]))
		stderr($tracker_lang['error'],$tracker_lang['no_fields_blank']);
		sql_query("DELETE FROM censoredtorrents WHERE id IN (" . implode(", ", array_map("sqlesc", $_GET["delt"])) . ")");

		$REL_CACHE->clearGroupCache("block-cen");
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