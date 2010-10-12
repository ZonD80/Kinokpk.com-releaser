<?php
require_once('include/bittorrent.php');
dbconn();
$cats = $REL_SEO->assoc_cats();

$res = sql_query("SELECT id FROM categories");
while (list($id)=mysql_fetch_array($res)) {
print 'rewrite ^/'.$cats[$id].'/$ /browse.php?cat='.$id.' last;<br/>';
}
?>