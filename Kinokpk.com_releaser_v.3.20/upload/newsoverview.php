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
dbconn();
loggedinorreturn();

$newsid = (int) $_GET['id'];
if (!is_valid_id($newsid)) 			stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
//$action = $_GET["action"];
//$returnto = $_GET["returnto"];

stdhead("Комментирование новости");


if (isset($_GET['id'])) {

	$sql = sql_query("SELECT * FROM news WHERE id = {$newsid} ORDER BY id DESC") or sqlerr(__FILE__, __LINE__);
$news = mysql_fetch_assoc($sql);
if (!$news) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

		$added = mkprettytime($news['added']) . " (" . (get_elapsed_time($news["added"],false)) . " {$REL_LANG->say_by_key('ago')})";
		print("<h1>{$news['subject']}</h1>\n");
		print("<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n" .
 "<tr><td class=\"colhead\">Содержание&nbsp;<a href=\"".$REL_SEO->make_link('newsoverview','id',$newsid)."#comments\">Комментировать</a></td></tr>\n");
		print("<tr><td style=\"vertical-align: top; text-align: left;\">".format_comment($news['body'])."</td></tr>\n");
		print("<tr align=\"right\"><td class=\"colhead\">Добавлена:&nbsp;{$added}</td></tr>\n");

	print("</table><br />\n");

	$subres = sql_query("SELECT SUM(1) FROM newscomments WHERE news = ".$newsid);
	$subrow = mysql_fetch_array($subres);
	$count = $subrow[0];

	$limited = 10;

	if (!$count) {

		print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
		print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
		print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев к новости</div>");
		print("<div align=\"right\"><a href=\"".$REL_SEO->make_link('newsoverview','id',$newsid)."#comments\" class=altlink_white>Добавить комментарий</a></div>");
		print("</td></tr><tr><td align=\"center\">");
		print("Комментариев нет. <a href=\"".$REL_SEO->make_link('newsoverview','id',$newsid)."#comments\">Желаете добавить?</a>");
		print("</td></tr></table><br />");

	}
	else {
		list($pagertop, $pagerbottom, $limit) = pager($limited, $count, $REL_SEO->make_link('newsoverview','id',$newsid)."&", array(lastpagedefault => 1));

		$subres = sql_query("SELECT nc.id, nc.ip, nc.text, nc.ratingsum, nc.user, nc.added, nc.editedby, nc.editedat, u.avatar, u.warned, ".
                  "u.username, u.title, u.class, u.donor, u.enabled, u.ratingsum AS urating, u.gender, sessions.time AS last_access, e.username AS editedbyname FROM newscomments AS nc LEFT JOIN users AS u ON nc.user = u.id LEFT JOIN sessions ON nc.user=sessions.uid LEFT JOIN users AS e ON nc.editedby = e.id WHERE news = " .
                  "".$newsid." GROUP BY nc.id ORDER BY nc.id $limit") or sqlerr(__FILE__, __LINE__);
		$allrows = array();

		while ($subrow = mysql_fetch_array($subres)) {
			$subrow['subject'] = $news['subject'];
			$subrow['link'] = $REL_SEO->make_link('newsoverview','id',$newsid)."#comm{$subrow['id']}";
			$allrows[] = $subrow;
		}




		print("<table class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
		print("<tr><td class=\"colhead\" align=\"center\" >");
		print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
		print("<div align=\"right\"><a href=\"".$REL_SEO->make_link('newsoverview','id',$newsid)."#comments\" class=altlink_white>Добавить комментарий</a></div>");
		print("</td></tr>");

		print("<tr><td>");
		print($pagertop);
		print("</td></tr>");
		print("<tr><td>");
		commenttable($allrows,"newscomment");
		print("</td></tr>");
		print("<tr><td>");
		print($pagerbottom);
		print("</td></tr>");
		print("</table>");
	}



	print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
	print("<tr><td class=colhead align=\"left\" colspan=\"2\">  <a name=comments>&nbsp;</a><b>:: Добавить комментарий к новости  | ".is_i_notified($newsid,'newscomments')."</b></td></tr>");
	print("<tr><td width=\"100%\" align=\"center\" >");
	//print("Ваше имя: ");
	//print("".$CURUSER['username']."<p>");
	print ( "<form name=comment method=\"post\" action=\"".$REL_SEO->make_link('newscomment','action','add')."\">" );
	print ( "<table width=\"100%\"><tr><td align=\"center\">" . textbbcode ( "text") . "</td></tr>" );

	print ( "<tr><td  align=\"center\">" );
	print ( "<input type=\"hidden\" name=\"nid\" value=\"$newsid\"/>" );
	print ( "<input type=\"submit\" value=\"Разместить комментарий\" />" );
	print ( "</td></tr></table></form></td></tr></table>" );

}

stdfoot();
?>