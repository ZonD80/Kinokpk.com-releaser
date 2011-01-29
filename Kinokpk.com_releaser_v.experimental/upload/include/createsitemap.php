<?php
/**
 * Sitemap generator
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

if (!defined("IN_TRACKER")) die('Direct access to this file not allowed.');
gensitemap();


function gensitemap(){
	global $REL_CONFIG, $REL_SEO;
	$txt = '<?xml version="1.0" encoding="utf-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

	$txt .='
<url><loc>'.$REL_SEO->make_link('browse').'</loc><lastmod>'.t().'</lastmod><changefreq>hourly</changefreq><priority>1</priority></url>
<url><loc>'.$REL_CONFIG['defaultbaseurl'].'/</loc><lastmod>'.t().'</lastmod><changefreq>hourly</changefreq><priority>1</priority></url>
';

	$sql = sql_query("SELECT id,name,added FROM torrents ORDER BY id DESC LIMIT 300");
	while($a = mysql_fetch_assoc($sql)){
		$txt .='
<url><loc>'.htmlspecialchars($REL_SEO->make_link('details','id',$a['id'],'name',translit($a['name']))).'</loc><lastmod>'.t($a['added']).'</lastmod><changefreq>daily</changefreq><priority>0.50</priority></url>';
	}

	$sql = sql_query("SELECT id FROM categories");
	while($a = mysql_fetch_assoc($sql)){
		$txt .='
<url><loc>'.htmlspecialchars($REL_SEO->make_link('browse','cat',$a['id'])).'</loc><lastmod>'.t().'</lastmod><changefreq>hourly</changefreq><priority>0.50</priority></url>';
	}

	$txt .='
</urlset>

';

	@file_put_contents(ROOT_PATH."/Sitemap.xml",$txt) or stderr($REL_LANG->_('Error'),$REL_LANG->_('Unable to write Sitemap.xml file'));
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