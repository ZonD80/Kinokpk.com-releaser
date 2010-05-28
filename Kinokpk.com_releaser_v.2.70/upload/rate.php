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
require_once("include/bittorrent.php");

dbconn();

if (isset($_GET['ajax']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
	$ajax = 1;
	header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);

} else $ajax=0;
$rid = (int)$_GET['id'];
$type = (string)$_GET['type'];


$allowed_types = array('torrents','users','comments','pollcomments','newscomments','usercomments','reqcomments');


if ($_GET['act']=='up') $act='+1'; else $act='-1';

if (!in_array($type,$allowed_types)) $invalid=true;

if (!$rid || !$type || $invalid) die($tracker_lang['invalid_id']);

$voted = @mysql_result(sql_query("SELECT id FROM ratings WHERE userid={$CURUSER['id']} AND rid=$rid AND type='$type'"),0);
if (!$voted) {
	sql_query("INSERT INTO ratings (rid,userid,type,added) VALUES ($rid,{$CURUSER['id']},'$type',".time().")");
	sql_query("UPDATE $type SET ratingsum=ratingsum$act WHERE id=$rid");

	if ($ajax) die($tracker_lang['voted']); else
	{
		header('Refresh: 2; url='.htmlspecialchars($_SERVER['HTTP_REFERER']));

		stderr($tracker_lang['rating'],$tracker_lang['voted'],'success');
	}
} else {

	if ($ajax) die($tracker_lang['already_rated']); else
	{
		header('Refresh: 2; url='.htmlspecialchars($_SERVER['HTTP_REFERER']));

		stderr($tracker_lang['error'],$tracker_lang['already_rated']);
	}
}

