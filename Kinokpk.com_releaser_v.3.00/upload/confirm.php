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

$md5 = $_GET["secret"];


dbconn();
getlang('confirm');
if (!is_valid_id($_GET["id"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
$id = (int) $_GET["id"];

$res = sql_query("SELECT passhash, editsecret, confirmed, language FROM users WHERE id = $id");
$row = mysql_fetch_array($res);

if (!$row)
stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

if ($row["confirmed"]) {
	safe_redirect(" ok.php?type=confirmed");
	exit();
}

$sec = hash_pad($row["editsecret"]);
if ($md5 != md5($sec))
stderr($tracker_lang['error'],'Secret does not match value');

sql_query("UPDATE users SET confirmed=1, editsecret='' WHERE id=$id AND confirmed=0");

if (!mysql_affected_rows())
stderr($tracker_lang['error'],'Error changing confirm status. Contact site admin please.');


logincookie($id, $row["passhash"],$row['language']);


safe_redirect(" ok.php?type=confirm");

?>