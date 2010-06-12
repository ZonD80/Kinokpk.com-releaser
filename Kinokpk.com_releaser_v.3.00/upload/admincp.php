<?php
/**
 * Admin control panel frontend
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
dbconn();
loggedinorreturn();
getlang('admincp');
httpauth();

stdhead($tracker_lang['panel_admin']);
begin_main_frame();


if (get_user_class() >= UC_SYSOP) {
	begin_frame($tracker_lang['1']); ?>
<table width=100% cellspacing=10 align=center>
	<tr>
		<td><a href="siteonoff.php">Управление Отключением / Включением сайта
		и классами доступа</a></td>
		<td><a href="blocksadmin.php">Управление Блоками</a></td>
		<td><a href="templatesadmin.php">Настройки шкурок</a></td>
		<td><a href="configadmin.php"><b>Основные настройки</b></a></td>
	</tr>
	<tr>
		<td><a href="spam.php">ЛС пользователей :)</a></td>
		<td><a href="category.php">Категории</a></td>
		<td><a href="stampadmin.php">Штампы и печати</a></td>
		<td><a href="countryadmin.php">Админка стран и флагов</a></td>
	</tr>
	<tr>
		<td><a href="retrackeradmin.php">Управление ретрекером</a></td>
		<td><a href="cronadmin.php"><b>Настройка cron-функций и рейтинга</b></a></td>
		<td colspan="2"><a href="pagescategory.php">Категории страниц</a></td>
	</tr>
</table>
	<? end_frame();
}

if (get_user_class() >= UC_ADMINISTRATOR) { ?>
<? begin_frame($tracker_lang['2']); ?>
<table width=100% cellspacing=10 align=center>
	<tr>
		<td><a href="unco.php">Неподтв. юзеры</a></td>
		<td><a href="delacctadmin.php">Удалить юзера</a></td>
		<td><a href="rgadmin.php">Релиз-группы</a></td>
		<td><a href="bans.php">Баны</a></td>
	</tr>
	<tr>
		<td><a href="banemailadmin.php">Бан емайлов</a></td>
		<td><a href="email.php">Массовый E-mail</a></td>
		<td><a href="staffmess.php">Масовое ПМ</a></td>
		<td><a href="pollsadmin.php">Опросы</a></td>
	</tr>
	<tr>
		<td><a href="mysqlstats.php">Статистика MySQL</a></td>
		<td><a href="passwordadmin.php">Сменить пароль юзверю</a></td>
		<td><a href="clearcache.php">Очистка кешей</a></td>
		<td><a href="faqadmin.php">Настройка FAQ</a></td>
	</tr>
	<tr>
		<td><a href="rulesadmin.php">Настройка Правил</a></td>
		<td><a href="reltemplatesadmin.php">Настройка Шаблонов Релизов</a></td>
		<td colspan="2"><a href="news.php">Добавить новость</a> | <a
			href="newsarchive.php">Все новости</a></td>

	</tr>
</table>
<? end_frame();
}

if (get_user_class() >= UC_MODERATOR) { ?>
<? begin_frame("Средства персонала - <font color=#004E98>Видно модераторам.</font>"); ?>


<table width=100% cellspacing=3>
	<tr>
	</tr>
	<tr>
		<td><a href="users.php?act=users">Пользователи с рейтингом ниже 0</a></td>
		<td><a href="users.php?act=banned">Отключенные пользователи</a></td>
		<td><a href="users.php?act=last">Новые пользователи</a></td>
		<td><a href="log.php">Лог сайта</a></td>
	</tr>
	<tr>
		<td><a href="warned.php">Предупр. юзеры</a></td>
		<td><a href="adduser.php">Добавить юзера</a></td>
		<td><a href="recover.php">Востан. юзера</a></td>
		<td><a href="uploaders.php">Аплоадеры</a></td>
	</tr>
	<tr>
		<td colspan="4"><a href="users.php">Список юзеров</a></td>
	</tr>
	<tr>
		<td><a href="stats.php">Статистика</a></td>
		<td><a href="testip.php">Проверка IP</a></td>
		<td><a href="reports.php">Жалобы</a></td>
		<td><a href="ipcheck.php">Повторные IP</a></td>
	</tr>
	<tr>
		<td colspan="4" class=embedded>
		<form method=get action="users.php">Поиск: <input type=text size=30
			name=search> <select name=class>
			<option value='-'>(Выберите)</option>
			<?php
			for ($i=0;;$i++) {
				if ($s=get_user_class_name($i))
				print("<option value=\"$i\">$s</option>");
				else
				break;
			}
			?>
		</select> <input type=submit value='Искать'></form>
		</td>
	</tr>
	<tr>
		<td class=embedded><a href="usersearch.php">Административный поиск</a></td>
	</tr>
</table>

			<?php
			end_frame();
}
end_main_frame();
stdfoot();
?>