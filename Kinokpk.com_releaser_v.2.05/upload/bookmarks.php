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

stdhead($tracker_lank['bookmarks']);

$res = sql_query("SELECT COUNT(id) FROM bookmarks WHERE userid = ".sqlesc($CURUSER["id"]));
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$count) {
	stdmsg($tracker_lang['error'], $tracker_lang['you_have_no_bookmarks'], 'error');
} else {
?>
<table class="embedded" cellspacing="0" cellpadding="5" width="100%">
<tr><td class="colhead" align="center" colspan="12">Список закладок</td></tr>
<?

$perpage = 25;

list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "bookmarks.php?");

$res = sql_query("SELECT bookmarks.id AS bookmarkid, users.username, users.class, users.id AS owner, torrents.id, torrents.name, torrents.type, torrents.comments, torrents.leechers, torrents.seeders, IF(torrents.numratings < $minvotes, NULL, ROUND(torrents.ratingsum / torrents.numratings)) AS rating, categories.name AS cat_name, categories.image AS cat_pic, torrents.save_as, torrents.numfiles, torrents.added, torrents.filename, torrents.size, torrents.views, torrents.visible, torrents.free, torrents.hits, torrents.times_completed, torrents.category FROM bookmarks INNER JOIN torrents ON bookmarks.torrentid = torrents.id LEFT JOIN users ON torrents.owner = users.id LEFT JOIN categories ON torrents.category = categories.id WHERE bookmarks.userid = ".sqlesc($CURUSER["id"])." ORDER BY torrents.id DESC $limit") or sqlerr(__FILE__, __LINE__);

print("<tr><td class=\"index\" colspan=\"12\">");
print($pagertop);
print("</td></tr>");
$returnto = "bookmarks&amp;page=".$_GET['page']."&amp;cat=".$_GET['cat'];

torrenttable($res, "bookmarks", $returnto);
print("<tr><td class=\"index\" colspan=\"12\">");
print($pagerbottom);
print("</td></tr>");
print("</table>");
}

stdfoot();

?>