<?php
/**
 * User torrents viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require_once("include/bittorrent.php");

dbconn();

loggedinorreturn();


stdhead($REL_LANG->say_by_key('my_releases'));

$where = "WHERE owner = " . $CURUSER["id"] . " AND banned=0";
$res = sql_query("SELECT SUM(1) FROM torrents $where");
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$count) {
	stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('not_releases'),'error');
	stdfoot();
	die();
}
else {
	print('<div id="releases-table">');

	print('<table class="embedded" cellspacing="0" cellpadding="3" width="100%">
<tr><td class="colhead" align="center" colspan="9">'.$REL_LANG->say_by_key('my_releases').'</td></tr>');


	list($pagertop, $pagerbottom, $limit) = pager(20, $count, $REL_SEO->make_link('mytorrents'));
	$tree = make_tree();
	$res = sql_query("SELECT torrents.images, torrents.comments, SUM(trackers.leechers) AS leechers, SUM(trackers.seeders) AS seeders, torrents.id, torrents.name, filename, numfiles, added, size, views, visible, free, hits, times_completed, category FROM torrents LEFT JOIN trackers ON torrents.id=trackers.torrent $where GROUP BY id ORDER BY id DESC $limit");
	$resarray = array();
	while ($resvalue = mysql_fetch_array($res)) {
		$chsel = array();
		$cats = explode(',',$resvalue['category']);
		$cat= array_shift($cats);
		$cat = get_cur_branch($tree,$cat);
		$childs = get_childs($tree,$cat['parent_id']);
		if ($childs) {
			foreach($childs as $child)
			if (($cat['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"".$REL_SEO->make_link('browse','cat',$child['id'])."\">".makesafe($child['name'])."</a>";
			$resvalue['cat_names'] = get_cur_position_str($tree,$cat['id']).(is_array($chsel)?', '.implode(', ',$chsel):'');
		}  else $resvalue['cat_names'] = get_cur_position_str($tree,$cat['id']);
		$resarray[$resvalue['id']] = $resvalue;

	}

	if (!$resarray) {stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('not_releases'),'error'); stdfoot(); die(); }


	print("<tr><td class=\"index\" colspan=\"9\">");
	print($pagertop);
	print("</td></tr>");
	torrenttable($resarray, "mytorrents");

	print("<tr><td class=\"index\" colspan=\"9\">");
	print($pagerbottom);
	print("</td></tr>");

	print("</table></div>");

}

stdfoot();

?>