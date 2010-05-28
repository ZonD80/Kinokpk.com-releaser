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

if (isset($_GET['ajax'])  && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
	header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);
	$ajax = 1;
}
else $ajax=0;

$where = "WHERE owner = " . $CURUSER["id"] . " AND banned=0";
$res = sql_query("SELECT COUNT(*) FROM torrents $where");
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$ajax) stdhead($tracker_lang['my_releases']);

if (!$count) {
	stdmsg($tracker_lang['error'], $tracker_lang['not_releases'],'error');
	if (!$ajax) stdfoot();
	die();
}
else {
	print('<div id="releases-table">');

	print('<table class="embedded" cellspacing="0" cellpadding="3" width="100%">
<tr><td class="colhead" align="center" colspan="9">'.$tracker_lang['my_releases'].'</td></tr>');

	if (!$ajax) print('<script type="text/javascript">

var no_ajax = true;
var switched=0;
function pageswitcher(page) {

   (function($){
     if ($) no_ajax = false;
   $("#releases-table").empty();
   $("#releases-table").append(\'<div align="center"><img src="pic/loading.gif" border="0"/></div>\');
   $.get("mytorrents.php", { ajax: 1, page: page }, function(data){
   $("#releases-table").empty();
   $("#releases-table").append(data);

});
})(jQuery);

if (!switched){
window.location.href = window.location.href+"#releases-table";
switched++;
}
else window.location.href = window.location.href;

return no_ajax;
}
</script>');


	list($pagertop, $pagerbottom, $limit) = browsepager(20, $count, "mytorrents.php?" , "#releases-table");
	$tree = make_tree();
	$res = sql_query("SELECT torrents.images, torrents.comments, torrents.leechers+torrents.remote_leechers AS leechers, torrents.seeders+torrents.remote_seeders AS seeders, torrents.id, torrents.name, filename, numfiles, added, size, views, visible, free, hits, times_completed, category FROM torrents $where ORDER BY id DESC $limit");
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

	if (!$resarray) {stdmsg($tracker_lang['error'], $tracker_lang['not_releases'],'error'); if(!$ajax) stdfoot(); die(); }


	print("<tr><td class=\"index\" colspan=\"9\">");
	print($pagertop);
	print("</td></tr>");
	torrenttable($resarray, "mytorrents");

	print("<tr><td class=\"index\" colspan=\"9\">");
	print($pagerbottom);
	print("</td></tr>");

	print("</table></div>");
	if ($ajax ) die();

}

stdfoot();

?>