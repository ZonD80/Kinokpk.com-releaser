<?

/*
 Project: Kinokpk.com releaser
 This file is part of Kinokpk.com releaser.
 Kinokpk.com releaser is based on TBDev,
 originally by RedBeard of TorrentBits, extensively modified by
 Gartenzwerg and Yuna Scatari.
 Kinokpk.com releaser is free software;
 you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 Kinokpk.com is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Kinokpk.com releaser; if not, write to the
 Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 MA  02111-1307  USA
 Do not remove above lines!
 */

require_once("include/bittorrent.php");
dbconn();

if (isset($_GET['ajax']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
	$ajax = 1;
	header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);

	$page = (int) $_GET["page"];

} else $ajax=0;

loggedinorreturn();

if (!$ajax) {stdhead("Все новости ".$CACHEARRAY['sitename']."");

print'<script language="javascript" type="text/javascript">

var no_ajax = true;

function pageswitcher(page) {
     (function($){
    if ($) no_ajax = false;
    $("#news-table").empty();
    $("#news-table").append(\'<div align="center"><img src="pic/loading.gif" border="0"/></div>\');
    $.get("newsarchive.php", { ajax: "", page: page }, function(data){
    $("#news-table").empty();
    $("#news-table").append(data);
});
})(jQuery);

window.location.href = "#news-table";

return no_ajax;
}
</script>';
}
$count = get_row_count("news");
$perpage = 20; //Сколько новостей на страницу

list($pagertop, $pagerbottom, $limit) = browsepager($perpage, $count, $_SERVER["PHP_SELF"] . "?",'#news-table');
$resource = sql_query("SELECT news.* , COUNT(newscomments.id) FROM news LEFT JOIN newscomments ON newscomments.news = news.id GROUP BY news.id ORDER BY news.added DESC $limit");

print("<div id='news-table'>");
print ("<table border='0' cellspacing='0' width='100%' cellpadding='5'>
        <tr><td class='colhead' align='center'><b>Архив новостей &quot;".$CACHEARRAY['sitename']."&quot;</b></td></tr>
        <tr><td>".$pagertop."</td></tr>");

if ($count)
{

	while(list($id, $userid, $added, $body, $subject,$comments) = mysql_fetch_array($resource))
	{

		$date = date("d.m.Y",strtotime($added));

		print("<tr><td>");
		print("<table border='0' cellspacing='0' width='100%' cellpadding='5'>
            <tr><td class='colhead'>".$subject."");
		print("</td></tr><tr><td>".format_comment($body)."</td></tr>");
		print("</td></tr>");
		print("<tr><td style='background-color: #F9F9F9'>

            <div style='float:left;'><b>Размещено</b>: ".$added." <b>Комментариев:</b> ".$comments." [<a href=\"newsoverview.php?id=".$id."#comments\">Комментировать</a>]</div>");

		if (get_user_class() >= UC_ADMINISTRATOR)
		{
			print("<div style='float:right;'>
            <font class=\"small\">
            [<a class='altlink' href=\"news.php?action=edit&newsid=".$id."&returnto=".urlencode($_SERVER['PHP_SELF'])."\">Редактировать</a>]
            [<a class='altlink' onClick=\"return confirm('Удалить эту новость?')\" href=\"news.php?action=delete&newsid=".$id."&returnto=".urlencode($_SERVER['PHP_SELF'])."\">Удалить</a>]
            </font></div>");
		}
		print("</td></tr></table>");

	}
}
else
{
	print("<tr><td><center><h3>Извините, но новостей нет...</h3></center></td></tr>");
}

print ("<tr><td>".$pagerbottom."</td></tr></table>");

print("</div>");

if (!$ajax) stdfoot();
?>