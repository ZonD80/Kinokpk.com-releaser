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

if (!defined("IN_TRACKER")) die('Direct access to this file not allowed.');
gensitemap();


function gensitemap(){
	global $CACHEARRAY;
	$txt = '<?xml version="1.0" encoding="windows-1251"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

	$txt .='
<url><loc>'.$CACHEARRAY['defaultbaseurl'].'/browse.php</loc><lastmod>'.t().'</lastmod><changefreq>hourly</changefreq><priority>1</priority></url>
<url><loc>'.$CACHEARRAY['defaultbaseurl'].'/</loc><lastmod>'.t().'</lastmod><changefreq>hourly</changefreq><priority>1</priority></url>
';

	$sql = sql_query("SELECT id,added FROM torrents ORDER BY id DESC LIMIT 300");
	while($a = mysql_fetch_assoc($sql)){
		$txt .='
<url><loc>'.$CACHEARRAY['defaultbaseurl'].'/details.php?id='.$a['id'].'</loc><lastmod>'.t($a['added']).'</lastmod><changefreq>daily</changefreq><priority>0.50</priority></url>';
	}

	$sql = sql_query("SELECT id FROM categories");
	while($a = mysql_fetch_assoc($sql)){
		$txt .='
<url><loc>'.$CACHEARRAY['defaultbaseurl'].'/browse.php?cat='.$a['id'].'</loc><lastmod>'.t().'</lastmod><changefreq>hourly</changefreq><priority>0.50</priority></url>';
	}

	$txt .='
</urlset>

';

	@file_put_contents(ROOT_PATH."/Sitemap.xml",$txt) or stderr("Ошибка!","Невозможно записать файл!");
}

function t($t=false){
	if(!$t) return date('c'); //2004-02-12T15:19:21+00:00
	return date('c',$t);
}

#
#    GOOGLE SITEMAP CREATION
#        by n-sw-bit
#
#
?>