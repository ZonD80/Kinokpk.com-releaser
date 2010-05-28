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

if (get_user_class() < UC_SYSOP) bark("Access denied. You're not SYSOP.");
if (!isset($_GET['action'])) {
  stdhead("Админка штампов и печатей");
  print("<div algin=\"center\"><h1>Админка штампов</h1></div>");
  print("<table width=\"100%\" border=\"0\"><tr><td><a href=\"?action=add\">Добавить штамп</a></td></tr></table>");
  $stamparray = mysql_query("SELECT * FROM stamps ORDER BY id ASC");
  print("<table width=\"100%\" border=\"1\"><tr><td class=\"colhead\">ID</td><td class=\"colhead\">Картинка</td><td class=\"colhead\">Порядок</td><td class=\"colhead\">Класс доступа</td><td class=\"colhead\">Ред/Уд</td></tr><form name=\"saveids\" action=\"?action=saveids\" method=\"post\">");
  while($stamp = mysql_fetch_array($stamparray)) {
    print("<tr><td>".$stamp['id']."</td><td><img src=\"".$DEFALUTBASEURL."/pic/stamp/".$stamp['image']."\"></td><td><input type=\"text\" name=\"sort[".$stamp['id']."]\" size=\"4\" value=\"".$stamp['sort']."\"></td><td>".get_user_class_name($stamp['class'])."</td><td><a href=\"?action=edit&id=".$stamp['id']."\">E</a> | <a onClick=\"return confirm('Вы уверены?')\" href=\"?action=delete&id=".$stamp['id']."\">D</a></td></tr>");
  }
  print("</table><input type=\"submit\" class=\"btn\" value=\"Сохранить порядок отображения\"></form>");
  stdfoot();
}

elseif ($_GET['action'] == 'saveids') {

if (is_array($_POST['sort'])) {

    foreach ($_POST['sort'] as $id => $s) {

    mysql_query("UPDATE stamps SET sort = ".intval($s)."  WHERE id = " . $id);
  }
                      header("Location: stampadmin.php");
                exit();
}
 else bark("Missing form data");
}

elseif ($_GET['action'] == 'add') {
  stdhead("Добавление штампа");
  print("<table width=\"100%\"><form action=\"?action=saveadd\" enctype=\"multipart/form-data\" name=\"savearray\" method=\"post\"><tr><td class=\"colhead\">Картинка (имя файла)</td></tr><tr><td><input type=\"text\" name=\"image\" size=\"80\"></td></tr><tr><td class=\"colhead\">Сортировка (порядок положения)</td></tr><tr><td><input type=\"text\" name=\"sort\" size=\"4\"></td></tr><tr><td class=\"colhead\">Класс доступа</td></tr><tr><td>
  <select size=\"1\" name=\"class\">
<option value=\"6\">Только Директорат</option>
<option value=\"5\">Администраторы и выше</option>
<option value=\"4\">Модераторы и выше</option>
<option value=\"3\">Аплоадеры и выше</option>
<option value=\"2\">VIP'ы и выше</option>
<option value=\"1\">Продвинутые и выше</option>
<option value=\"0\">Пользователи и выше</option>
</select></td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"Добавить\"></form></td></tr></table>");
stdfoot();
}

elseif ($_GET['action'] == 'delete') {
  if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");
  mysql_query("DELETE FROM stamps WHERE id = ".$_GET['id']);
                        header("Location: stampadmin.php");
                exit();
  
}

elseif ($_GET['action'] == 'edit') {
    if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");
    
    $stamparray = mysql_query("SELECT * FROM stamps WHERE id=".$_GET['id']);
    list($id,$sort,$class,$image) = mysql_fetch_array($stamparray);
    
                        stdhead("Редактирование штампа");
  print("<table width=\"100%\"><form name=\"save\" enctype=\"multipart/form-data\" action=\"?action=saveedit\" method=\"post\"><tr><td class=\"colhead\">Картинка (имя файла)</td></tr><tr><td><input type=\"hidden\" name=\"id\" value=\"".$id."\"><input type=\"text\" name=\"image\" size=\"80\" value=\"".$image."\"></td></tr><tr><td class=\"colhead\">Сортировка (порядок положения)</td></tr><tr><td><input type=\"text\" name=\"sort\" size=\"4\" value=\"".$sort."\"></td></tr><tr><td class=\"colhead\">Класс доступа</td></tr><tr><td>
<select size=\"1\" name=\"class\">
<option ".($class == "6" ? "selected" : "")." value=\"6\">Только Директорат</option>
<option ".($class == "5" ? "selected" : "")." value=\"5\">Администраторы и выше</option>
<option ".($class == "4" ? "selected" : "")." value=\"4\">Модераторы и выше</option>
<option ".($class == "3" ? "selected" : "")." value=\"3\">Аплоадеры и выше</option>
<option ".($class == "2" ? "selected" : "")." value=\"2\">VIP'ы и выше</option>
<option ".($class == "1" ? "selected" : "")." value=\"1\">Продвинутые и выше</option>
<option ".($class == "0" ? "selected" : "")." value=\"0\">Пользователи и выше</option>
</select></td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"Отредактировать\"></form></td></tr></table>");
stdfoot();
}

elseif ($_GET['action'] == 'saveedit') {
  mysql_query("UPDATE stamps SET image='".$_POST['image']."',sort=".intval($_POST['sort']).",class=".intval($_POST['class'])." WHERE id=".intval($_POST['id']));
                        header("Location: stampadmin.php");
                exit();
  }
elseif ($_GET['action'] == 'saveadd') {

  mysql_query("INSERT INTO stamps (image,sort,class) VALUES ('".$_POST['image']."',".intval($_POST['sort']).",".intval($_POST['class']).")");
                      header("Location: stampadmin.php");
                exit();
}


