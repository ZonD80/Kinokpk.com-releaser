<?php
require_once("include/bittorrent.php");
dbconn();
if (get_user_class() < UC_MODERATOR) die ("your class is too low to access this page. contact site admin.");
$res = mysql_query("SELECT id,name FROM torrents WHERE topic_id = 0 ORDER BY id ASC");
print("<h1>Not exported/synchronized releases:</h1><hr/><table border=\"1\">");
while (list($id,$name) = mysql_fetch_array($res)){
  print("<tr><td>ID: $id</td><td>Link: <a href=\"$DEFAULTBASEURL/details.php?id=$id&hit=1\">[Link]</a> | <a href=\"$DEFAULTBASEURL/edit.php?id=$id\">[Edit]</a></td><td>Name: $name</td></tr>");
}
print("</table>");
?>