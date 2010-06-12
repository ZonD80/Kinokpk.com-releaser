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

require_once("include/bittorrent.php");

dbconn();
getlang('mytorrents');
loggedinorreturn();


stdhead($tracker_lang['my_releases']);

$where = "WHERE owner = " . $CURUSER["id"] . " AND banned=0";
$res = sql_query("SELECT SUM(1) FROM torrents $where");
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$count) {
	stdmsg($tracker_lang['error'], $tracker_lang['not_releases'],'error');
	stdfoot();
	die();
}
else {
	print('<div id="releases-table">');

	print('<table class="embedded" cellspacing="0" cellpadding="3" width="100%">
<tr><td class="colhead" align="center" colspan="9">'.$tracker_lang['my_releases'].'</td></tr>');


	list($pagertop, $pagerbottom, $limit) = pager(20, $count, "mytorrents.php?");
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
			if (($cat['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"browse.php?cat={$child['id']}\">".makesafe($child['name'])."</a>";
			$resvalue['cat_names'] = get_cur_position_str($tree,$cat['id']).(is_array($chsel)?', '.implode(', ',$chsel):'');
		}  else $resvalue['cat_names'] = get_cur_position_str($tree,$cat['id']);
		$resarray[$resvalue['id']] = $resvalue;

	}

	if (!$resarray) {stdmsg($tracker_lang['error'], $tracker_lang['not_releases'],'error'); stdfoot(); die(); }


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