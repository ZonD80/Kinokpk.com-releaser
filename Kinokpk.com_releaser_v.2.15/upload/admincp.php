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
stdhead("Панель администратора");
begin_main_frame();


 if (get_user_class() >= UC_SYSOP) {
begin_frame("Инструменты владельца<font color=#FF0000> - Видно сис. администраторам.</font>"); ?>
<table width=100% cellspacing=10 align=center>
<tr>
<td><a href="siteonoff.php">Управление Отключением / Включением сайта и классами доступа</a></td>
<td><a href="blocksadmin.php">Управление Блоками</a></td>
<td><a href="templatesadmin.php">Настройки шкурок</a></td>
<td><a href="configadmin.php">Основные насройки (config.php)</a></td>
</tr>
<tr>
<td><a href="spam.php">ЛС пользователей :)</a></td>
<td><a href="category.php">Категории / Теги</a></td>
<td><a href="stampadmin.php">Штампы и печати</a></td>
<td><a href="descrtypesadmin.php">Шаблоны релизов</a></td>
</tr>
</table>
<? end_frame();
}

if (get_user_class() >= UC_ADMINISTRATOR) { ?>
<? begin_frame("Инструменты владельца<font color=#009900> - Видно администраторам.</font>"); ?>
<table width=100% cellspacing=10 align=center>
<tr>
<td><a href="unco.php">Неподтв. юзеры</a></td>
<td><a href="delacctadmin.php">Удалить юзера</a></td>
<td><a href="javascript://" onClick="alert('Coming soon')">Бан клиентов</a></td>
<td><a href="bans.php">Баны</a></td>
</tr>
<tr>
<td><a href="topten.php">Top 10</a></td>
<td><a href="findnotconnectable.php">Юзеры за NAT</a></td>
<td><a href="email.php">Массовый E-mail</a></td>
<td><a href="staffmess.php">Масовое ПМ</a></td>
</tr>
<tr>
<td><a href="pollsadmin.php">Опросы</a></td>
<td><a href="faqadmin.php">Редактор ЧаВо</a></td>
<td><a href="mysqlstats.php">Статистика MySQL</a></td>
<td><a href="passwordadmin.php">Сменить пароль юзверю</a></td>
</tr>
<tr>
<td colspan="4"><a href="banemailadmin.php">Бан емайлов</a></td>
</tr>
</table>
<? end_frame();
}

if (get_user_class() >= UC_MODERATOR) { ?>
<? begin_frame("Средства персонала - <font color=#004E98>Видно модераторам.</font>"); ?>


<table width=100% cellspacing=3>
<tr>
<? if (get_user_class() >= UC_MODERATOR) { ?>
</tr>
<tr>
<td><a href="users.php?act=users">Пользователи с рейтингом ниже 0.20</a></td>
<td><a href="users.php?act=banned">Отключенные пользователи</a></td>
<td><a href="users.php?act=last">Новые пользователи</a></td>
<td><a href="log.php">Лог сайта</a></td>
</tr>
</table>

<? end_frame(); ?>
<br />
<? begin_frame("Модераторы и средства - <font color=#004E98>Видно модераторам.</font>"); ?>

<br />
<table width=100% cellspacing=3>

</table>
<table width=100% cellspacing=10 align=center>
<tr>
<td><a href="warned.php">Предупр. юзеры</a></td>
<td><a href="adduser.php">Добавить юзера</a></td>
<td><a href="recover.php">Востан. юзера</a></td>
<td><a href="uploaders.php">Аплоадеры</a></td>
</tr>
<tr>
<td><a href="users.php">Список юзеров</a></td>
<td><a href="tags.php">Теги</a></td>
<td><a href="smilies.php">Смайлы</a></td>
</tr>
<tr>
<td><a href="stats.php">Статистика</a></td>
<td><a href="testip.php">Проверка IP</a></td>
<td><a href="reports.php">Жалобы</a></td>
<td><a href="ipcheck.php">Повторные IP</a></td>
</tr>
</table>
<br />

<? end_frame(); ?>

<? begin_frame("Искать пользователя - <font color=#004E98>Видно модераторам.</font>"); ?>


<table width=100% cellspacing=3>
<tr>
<td class=embedded>
<form method=get action="users.php">
Поиск: <input type=text size=30 name=search>
<select name=class>
<option value='-'>(Выберите)</option>
<option value=0>Пользователь</option>
<option value=1>Опытный пользователь</option>
<option value=2>VIP</option>
<option value=3>Заливающий</option>
<option value=4>Модератор</option>
<option value=5>Администратор</option>
<option value=6>Владелец</option>
</select>
<input type=submit value='Искать'>
</form>
</td>
</tr>
<tr><td class=embedded><li><a href="usersearch.php">Административный поиск</li></a></td></tr>
</table>

<? end_frame(); ?>
<br />
<? if ($act == "users") {
begin_frame("Пользователи с рейтингом ниже 0.20");

echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>Пользователь</td><td class=colhead>Рейтинг</td><td class=colhead>IP</td><td class=colhead>Зарегистрирован</td><td class=colhead>Последний раз был на трекере</td><td class=colhead>Скачал</td><td class=colhead>Раздал</td></tr>";


$result = sql_query ("SELECT * FROM users WHERE uploaded / downloaded <= 0.20 AND enabled = 'yes' ORDER BY downloaded DESC ");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td colspan=7>Извините, записей не обнаружено!</td></tr>";}
echo "</table>";
end_frame(); }?>

<? if ($act == "last") {
begin_frame("Последние пользователи");

echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>Пользователь</td><td class=colhead>Рейтинг</td><td class=colhead>IP</td><td class=colhead>Зарегистрирован</td><td class=colhead>Последний&nbsp;раз&nbsp;был&nbsp;на&nbsp;трекере</td><td class=colhead>Скачал</td><td class=colhead>Раздал</td></tr>";

$result = sql_query ("SELECT * FROM users WHERE enabled = 'yes' AND status = 'confirmed' ORDER BY added DESC limit 100");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
else {
$ratio = number_format($row["uploaded"] / $row["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
}
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td>Sorry, no records were found!</td></tr>";}
echo "</table>";
end_frame(); }?>


<? if ($act == "banned") {
begin_frame("Забаненые пользователи");

echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>Пользователь</td><td class=colhead>Рейтинг</td><td class=colhead>IP</td><td class=colhead>Зарегистрирован</td><td class=colhead>Последний раз был</td><td class=colhead>Скачал</td><td class=colhead>Раздал</td></tr>";
$result = sql_query ("SELECT * FROM users WHERE enabled = 'no' ORDER BY last_access DESC ");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
else {
$ratio = number_format($row["uploaded"] / $row["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
}
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td colspan=7>Извините, записей не обнаружено!</td></tr>";}
echo "</table>";
end_frame(); } }

end_main_frame();
stdfoot();
}
?>