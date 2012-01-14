<?php
/**
 * Logs viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require_once "include/bittorrent.php";

INIT();

loggedinorreturn();

get_privilege('view_logs');

// delete items older than a week
if (isset($_GET['truncate'])) {
	get_privilege('truncate_logs');
	$REL_DB->query("TRUNCATE TABLE sitelog");
	$REL_TPL->stderr($REL_LANG->say_by_key('success'),$REL_LANG->_('Logs cleared successfully. <a href="%s">Go back to logs</a>',$REL_SEO->make_link('log')),'success');

}

$type = htmlspecialchars(trim((string)$_GET["type"]));

if (!pagercheck()) {
$REL_TPL->stdhead($REL_LANG->say_by_key('logs'));

if(!$type) {
	print ('<h1><a href="'.$REL_SEO->make_link('log','truncate','').'">'.$REL_LANG->_('Truncate logs').'</a></h1>');
	print("<p align=center>");
	$logs = $REL_DB->query("SELECT type FROM sitelog GROUP BY type");
	while (list($logt) = mysql_fetch_array($logs)) {
		print (' |<a href="'.$REL_SEO->make_link('log','type',$logt).'">'.$logt.'</a>| ');
	}
	print '</p>';
	$REL_TPL->stdfoot();
	die();
}

}
$count = @mysql_result($REL_DB->query("SELECT SUM(1) FROM `sitelog` WHERE type = ".sqlesc($type)),0);

if (!$count) print("<b>".$REL_LANG->say_by_key('log_file_empty')."</b>\n");
else
{
	$limit = ajaxpager(25, $count, array('log','type',$type), 'logtable');
	$res = $REL_DB->query("SELECT txt, added FROM `sitelog` WHERE type = ".sqlesc($type)." ORDER BY `added` DESC LIMIT 50");
	if (!pagercheck()) {
	print("<h1>".$REL_LANG->say_by_key('logs')."| <a href=\"".$REL_SEO->make_link('log')."\">{$REL_LANG->_('To log types')}</a></h1>\n");
	print("<div id=\"pager_scrollbox\"><table id=\"logtable\" border=1 cellspacing=0 cellpadding=5>\n");
	print("<tr><td class=colhead align=left>".$REL_LANG->say_by_key('time')."</td><td class=colhead align=left>".$REL_LANG->say_by_key('event')."</td></tr>\n");
	}
	while ($arr = mysql_fetch_assoc($res))
	{
		$time = mkprettytime($arr['added']);
		print("<tr><td>$time</td><td class=\"bigtextarea\">".format_comment($arr[txt])."</td></tr>\n");
	}
	if (pagercheck()) die();
	print("</table></div>");
}
$REL_TPL->stdfoot();
?>
