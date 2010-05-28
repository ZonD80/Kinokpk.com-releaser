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
loggedinorreturn();

function bark($msg, $error = true) {
global $tracker_lang;
stdhead(($error ? $tracker_lang['error'] : $tracker_lang['torrent']." ".$tracker_lang['bookmarked']));
   stdmsg(($error ? $tracker_lang['error'] : $tracker_lang['success']), $msg, ($error ? 'error' : 'success'));
stdfoot();
exit;
}

$id = (int) $_GET["torrent"];

if (!isset($id))
       bark($tracker_lang['torrent_not_selected']);

$res = sql_query("SELECT name FROM torrents WHERE id = $id") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_array($res);

if ((get_row_count("bookmarks", "WHERE userid = $CURUSER[id] AND torrentid = $id")) > 0)
       bark($tracker_lang['torrent']." \"".$arr['name']."\"".$tracker_lang['already_bookmarked']);

sql_query("INSERT INTO bookmarks (userid, torrentid) VALUES ($CURUSER[id], $id)") or sqlerr(__FILE__,__LINE__);

header("Refresh: 3; url=browse.php");
bark($tracker_lang['torrent']." \"".$arr['name']."\"".$tracker_lang['bookmarked'],false);

?>