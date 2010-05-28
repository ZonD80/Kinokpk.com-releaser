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
getlang('takerate');
function bark($msg) {
	genbark($msg, $tracker_lang['rating_failed']);
}

if (!isset($CURUSER))
bark($tracker_lang['must_logged']);

if (!is_valid_id($_POST['id'])) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);


$id = 0 + $_POST['id'];

if (!is_valid_id($_POST['rating'])) stderr($tracker_lang['error'],$tracker_lang['number_rate_wrong']);
$rating = 0 + $_POST['rating'];
if ($rating <= 0 || $rating > 5)
stderr($tracker_lang['error'],$tracker_lang['number_rate_wrong']);

$res = sql_query("SELECT owner FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
bark($tracker_lang['no_such_torrent']);

//if ($row["owner"] == $CURUSER["id"])
//	bark("You can't vote on your own torrents.");

$res = sql_query("INSERT INTO ratings (torrent, user, rating, added) VALUES ($id, " . $CURUSER["id"] . ", $rating, NOW())");
if (!$res) {
	if (mysql_errno() == 1062)
	bark($tracker_lang['you_rated_torrent']);
	else
	bark(mysql_error());
}

sql_query("UPDATE torrents SET numratings = numratings + 1, ratingsum = ratingsum + $rating WHERE id = $id");

sql_query("UPDATE users SET bonus=bonus+5 WHERE id =".$CURUSER['id']);

if ($_GET['returnto'] <> '') header("Location: ".htmlspecialchars($_GET['returnto'])); else
header("Location: details.php?id=$id&rated=1");

?>