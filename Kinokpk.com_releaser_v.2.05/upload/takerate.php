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

function bark($msg) {
	genbark($msg, "Rating failed!");
}

if (!isset($CURUSER))
	bark("Must be logged in to vote");

if (!mkglobal("rating:id"))
	bark("missing form data");

$id = 0 + $id;
if (!$id)
	bark("invalid id");

$rating = 0 + $rating;
if ($rating <= 0 || $rating > 5)
	bark("invalid rating");

$res = sql_query("SELECT owner FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
	bark("no such torrent");

//if ($row["owner"] == $CURUSER["id"])
//	bark("You can't vote on your own torrents.");

$res = sql_query("INSERT INTO ratings (torrent, user, rating, added) VALUES ($id, " . $CURUSER["id"] . ", $rating, NOW())");
if (!$res) {
	if (mysql_errno() == 1062)
		bark("You have already rated this torrent.");
	else
		bark(mysql_error());
}

sql_query("UPDATE torrents SET numratings = numratings + 1, ratingsum = ratingsum + $rating WHERE id = $id");

    sql_query("UPDATE users SET bonus=bonus+5 WHERE id =".$CURUSER['id']);
    
if ($_GET['returnto'] <> '') header("Refresh: 1; url=".$_GET['returnto'].""); else
header("Refresh: 1; url=details.php?id=$id&rated=1");

?>