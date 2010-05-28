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

stdhead("Мои релизы");

$where = "WHERE owner = " . $CURUSER["id"] . " AND banned != 'yes'";
$res = sql_query("SELECT COUNT(*) FROM torrents $where");
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$count) {
	stdmsg($tracker_lang['error'], "Вы не загружали релизы на этот трекер.");
	stdfoot();
	die();
}
else {
?>
<table class="embedded" cellspacing="0" cellpadding="3" width="100%">
<tr><td class="colhead" align="center" colspan="12">Мои релизы</td></tr>
<?

	list($pagertop, $pagerbottom, $limit) = pager(20, $count, "mytorrents.php?");

	$res = sql_query("SELECT torrents.type, torrents.comments, torrents.leechers, torrents.seeders, IF(torrents.numratings < $minvotes, NULL, ROUND(torrents.ratingsum / torrents.numratings, 1)) AS rating, torrents.id, categories.name AS cat_name, categories.image AS cat_pic, torrents.name, save_as, filename, numfiles, added, size, views, visible, free, hits, times_completed, category FROM torrents LEFT JOIN categories ON torrents.category = categories.id $where ORDER BY id DESC $limit");

	print("<tr><td class=\"index\" colspan=\"12\">");
	print($pagertop);
	print("</td></tr>");
  $returnto = "mytorrents&amp;page=".$_GET['page']."&amp;cat=".$_GET['cat'];
	torrenttable($res, "mytorrents", $returnto);

	print("<tr><td class=\"index\" colspan=\"12\">");
	print($pagerbottom);
	print("</td></tr>");

	print("</table>");

}

stdfoot();

?>