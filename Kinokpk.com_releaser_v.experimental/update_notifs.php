<?php
require_once('include/bittorrent.php');
dbconn();
$res = sql_query("SELECT notifs,emailnotifs,id FROM users order by id asc");
while ($row = mysql_fetch_assoc($res)) {
$notifs = explode(',',$row['notifs']);
$emailnotifs = explode(',',$row['emailnotifs']);
foreach ($notifs as $key=>$notify) {
if ($notify=='comments') $notifs[$key]='relcomments';
}
foreach ($emailnotifs as $key=>$notify) {
if ($notify=='comments') $emailnotifs[$key]='relcomments';
}
sql_query("update users set notifs='".implode(',',$notifs)."',emailnotifs='".implode(',',$emailnotifs)."' where id={$row['id']}");
print "user {$row['id']} updated<br/>";
}
?>