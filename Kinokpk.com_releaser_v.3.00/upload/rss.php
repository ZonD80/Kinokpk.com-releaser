<?php
/**
 * Rss 2.0 feed
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
dbconn(false);

include(ROOT_PATH.'classes/rssatom/rssatom.php');
$feeds=new FeedGenerator;
$CACHEARRAY['sitename'] = htmlspecialchars(iconv("windows-1251","utf-8",$CACHEARRAY['sitename']));
$feeds->setGenerator(new RssGenerator); # or AtomGenerator
$feeds->setAuthor($CACHEARRAY['adminemail']." (Site Admin)");
$feeds->setTitle($CACHEARRAY['sitename']);
$feeds->setChannelLink($CACHEARRAY['defaultbaseurl']."/rss.php");
$feeds->setLink($CACHEARRAY['defaultbaseurl']);
$feeds->setDescription($CACHEARRAY['sitename'].iconv("windows-1251","utf-8"," - новости RSS 2.0"));
$feeds->setID($CACHEARRAY['defaultbaseurl']."/rss.php");

$res = sql_query("SELECT torrents.id,torrents.name,torrents.descr,torrents.images, torrents.relgroup AS rgid, relgroups.name AS rgname, relgroups.image AS rgimage, IF((torrents.relgroup=0) OR (relgroups.private=0),1,0) AS relgroup_allowed FROM torrents LEFT JOIN relgroups ON torrents.relgroup=relgroups.id ORDER BY torrents.added DESC LIMIT 5");

while ($row = mysql_fetch_assoc($res)) {
	if ((!$row['relgroup_allowed'] && $row['rgid'])) {
		$row['name'] = $tracker_lang['relgroup_release'].'&nbsp;'.$rgcontent;
		$row['descr'] = $tracker_lang['relgroup_release'];
		$row['images'] = 'pic/privaterg.gif';
	}
	while (preg_match("#\[spoiler(.+?)\](.*?)\[/spoiler\]#si", $descr)) preg_replace("#\[spoiler(.+?)\](.*?)\[/spoiler\]#si",'',$descr);
	$items=true;
	if ($row['images']) $image = array_shift(explode(",",$row['images'])); else $image='pic/noimage.gif';
	$content='<table width="100%" border="1"><tr><td valign="top"><img src="'.$image.'" width="100" title="'.makesafe($row['name']).'"></td><td>'.format_comment($row['descr']).'</td></tr></table>';
	$feeds->addItem(new FeedItem($CACHEARRAY['defaultbaseurl']."/details.php?id={$row['id']}", iconv("windows-1251","utf-8",$row['name']), $CACHEARRAY['defaultbaseurl']."/details.php?id={$row['id']}", iconv("windows-1251","utf-8",$content)));

}
if (!$items)
$feeds->addItem(new FeedItem($CACHEARRAY['defaultbaseurl'], iconv("windows-1251","utf-8",$tracker_lang['error']), $CACHEARRAY['defaultbaseurl'], iconv("windows-1251","utf-8",$tracker_lang['no_torrents'])));

$feeds->display();
?>