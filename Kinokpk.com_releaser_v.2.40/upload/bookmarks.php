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

dbconn(false);

loggedinorreturn();

if (isset($_GET['ajax'])  && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
	header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);
	$ajax = 1;
}
else $ajax=0;

if (!$ajax) stdhead($tracker_lang['bookmarks']);

$res = sql_query("SELECT COUNT(id) FROM bookmarks WHERE userid = ".sqlesc($CURUSER["id"]));
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$count) {
	stdmsg($tracker_lang['error'], $tracker_lang['you_have_no_bookmarks'],'error');
	stdfoot();
	die();
} else {

	if (!$ajax) 		print ("<form method=\"post\" action=\"takedelbookmark.php\"/>");

	print('<div id="releases-table">');
	print('<table class="embedded" cellspacing="0" cellpadding="5" width="100%"><tr><td class="colhead" align="center" colspan="9">Список закладок</td></tr>');
	if (!$ajax) print('<script type="text/javascript">

var no_ajax = true;

function pageswitcher(page) {

   (function($){
     if ($) no_ajax = false;
   $("#releases-table").empty();
   $("#releases-table").append(\'<div align="center"><img src="pic/loading.gif" border="0"/></div>\');
   $.get("bookmarks.php", { ajax: "", page: page }, function(data){
   $("#releases-table").empty();
   $("#releases-table").append(data);

});
})(jQuery);

window.location.href = "#releases-table";

return no_ajax;
}
</script>');

	$perpage = 25;

	list($pagertop, $pagerbottom, $limit) = browsepager($perpage, $count, "bookmarks.php?" , "#releases-table");

	$res = sql_query("SELECT bookmarks.id AS bookmarkid, users.username, users.class, users.id AS owner, torrents.id, torrents.name, torrents.type, torrents.comments, torrents.leechers, torrents.seeders, torrents.images, IF(torrents.numratings < {$CACHEARRAY['minvotes']}, NULL, ROUND(torrents.ratingsum / torrents.numratings)) AS rating, categories.name AS cat_name, categories.image AS cat_pic, torrents.save_as, torrents.numfiles, torrents.added, torrents.filename, torrents.size, torrents.views, torrents.visible, torrents.free, torrents.hits, torrents.times_completed, torrents.category FROM bookmarks INNER JOIN torrents ON bookmarks.torrentid = torrents.id LEFT JOIN users ON torrents.owner = users.id LEFT JOIN categories ON torrents.category = categories.id WHERE bookmarks.userid = ".sqlesc($CURUSER["id"])." ORDER BY torrents.id DESC $limit") or sqlerr(__FILE__, __LINE__);

	$resarray = array();
	while ($resvalue = mysql_fetch_array($res)) {
		$resarray[] = $resvalue;
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
	if ($ajax) die();
	print("<div align=\"right\"><input type=\"submit\" OnClick=\"return confirm('Вы уверены?');\" value=\"".$tracker_lang['delete']."\"/></div></form>\n");

}

stdfoot();

?>