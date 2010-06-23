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
function bark($msg) {
	stdhead();
	stdmsg($REL_LANG->say_by_key('error'), $msg);
	stdfoot();
	exit;
}
dbconn();
$REL_LANG->load('takedelbookmark');
loggedinorreturn();

if (!isset($_POST[delbookmark]))
bark($REL_LANG->say_by_key('no_selection'));

$res2 = sql_query("SELECT id, userid FROM bookmarks WHERE id IN (" . implode(", ", array_map("sqlesc", $_POST[delbookmark])) . ")") or sqlerr(__FILE__, __LINE__);

while ($arr = mysql_fetch_assoc($res2)) {
	if (($arr[userid] == $CURUSER[id]) || (get_user_class() > 3))
	sql_query("DELETE FROM bookmarks WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
	else
	bark($REL_LANG->say_by_key('not_try_remove'));
}

header("Refresh: 0; url=" . $_SERVER['HTTP_REFERER']);
?>