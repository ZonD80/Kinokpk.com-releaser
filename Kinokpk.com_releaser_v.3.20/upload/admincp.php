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
$REL_LANG->load('admincp');
httpauth();

stdhead($REL_LANG->say_by_key('panel_admin'));
begin_main_frame();


if (get_user_class() >= UC_SYSOP) {
	begin_frame($REL_LANG->say_by_key('1')); ?>
<table width=100% cellspacing=10 align=center>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('siteonoff');?>">Управление Отключением / Включением сайта
		и классами доступа</a></td>
		<td><a href="<?=$REL_SEO->make_link('blocksadmin');?>">Управление Блоками</a></td>
		<td><a href="<?=$REL_SEO->make_link('templatesadmin');?>">Настройки шкурок</a></td>
		<td><a href="<?=$REL_SEO->make_link('configadmin');?>"><b>Основные настройки</b></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('spam');?>">ЛС пользователей :)</a></td>
		<td><a href="<?=$REL_SEO->make_link('category');?>">Категории</a></td>
		<td><a href="<?=$REL_SEO->make_link('stampadmin');?>">Штампы и печати</a></td>
		<td><a href="<?=$REL_SEO->make_link('countryadmin');?>">Админка стран и флагов</a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('retrackeradmin');?>">Управление ретрекером</a></td>
		<td><a href="<?=$REL_SEO->make_link('cronadmin');?>"><b>Настройка cron-функций и рейтинга</b></a></td>
		<td colspan="2"><a href="<?=$REL_SEO->make_link('pagescategory');?>">Категории страниц</a></td>
	</tr>
</table>
	<? end_frame();
}

if (get_user_class() >= UC_ADMINISTRATOR) { ?>
<? begin_frame($REL_LANG->say_by_key('2')); ?>
<table width=100% cellspacing=10 align=center>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('unco');?>">Неподтв. юзеры</a></td>
		<td><a href="<?=$REL_SEO->make_link('delacctadmin');?>">Удалить юзера</a></td>
		<td><a href="<?=$REL_SEO->make_link('rgadmin');?>">Релиз-группы</a></td>
		<td><a href="<?=$REL_SEO->make_link('bans');?>">Баны</a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('banemailadmin');?>">Бан емайлов</a></td>
		<td><a href="<?=$REL_SEO->make_link('email');?>">Массовый E-mail</a></td>
		<td><a href="<?=$REL_SEO->make_link('staffmess');?>">Масовое ПМ</a></td>
		<td><a href="<?=$REL_SEO->make_link('pollsadmin');?>">Опросы</a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('mysqlstats');?>">Статистика MySQL</a></td>
		<td><a href="<?=$REL_SEO->make_link('passwordadmin');?>">Сменить пароль юзверю</a></td>
		<td><a href="<?=$REL_SEO->make_link('clearcache');?>">Очистка кешей</a></td>
		<td><a href="<?=$REL_SEO->make_link('reltemplatesadmin');?>">Настройка Шаблонов Релизов</a></td>
	</tr>
	<tr>
		<td colspan="4"><a href="<?=$REL_SEO->make_link('news');?>">Добавить новость</a> | <a
			href="<?=$REL_SEO->make_link('newsarchive');?>">Все новости</a></td>

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
		<td><a href="<?=$REL_SEO->make_link('users','act','users');?>">Пользователи с рейтингом ниже 0</a></td>
		<td><a href="<?=$REL_SEO->make_link('users','act','banned');?>">Отключенные пользователи</a></td>
		<td><a href="<?=$REL_SEO->make_link('users','act','last');?>">Новые пользователи</a></td>
		<td><a href="<?=$REL_SEO->make_link('log');?>">Лог сайта</a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('warned');?>">Предупр. юзеры</a></td>
		<td><a href="<?=$REL_SEO->make_link('adduser');?>">Добавить юзера</a></td>
		<td><a href="<?=$REL_SEO->make_link('recover');?>">Востан. юзера</a></td>
		<td><a href="<?=$REL_SEO->make_link('uploaders');?>">Аплоадеры</a></td>
	</tr>
	<tr>
		<td colspan="4"><a href="<?=$REL_SEO->make_link('users');?>">Список юзеров</a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('stats');?>">Статистика</a></td>
		<td><a href="<?=$REL_SEO->make_link('testip');?>">Проверка IP</a></td>
		<td><a href="<?=$REL_SEO->make_link('reports');?>">Жалобы</a></td>
		<td><a href="<?=$REL_SEO->make_link('ipcheck');?>">Повторные IP</a></td>
	</tr>
	<tr>
		<td colspan="4" class=embedded>
		<form method=get action="<?=$REL_SEO->make_link('users')?>">Поиск: <input type=text size=30
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
		<td class=embedded><a href="<?=$REL_SEO->make_link('usersearch');?>">Административный поиск</a></td>
	</tr>
</table>

			<?php
			end_frame();
}
end_main_frame();
stdfoot();
?>