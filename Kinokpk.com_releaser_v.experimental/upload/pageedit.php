<?php
/**
 * Page edit form
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

dbconn();

loggedinorreturn();

if (!is_valid_id($_GET['id'])) 			stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

$id = (int) $_GET['id'];
$tree = make_pages_tree(get_user_class());

$res = sql_query("SELECT * FROM pages WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

stdhead("Редактирование страницы \"" . makesafe($row["name"]) . "\"");

if (($CURUSER["id"] != $row["owner"]) && (get_user_class() < UC_MODERATOR)) {
	stdmsg($REL_LANG->say_by_key('error'),"Вы не можете редактировать эту страницы.");
} else {
	print("<form name=\"edit\" method=post action=pagetakeedit.php enctype=multipart/form-data>\n");
	print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
	if (isset($_GET["returnto"]))
	print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_GET["returnto"]) . "\" />\n");
	print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
	print("<tr><td class=\"colhead\" colspan=\"2\">Редактировать страницу</td></tr>");
	tr("Название страницы"."<font color=\"red\">*</font>", "<input type=\"text\" name=\"name\" value=\"" . strip_tags($row["name"]) . "\" size=\"80\" />", 1);
	tr('Теги страницы'."<br /><small>Заполняются через запятую, <b>без пробелов</b></small>", "<input type=\"text\" name=\"tags\" size=\"80\" value=\"".strip_tags($row['tags'])."\" />\n", 1);
	tr($REL_LANG->say_by_key('description'),textbbcode("descr",$row['content']),1);

	// make main category an childs
	$cats = explode(',',$row['category']);
	$cat= array_shift($cats);
	$cat = get_cur_branch($tree,$cat);
	$childs = get_childs($tree,$cat['parent_id']);
	if ($childs) {
		$chsel='<table width="100%" border="1">';
		foreach($childs as $child)
		if ($cat['id'] != $child['id']) $chsel.="<tr><td><input type=\"checkbox\" name=\"type[]\" value=\"{$child['id']}\"".(in_array($child['id'],$cats)?' checked':'').">&nbsp;{$child['name']}</td></tr>";
		$chsel.="</table>";
	}
	tr ("Главный раздел",gen_select_area('type[]',$tree,$cat['id']),1);
	tr ("Подразделы",$chsel,1);

	if (get_user_class() >= UC_MODERATOR) {

		// class selection
		$classsel = '<select name="class">';
		for ($i=get_user_class();$i--;$i<=0){
			$classsel.= "<option value=\"$i\"".($row['class']==$i?' selected':'').">".get_user_class_name($i)."</option>\n";
		}
			$classsel.= "<option value=\"-1\"".($row['class']==$i?' selected':'').">".$REL_LANG->say_by_key('guest')."</option>\n";
		$classsel .='</select>';
		// class selection end
		tr('Класс доступа',$classsel,1);
		tr('Запретить комментарии',"<input type=\"checkbox\" name=\"denycomments\" value=\"1\"".(($row['denycomments'])?" checked=\"1\"":'').">",1);
		tr("Важная", "<input type=\"checkbox\" name=\"sticky\" value=\"1\"".(($row['sticky'])?" checked=\"1\"":'').">Прикрепить эту страницу (всегда наверху)", 1);
		tr("Обновить<br />(поместить наверх)", "<input type=\"checkbox\" name=\"upd\" value=\"1\">У страницы обновится дата создания и она станет первой в списке страниц", 1);

		tr("Создатель - ".$REL_LANG->say_by_key('from_system'),"<input type=\"checkbox\" name=\"system\" value=\"1\"".((!$row['owner'])?" checked=\"1\"":'').">",1);
		tr("Индексируется","<input type=\"checkbox\" name=\"indexed\" value=\"1\"".(($row['indexed'])?" checked=\"1\"":'').">",1);
		print('<tr><td colspan="2" align="center"><input type="submit" onclick="return confirm(\'Вы уверены?\');" name="delete" value="Удалить эту страницу"></td></tr>');
	}
	print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"{$REL_LANG->say_by_key('edit')}\" style=\"height: 25px; width: 100px\"> <input type=reset value=\"Обратить изменения\" style=\"height: 25px; width: 100px\"></td></tr>\n");
	print("</table>\n");
	print("</form>\n");

	stdfoot();
}

?>