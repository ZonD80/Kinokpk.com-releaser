<?php
/**
 * Release groups news viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
dbconn();
loggedinorreturn();


$rgnewsid = (int) $_GET['id'];
if (!is_valid_id($rgnewsid)) 			stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
//$action = $_GET["action"];
//$returnto = $_GET["returnto"];




$sql = sql_query("SELECT * FROM rgnews WHERE id = {$rgnewsid} ORDER BY id DESC") or sqlerr(__FILE__, __LINE__);

if (mysql_num_rows($sql) == 0) {
	stderr($REL_LANG->say_by_key('error'),'Извините...Нет новости с таким ID!');

}

$rgnews = mysql_fetch_assoc($sql);

$relgroup = sql_query("SELECT id,name,owners,private FROM relgroups WHERE id={$rgnews['relgroup']}") or sqlerr(__FILE__,__LINE__);
$relgroup = mysql_fetch_assoc($relgroup);

if (!$relgroup) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

if (in_array($CURUSER['id'],@explode(',',$relgroup['owners'])) || (get_user_class() >= UC_MODERATOR)) $I_OWNER=true;

if ($relgroup['private']) {
	if (!$I_OWNER && !in_array($CURUSER['id'],@explode(',',$relgroup['onwers']))) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_access_priv_rg'));
}

stdhead("Комментирование новости");

print("<h1>Обзор Новости | <a href=\"".$REL_SEO->make_link('relgroups','id',$relgroup['id'])."\">К странице релиз группы</a></h1>");
print('<div align="center">'.$REL_LANG->say_by_key('news').' '.$REL_LANG->say_by_key('relgroups').(((get_user_class() >= UC_ADMINISTRATOR) || $I_OWNER) ? "<font class=\"small\"> - [<a class=\"altlink\" href=\"".$REL_SEO->make_link('rgnews','id=',$relgroup['id'])."\"><b>".$REL_LANG->say_by_key('create')."</b></a>][<a href=\"".$REL_SEO->make_link('rgnews','action','edit','id',$relgroup['id'],'newsid',$rgnewsid,'returnto',$REL_SEO->make_link('rgnews','id',$rgnewsid))."\"><b>Редактировать эту новость</b></a>][<a onclick=\"return confirm('Вы уверены?');\" href=\"".$REL_SEO->make_link('rgnews','action','delete','id',$relgroup['id'],'newsid',$rgnewsid,'returnto',$REL_SEO->make_link('rgnews','id',$rgnewsid))."\"><b>Удалить</b></a>]</font>" : "").'</div>');


print("<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\"><tr>\n" .
"<td class=\"colhead\">Добавлена</td><td class=\"colhead\">Заглавие</td><td class=\"colhead\">Содержание</td></tr>\n");


$added = mkprettytime($rgnews['added']) . " (" . (get_elapsed_time($rgnews["added"],false)) . " {$REL_LANG->say_by_key('ago')})";
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
	print("<div align=\"right\"><a href=\"".$REL_SEO->make_link('rgnewsoverview','id',$rgnewsid)."#comments\" class=altlink_white>Добавить комментарий</a></div>");
	print("</td></tr><tr><td align=\"center\">");
	print("Комментариев нет. <a href=\"".$REL_SEO->make_link('rgnewsoverview','id',$rgnewsid)."#comments\">Желаете добавить?</a>");
	print("</td></tr></table><br />");

}
else {
	list($pagertop, $pagerbottom, $limit) = pager($limited, $count, $REL_SEO->make_link('rgnewsoverview','id',$rgnewsid)."&", array(lastpagedefault => 1));

	$subres = sql_query("SELECT nc.id, nc.ip, nc.text, nc.ratingsum, nc.user, nc.added, nc.editedby, nc.editedat, u.avatar, u.warned, ".
                  "u.username, u.title, u.class, u.donor, u.enabled, u.ratingsum AS urating, u.gender, sessions.time AS last_access, e.username AS editedbyname FROM rgnewscomments AS nc LEFT JOIN users AS u ON nc.user = u.id LEFT JOIN sessions ON nc.user=sessions.uid LEFT JOIN users AS e ON nc.editedby = e.id WHERE rgnews = " .
                  "".$rgnewsid." ORDER BY nc.id $limit") or sqlerr(__FILE__, __LINE__);
	$allrows = array();

	while ($subrow = mysql_fetch_array($subres)) {
		$subrow['subject'] = $rgnews['subject'];
		$subrow['link'] = $REL_SEO->make_link('rgnewsoverview','id',$rgnewsid)."#comm{$subrow['id']}";
		$allrows[] = $subrow;
	}




	print("<table class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
	print("<tr><td class=\"colhead\" align=\"center\" >");
	print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
	print("<div align=\"right\"><a href=\"".$REL_SEO->make_link('rgnewsoverview','id',$rgnewsid)."#comments\" class=altlink_white>Добавить комментарий</a></div>");
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
print("<form name=rgnews method=\"post\" action=\"".$REL_SEO->make_link('rgnewscomment','action','add')."\">");
print("<center><table border=\"0\"><tr><td class=\"clear\">");
print("<div align=\"center\">". textbbcode("text") ."</div>");
print("</td></tr></table></center>");
print("</td></tr><tr><td  align=\"center\" colspan=\"2\">");
print("<input type=\"hidden\" name=\"nid\" value=\"".$rgnewsid."\"/>");
print("<input type=\"submit\" class=btn value=\"Разместить комментарий\" />");
print("</td></tr></form></table>");


stdfoot();
?>