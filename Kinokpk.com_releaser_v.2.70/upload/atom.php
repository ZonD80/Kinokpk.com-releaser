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


include(ROOT_PATH.'classes/rssatom/rssatom.php');
$feeds=new FeedGenerator;
$feeds->setGenerator(new AtomGenerator); # or AtomGenerator
$feeds->setAuthor($CACHEARRAY['adminemail']." (Site Admin)");
$feeds->setTitle($CACHEARRAY['sitename']);
$feeds->setChannelLink($CACHEARRAY['defaultbaseurl']."/atom.php");
$feeds->setLink($CACHEARRAY['defaultbaseurl']);
$feeds->setDescription($CACHEARRAY['sitename']." - новости ATOM 1.0");
$feeds->setID($CACHEARRAY['defaultbaseurl']."/atom.php");

$res = sql_query("SELECT id,name,descr,images FROM torrents ORDER BY added DESC LIMIT 5");

while ($row = mysql_fetch_assoc($res)) {
	$items=true;
	if ($row['images']) $image = array_shift(explode(",",$row['images'])); else $image='pic/noimage.gif';
	$content='<table width="100%" border="1"><tr><td valign="top"><img src="'.$image.'" width="100" title="'.makesafe($row['name']).'"></td><td>'.format_comment($row['descr']).'</td></tr></table>';
	$feeds->addItem(new FeedItem($CACHEARRAY['defaultbaseurl']."/details.php?id={$row['id']}",$row['name'], $CACHEARRAY['defaultbaseurl']."/details.php?id={$row['id']}", $content));

}
if (!$items)
$feeds->addItem(new FeedItem($CACHEARRAY['defaultbaseurl'], $tracker_lang['error'], $CACHEARRAY['defaultbaseurl'], $tracker_lang['no_torrents']));

$feeds->display();
?>