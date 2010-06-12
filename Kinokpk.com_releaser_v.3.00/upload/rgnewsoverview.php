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

require "include/bittorrent.php";
dbconn();
loggedinorreturn();
getlang('relgroups');

$rgnewsid = (int) $_GET['id'];
if (!is_valid_id($rgnewsid)) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
//$action = $_GET["action"];
//$returnto = $_GET["returnto"];




$sql = sql_query("SELECT * FROM rgnews WHERE id = {$rgnewsid} ORDER BY id DESC") or sqlerr(__FILE__, __LINE__);

if (mysql_num_rows($sql) == 0) {
	stderr($tracker_lang['error'],'Извините...Нет новости с таким ID!');

}

$rgnews = mysql_fetch_assoc($sql);

$relgroup = sql_query("SELECT id,name,owners,private FROM relgroups WHERE id={$rgnews['relgroup']}") or sqlerr(__FILE__,__LINE__);
$relgroup = mysql_fetch_assoc($relgroup);

if (!$relgroup) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

if (in_array($CURUSER['id'],@explode(',',$relgroup['owners'])) || (get_user_class() >= UC_MODERATOR)) $I_OWNER=true;

if ($relgroup['private']) {
	if (!$I_OWNER && !in_array($CURUSER['id'],@explode(',',$relgroup['onwers']))) stderr($tracker_lang['error'],$tracker_lang['no_access_priv_rg']);
}

stdhead("Комментирование новости");

print("<h1>Обзор Новости | <a href=\"relgroups.php?id={$relgroup['id']}\">К странице релиз группы</a></h1>");
print('<div align="center">'.$tracker_lang['news'].' '.$tracker_lang['relgroups'].(((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) ? "<font class=\"small\"> - [<a class=\"altlink\" href=\"rgnews.php?id={$relgroup['id']}\"><b>".$tracker_lang['create']."</b></a>][<a href=\"rgnews.php?action=edit&amp;id={$relgroup['id']}&amp;newsid=" . $rgnewsid . "&amp;returnto=" . urlencode($_SERVER['PHP_SELF'] . "?id=$rgnewsid")."\"><b>Редактировать эту новость</b></a>][<a onclick=\"return confirm('Вы уверены?');\" href=\"rgnews.php?action=delete&amp;id={$relgroup['id']}&amp;newsid=" . $rgnewsid . "&amp;returnto=" . urlencode("relgroups.php?id={$relgroup['id']}")."\"><b>Удалить</b></a>]</font>" : "").'</div>');


print("<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\"><tr>\n" .
"<td class=\"colhead\">Добавлена</td><td class=\"colhead\">Заглавие</td><td class=\"colhead\">Содержание</td></tr>\n");


$added = mkprettytime($rgnews['added']) . " (" . (get_elapsed_time($rgnews["added"],false)) . " {$tracker_lang['ago']})";
print("<tr><td width=\"100px\">{$added}</td><td width=\"200px\">{$rgnews['subject']}</td><td style=\"vertical-align: top; text-align: left;\">".format_comment($rgnews['body'])."</td></tr>\n");


print("</table><br />\n");

$subres = sql_query("SELECT SUM(1) FROM rgnewscomments WHERE rgnews = ".$rgnewsid);
$subrow = mysql_fetch_array($subres);
$count = $subrow[0];

$limited = 10;

if (!$count) {

	print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
	print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
	print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев к новости</div>");
	print("<div align=\"right\"><a href=rgnewsoverview.php?id=$rgnewsid#comments class=altlink_white>Добавить комментарий</a></div>");
	print("</td></tr><tr><td align=\"center\">");
	print("Комментариев нет. <a href=rgnewsoverview.php?id=$rgnewsid#comments>Желаете добавить?</a>");
	print("</td></tr></table><br />");

}
else {
	list($pagertop, $pagerbottom, $limit) = pager($limited, $count, "rgnewsoverview.php?id=".$rgnewsid."&", array(lastpagedefault => 1));

	$subres = sql_query("SELECT nc.id, nc.ip, nc.text, nc.ratingsum, nc.user, nc.added, nc.editedby, nc.editedat, u.avatar, u.warned, ".
                  "u.username, u.title, u.class, u.donor, u.enabled, u.ratingsum AS urating, u.gender, sessions.time AS last_access, e.username AS editedbyname FROM rgnewscomments AS nc LEFT JOIN users AS u ON nc.user = u.id LEFT JOIN sessions ON nc.user=sessions.uid LEFT JOIN users AS e ON nc.editedby = e.id WHERE rgnews = " .
                  "".$rgnewsid." ORDER BY nc.id $limit") or sqlerr(__FILE__, __LINE__);
	$allrows = array();

	while ($subrow = mysql_fetch_array($subres)) {
		$subrow['subject'] = $rgnews['subject'];
		$subrow['link'] = "rgnewsoverview.php?id=$rgnewsid#comm{$subrow['id']}";
		$allrows[] = $subrow;
	}




	print("<table class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
	print("<tr><td class=\"colhead\" align=\"center\" >");
	print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
	print("<div align=\"right\"><a href=rgnewsoverview.php?id=$rgnewsid#comments class=altlink_white>Добавить комментарий</a></div>");
	print("</td></tr>");

	print("<tr><td>");
	print($pagertop);
	print("</td></tr>");
	print("<tr><td>");
	commenttable($allrows,"rgnewscomment");
	print("</td></tr>");
	print("<tr><td>");
	print($pagerbottom);
	print("</td></tr>");
	print("</table>");
}



print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
print("<tr><td class=colhead align=\"left\" colspan=\"2\">  <a name=comments>&nbsp;</a><b>:: Добавить комментарий к новости  | ".is_i_notified($rgnewsid,'rgnewscomments')."</b></td></tr>");
print("<tr><td width=\"100%\" align=\"center\" >");
//print("Ваше имя: ");
//print("".$CURUSER['username']."<p>");
print("<form name=rgnews method=\"post\" action=\"rgnewscomment.php?action=add\">");
print("<center><table border=\"0\"><tr><td class=\"clear\">");
print("<div align=\"center\">". textbbcode("text") ."</div>");
print("</td></tr></table></center>");
print("</td></tr><tr><td  align=\"center\" colspan=\"2\">");
print("<input type=\"hidden\" name=\"nid\" value=\"".$rgnewsid."\"/>");
print("<input type=\"submit\" class=btn value=\"Разместить комментарий\" />");
print("</td></tr></form></table>");


stdfoot();
?>