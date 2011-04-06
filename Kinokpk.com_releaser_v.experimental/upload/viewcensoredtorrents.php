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

INIT();



loggedinorreturn();


if (is_array($_GET["delt"]))
{
	if (get_privilege('censored_admin',false)) {
		if (empty($_GET["delt"]))
		stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_fields_blank'));
		sql_query("DELETE FROM censoredtorrents WHERE id IN (" . implode(", ", array_map("sqlesc", $_GET["delt"])) . ")");

		$REL_CACHE->clearGroupCache("block-cen");
		stderr($REL_LANG->say_by_key('success'), $REL_LANG->say_by_key('ban_uninstalled'));
		$REL_TPL->stdfoot();
		die;
	}
	else  {
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('not_permission_prohibitions'));
		$REL_TPL->stdfoot();
		die;
	}
}


$REL_TPL->stdhead($REL_LANG->say_by_key('ban_releases'));

if (get_privilege('censored_admin',false)) $moder=1;

print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\" >");
print("<tr><td class=\"colhead\" align=\"center\" colspan=\"15\">".$REL_LANG->say_by_key('banned_releases')."</td></tr>");
$res = sql_query("SELECT * FROM censoredtorrents ORDER BY id DESC");
print ("<tr>");
print("<tr><td class=\"colhead\" align=\"center\">".$REL_LANG->say_by_key('title')."</td><td class=\"colhead\" align=\"center\">".$REL_LANG->say_by_key('reason_ban')."</td></tr>");
while ($row = mysql_fetch_array($res)) {
	if ($moder) $name = $row['name'] . "<br /><a href=\"".$REL_SEO->make_link('censoredtorrents','id',$row['id'])."\">".$REL_LANG->say_by_key('more')."</a>"; else $name = $row['name'];


	print("<tr><td align=\"center\">".$name."</td><td align=\"center\">".format_comment($row['reason'])."</td></tr>");
}

print("</table>");
if ($moder)
print("<div align=\"center\">[<a href=\"".$REL_SEO->make_link('censoredtorrents','action','new')."\"><b>".$REL_LANG->say_by_key('add_ban')."</b></a>]</div>");
$REL_TPL->stdfoot();

?>