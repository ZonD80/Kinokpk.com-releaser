<?php
global $REL_LANG, $REL_SEO;
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../".$REL_SEO->make_link('index'));
	exit;
}


$blocktitle = $REL_LANG->say_by_key('helpseed');
$content .= "<div align=\"center\"><font color=\"#FF6633\"><b>".$REL_LANG->say_by_key('help_seed')."</b></font></div>
<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"10\"><tr><td class=\"text\">";
$res = sql_query("SELECT id, name, seeders, leechers FROM torrents WHERE (seeders > 0 AND leechers = 0) OR (leechers / seeders >= 4) ORDER BY leechers DESC LIMIT 20") or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) > 0) {
	while ($arr = mysql_fetch_assoc($res)) {
		$torrname = $arr['name'];
		if (mb_strlen($torrname) > 55)
		$torrname = substr($torrname, 0, 55) . "...";
		$content .= "<small><b><a href=\"".$REL_SEO->make_link('details','id',$arr['id'],'name',translit($arr['name']))."\" title=\"".$arr['name']."\">".$torrname."</a></b><font color=\"#0099FF\"><b> (".sprintf($REL_LANG->say_by_key('new_torrents_stats'), number_format($arr['seeders']), number_format($arr['leechers'])).")</b></font></small><br />\n";
	}
} else
$content .= "<b> ".$REL_LANG->say_by_key('no_need_seeding')." </b>\n";
$content .= "

</td></tr></table>";
?>