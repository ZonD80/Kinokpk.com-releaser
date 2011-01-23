<pre>
<?
require_once('include/bittorrent.php');
dbconn();
$id = (int)$_GET['id'];
$REL_TPL->stdhead();
if (!$id) {
$res = $REL_DB->query("SELECT id,online FROM torrents where online<>'' ORDER BY id DESC");
while ($row = mysql_fetch_assoc($res)) {
//$row['descr']='http://www.kinopoisk.ru/level/1/film/405608/';
$REL_DB->query('update torrents set online='.sqlesc(str_replace('swf/player.swf','http://tr.kinopoisk.ru/js/jw/player-licensed.swf',$row['online'])).' where id='.$row['id']);
print "{$row['id']} done<br/>}";
}
}
$REL_TPL->stdfoot();
?>