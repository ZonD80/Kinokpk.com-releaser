<?php
/**
 * Bookmarks Browser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require_once("include/bittorrent.php");

dbconn();

loggedinorreturn();


stdhead($tracker_lang['bookmarks']);

$res = sql_query("SELECT SUM(1) FROM bookmarks WHERE userid = ".sqlesc($CURUSER["id"]));
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$count) {
	stdmsg($tracker_lang['error'], $tracker_lang['you_have_no_bookmarks'],'error');
	stdfoot();
	die();
} else {

	print ("<form method=\"post\" action=\"takedelbookmark.php\"/>");

	print('<div id="releases-table">');
	print('<table class="embedded" cellspacing="0" cellpadding="5" width="100%"><tr><td class="colhead" align="center" colspan="9">Список закладок</td></tr>');


	$perpage = 25;

	list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "bookmarks.php?");

	$tree = make_tree();

	$res = sql_query("SELECT bookmarks.id AS bookmarkid, users.username, users.class, users.id AS owner, torrents.id, torrents.name, torrents.comments, SUM(trackers.leechers) AS leechers, SUM(trackers.seeders) AS seeders, torrents.images, torrents.numfiles, torrents.added, torrents.filename, torrents.size, torrents.views, torrents.visible, torrents.free, torrents.hits, torrents.times_completed, torrents.category, IF((torrents.relgroup=0) OR (relgroups.private=0) OR FIND_IN_SET({$CURUSER['id']},relgroups.owners) OR FIND_IN_SET({$CURUSER['id']},relgroups.members),1,(SELECT 1 FROM rg_subscribes WHERE rgid=torrents.relgroup AND userid={$CURUSER['id']})) AS relgroup_allowed FROM bookmarks INNER JOIN torrents ON bookmarks.torrentid = torrents.id LEFT JOIN relgroups ON torrents.relgroup=relgroups.id LEFT JOIN users ON torrents.owner = users.id LEFT JOIN trackers ON torrents.id=trackers.torrent WHERE bookmarks.userid = ".sqlesc($CURUSER["id"])." GROUP BY torrents.id ORDER BY torrents.id DESC $limit") or sqlerr(__FILE__, __LINE__);

	$resarray = array();
	while ($resvalue = mysql_fetch_array($res)) {
		$chsel = array();
		$cats = explode(',',$resvalue['category']);
		$cat= array_shift($cats);
		$cat = get_cur_branch($tree,$cat);
		$childs = get_childs($tree,$cat['parent_id']);
		if ($childs) {
			foreach($childs as $child)
			if (($cat['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"browse.php?cat={$child['id']}\">".makesafe($child['name'])."</a>";
			$resvalue['cat_names'] = get_cur_position_str($tree,$cat['id']).(is_array($chsel)?', '.implode(', ',$chsel):'');
		}  else $resvalue['cat_names'] = get_cur_position_str($tree,$cat['id']);
		$resarray[$resvalue['id']] = $resvalue;

	}
	if (!$resarray) {
		stdmsg($tracker_lang['error'], $tracker_lang['you_have_no_bookmarks'],'error');
		stdfoot();
		die();
	}

	print("<tr><td colspan=\"9\">");
	print($pagertop);
	print("</td></tr>");
	torrenttable($resarray, "bookmarks");
	print("<tr><td colspan=\"9\">");
	print($pagerbottom);
	print("</td></tr>");
	print("</table></div>");
	print("<div align=\"right\"><input type=\"submit\" OnClick=\"return confirm('Вы уверены?');\" value=\"".$tracker_lang['delete']."\"/></div></form>\n");

}

stdfoot();

?>