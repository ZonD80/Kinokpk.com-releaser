<?php

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


loggedinorreturn();

$rgid=(int)$_GET['id'];

if (!is_valid_id($rgid)) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

$relgroup = sql_query("SELECT name,owners,private FROM relgroups WHERE id=$rgid") or sqlerr(__FILE__,__LINE__);
$relgroup = mysql_fetch_assoc($relgroup);

if (!$relgroup) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

if (in_array($CURUSER['id'],@explode(',',$relgroup['owners'])) || (get_user_class() >= UC_MODERATOR)) $I_OWNER=true;

if ($relgroup['private']) {
	if (!in_array($rgid,@explode(',',$CURUSER['relgroups'])) && !$I_OWNER && !in_array($CURUSER['id'],@explode(',',$relgroup['onwers']))) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_access_priv_rg'));
}

$count = get_row_count("rgnews"," WHERE relgroup=$rgid");
$perpage = 20; //Сколько новостей на страницу

list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] . "?");
$resource = sql_query("SELECT rgnews.* , SUM(1) FROM rgnews LEFT JOIN rgnewscomments ON rgnewscomments.rgnews = rgnews.id GROUP BY rgnews.id ORDER BY rgnews.added DESC $limit");

print("<div id='rgnews-table'>");
print ("<table border='0' cellspacing='0' width='100%' cellpadding='5'>
        <tr><td class='colhead' align='center'><b>Архив новостей &quot;".$relgroup['name']."&quot;</b></td></tr>");

if ($count)
{
	print("<tr><td>".$pagertop."</td></tr>");

	while(list($id, $userid, $added, $body, $subject,$comments) = mysql_fetch_array($resource))
	{

		print("<tr><td>");
		print("<table border='0' cellspacing='0' width='100%' cellpadding='5'>
            <tr><td class='colhead'>".$subject."");
		print("</td></tr><tr><td>".format_comment($body)."</td></tr>");
		print("</td></tr>");
		print("<tr><td style='background-color: #F9F9F9'>

            <div style='float:left;'><b>Размещено</b>: ".mkprettytime($added)." <b>Комментариев:</b> ".$comments." [<a href=\"".$REL_SEO->make_link('rgnewsoverview','id',$id)."#comments\">Комментировать</a>]</div>");

		if ((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER)
		{
			print("<div style='float:right;'>
            <font class=\"small\">
            [<a class='altlink' href=\"".$REL_SEO->make_link('rgnews','action','edit','id',$rgid,'newsid',$id,'returnto',urlencode($_SERVER['PHP_SELF']))."\">Редактировать</a>]
            [<a class='altlink' onClick=\"return confirm('Удалить эту новость?')\" href=\"".$REL_SEO->make_link('rgnews','action','delete','id',$rgid,'newsid',$id,'returnto',urlencode($_SERVER['PHP_SELF']))."\">Удалить</a>]
            </font></div>");
		}
		print("</td></tr></table>");

	}
	print ("<tr><td>".$pagerbottom."</td></tr>");
}
else
{
	print("<tr><td><center><h3>Извините, но новостей нет...</h3></center></td></tr>");
}

print("</table>");

print("</div>");

stdfoot();
?>