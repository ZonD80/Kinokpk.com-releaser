<pre>
<?php
require_once('include/bittorrent.php');
dbconn();
$export = array('pollcomments'=>'poll', 'newscomments'=>'news', 'usercomments'=>'userid', 'reqcomments'=>'reqest', 'rgcomments'=>'relgroup','rgnewscomments'=>'rgnews');
foreach (array_keys($export) as $exp) {
sql_query("INSERT INTO comments (user, toid, added, text, ip, type) SELECT user, {$export[$exp]}, added, text, ip, '".str_replace('comments','',$exp)."' FROM $exp");
print "$exp moved  ";
sql_query("DROP table $exp");
print ("$exp dropped<br/>");
}
?>