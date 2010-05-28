<?php
// Stamps administration for TBDEV Kinokpk.com edition //
// Also tested on TBDEV YSE //
// Copyright ZonD80 //
require_once "include/bittorrent.php";

function bark($msg) {
	genbark($msg, $tracker_lang['error']);
}

dbconn();

if (get_user_class() < UC_SYSOP) bark("Access denied. You're not SYSOP.");
if (!isset($_GET['action'])) {
  stdhead("Админка Форматов исходника");
  print("<div algin=\"center\"><h1>Админка форматов исходника</h1></div>");
  print("<table width=\"100%\" border=\"0\"><tr><td><a href=\"?action=add\">Добавить формат</a></td></tr></table>");
  $sourcearray = mysql_query("SELECT * FROM sources ORDER BY id ASC");
  print("<table width=\"100%\" border=\"1\"><tr><td class=\"colhead\">ID</td><td class=\"colhead\">Формат</td><td class=\"colhead\">Ред/Уд</td></tr>");
  while($source = mysql_fetch_array($sourcearray)) {
    print("<tr><td>".$source['id']."</td><td>".$source['name']."</td><td><a href=\"?action=edit&id=".$source['id']."\">E</a> | <a onClick=\"return confirm('Вы уверены?')\" href=\"?action=delete&id=".$source['id']."\">D</a></td></tr>");
  }
  print("</table>");
  stdfoot();
}

elseif ($_GET['action'] == 'add') {
  stdhead("Добавление формата исходника");
  print("<table width=\"100%\"><tr><td class=\"colhead\">Формат исходника</td></tr><form action=\"?action=saveadd\" enctype=\"multipart/form-data\" name=\"saveadd\" method=\"post\"><tr><td><input type=\"text\" size=\"60\" name=\"source\"></td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"Добавить\"></form></td></tr></table>");
stdfoot();
}

elseif ($_GET['action'] == 'delete') {
  if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");
  mysql_query("DELETE FROM sources WHERE id = ".$_GET['id']);
                        header("Location: sourceadmin.php");
                exit();
  
}

elseif ($_GET['action'] == 'edit') {
    if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");
    
    $sourcearray = mysql_query("SELECT * FROM sources WHERE id=".$_GET['id']);
    list($id,$name) = mysql_fetch_array($sourcearray);
    
                        stdhead("Редактирование формата исходника");
  print("<table width=\"100%\"><form name=\"save\" enctype=\"multipart/form-data\" action=\"?action=saveedit\" method=\"post\"><tr><td class=\"colhead\">Картинка (имя файла)</td></tr><tr><td><input type=\"hidden\" name=\"id\" value=\"".$id."\"><input type=\"text\" name=\"source\" size=\"60\" value=\"".$name."\"></td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"Отредактировать\"></form></td></tr></table>");
stdfoot();
}

elseif ($_GET['action'] == 'saveedit') {
  mysql_query("UPDATE sources SET name='".$_POST['source']."' WHERE id=".intval($_POST['id']));
                        header("Location: sourceadmin.php");
                exit();
  }
elseif ($_GET['action'] == 'saveadd') {

  mysql_query("INSERT INTO sources (name) VALUES ('".$_POST['source']."')");
                      header("Location: sourceadmin.php");
                exit();
}


