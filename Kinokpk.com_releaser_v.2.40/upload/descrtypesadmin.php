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

require_once "include/bittorrent.php";

function bark($msg) {
	genbark($msg, $tracker_lang['error']);
}

dbconn();
loggedinorreturn();
gzip();
httpauth();

if (get_user_class() < UC_SYSOP) bark("Access denied. You're not SYSOP.");


if ((!isset($_GET['class'])) && (!isset($_GET['faq']))) {
	stdhead("Админка динамических описаний");
	print('<table width="100%" border="1"><tr><td class="colhead"><h1>Выберите шаблон</h1></td><td class="colhead" width="100px" aling="right"><div align="center"><a href="?faq"><font color="red">FAQ</font></a></div></td></tr></table><table width="100%" border="1"><tr><td><a href="?class=type">шаблоны контента</a></td><td><a href="?class=details">Детали шаблонов контента</a></td></tr></table>');
	stdfoot();
	die;
} elseif (isset($_GET['faq'])) {
	stdhead("Справочная информация системы динамических описаний");
	print("<div aling=\"center\"><h1>Справка для админа (система динамических описаний)</h1></div>");
	print nl2br('В основном эта инструкция касается задания параметров для шаблона option.
  Если вам надо задать параметры для шаблона option, то это можно сделать, перечисляя доступные опции через запятую, например:
  задав <b>в поле маска</b>: DVDrip,HDTVrip вы получите
  <select><option value="DVDrip">DVDrip</option><option value="HDTVrip">HDTVrip</option></select>
  
  Если же вам необходимо скрыть вставляемое в шаблон значение, заменив его описанием, то это можно сделать, заполнив описание соответственно для каждой опции:
  маска: DVDrip,HDTVrip
  описание: худшее кач-во, лучшее кач-во
  итог:
  <select><option value="DVDrip">худшее кач-во</option><option value="HDTVrip">лучшее кач-во</option></select>
  
  <div aling="center"><b>ВНИМАНИЕ!</b></div>
  
  Если вам необходимо обеспечить форматирование вставляемого или отображаемго значения,то:
  <b>Маска - редактируется в BBкодах
  Описание - редактируется в HTML</b>');
	stdfoot();
	die;

}
elseif (!isset($_GET['action']) && ($_GET['class'] == 'type')) {
	stdhead("Админка динамических шаблонов контента");
	$res = sql_query("SELECT descr_types.id, descr_types.type, categories.name AS category FROM descr_types LEFT JOIN categories ON descr_types.category = categories.id ORDER BY descr_types.id ASC");
	print("<table width=\"100%\" border=\"0\"><tr><td><a href=\"?class=type&action=add\">Добавить шаблон контента</a></td><td><a href=\"descrtypesadmin.php\">К началу страницы администрирования шаблонов контента</a></td></tr></table>");
	print('<table width="100%" border="1"><tr><td class="colhead">ID</td><td class="colhead">Название</td><td class="colhead">Категория</td><td class="colhead">Ред/Уд</td></tr>');
	while ($row = mysql_fetch_array($res)) {
		print("<tr><td>".$row['id']."</td><td>".$row['type']."</td><td>".$row['category']."</td><td><a href=\"?class=type&action=edit&id=".$row['id']."\">E</a> | <a onClick=\"return confirm('Вы уверены?')\" href=\"?class=type&action=delete&id=".$row['id']."\">D</a></td></tr>");
	}
	print("</table>");
	stdfoot();
	die;
}

elseif (!isset($_GET['action']) && ($_GET['class'] == 'details')) {
	stdhead("Админка деталей динамических шаблонов контента");
	print("<div algin=\"center\"><h1>Детали шаблонов контента</h1></div>");
	print("<table width=\"100%\" border=\"0\"><tr><td><a href=\"?class=details&action=add\">Добавить переменную</a></td><td><a href=\"descrtypesadmin.php\">К началу страницы администрирования шаблонов контента</a></td></tr></table>");
	$detarray = sql_query("SELECT descr_details.*, descr_types.type FROM descr_details LEFT JOIN descr_types ON descr_details.typeid = descr_types.id ORDER BY descr_details.typeid,descr_details.sort ASC");
	print("<table width=\"100%\" border=\"1\"><tr><td class=\"colhead\">ID</td><td class=\"colhead\">Родитель</td><td class=\"colhead\">Порядок</td><td class=\"colhead\">Название</td><td class=\"colhead\">Описание</td><td class=\"colhead\">Тип</td><td class=\"colhead\">Размер поля</td><td class=\"colhead\">Цифровой?</td><td class=\"colhead\">Обязателен ввод</td><td class=\"colhead\">Маска</td><td class=\"colhead\">Виден на главной</td><td class=\"colhead\">Автоспойлер</td><td class=\"colhead\">Скрыт</td><td class=\"colhead\">Ред/Уд</td></tr><form name=\"saveids\" action=\"?class=details&action=saveids\" method=\"post\">");
	while($detail = mysql_fetch_array($detarray)) {
		print("<tr><td>".$detail['id']."</td><td>".$detail['type']."</td><td><input type=\"text\" name=\"sort[".$detail['id']."]\" size=\"4\" value=\"".$detail['sort']."\"></td><td>".$detail['name']."</td><td>".(($detail['description'] == '')?"---":$detail['description'])."</td><td>".$detail['input']."</td><td>".$detail['size']."</td><td>".$detail['isnumeric']."</td><td>".$detail['required']."</td><td>".(!empty($detail['mask'])?$detail['mask']:"---")."</td><td>".$detail['mainpage']."</td><td>".$detail['spoiler']."</td><td>".$detail['hide']."</td><td><a href=\"?class=details&action=edit&id=".$detail['id']."\">E</a> | <a onClick=\"return confirm('Вы уверены?')\" href=\"?class=details&action=delete&id=".$detail['id']."\">D</a></td></tr>");
	}
	print("</table><input type=\"submit\" class=\"btn\" value=\"Сохранить порядок отображения\"></form>");
	stdfoot();
}

elseif (($_GET['action'] == 'saveids') && ($_GET['class'] == 'details')) {
	if (is_array($_POST['sort'])) {

		foreach ($_POST['sort'] as $id => $s) {

			sql_query("UPDATE descr_details SET sort = ".intval($s)."  WHERE id = " . $id);
		}
		header("Location: descrtypesadmin.php?class=details");
		exit();
	}
	else bark("Missing form data");
}

elseif (($_GET['action'] == 'add') && ($_GET['class'] == 'type')) {
	stdhead("Добавление динамического шаблона");
	$res = sql_query("SELECT id,name AS type FROM categories ORDER BY id ASC");
	while ($row = mysql_fetch_array($res)){
		$ids[] = $row['id'];
		$types[] = $row['type'];
	}

	$s = "<select name=\"сategory\">\n";

	foreach($ids as $opt => $desc) {

		$s .= "<option value=\"".$desc."\">" . $types[$opt] . "</option>\n";

	}

	$s .= "</select>\n";
	print("<table width=\"100%\"><form action=\"?class=type&action=saveadd\" enctype=\"multipart/form-data\" name=\"savearray\" method=\"post\"><tr><td class=\"colhead\">Название шаблона контента</td></tr><tr><td><input type=\"text\" name=\"type\" size=\"80\"></td></tr><tr><td class=\"colhead\">Категория:$s</td></tr></table>
  <input type=\"submit\" class=\"btn\" value=\"Добавить\"></form>");
	stdfoot();
}

elseif (($_GET['action'] == 'add') && ($_GET['class'] == 'details')) {
	stdhead("Добавление детали шаблона");
	$res = sql_query("SELECT * FROM descr_types ORDER BY id ASC");
	while ($row = mysql_fetch_array($res)){
		$ids[] = $row['id'];
		$types[] = $row['type'];
	}
	print("<div align=\"center\"><b>Добавление детали шаблона контента</b></div><br/><table width=\"100%\" border=\"1\"><form action=\"?class=details&action=saveadd\" enctype=\"multipart/form-data\" name=\"savearray\" method=\"post\"><tr><td>Название детали для контента:</td><td><input type=\"text\" name=\"name\" size=\"40\"></td></tr>");

	$s = "<select name=\"typeid\">\n";

	foreach($ids as $opt => $desc) {

		$s .= "<option value=\"".$desc."\">" . $types[$opt] . "</option>\n";

	}

	$s .= "</select>\n";
	print("<tr><td>Родитель:</td><td>$s</td></tr>");
	print('<tr><td>Сортировка:</td><td><input type="text" size="3" name="sort"></td></tr>');
	print('<tr><td>Маска (BBcodes) :</td><td><textarea name="mask" rows="10" cols="60" wrap="off"></textarea></td></tr>');
	print('<tr><td>Описание (HTML) :</td><td><textarea name="description" rows="10" cols="60" wrap="off"></textarea></td></tr>');
	print('<tr><td>Тип:</td><td><select name="input">
<option value="text">Текст</option>
<option value="bbcode">ББкод</option>
<option value="option">Опции</option>
<option value="links">Ссылки</option>
</select></td></tr>');
	print('<tr><td>Размер (только для текста, size) :</td><td><input type="text" size="2" name="size"></td></tr>');
	print('<tr><td>Цифровое поле (проверка на is_numeric) :</td><td><input type="checkbox" name="isnumeric" value="yes"></td></tr>');
	print('<tr><td>Обязательно для заполнения (проверка на !empty) :</td><td><input type="checkbox" name="required" value="yes"></td></tr>');
	print('<tr><td>Скрыто от гостей (проверка $CURUSER) :</td><td><input type="checkbox" name="hide" value="yes"></td></tr>');
	print('<tr><td>Автоматически сворачивается в спойлер :</td><td><input type="checkbox" name="spoiler" value="yes"></td></tr>');
	print('<tr><td>Отображается в блоке релизов на главной :</td><td><input type="checkbox" name="mainpage" value="yes"></td></tr>');
	print("</table><input type=\"submit\" class=\"btn\" value=\"Добавить\"></form>");
	stdfoot();
}

elseif (($_GET['action'] == 'delete') && ($_GET['class'] == 'type')) {
	if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");
	sql_query("DELETE FROM descr_types WHERE id = ".$_GET['id']);
	sql_query("DELETE FROM descr_details WHERE typeid = ".$_GET['id']);
	header("Location: descrtypesadmin.php?class=type");
	exit();

}

elseif (($_GET['action'] == 'delete') && ($_GET['class'] == 'details')) {
	if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");
	sql_query("DELETE FROM descr_details WHERE id = ".$_GET['id']);
	@sql_query("DELETE FROM descr_torrents WHERE typeid = ".$_GET['id']);
	header("Location: descrtypesadmin.php?class=details");
	exit();

}

elseif (($_GET['action'] == 'edit') && ($_GET['class'] == 'type')) {
	if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");

	$typearray = sql_query("SELECT * FROM descr_types WHERE id=".$_GET['id']);
	list($id,$type,$category) = mysql_fetch_array($typearray);

	$res = sql_query("SELECT id,name AS type FROM categories ORDER BY id ASC");
	while ($row = mysql_fetch_array($res)){
		$ids[] = $row['id'];
		$types[] = $row['type'];
	}
	$s = "<select name=\"сategory\">\n";

	foreach($ids as $opt => $desc) {

		$s .= "<option ".(($desc==$category)?"selected":"")." value=\"".$desc."\">" . $types[$opt] . "</option>\n";

	}

	$s .= "</select>\n";
	stdhead("Редактирование шаблона контента");
	print("<table width=\"100%\"><form name=\"save\" enctype=\"multipart/form-data\" action=\"?class=type&action=saveedit\" method=\"post\"><tr><td class=\"colhead\">Название шаблона контента</td></tr><tr><td><input type=\"text\" name=\"type\" size=\"80\" value=\"$type\"></td></tr><tr><td class=\"colhead\">Категория:$s</table>
  <input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">
  <input type=\"submit\" class=\"btn\" value=\"Отредактировать\"></form>");
	stdfoot();
}

elseif (($_GET['action'] == 'edit') && ($_GET['class'] == 'details')) {
	if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");

	stdhead("Редактирование деталей шаблона контента");
	$res = sql_query("SELECT * FROM descr_types ORDER BY id ASC");
	while ($row = mysql_fetch_array($res)){
		$ids[] = $row['id'];
		$types[] = $row['type'];
	}
	$detarray = sql_query("SELECT * FROM descr_details WHERE id=".$_GET['id']);
	$detail = mysql_fetch_array($detarray);
	print("<div align=\"center\"><b>Редактирование детали шаблона контента</b></div><br/><table width=\"100%\" border=\"1\"><form action=\"?class=details&action=saveedit\" enctype=\"multipart/form-data\" name=\"save\" method=\"post\"><tr><td>Название детали для контента:</td><td><input type=\"text\" name=\"name\" size=\"40\" value=\"".$detail['name']."\"></td></tr>");

	$s = "<select name=\"typeid\">\n";

	foreach($ids as $opt => $desc) {

		$s .= "<option ".(($desc==$detail['typeid'])?"selected":"")." value=\"".$desc."\">" . $types[$opt] . "</option>\n";

	}

	$s .= "</select>\n";
	print("<tr><td>Родитель:</td><td>$s</td></tr>");
	print('<tr><td>Сортировка:</td><td><input type="text" size="3" name="sort" value="'.$detail['sort'].'"></td></tr>');
	print('<tr><td>Маска:</td><td><textarea name="mask" rows="10" cols="60" wrap="off">'.$detail['mask'].'</textarea></td></tr>');
	print('<tr><td>Описание:</td><td><textarea name="description" rows="10" cols="60" wrap="off">'.$detail['description'].'</textarea></td></tr>');
	print('<tr><td>Тип:</td><td><select name="input">
<option '.(($detail['input'] == 'text')?"selected":"").' value="text">Текст</option>
<option '.(($detail['input'] == 'bbcode')?"selected":"").' value="bbcode">ББкод</option>
<option '.(($detail['input'] == 'option')?"selected":"").' value="option">Опции</option>
<option '.(($detail['input'] == 'links')?"selected":"").' value="links">Ссылки</option>
</select></td></tr>');
	print('<tr><td>Размер (только для текста):</td><td><input type="text" size="2" name="size" value="'.$detail['size'].'"></td></tr>');
	print('<tr><td>Цифровое поле:</td><td><input type="checkbox" name="isnumeric" value="yes" '.(($detail['isnumeric'] == 'yes')?"checked":"").'></td></tr>');
	print('<tr><td>Обязательно для заполнения:</td><td><input type="checkbox" name="required" value="yes" '.(($detail['required'] == 'yes')?"checked":"").'></td></tr>');
	print('<tr><td>Скрыто от гостей:</td><td><input type="checkbox" name="hide" value="yes" '.(($detail['hide'] == 'yes')?"checked":"").'></td></tr>');
	print('<tr><td>Скрывается спойлером:</td><td><input type="checkbox" name="spoiler" value="yes" '.(($detail['spoiler'] == 'yes')?"checked":"").'></td></tr>');
	print('<tr><td>Отображается в блоке релизов на главной:</td><td><input type="checkbox" name="mainpage" value="yes" '.(($detail['mainpage'] == 'yes')?"checked":"").'></td></tr>');
	print("</table><input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\"><input type=\"submit\" class=\"btn\" value=\"Отредактировать\"></form>");
	stdfoot();
}

elseif (($_GET['action'] == 'saveedit') && ($_GET['class'] == 'type')) {

	sql_query("UPDATE descr_types SET type='".$_POST['type']."', category = ".intval($_POST['сategory'])." WHERE id=".intval($_POST['id']));
	header("Location: descrtypesadmin.php?class=type");
	exit();
}

elseif (($_GET['action'] == 'saveedit') && ($_GET['class'] == 'details')) {
	if ($_POST['size'] == '') $size = 0; else $size=$_POST['size'];
	if ($_POST['isnumeric'] == 'yes') $isnumeric='yes'; else $isnumeric = 'no';
	if ($_POST['required'] == 'yes') $required = 'yes'; else $required = 'no';
	if ($_POST['spoiler'] == 'yes') $spoiler = 'yes'; else $spoiler = 'no';
	if ($_POST['hide'] == 'yes') $hide = 'yes'; else $hide='no';
	if ($_POST['mainpage'] == 'yes') $mainpage = 'yes'; else $mainpage='no';
	sql_query("UPDATE descr_details SET typeid=".$_POST['typeid'].", sort=".intval($_POST['sort']).", name='".$_POST['name']."',description='".$_POST['description']."',input='".$_POST['input']."', size='".$size."', isnumeric='".$isnumeric."', required='".$required."', mask='".$_POST['mask']."', spoiler='".$spoiler."', hide='".$hide."', mainpage='".$mainpage."' WHERE id=".intval($_POST['id']));
	header("Location: descrtypesadmin.php?class=details");
	exit();
}

elseif (($_GET['action'] == 'saveadd') && ($_GET['class'] == 'type')) {

	sql_query("INSERT INTO descr_types (type, category) VALUES ('".$_POST['type']."', ".intval($_POST['сategory']).")") or die(mysql_error());

	header("Location: descrtypesadmin.php?class=type");
	exit();
}

elseif (($_GET['action'] == 'saveadd') && ($_GET['class'] == 'details')) {
	if ($_POST['size'] == '') $size = 0; else $size=$_POST['size'];
	if ($_POST['isnumeric'] == 'yes') $isnumeric='yes'; else $isnumeric = 'no';
	if ($_POST['required'] == 'yes') $required = 'yes'; else $required = 'no';
	if ($_POST['spoiler'] == 'yes') $spoiler = 'yes'; else $spoiler = 'no';
	if ($_POST['hide'] == 'yes') $hide = 'yes'; else $hide='no';
	if ($_POST['mainpage'] == 'yes') $mainpage = 'yes'; else $mainpage='no';

	sql_query("INSERT INTO descr_details (typeid,sort,name,description,input,size,isnumeric,required,mask,spoiler,hide,mainpage) VALUES (".$_POST['typeid'].",".intval($_POST['sort']).",'".($_POST['name'])."','".$_POST['description']."','".$_POST['input']."',$size,'$isnumeric','$required','".$_POST['mask']."','$spoiler','$hide','$mainpage')");
	header("Location: descrtypesadmin.php?class=details");
	exit();
}
