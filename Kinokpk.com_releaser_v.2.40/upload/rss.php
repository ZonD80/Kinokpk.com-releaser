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
dbconn(false);


$CACHEARRAY['sitename'] = iconv("CP1251", "UTF-8",$CACHEARRAY['sitename']);
include('classes/rssatom/rssatom.php');
$feeds=new FeedGenerator;
$feeds->setGenerator(new RssGenerator); # or AtomGenerator
$feeds->setAuthor($CACHEARRAY['adminemail']." (Site Admin)");
$feeds->setTitle($CACHEARRAY['sitename']);
$feeds->setChannelLink($CACHEARRAY['defaultbaseurl']."/rss.php");
$feeds->setLink($CACHEARRAY['defaultbaseurl']);
$feeds->setDescription($CACHEARRAY['sitename'].iconv("CP1251", "UTF-8"," - новости RSS 2.0"));
$feeds->setID($CACHEARRAY['defaultbaseurl']."/rss.php");

$idsrow = sql_query("SELECT id FROM torrents ORDER BY added DESC LIMIT 5");
while (list($id) = mysql_fetch_array($idsrow))
$ids[] = $id;


if (!is_array($ids)) $feeds->addItem(new FeedItem($CACHEARRAY['defaultbaseurl'], iconv("CP1251", "UTF-8","Релизов нету!"), $DEFAULTBASEUR, iconv("CP1251", "UTF-8","Нет релизов"))); else {

	foreach ($ids as $id) {
		$peers[$id] = array();
		$dd[$id] = array();
	}

	$ids = implode(",",$ids);

	if (!defined("CACHE_REQUIRED")){
		require_once(ROOT_PATH . 'classes/cache/cache.class.php');
		require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
		define("CACHE_REQUIRED",1);
	}
	$cache=new Cache();
	$cache->addDriver('file', new FileCacheDriver());

	$reldata = $cache->get('block-indextorrents', 'query');
	if($reldata===false){
		$reldata = array();
		$res = sql_query("SELECT torrents.*, categories.id AS catid, categories.name AS catname, categories.image AS catimage, users.username, descr_torrents.value, descr_details.name AS dname, descr_details.input FROM torrents LEFT JOIN users ON torrents.owner = users.id LEFT JOIN categories ON torrents.category = categories.id LEFT JOIN descr_torrents ON torrents.id = descr_torrents.torrent LEFT JOIN descr_details ON descr_details.id = descr_torrents.typeid  WHERE banned = 'no' AND visible = 'yes' AND torrents.id IN ($ids) AND descr_details.mainpage = 'yes' ORDER BY torrents.added DESC, descr_details.sort ASC");
		 
		while ($relarray = mysql_fetch_array($res))
		$reldata[] = $relarray;

		$cache->set('block-indextorrents', 'query', $reldata);

	}

	// Now fucking my brain...
	foreach ($reldata as $release) {
		$namear[$release['id']] = $release['name'];
		$filenamear[$release['id']] = $release['filename'];
		$imagesar[$release['id']] = explode(",",$release['images']);
		$cat[$release['id']] = array('id'=>$release['catid'],'name' => $release['catname'],'img'=>$release['catimage']);
		$usernamear[$release['id']] = $release['username'];
		$ownerar[$release['id']] = $release['owner'];
		$sizear[$release['id']] = $release['size'];
		$addedar[$release['id']] = $release['added'];
		$commentsar[$release['id']] = $release['comments'];

		$tagsar[$release['id']] = $release['tags'];
		array_push($dd[$release['id']],array('name'=>$release['dname'],'value'=>$release['value'])); //'input'=>$release['input']));

	}
	//print_r($dd);

	foreach ($namear as $id => $tname) {
		 
		$content = '';
		 
		$filename = $filenamear[$id];
		$torid = $id;
		$catid = $cat[$id]['id'];
		$catname = $cat[$id]['name'];
		$catimage = $cat[$id]['img'];
		$torname = $tname;
		$descr = '<table width="100%" border="0">';

		$tags = '';
		foreach(explode(",", $tagsar[$id]) as $tag)
		$tags .= "<a href=\"browse.php?tag=".rawurlencode($tag)."\">".$tag."</a>, ";

		if ($tags)
		$tags = substr($tags, 0, -2);

		$descr .= "<tr><td valign=\"top\"><b>Жанр:</b></td><td>".$tags."</td></tr>";

		foreach ($dd[$id] as $dddescr)
		if ($dddescr['value'] != '') $descr .= "<tr><td valign=\"top\"><b>".$dddescr['name'].":</b></td><td>".format_comment($dddescr['value'])."</td></tr>";
		 
		$descr .="</table>";

		$uprow = (isset($usernamear[$id]) ? ("<a href=userdetails.php?id=" . $ownerar[$id] . ">" . htmlspecialchars($usernamear[$id]) . "</a>") : "<i>Аноним</i>");

		$content .= "<table width=\"100%\" class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">";
		$content .= "<tr>";
		$content .= "<td class=\"colhead\" colspan=\"2\" align=center>";
		$content .= $namear[$id];
		$content .= "<a class=\"altlink_white\" href=\"bookmark.php?torrent=$id\">   ";
		$content .= "</font></td>";
		$content .= "</tr>";

		$img1 = array_shift($imagesar[$id]);

		if ($img1 != NULL)
		$img1 = "<img width=\"160\" border='0' src=\"{$CACHEARRAY['defaultbaseurl']}/thumbnail.php?image=$img1&for=rss\" />"; else $img1 = "<img src=\"{$CACHEARRAY['defaultbaseurl']}/pic/noimage.gif\" width=\"160\" border=\"0\" />";
		$content .= "<tr valign=\"top\"><td align=\"center\" width=\"160\">";
		$content .= $img1;
		$img2 = array_shift($imagesar[$id]);

		if ($img2 != NULL) {
			$img2 = "<img width=\"160\" border='0' src=\"{$CACHEARRAY['defaultbaseurl']}/thumbnail.php?image=$img2&for=rss\" />";
			$content .= "<br /><br />$img2"; }
			$content .= "</td>";
			$content .= "<td><div align=\"left\">".$descr."</div>
            <table width=\"100%\"><tr><td>
            <hr />
            <b>Выложил: </b>$usernamear[$id]<br>
            <b>Размер: </b>".mksize($sizear[$id])."<br>";

			$content .= "<b>Добавлен: </b>$addedar[$id]
            <hr />
                        <b>Коментарии: </b>$commentsar[$id]</b><br></td><td>
            <div align=\"right\">".(!empty($catname) ? "<img src=\"{$CACHEARRAY['defaultbaseurl']}/pic/cats/$catimage\" alt=\"$catname\" title=\"$catname\" border=\"0\" />" : "")."<br/>
            [<a href=\"details.php?id=$id\" alt=\"$namear[$id]\" title=\"$namear[$id]\"><b>Просмотреть</b></a>] [<a href=\"browse.php\">Полный список релизов</a>]</div></td></table></td>";
			$content .= "</tr>";
			$content .= "</table>";
			//        print_r ($cat);
			 
			$content = str_replace('<div style="position: static;" class="news-wrap"><div class="news-head folded clickable">','',$content);
			$content = str_replace('</div><div style="display: none;" class="news-body">','',$content);
			$content = str_replace('</div></div>','',$content);
			 
			$feeds->addItem(new FeedItem($CACHEARRAY['defaultbaseurl']."/details.php?id=$id", iconv("CP1251", "UTF-8",$namear[$id]), $CACHEARRAY['defaultbaseurl']."/details.php?id=$id", iconv("CP1251", "UTF-8",$content)));

	}

}
$feeds->display();
?>
