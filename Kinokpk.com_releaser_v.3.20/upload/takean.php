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
$REL_LANG->load('takean');
if ((@strpos($_SERVER['HTTP_REFERER'],"edit.php") === false) || !is_numeric($_GET['id'])) die ($REL_LANG->say_by_key('wrong_id'));
$id = $_GET['id'];
$curowner = sql_query("SELECT owner FROM torrents WHERE id = ".$id);
$curowner = mysql_result($curowner,0);
if ($curowner != 0) {
	sql_query("UPDATE torrents SET owner=0, orig_owner = ".$curowner." WHERE id = ".$id);

	$clearcache = array('block-indextorrents','browse-normal','browse-tags','browse-cat');

	foreach ($clearcache as $cachevalue)
	$REL_CACHE->clearGroupCache($cachevalue);
	print('<html>
<head>
<title>'.$REL_LANG->say_by_key('anonymous_release').'</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
'.$REL_LANG->say_by_key('release_anonymous').'<br />
[<a href="javascript:self.close()">'.$REL_LANG->say_by_key('close_window').'</a>]
</body>
</html>');
}
elseif ($curowner == 0) {
	$origowner = sql_query("SELECT torrents.orig_owner AS id, users.username  FROM torrents LEFT JOIN users ON torrents.orig_owner = users.id WHERE torrents.id =".$id);
	$origowner = mysql_fetch_array($origowner);
	sql_query("UPDATE torrents SET owner = ".$origowner['id'].", orig_owner = 0 WHERE id = ".$id);

	$clearcache = array('block-indextorrents','browse-normal','browse-tags','browse-cat');

	foreach ($clearcache as $cachevalue)
	$REL_CACHE->clearGroupCache($cachevalue);
	print('<html>
<head>
<title>'.$REL_LANG->say_by_key('make_anonymous').'</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
'.$REL_LANG->say_by_key('owner_release').' '.$origowner['username'].' '.$REL_LANG->say_by_key('restored').'<br />
[<a href="javascript:self.close()">'.$REL_LANG->say_by_key('close_window').'</a>]
</body>
</html>');
}
?>
