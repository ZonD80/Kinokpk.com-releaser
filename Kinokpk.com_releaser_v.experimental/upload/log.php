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
if (isset($_GET['truncate']) && get_privilege('truncate_logs',false)) {
	sql_query("TRUNCATE TABLE sitelog") or sqlerr(__FILE__,__LINE__);
	stderr($REL_LANG->say_by_key('success'),'Логи очищены <a href="'.$REL_SEO->make_link('log').'">К логам</a>','success');

} elseif (isset($_GET['truncate'])) 	stderr($REL_LANG->say_by_key('error'),'Логи могут быть очищены только системным администратором');

$type = htmlspecialchars(trim((string)$_GET["type"]));

if (!pagercheck()) {
$REL_TPL->stdhead($REL_LANG->say_by_key('logs'));

if(!$type) {
	print ('<h1><a href="'.$REL_SEO->make_link('log','truncate','').'">Очистить логи</a></h1>');
	print("<p align=center>");
	$logs = sql_query("SELECT type FROM sitelog GROUP BY type");
	while (list($logt) = mysql_fetch_array($logs)) {
		print (' |<a href="'.$REL_SEO->make_link('log','type',$logt).'">'.$logt.'</a>| ');
	}
	print '</p>';
	$REL_TPL->stdfoot();
	die();
}

}
$count = @mysql_result(sql_query("SELECT SUM(1) FROM `sitelog` WHERE type = ".sqlesc($type)),0);

if (!$count) print("<b>".$REL_LANG->say_by_key('log_file_empty')."</b>\n");
else
{
	$limit = ajaxpager(25, $count, array('log','type',$type), 'logtable > tbody:last');
	$res = sql_query("SELECT txt, added FROM `sitelog` WHERE type = ".sqlesc($type)." ORDER BY `added` DESC LIMIT 50") or sqlerr(__FILE__, __LINE__);
	if (!pagercheck()) {
	print("<h1>".$REL_LANG->say_by_key('logs')."| <a href=\"".$REL_SEO->make_link('log')."\">к типам логов</a></h1>\n");
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
