<?php
#
#    GOOGLE SITEMAP CREATION
#        by n-sw-bit
#
#
gensitemap();


function gensitemap(){
  global $CACHEARRAY;
$txt = '<?xml version="1.0" encoding="UTF-8"?>
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
    return date('c',strtotime($t));
}

#
#    GOOGLE SITEMAP CREATION
#        by n-sw-bit
#
#
?>