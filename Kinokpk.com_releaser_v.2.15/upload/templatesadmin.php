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

if (get_user_class() < UC_SYSOP) die("Access denied. U're not sysop.");

if (!isset($_GET['action'])) {
  stdhead("Админка шкурок");
  begin_frame("Администрация шкурок");
  $res = sql_query("SELECT * FROM stylesheets ORDER BY id DESC");
  print('<div align="center"><a href="templatesadmin.php?action=add">Добавить новый шаблон в базу</a></div>');
  print('<table width="100%" border="1"><tr><td class="colhead">ID</td><td class="colhead">URI</td><td class="colhead">Название</td><td class="colhead">Редактировать</td></tr>');
  while ($row = mysql_fetch_array($res)){
  print("<tr><td>{$row['id']}</td><td>{$row['uri']}</td><td>{$row['name']}</td><td><a href=\"templatesadmin.php?action=edit&id={$row['id']}\">Ред.</a> / <a onClick=\"return confirm('Вы уверены?')\" href=\"templatesadmin.php?action=delete&id={$row['id']}\">Уд.</a></td></tr>");
}
print("</table>");
  
  end_frame();
  stdfoot();
}

elseif ($_GET['action'] == 'add') {
  stdhead("Админка шкурок: добавление новой шкурки");
    begin_frame("Новая шкурка");
    print('<table width="400px"><form action="templatesadmin.php?action=saveadd" method="POST">
    <tr><td>URI</td><td><input type="text" size="20" name="uri"></td></tr>
    <tr><td>Название</td><td><input type="text" size="20" name="name"></td></tr><tr><td><input type="submit" value="Добавить"></td></tr></form></table>');
    end_frame();
    stdfoot();
}

elseif ($_GET['action'] == 'saveadd') {
  if (empty($_POST['name']) || empty($_POST['uri'])) stderr($tracker_lang['error'],"Вы не ввели URI либо Название шкурки");
  
  sql_query("INSERT INTO stylesheets (uri,name) VALUES (".sqlesc($_POST['uri']).",".sqlesc($_POST['name']).")");
  header('Location: templatesadmin.php');
}

elseif ($_GET['action'] == 'delete') {
  if (!is_valid_id($_GET['id'])) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
  
  sql_query("DELETE FROM stylesheets WHERE id={$_GET['id']} LIMIT 1");
  header('Location: templatesadmin.php');
}

elseif ($_GET['action'] == 'edit') {
    if (!is_valid_id($_GET['id'])) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
     $id=0+$_GET['id'];
     
    $res = sql_query("SELECT * FROM stylesheets WHERE id=$id");
    $row = mysql_fetch_array($res);
    if (!$row) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
    
      stdhead("Админка шкурок: редактирование шкурки");
    begin_frame("Редактор шкурки");
    print('<table width="400px"><form action="templatesadmin.php?action=saveedit&id='.$id.'" method="POST">
    <tr><td>URI</td><td><input type="text" size="20" name="uri" value="'.$row['uri'].'"></td></tr>
    <tr><td>Название</td><td><input type="text" size="20" name="name" value="'.$row['name'].'"></td></tr><tr><td><input type="submit" value="Отредактировать"></td></tr></form></table>');
    end_frame();
    stdfoot();

}

elseif ($_GET['action'] == 'saveedit') {
       if (!is_valid_id($_GET['id'])) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
     $id=0+$_GET['id'];
     
     if (empty($_POST['name']) || empty($_POST['uri'])) stderr($tracker_lang['error'],"Вы не ввели URI либо Название шкурки");
     
     sql_query("UPDATE stylesheets SET uri=".sqlesc($_POST['uri']).", name=".sqlesc($_POST['name'])." WHERE id=$id");
       header('Location: templatesadmin.php');

}
else stderr($tracker_lang['error'],"Unknown action");

?>