<?php
/**
 * News overview
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require "include/bittorrent.php";
INIT();
loggedinorreturn();

$newsid = (int) $_GET['id'];
if (!is_valid_id($newsid)) 			stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
//$action = $_GET["action"];
//$returnto = $_GET["returnto"];

$REL_TPL->stdhead("Комментирование новости");


if (isset($_GET['id'])) {

	$sql = sql_query("SELECT * FROM news WHERE id = {$newsid} ORDER BY id DESC") or sqlerr(__FILE__, __LINE__);
	$news = mysql_fetch_assoc($sql);
	if (!$news) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
	if (!pagercheck()) {
		$added = mkprettytime($news['added']) . " (" . (get_elapsed_time($news["added"],false)) . " {$REL_LANG->say_by_key('ago')})";
		print("<h1>{$news['subject']}</h1>\n");
		print("<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n" .
 "<tr><td class=\"colhead\">Содержание&nbsp;<a href=\"".$REL_SEO->make_link('newsoverview','id',$newsid)."#comments\">Комментировать</a></td></tr>\n");
		print("<tr><td style=\"vertical-align: top; text-align: left;\">".format_comment($news['body'])."</td></tr>\n");
		print("<tr align=\"right\"><td class=\"colhead\">Добавлена:&nbsp;{$added}</td></tr>\n");

		print("</table><br />\n");
	}
		


	$REL_TPL->assignByRef('to_id',$newsid);
	$REL_TPL->assignByRef('is_i_notified',is_i_notified ( $newsid, 'newscomments' ));
	$REL_TPL->assign('textbbcode',textbbcode('text'));
	$REL_TPL->assignByRef('FORM_TYPE_LANG',$REL_LANG->_('News'));
	$FORM_TYPE = 'news';
	$REL_TPL->assignByRef('FORM_TYPE',$FORM_TYPE);
	$REL_TPL->display('commenttable_form.tpl');
	
	$subres = sql_query("SELECT SUM(1) FROM comments WHERE toid = ".$newsid." AND type='news'");
	$subrow = mysql_fetch_array($subres);
	$count = $subrow[0];

	if (!$count) {

		print('<div id="newcomment_placeholder">'."<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
		print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
		print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев к новости</div>");
		print("<div align=\"right\"><a href=\"".$REL_SEO->make_link('newsoverview','id',$newsid)."#comments\" class=altlink_white>Добавить комментарий</a></div>");
		print("</td></tr><tr><td align=\"center\">");
		print("Комментариев нет. <a href=\"".$REL_SEO->make_link('newsoverview','id',$newsid)."#comments\">Желаете добавить?</a>");
		print("</td></tr></table><br /></div>");

	}
	else {
		

		$limit = ajaxpager(25, $count, array('newsoverview','id',$newsid), 'comments-table > tbody:last');

		$subres = sql_query("SELECT nc.type, nc.id, nc.ip, nc.text, nc.ratingsum, nc.user, nc.added, nc.editedby, nc.editedat, u.avatar, u.warned, ".
                  "u.username, u.title, u.info, u.class, u.donor, u.enabled, u.ratingsum AS urating, u.gender, sessions.time AS last_access, e.username AS editedbyname FROM comments AS nc LEFT JOIN users AS u ON nc.user = u.id LEFT JOIN sessions ON nc.user=sessions.uid LEFT JOIN users AS e ON nc.editedby = e.id WHERE nc.toid = " .
                  "".$newsid." AND nc.type='news' GROUP BY nc.id ORDER BY nc.id DESC $limit") or sqlerr(__FILE__, __LINE__);
		$allrows = prepare_for_commenttable($subres,$news['subject'],$REL_SEO->make_link('newsoverview','id',$newsid));
		if (!pagercheck()) {
			print("<div id=\"pager_scrollbox\"><table id=\"comments-table\" cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" style=\"float:left;\">");
			print("<tr><td class=\"colhead\" align=\"center\" >");
			print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
			print("<div align=\"right\"><a href=\"".$REL_SEO->make_link('newsoverview','id',$newsid)."#comments\" class=altlink_white>Добавить комментарий</a></div>");
			print("</td></tr>");
			print ( "<tr><td><div id=\"newcomment_placeholder\"></td></tr>" );
			print("<tr><td>");
			commenttable($allrows);
			print("</td></tr>");

			print("</table></div>");
		} else {
			print("<tr><td>");
			commenttable($allrows);
			print("</td></tr>");
			die();
		}
	}

}

$REL_TPL->stdfoot();
?>