<?php
/**
 * Page viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

dbconn();
//loggedinorreturn();


if (!is_valid_id($_GET['id'])) 			stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
$id = (int) $_GET["id"];



$res = sql_query("SELECT pages.*, users.username, users.ratingsum AS userrating, users.class as uclass FROM pages LEFT JOIN users ON pages.owner = users.id WHERE pages.id = $id")
or sqlerr(__FILE__, __LINE__);
$row = mysql_fetch_array($res);

if (($row['class']<>-1) && !$CURUSER) loggedinorreturn();
$owned = $moderator = 0;
if (get_user_class() >= UC_MODERATOR)
$owned = $moderator = 1;
elseif ($CURUSER["id"] == $row["owner"])
$owned = 1;

if (!$row)
stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
else {
	if (get_user_class()<$row['class'] && !$owned) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('access_denied'));


	stdhead("Страница "." \"" . $row["name"] . "\"");

	if ($CURUSER["id"] == $row["owner"] || get_user_class() >= UC_MODERATOR)
	$owned = 1;
	else
	$owned = 0;

	$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";


	print("<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
	print("<tr><td class=\"colhead\" colspan=\"2\"><div style=\"float: left; width: auto;\">:: "."Просмотр страницы | <a href=\"".$REL_SEO->make_link('pagebrowse')."\">К списку</a>"."</div><div align=\"right\"><a href=\"".$REL_SEO->make_link('pagedetails','id',$id,'name',translit($row['name']))."#comments\"><b>{$REL_LANG->say_by_key('add_comment')}</b></a></div></td></tr>");
	if (isset($_GET["returnto"])) {
		$addthis = "&amp;returnto=" . urlencode($_GET["returnto"]);
		$keepget .= $addthis;
	}
	$editlink = "a href=\"".(isset($_GET["returnto"]) ? $REL_SEO->make_link('pageedit','id',$row["id"],'ruturno',urlencode($_GET["returnto"])) : $REL_SEO->make_link('pageedit','id',$row["id"]))."\" class=\"sublink\"";
	if ($owned)
	{ $s="<br />";
	$s .= " $spacer<$editlink>[".$REL_LANG->say_by_key('edit')."]</a>";}

	tr ("Страница".$s, "<h1>".$row["name"] . "</h1>", 1, 1, "10%");

	print('<tr><td colspan="2" style="text-align:left;">'.format_comment($row['content']).'<hr />Эту страницу можно найти по: '.makesafe($row['tags']).'</td></tr>');

	// make main category and childs

	$tree = make_pages_tree(get_user_class());

	$cats = explode(',',$row['category']);
	$cat= array_shift($cats);
	$cat = get_cur_branch($tree,$cat);
	$childs = get_childs($tree,$cat['parent_id']);
	if ($childs) {
		foreach($childs as $child)
		if (($cat['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"".$REL_SEO->make_link('pagebrowse','cat',$child['id'],'name',translit($child['name']))."\">".makesafe($child['name'])."</a>";
	}
	tr ($REL_LANG->say_by_key('type'),get_cur_position_str($tree,$cat['id'],'pagebrowse').(is_array($chsel)?', '.implode(', ',$chsel):''),1);
	if (!$CURUSER) {
			print("</table></p>\n");
		stdfoot(); die();
	}
	//    if (!empty($row['online']))
	// tr("Смотреть онлайн<br />","<form method=\"post\" action=\"online/onlinevideo.php\"><input type=\"hidden\" name=\"onlinevideo\" value=\"".$row['online']."\"><input type=\"submit\" value=\"Смотреть онлайн на $REL_CONFIG['defaultbaseurl']\" /></form> <b>ТОЛЬКО ДЛЯ КПК НА WINDOWS MOBILE 5.0 ВЫШЕ! ФОРМАТ WMV</b>",1,1);
	tr("Класс доступа",get_user_class_name($row['class']),1);


	tr($REL_LANG->say_by_key('added'), mkprettytime($row["added"]));
	tr($REL_LANG->say_by_key('views'), $row["views"]);
	$uprow = (isset($row["username"]) ? ("<a href=\"".$REL_SEO->make_link('userdetails','id',$row["owner"],'username',translit($row["username"]))."\">" . get_user_class_color($row['uclass'],$row["username"]) . "</a>") : "<i>Аноним</i>");

	tr("Автор", ($row['owner']?$uprow.$spacer.ratearea($row['userrating'],$row['owner'],'users',$CURUSER['id']):$REL_LANG->say_by_key('from_system')), 1);
	tr('Индексируется в текстовых областях', ($row["indexed"]?$REL_LANG->say_by_key('yes'):$REL_LANG->say_by_key('no')));
	tr($REL_LANG->say_by_key('vote'),ratearea($row['ratingsum'],$id,'pages',(($row['owner']==$CURUSER['id'])?$id:0) ),1);


	print("</table></p>\n");
	/*
	 print("<div align=\"center\"><a href=\"#\" onclick=\"location.href='pass_on.php?to=pre&from=" .$id. "'; return false;\">
	 << Предыдущий релиз</a>&nbsp;
	 <a href=\"#\" onclick=\"location.href='pass_on.php?to=pre&from=" .$id. "&cat=" .$row['category']. "'; return false;\">[из этой категории]</a>
	 &nbsp; | &nbsp;
	 <a href=\"#\" onclick=\"location.href='pass_on.php?to=next&from=" .$id. "&cat=" .$row['category']. "'; return false;\">[из этой категории]</a>&nbsp;
	 <a href=\"#\" onclick=\"location.href='pass_on.php?to=next&from=" .$id. "'; return false;\">
	 Следующий релиз >></a><br />
	 <a href=\"browse.php\">Все релизы</a>
	 &nbsp; | &nbsp;
	 <a href=\"browse.php?cat=" .$row['category']. "\">Все релизы этой категории</a></div>");  */

}
if (!$row['denycomments']) {
	$count = @mysql_result(sql_query("SELECT SUM(1) FROM pagecomments WHERE page = $id"),0);

	$limited = 10;

	if (!$count) {

		print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
		print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
		print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев {$REL_CONFIG['defaultbaseurl']}</div>");
		print("<div align=\"right\"><a href=\"".$REL_SEO->make_link('pagedetails','id',$id,'name',translit($row['name']))."#comments\" class=altlink_white>Добавить комментарий</a></div>");
		print("</td></tr><tr><td align=\"center\">");
		print("Комментариев нет. <a href=\"".$REL_SEO->make_link('pagedetails','id',$id,'name',translit($row['name']))."#comments\">Желаете добавить?</a>");
		print("</td></tr></table><br />");

	}
	else {
		list($pagertop, $pagerbottom, $limit) = pager($limited, $count, $REL_SEO->make_link('pagedetails','id',$id,'name',translit($row['name']))."&", array('lastpagedefault' => 1));

		$subres = sql_query("SELECT c.id, c.post_id, c.ip, c.ratingsum, c.text, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, ".
                  "u.username, u.title, u.class, u.donor, u.enabled, u.ratingsum AS urating, u.gender, s.time AS last_access, e.username AS editedbyname FROM pagecomments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN users AS e ON c.editedby = e.id  LEFT JOIN sessions AS s ON s.uid=u.id WHERE page = " .
                  "$id GROUP BY c.id ORDER BY c.id $limit") or sqlerr(__FILE__, __LINE__);
		$allrows = array();
		while ($subrow = mysql_fetch_array($subres)) {
			$subrow['subject'] = $row['name'];
			$subrow['link'] = $REL_SEO->make_link('pagedetails','id',$id,'name',translit($row['name']))."#comm{$subrow['id']}";
			$allrows[] = $subrow;
		}

		print("<table id=\"comments-table\" class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
		print("<tr><td class=\"colhead\" align=\"center\" >");
		print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
		print("<div align=\"right\"><a href=\"".$REL_SEO->make_link('pagedetails','id',$id,'name',translit($row['name']))."#comments\" class=\"altlink_white\">{$REL_LANG->say_by_key('add_comment')}</a></div>");
		print("</td></tr>");

		print("<tr><td>");
		print($pagertop);
		print("</td></tr>");
		print("<tr><td>");
		commenttable($allrows,'pagecomment');
		print("</td></tr>");
		print("<tr><td>");
		print($pagerbottom);
		print("</td></tr>");
		print("</table>");
	}

	print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
	print("<tr><td class=colhead align=\"left\" colspan=\"2\">  <div id=\"comments\"></div><b>:: {$REL_LANG->say_by_key('add_comment')} к странице | ".is_i_notified($id,'pagecomments')."</b></td></tr>");
	print("<tr><td width=\"100%\" align=\"center\" >");
	//print("Ваше имя: ");
	//print("".$CURUSER['username']."<p>");
	print ( "<form name=comment method=\"post\" action=\"".$REL_SEO->make_link('pagecomment','action','add')."\">" );
	print ( "<table width=\"0%\"><tr><td align=\"center\">" . textbbcode ( "text") . "</td></tr>" );

	print ( "<tr><td  align=\"center\">" );
	print ( "<input type=\"hidden\" name=\"tid\" value=\"$id\"/>" );
	print ( "<input type=\"submit\" class=\"btn\" value=\"Разместить комментарий\" />" );
	print ( "</td></tr></table></form></td></tr></table>" );
} else stdmsg('Запрет','Комментарии к этой странице запрещены');


sql_query("UPDATE pages SET views = views + 1 WHERE id = $id");
set_visited('pages',$id);
stdfoot();

?>
