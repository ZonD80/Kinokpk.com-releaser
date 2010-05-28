<?

require "include/bittorrent.php";
dbconn(false);
getlang('log');
loggedinorreturn();

// delete items older than a week
$secs = 7 * 86400;
stdhead($tracker_lang['logs']);
$type = htmlspecialchars($_GET["type"]);
if(!$type || $type == 'simp') $type = "tracker";
print("<p align=center>"  .
($type == tracker || !$type ? $tracker_lang['tracker'] : "<a href=log.php?type=tracker>".$tracker_lang['tracker']."</a>") . " | " .
($type == bans ? "<b>".$tracker_lang['bans']."</b>" : "<a href=log.php?type=bans>".$tracker_lang['bans']."</a>") . " | " .
($type == release ? "<b>".$tracker_lang['release']."</b>" : "<a href=log.php?type=release>".$tracker_lang['release']."</a>") . " | " .
($type == exchange ? "<b>".$tracker_lang['exchange']."</b>" : "<a href=log.php?type=exchange>".$tracker_lang['exchange']."</a>") . " | " .
($type == torrent ? "<b>".$tracker_lang['torrent']."</b>" : "<a href=log.php?type=torrent>".$tracker_lang['torrent']."</a>") . " | " .
($type == error ? "<b>".$tracker_lang['errors']."</b>" : "<a href=log.php?type=error>".$tracker_lang['errors']."</a>") . "</p>\n");

if (($type == 'speed' || $type == 'error') && $CURUSER['class'] < 4) {
	stdmsg($tracker_lang['error'],$tracker_lang['access_closed']);
	stdfoot();
	die();
}

sql_query("DELETE FROM sitelog WHERE " . gmtime() . " - UNIX_TIMESTAMP(added) > $secs") or sqlerr(__FILE__, __LINE__);
$limit = ($type == 'announce' ? "LIMIT 1000" : "");
$res = sql_query("SELECT txt, added, color FROM `sitelog` WHERE type = ".sqlesc($type)." ORDER BY `added` DESC $limit") or sqlerr(__FILE__, __LINE__);
print("<h1>".$tracker_lang['logs']."</h1>\n");
if (mysql_num_rows($res) == 0)
print("<b>".$tracker_lang['log_file_empty']."</b>\n");
else
{
	print("<table border=1 cellspacing=0 cellpadding=5>\n");
	print("<tr><td class=colhead align=left>".$tracker_lang['date']."</td><td class=colhead align=left>".$tracker_lang['time']."</td><td class=colhead align=left>".$tracker_lang['event']."</td></tr>\n");
	while ($arr = mysql_fetch_assoc($res))
	{
		$date = substr($arr['added'], 0, strpos($arr['added'], " "));
		$time = substr($arr['added'], strpos($arr['added'], " ") + 1);
		print("<tr style=\"background-color: $arr[color]\"><td>$date</td><td>$time</td><td align=left>".htmlspecialchars($arr[txt])."</td></tr>\n");
	}
	print("</table>");
}
stdfoot();
?>