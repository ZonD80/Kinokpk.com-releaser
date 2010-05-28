<?php

ob_start();
require_once("include/bittorrent.php");
dbconn(false);
loggedinorreturn();
if (get_user_class() < UC_UPLOADER) {
die($tracker_lang['access_denied']);
}
stdhead("Категории и подкатегории");
print("<table style='border:0;background:transparent;width:80%' cellspacing='0' cellpadding='2'><tr><td align='center' style='border:0;'>\n");

///////////////////// D E L E T E C A T E G O R Y \\\\\\\\\\\\\\\\\\\\\\\\\\\\

$sure = $_GET['sure'];
if($sure == "yes") {
$delid = (int) $_GET['delid'];
$query = "DELETE FROM categories WHERE id=" .sqlesc($delid) . " LIMIT 1";
$sql = sql_query($query);
begin_frame("Подтверждение удаления", true);
echo("Категория успешно удалена! [ <a href='category.php'>Назад</a> ]");
end_frame();
stdfoot();
die();
}
$delid = (int) $_GET['delid'];
$name = htmlspecialchars($_GET['cat']);
if($delid > 0) {
begin_frame("Подтверждение удаления", true);
echo("Вы действительно хотите удалить эту категорию? ($name) ( <strong><a href=\"". $_SERVER['PHP_SELF'] . "?delid=$delid&cat=$name&sure=yes\">Да</a></strong> / <strong><a href=\"". $_SERVER['PHP_SELF'] . "\">Нет</a></strong> )");
end_frame();
stdfoot();
die();

}

///////////////////// D E L E T E    TAG \\\\\\\\\\\\\\\\\\\\\\\\\\\\

if(is_valid_id($_GET['deltagid'])) {
$deltagid = $_GET['deltagid'];
$query = "DELETE FROM tags WHERE id=" .sqlesc($deltagid) . " LIMIT 1";
$sql = sql_query($query);
begin_frame("Подтверждение удаления", true);
print("Тег успешно удален! [ <a href='category.php'>Назад</a> ]");
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
begin_frame("Успешное редактирование", true);
echo("<div align='center'>Ваша категория отредактирована <strong>успешно!</strong> [ <a href='category.php'>Назад</a> ]</div>");
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
begin_frame("Редактирование категории", true);
echo("<form name='form1' method='get' action='" . $_SERVER['PHP_SELF'] . "'>");
echo("<table class=main cellspacing=0 cellpadding=5 width=50%>");
echo("<input type='hidden' name='edited' value='1'>");
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
begin_frame("Добавить новую категорию", true);
echo("<form name='form1' method='get' action='" . $_SERVER['PHP_SELF'] . "'>");
echo("<table class=main cellspacing=0 cellpadding=5 width=50%>");
echo("<tr><td>Название: </td><td align='right'><input type='text' size=50 name='cat_name'></td></tr>");
echo("<tr><td>Картинка: </td><td align='right'><input type='text' size=50 name='cat_img'><input type='hidden' name='add' value='true'></td></tr>");
echo("<tr><td>Сортировка: </td><td align='right'><input type='text' size=50 name='cat_sort'></td></tr>");
echo("<tr><td colspan=2 style='border:0'><div align='center'><input type='Submit' value='Создать категорию'></div></td></tr>");
echo("</table>");
if($success == TRUE) {
print("<strong>Удачно!</strong>");
}
echo("<br />");
echo("</form>");
end_frame();

///////////////////// A D D A N E W TAG \\\\\\\\\\\\\\\\\\\\\\\\\\\\
$addtag = $_GET['addtag'];
if($addtag == 'true') {
$tag_names = explode(",",htmlspecialchars($_GET['tag_name']));
$tag_category = (int) $_GET['tag_category'];
foreach ($tag_names as $tag_name) {
$query = "INSERT INTO tags (name,category) VALUES
(".sqlesc($tag_name).",".sqlesc($tag_category).")";
$sql = sql_query($query);
if($sql) {
$success = TRUE;
} else {
$success = FALSE;
}
}
}
begin_frame("Добавить новый тэг", true);
echo("<form name='form2' method='get' action='" . $_SERVER['PHP_SELF'] . "'>");
echo("<table class=main cellspacing=0 cellpadding=5 width=50%>");
echo("<tr><td>Название (можно добавить несколько тегов через запятую): </td><td align='right'><input type='text' name='tag_name'></td></tr>");
$s = "<select name=\"tag_category\" style='width:100%'>\n<option value=\"0\">(".$tracker_lang['choose'].")</option>\n";

$cats = genrelist();
foreach ($cats as $row)
	$s .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$s .= "</select>\n";
echo("<tr><td>Категория: </td><td align='right'>".$s."</td></tr>");
echo("<tr><td colspan=2 style='border:0'><div align='center'><input type='hidden' name='addtag' value='true'><input type='Submit' value='Создать тэг'></div></td></tr>");
echo("</table>");
if($success == TRUE) {
print("<strong>Удачно!</strong>");
}
echo("<br />");
echo("</form>");
end_frame();

///////////////////// E X I S T I N G C A T E G O R I E S \\\\\\\\\\\\\\\\\\\\\\\\\\\\

begin_frame("Существующие категории", true);
echo("<table class=main cellspacing=0 cellpadding=5>");
echo("<td>ID</td><td>Сортировка</td><td>Название</td><td>Картинка</td><td>Просмотр категории</td><td>Редактировать</td><td>Удалить</td>");
$query = "SELECT * FROM categories ORDER BY id ASC";
$sql = sql_query($query);
while ($row = mysql_fetch_array($sql)) {
$id = $row['id'];
$sort = $row['sort'];
$name = $row['name'];
$img = $row['image'];
$tagsrow = sql_query("SELECT id,name FROM tags WHERE category = $id");

print("<tr><td><strong>$id</strong> </td> <td><strong>$sort</strong></td> <td><strong>$name</strong></td> <td><img src='$DEFAULTBASEURL/pic/cats/$img' border='0' /></td><td><div align='center'><a href='browse.php?cat=$id'><img src='$DEFAULTBASEURL/pic/viewnfo.gif' border='0' class=special /></a></div></td> <td><a href='category.php?editid=$id&name=$name&img=$img&sort=$sort'><div align='center'><img src='$DEFAULTBASEURL/pic/multipage.gif' border='0' class=special /></a></div></td> <td><div align='center'><a href='category.php?delid=$id&cat=$name'><img src='$DEFAULTBASEURL/pic/warned2.gif' border='0' class=special align='center' /></a></div></td></tr>");
print('<tr><td colspan="12" bgcolor="#DBD8D1"><b>Тэги:</b> ');
while ($tagres = mysql_fetch_array($tagsrow))
print ($tagres['name'].' [<a href="category.php?deltagid='.$tagres['id'].'" onclick="return confirm(\'Вы уверены?\');">D</a>] ');
print('</td></tr>');
}
echo "</table>";
end_frame();
echo "</td></tr></table>";
stdfoot();

?>