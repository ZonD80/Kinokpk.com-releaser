<?php
/**
 * Release groups news archive
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
INIT();


loggedinorreturn();

$rgid=(int)$_GET['id'];

if (!is_valid_id($rgid)) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

$relgroup = sql_query("SELECT name,owners,private FROM relgroups WHERE id=$rgid") or sqlerr(__FILE__,__LINE__);
$relgroup = mysql_fetch_assoc($relgroup);

if (!$relgroup) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

if (in_array($CURUSER['id'],@explode(',',$relgroup['owners'])) || (get_privilege('edit_relgroups',false))) $I_OWNER=true;

if ($relgroup['private']) {
	if (!in_array($rgid,@explode(',',$CURUSER['relgroups'])) && !$I_OWNER && !in_array($CURUSER['id'],@explode(',',$relgroup['onwers']))) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_access_priv_rg'));
}

$count = get_row_count("rgnews"," WHERE relgroup=$rgid");

$resource = sql_query("SELECT rgnews.* , SUM(1) FROM rgnews LEFT JOIN comments ON comments.toid = rgnews.id WHERE comments.type='rgnews' GROUP BY rgnews.id ORDER BY rgnews.added DESC $limit");

print("<div id='rgnews-table'>");
print ("<table border='0' cellspacing='0' width='100%' cellpadding='5'>
        <tr><td class='colhead' align='center'><b>Архив новостей &quot;".$relgroup['name']."&quot;</b></td></tr>");

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

            <div style='float:left;'><b>Размещено</b>: ".mkprettytime($added)." <b>Комментариев:</b> ".$comments." [<a href=\"".$REL_SEO->make_link('rgnewsoverview','id',$id)."#comments\">Комментировать</a>]</div>");

		if ((get_privilege('edit_relgroups',false)) || $I_OWNER)
		{
			print("<div style='float:right;'>
            <font class=\"small\">
            [<a class='altlink' href=\"".$REL_SEO->make_link('rgnews','action','edit','id',$rgid,'newsid',$id,'returnto',urlencode($_SERVER['PHP_SELF']))."\">Редактировать</a>]
            [<a class='altlink' onClick=\"return confirm('Удалить эту новость?')\" href=\"".$REL_SEO->make_link('rgnews','action','delete','id',$rgid,'newsid',$id,'returnto',urlencode($_SERVER['PHP_SELF']))."\">Удалить</a>]
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