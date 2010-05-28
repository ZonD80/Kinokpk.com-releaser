<?php
if (!defined('BLOCK_FILE')) {
 Header("Location: ../index.php");
 exit;
}

global $tracker_lang;

$content .= "<b><center><font color=\"#FF6633\">".$tracker_lang['help_seed']."</font></center>
<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td class=\"text\">";
$res = sql_query("SELECT id, name, seeders, leechers FROM torrents WHERE (leechers > 0 AND seeders = 0) OR (leechers / seeders >= 4) ORDER BY leechers DESC LIMIT 20") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) > 0) {
	while ($arr = mysql_fetch_assoc($res)) {
		$torrname = $arr['name'];
		if (strlen($torrname) > 55)
		$torrname = substr($torrname, 0, 55) . "...";
		$content .= "<b><a href=\"details.php?id=".$arr['id']."&hit=1\" alt=\"".$arr['name']."\" title=\"".$arr['name']."\">".$torrname."</a></b><font color=\"#0099FF\"><b> (Раздают: ".number_format($arr['seeders'])." Качают: ".number_format($arr['leechers']).")</b></font><br />\n";
	}
} else
	$content .= "<b> ".$tracker_lang['no_need_seeding']." </b>\n";
$content .= "</font>
</b>
</td></tr></table>";
?>