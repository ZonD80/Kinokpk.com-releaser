<?php
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

require "include/bittorrent.php";
dbconn();
getlang('takean');
if ((@strpos($_SERVER['HTTP_REFERER'],"edit.php") === false) || !is_numeric($_GET['id'])) die ($tracker_lang['wrong_id']);
$id = $_GET['id'];
$curowner = sql_query("SELECT owner FROM torrents WHERE id = ".$id);
$curowner = mysql_result($curowner,0);
if ($curowner != 0) {
	sql_query("UPDATE torrents SET owner=0, orig_owner = ".$curowner." WHERE id = ".$id);

	$CACHE->clearGroupCache('block-indextorrents');
	print('<html>
<head>
<title>'.$tracker_lang['anonymous_release'].'</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
</head>
<body>
'.$tracker_lang['release_anonymous'].'<br />
[<a href="javascript:self.close()">'.$tracker_lang['close_window'].'</a>]
</body>
</html>');
}
elseif ($curowner == 0) {
	$origowner = sql_query("SELECT torrents.orig_owner AS id, users.username  FROM torrents LEFT JOIN users ON torrents.orig_owner = users.id WHERE torrents.id =".$id);
	$origowner = mysql_fetch_array($origowner);
	sql_query("UPDATE torrents SET owner = ".$origowner['id'].", orig_owner = 0 WHERE id = ".$id);

	$CACHE->clearGroupCache('block-indextorrents');
	print('<html>
<head>
<title>'.$tracker_lang['make_anonymous'].'</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
</head>
<body>
'.$tracker_lang['owner_release'].' '.$origowner['username'].' '.$tracker_lang['restored'].'<br />
[<a href="javascript:self.close()">'.$tracker_lang['close_window'].'</a>]
</body>
</html>');
}
?>
