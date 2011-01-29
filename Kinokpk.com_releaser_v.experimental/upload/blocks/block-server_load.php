<?php
global $REL_LANG, $REL_SEO, $REL_DB;
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../".$REL_SEO->make_lank('index'));
	exit;
}

$connected = (int)mysql_num_rows($REL_DB->query("SELECT uid FROM xbt_files_users GROUP BY uid"));

$blocktitle = $REL_LANG->say_by_key('server_load');
$avgload = get_server_load();
if (strtolower(substr(PHP_OS, 0, 3)) != 'win')
$percent = $avgload * 4;
else
$percent = $avgload;
if ($percent <= 50) $pic = "loadbargreen.gif";
elseif ($percent <= 70) $pic = "loadbaryellow.gif";
else $pic = "loadbarred.gif";
$width = $percent * 4;
$content .= "<center>
<table class=\"main\" border=\"0\" width=\"402\"><tr><td style=\"padding: 0px; background-repeat: repeat-x\" title=\"".$REL_LANG->say_by_key('loading').": $percent%, ".$REL_LANG->say_by_key('average')." (LA): $avgload\">"
."<img height=\"15\" width=\"$width\" src=\"pic/$pic\" alt=\"".$REL_LANG->say_by_key('loading').": $percent%, ".$REL_LANG->say_by_key('average')." (LA): $avgload\" title=\"".$REL_LANG->say_by_key('loading').": $percent%, ".$REL_LANG->say_by_key('average')." (LA): $avgload\" />"
."</td></tr></table>"
."<b>".$REL_LANG->say_by_key('the_unique_1')." $connected ".$REL_LANG->say_by_key('users_sl').".</b></center>";

?>