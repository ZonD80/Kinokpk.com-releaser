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
getlang('report');
loggedinorreturn();

if (!is_valid_id($_POST["torrentid"]))
stderr($tracker_lang["error"],$tracker_lang["invalid_id"]);

$torrentid = (int)$_POST["torrentid"];

$userid = $CURUSER["id"];

$motive = htmlspecialchars($_POST["motive"]);

if (!$motive) stderr($tracker_lang['error'],$tracker_lang['empty_motive']);

$reason = sqlesc($motive);
$subject = "'{$tracker_lang['complaint_lodged']}'";
$now = time();
$msg = sqlesc("".$tracker_lang['user']." <b><a href=\"userdetails.php?id=".$CURUSER["id"]."\">".$CURUSER["username"]."</a></b> ".$tracker_lang['complaint_torrent']." <a href=\"details.php?id=".$torrentid."\">$torrentid</a>\n\n".$tracker_lang['reason'].$motive);

$owntorrentquery = sql_query("SELECT NULL FROM torrents WHERE id = '$torrentid' and owner = '$userid'") or sqlerr(__FILE__,__LINE__);

$owntorrentrow = mysql_fetch_object($owntorrentquery);

if($owntorrentrow)
{
	header("Location: details.php?id=$torrentid&ownreport=1");
	die();
}

$alreadythankquery = sql_query("SELECT NULL FROM report WHERE torrentid = '$torrentid' and userid = '$userid'") or sqlerr(__FILE__,__LINE__);
$alreadythankrow = mysql_fetch_object($alreadythankquery);

if (!$alreadythankrow)
{
	sql_query("INSERT INTO report (torrentid, userid, motive, added) VALUES ($torrentid, $userid, $reason, ".time().")") or sqlerr(__FILE__,__LINE__);
	sql_query("INSERT INTO messages (sender, receiver, added, msg, subject, poster) SELECT 0, id, $now, $msg, $subject, 0 FROM users WHERE class >= ".UC_MODERATOR."") or sqlerr(__FILE__,__LINE__);
	header("Location: details.php?id=$torrentid&report=1");
	die();
}
else
{
	header("Location: details.php?id=$torrentid&alreadyreport=1");
	die();
}
?>