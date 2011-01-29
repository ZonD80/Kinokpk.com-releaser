<?php
global  $REL_LANG, $REL_CACHE, $REL_SEO;
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../".$REL_SEO->make_link('index'));
	exit;
}

$content .= "<table border=\"1\" width=\"100%\"><tr><td align=\"center\"><a href=\"".$REL_SEO->make_link('viewcensoredtorrents')."\">".$REL_LANG->say_by_key('view_all')."</a><hr/><table border=\"1\" class=\"main\" width=\"100%\">";


$ctorrents = $REL_CACHE->get('block-cen', 'query');

if ($ctorrents===false) {
	$ctorrentsrow=sql_query("SELECT * FROM censoredtorrents ORDER BY id DESC LIMIT 3");
	$ctorrents = array();
	while ($ctres = mysql_fetch_array($ctorrentsrow))
	$ctorrents[] = $ctres;

	$time = time();
	$REL_CACHE->set('block-cen', 'query', $ctorrents);

}

if ($ctorrents)
foreach ($ctorrents as $ct) {

	if (mb_strlen($ct['reason']) > 500) $reason = format_comment(substr($ct['reason'],0,500)."..."); else $reason = format_comment($ct['reason']);

	$content .= "<tr><td><b>".$ct['name']."</b><br/>".$reason."</tr>";
}


$content .= "<tr><td width=\"200\">".$REL_LANG->say_by_key('warning')."</td></tr></table>";
$content .= "</td></tr></table>";

?>