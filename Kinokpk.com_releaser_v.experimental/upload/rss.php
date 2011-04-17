<?php
/**
 * RSS 2.0 Feed
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT(false);

include(ROOT_PATH.'classes/rssatom/rssatom.php');
$feeds=new FeedGenerator;
$REL_CONFIG['sitename'] = htmlspecialchars($REL_CONFIG['sitename']);
$feeds->setGenerator(new RssGenerator); # or AtomGenerator
$feeds->setAuthor($REL_CONFIG['adminemail']." (Site Admin)");
$feeds->setTitle($REL_CONFIG['sitename']);
$feeds->setChannelLink($REL_CONFIG['defaultbaseurl']."/rss.php");
$feeds->setLink($REL_CONFIG['defaultbaseurl']);
$feeds->setDescription($REL_CONFIG['sitename'].$REL_LANG->_("News"));
$feeds->setID($REL_CONFIG['defaultbaseurl']."/rss.php");

$res = sql_query("SELECT torrents.id,torrents.name,torrents.descr,torrents.images, torrents.relgroup AS rgid, relgroups.name AS rgname, relgroups.image AS rgimage, IF((torrents.relgroup=0) OR (relgroups.private=0),1,0) AS relgroup_allowed FROM torrents LEFT JOIN relgroups ON torrents.relgroup=relgroups.id WHERE visible=1 AND banned=0 AND moderatedby<>0 ORDER BY torrents.added DESC LIMIT 5");

while ($row = mysql_fetch_assoc($res)) {
	if ((!$row['relgroup_allowed'] && $row['rgid'])) {
		$row['name'] = $REL_LANG->say_by_key('relgroup_release').'&nbsp;'.$rgcontent;
		$row['descr'] = $REL_LANG->say_by_key('relgroup_release');
		$row['images'] = 'pic/privaterg.gif';
	}
	while (preg_match("#\[spoiler(.+?)\](.*?)\[/spoiler\]#si", $descr)) preg_replace("#\[spoiler(.+?)\](.*?)\[/spoiler\]#si",'',$descr);
	$items=true;
	if ($row['images']) $image = array_shift(explode(",",$row['images'])); else $image='pic/noimage.gif';
	$content='<table width="100%" border="1"><tr><td valign="top"><img src="'.$image.'" width="100" title="'.makesafe($row['name']).'"></td><td>'.format_comment($row['descr'],true).'</td></tr></table>';
	$feeds->addItem(new FeedItem(htmlspecialchars($REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name']))), $row['name'], htmlspecialchars($REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name']))), $content));

}
if (!$items)
$feeds->addItem(new FeedItem($REL_CONFIG['defaultbaseurl'], $REL_LANG->say_by_key('error'), $REL_CONFIG['defaultbaseurl'], $REL_LANG->say_by_key('no_torrents')));

$feeds->display();
?>