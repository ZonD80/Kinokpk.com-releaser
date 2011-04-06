<?php
/**
 * News archive
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
INIT();
loggedinorreturn();

$REL_TPL->stdhead("Архив новостей");
$count = get_row_count("news");

$limit = "LIMIT 50";
$resource = sql_query("SELECT news.* , SUM(1) FROM news LEFT JOIN comments ON comments.toid = news.id WHERE comments.type='news' GROUP BY news.id ORDER BY news.added DESC $limit");

print("<div id='news-table'>");
print ("<table border='0' cellspacing='0' width='100%' cellpadding='5'>
        <tr><td class='colhead' align='center'><b>Архив новостей &quot;".$REL_CONFIG['sitename']."&quot;</b></td></tr>");

if ($count)
{

	while(list($id, $userid, $added, $body, $subject,$comments) = mysql_fetch_array($resource))
	{

		print("<tr><td>");
		print("<table border='0' cellspacing='0' width='100%' cellpadding='5'>
            <tr><td class='colhead'>".$subject."");
		print("</td></tr><tr><td>".format_comment($body)."</td></tr>");
		print("</td></tr>");
		print("<tr><td style='background-color: #F9F9F9'>

            <div style='float:left;'><b>Размещено</b>: ".mkprettytime($added)." <b>Комментариев:</b> ".$comments." [<a href=\"".$REL_SEO->make_link('newsoverview','id',$id)."#comments\">Комментировать</a>]</div>");

		if (get_privilege('news_operation',false))
		{
			print("<div style='float:right;'>
            <font class=\"small\">
            [<a class='altlink' href=\"".$REL_SEO->make_link('news','action','edit','newsid',$id,'returnto',urlencode($_SERVER['PHP_SELF']))."\">Редактировать</a>]
            [<a class='altlink' onClick=\"return confirm('Удалить эту новость?')\" href=\"".$REL_SEO->make_link('news','action','delete','newsid',$id,'returnto',urlencode($_SERVER['PHP_SELF']))."\">Удалить</a>]
            </font></div>");
		}
		print("</td></tr></table>");

	}
}
else
{
	print("<tr><td><center><h3>Извините, но новостей нет...</h3></center></td></tr>");
}

print("</table>");
print("</div>");

$REL_TPL->stdfoot();
?>