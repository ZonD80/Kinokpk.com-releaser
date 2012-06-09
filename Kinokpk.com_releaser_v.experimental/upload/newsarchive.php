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

$REL_TPL->stdhead($REL_LANG->_('News archive'));
$count = get_row_count("news");

$limit = "LIMIT 50";
$resource = $REL_DB->query("SELECT news.* , SUM(1) FROM news LEFT JOIN comments ON comments.toid = news.id WHERE comments.type='news' GROUP BY news.id ORDER BY news.added DESC $limit");

print("<div id='news-table'>");
print ("<table border='0' cellspacing='0' width='100%' cellpadding='5'>
        <tr><td class='colhead' align='center'><b>{$REL_LANG->_('News archive')}</b></td></tr>");

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

            <div style='float:left;'><b>{$REL_LANG->_('Added')}</b>: ".mkprettytime($added)." <b>{$REL_LANG->_('Comments')}:</b> ".$comments." [<a href=\"".$REL_SEO->make_link('newsoverview','id',$id)."#comments\">{$REL_LANG->_('Add new comment')}</a>]</div>");

		if (get_privilege('news_operation',false))
		{
			print("<div style='float:right;'>
            <font class=\"small\">
            [<a class='altlink' href=\"".$REL_SEO->make_link('news','action','edit','newsid',$id,'returnto',urlencode($_SERVER['PHP_SELF']))."\">{$REL_LANG->_('Edit')}</a>]
            [<a class='altlink' onClick=\"return confirm('{$REL_LANG->_('Are you sure?')}')\" href=\"".$REL_SEO->make_link('news','action','delete','newsid',$id,'returnto',urlencode($_SERVER['PHP_SELF']))."\">{$REL_LANG->_('Delete')}</a>]
            </font></div>");
		}
		print("</td></tr></table>");

	}
}
else
{
	print("<tr><td><center><h3>{$REL_LANG->_('Looks like no news here')}</h3></center></td></tr>");
}

print("</table>");
print("</div>");

$REL_TPL->stdfoot();
?>