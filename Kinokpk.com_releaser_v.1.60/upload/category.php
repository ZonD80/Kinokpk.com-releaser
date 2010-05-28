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

ob_start();
require_once("include/bittorrent.php");
dbconn(false);
loggedinorreturn();
if (get_user_class() < UC_SYSOP) {
die($tracker_lang['access_denied']);
}
stdhead("Категории");
print("<h1>Категории</h1>\n");
print("</br>");
print("<table width=70% border=1 cellspacing=0 cellpadding=2><tr><td align=center>\n");

///////////////////// D E L E T E C A T E G O R Y \\\\\\\\\\\\\\\\\\\\\\\\\\\\

$sure = $_GET['sure'];
if($sure == "yes") {
$delid = (int) $_GET['delid'];
$query = "DELETE FROM categories WHERE id=" .sqlesc($delid) . " LIMIT 1";
$sql = sql_query($query);
echo("Категория успешно удалена! [ <a href='category.php'>Назад</a> ]");
end_frame();
stdfoot();
die();
}
$delid = (int) $_GET['delid'];
$name = htmlspecialchars($_GET['cat']);
if($delid > 0) {
echo("Вы действителньо хотите удалить эту категорию? ($name) ( <strong><a href=\"". $_SERVER['PHP_SELF'] . "?delid=$delid&cat=$name&sure=yes\">Да</a></strong> / <strong><a href=\"". $_SERVER['PHP_SELF'] . "\">Нет</a></strong> )");
end_frame();
stdfoot();
die();

}

///////////////////// E D I T A C A T E G O R Y \\\\\\\\\\\\\\\\\\\\\\\\\\\\
$edited = $_GET['edited'];
if($edited == 1) {
$id = (int) $_GET['id'];
$cat_name = htmlspecialchars($_GET['cat_name']);
$cat_img = htmlspecialchars($_GET['cat_img']);
$cat_sort = (int) $_GET['cat_sort'];
$query = "UPDATE categories SET
name = ".sqlesc($cat_name).",
image = ".sqlesc($cat_img).",
sort = ".sqlesc($cat_sort)." WHERE id=".sqlesc($id);
$sql = sql_query($query);
if($sql) {
echo("<table class=main cellspacing=0 cellpadding=5 width=50%>");
echo("<tr><td><div align='center'>Ваша категория отредактирована <strong>успешно!</strong> [ <a href='category.php'>Назад</a> ]</div></tr>");
echo("</table>");
end_frame();
stdfoot();
die();
}
}

$editid = (int) $_GET['editid'];
$name = htmlspecialchars($_GET['name']);
$img = htmlspecialchars($_GET['img']);
$sort = (int) $_GET['sort'];
if($editid > 0) {
echo("<form name='form1' method='get' action='" . $_SERVER['PHP_SELF'] . "'>");
echo("<table class=main cellspacing=0 cellpadding=5 width=50%>");
echo("<div align='center'><input type='hidden' name='edited' value='1'>Редактирование категории <strong>&quot;$name&quot;</strong></div>");
echo("<br />");
echo("<input type='hidden' name='id' value='$editid'<table class=main cellspacing=0 cellpadding=5 width=50%>");
echo("<tr><td>Название: </td><td align='right'><input type='text' size=50 name='cat_name' value='$name'></td></tr>");
echo("<tr><td>Картинка: </td><td align='right'><input type='text' size=50 name='cat_img' value='$img'></td></tr>");
echo("<tr><td>Сортировка: </td><td align='right'><input type='text' size=50 name='cat_sort' value='$sort'></td></tr>");
echo("<tr><td></td><td><div align='right'><input type='Submit' value='Редактировать'></div></td></tr>");
echo("</table></form>");
end_frame();
stdfoot();
die();
}

///////////////////// A D D A N E W C A T E G O R Y \\\\\\\\\\\\\\\\\\\\\\\\\\\\
$add = $_GET['add'];
if($add == 'true') {
$cat_name = htmlspecialchars($_GET['cat_name']);
$cat_img = htmlspecialchars($_GET['cat_img']);
$cat_sort = (int) $_GET['cat_sort'];
$query = "INSERT INTO categories SET
name = ".sqlesc($cat_name).",
image = ".sqlesc($cat_img).",
sort = ".sqlesc($cat_sort);
$sql = sql_query($query);
if($sql) {
$success = TRUE;
} else {
$success = FALSE;
}
}
print("<strong>Добавить новую категорию</strong>");
print("<br />");
print("<br />");
echo("<form name='form1' method='get' action='" . $_SERVER['PHP_SELF'] . "'>");
echo("<table class=main cellspacing=0 cellpadding=5 width=50%>");
echo("<tr><td>Название: </td><td align='right'><input type='text' size=50 name='cat_name'></td></tr>");
echo("<tr><td>Картинка: </td><td align='right'><input type='text' size=50 name='cat_img'><input type='hidden' name='add' value='true'></td></tr>");
echo("<tr><td>Сортировка: </td><td align='right'><input type='text' size=50 name='cat_sort'></td></tr>");
echo("<tr><td></td><td><div align='right'><input type='Submit' value='Создать категорию'></div></td></tr>");
echo("</table>");
if($success == TRUE) {
print("<strong>Удачно!</strong>");
}
echo("<br />");
echo("</form>");

///////////////////// E X I S T I N G C A T E G O R I E S \\\\\\\\\\\\\\\\\\\\\\\\\\\\

print("<strong>Существующие категории:</strong>");
print("<br />");
print("<br />");
echo("<table class=main cellspacing=0 cellpadding=5>");
echo("<td>ID</td><td>Сортировка</td><td>Название</td><td>Картинка</td><td>Просмотр категории</td><td>Редактировать</td><td>Удалить</td>");
$query = "SELECT * FROM categories WHERE 1=1 ORDER BY sort";
$sql = sql_query($query) or sqlerr(__FILE__, __LINE__);
while ($row = mysql_fetch_array($sql)) {
$id = (int) $row['id'];
$sort = $row['sort'];
$name = $row['name'];
$img = $row['image'];
echo("<tr><td><strong>$id</strong> </td> <td><strong>$sort</strong></td> <td><strong>$name</strong></td> <td><img src='$DEFAULTBASEURL/pic/cats/$img' border='0' /></td><td><div align='center'><a href='browse.php?cat=$id'><img src='$DEFAULTBASEURL/pic/viewnfo.gif' border='0' class=special /></a></div></td> <td><a href='category.php?editid=$id&name=$name&img=$img&sort=$sort'><div align='center'><img src='$DEFAULTBASEURL/pic/multipage.gif' border='0' class=special /></a></div></td> <td><div align='center'><a href='category.php?delid=$id&cat=$name'><img src='$DEFAULTBASEURL/pic/warned2.gif' border='0' class=special align='center' /></a></div></td></tr>");
}

end_frame();
end_frame();
stdfoot();

?>