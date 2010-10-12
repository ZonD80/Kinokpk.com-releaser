<?php
require_once('include/bittorrent.php');
dbconn();
$res = sql_query("SELECT bid,which FROM orbital_blocks WHERE which LIKE '%ihome%'");
while ($row = mysql_fetch_assoc($res)) {
sql_query("UPDATE orbital_blocks SET which=".sqlesc(str_replace('ihome','index',$row['which']))." WHERE bid={$row['bid']}");
print "block {$row['bid']} done<br/>";
}
$REL_CACHE->clearGroupCache('blocks');
print 'block cache cleared<br/>';
?>