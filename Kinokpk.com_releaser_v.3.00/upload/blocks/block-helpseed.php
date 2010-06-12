<?php
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../index.php");
	exit;
}
getlang('blocks');
global $tracker_lang;

$blocktitle = $tracker_lang['helpseed'];
$content .= "<div align=\"center\"><font color=\"#FF6633\"><b>".$tracker_lang['help_seed']."</b></font></div>
<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td class=\"text\">";
$res = sql_query("SELECT id, name, SUM(trackers.seeders) AS seeders, SUM(trackers.leechers) AS leechers FROM torrents LEFT JOIN trackers ON torrents.id = trackers.torrent WHERE (leechers > 0 AND seeders = 0) OR (leechers / seeders >= 4) GROUP BY torrents.id ORDER BY leechers DESC LIMIT 20") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) > 0) {
	while ($arr = mysql_fetch_assoc($res)) {
		$torrname = $arr['name'];
		if (strlen($torrname) > 55)
		$torrname = substr($torrname, 0, 55) . "...";
		$content .= "<small><b><a href=\"details.php?id=".$arr['id']."\" title=\"".$arr['name']."\">".$torrname."</a></b><font color=\"#0099FF\"><b> (".sprintf($tracker_lang['new_torrents_stats'], number_format($arr['seeders']), number_format($arr['leechers'])).")</b></font></small><br />\n";
	}
} else
$content .= "<b> ".$tracker_lang['no_need_seeding']." </b>\n";
$content .= "

</td></tr></table>";
?>