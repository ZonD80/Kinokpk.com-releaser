<?php
/**
 * Installer for 3.00
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
define('ROOT_PATH',str_replace('install','',dirname(__FILE__)));


if ($_GET['setlang']) {
	setcookie('lang',(string)$_GET['setlang']);
	print('<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /></head><a href="index.php">Продолжить / Continue</a></html>');
	footers();
	die();
}
if (!$_COOKIE['lang'] || (strlen($_COOKIE['lang'])>2)) {
	print("<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\" /></head><h1>Выберите язык / Choose a language: <a href=\"index.php?setlang=ru\">Русский</a>, <a href=\"index.php?setlang=en\">English</a></h1></html>");
	footers();
	die();
} else require_once(ROOT_PATH.'install/lang_'.$_COOKIE['lang'].'.php');
require_once(ROOT_PATH.'include/bittorrent.php');

$step = (int)$_GET['step'];

function headers2() {
	global $step;
	header("X-Powered-By: Kinokpk.com releaser ".RELVERSION);
	header("Cache-Control: no-cache, must-revalidate, max-age=0");
	//header("Expires:" . gmdate("D, d M Y H:i:s") . " GMT");
	header("Expires: 0");
	header("Pragma: no-cache");
	print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title>Kinokpk.com releaser 3.00 installer, step: '.$step.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /></head><body>');

	if (ini_get("register_globals")) die('<font color="red" size="20">Отключи register_globals, дурачок! / Turn off register_globals, noob!</font>');

}

function footers() {
	print('<hr /><div align="right">Kinokpk.com releaser 3.00 installer</div></body></html>');
}
function cont($step) {
	global $lang;
	print '<a href="index.php?step='.$step.'">'.$lang['continue'].'</a>';

}

headers2();


if (!$step) {
	print $lang['hello'];
	print $lang['agree'];
	print('<iframe width="100%" height="300px" src="gnu.html">GNU</iframe>');
	print $lang['agree_continue'];
}

elseif ($step==1){

	print "<h1 align=\"center\">{$lang['check_settings']}</h1><hr/>";
	print "PHP {$lang['version']} >= 5.2.3: ".(((version_compare(PHP_VERSION,"5.2.3",'>'))||(version_compare(PHP_VERSION,"5.2.3") === 1))?$lang['ok_support']:$lang['not_support'])."<br/>";
	print "MySQL {$lang['support']}: ".(function_exists("mysql_connect")?$lang['ok_support']:$lang['not_support'])."<br/>";
	print "GD2 {$lang['support']}: ".(function_exists("imagecreatefromjpeg")&&function_exists("imagecreatefrompng")&&function_exists("imagecreatefromgif")?$lang['ok_support']:$lang['not_support'])."<br/>";
	print "Zlib {$lang['support']}: ".(extension_loaded("zlib")?$lang['ok_support']:$lang['not_support'])."<br/>";
	print "Safe Mode {$lang['support']}: ".(ini_get("safe_mode")?$lang['safe_mode_on']:$lang['safe_mode_off'])."<br/>";
	print "Iconv {$lang['support']}: ".(function_exists("iconv")?$lang['ok_support']:$lang['not_support'])."<hr/>";

	$important_files = array(
	ROOT_PATH.'torrents/',
	ROOT_PATH.'avatars/',
	ROOT_PATH.'cache/',
	ROOT_PATH.'Sitemap.xml',
	ROOT_PATH.'include/secrets.php',
	);
	print($lang['chmod_check'].'<hr />');
	foreach($important_files as $file){

		if(!file_exists($file) || !is_writable($file)){
			print "$file: {$lang['invalid_rights']}<br/>";
		}
		elseif(is_writable($file)){
			print "$file:  {$lang['ok']}<br/>";
		}
	}

	print('<hr />');

	print $lang['fail_notice'];
	cont(2);
}

elseif ($_GET['step'] == 2) {
	print "<h1 align=\"center\">{$lang['mysql']}</h1><hr/>";
	print '<form action="index.php?step=3" method="POST">
<table><tr><td>'.$lang['mysql_host'].'</td><td><input type="text" name="mysql_host" value="localhost"></td></tr>
<tr><td>'.$lang['mysql_db'].'</td><td><input type="text" name="mysql_db"></td></tr>
<tr><td>'.$lang['mysql_user'].'</td><td><input type="text" name="mysql_user"></td></tr>
<tr><td>'.$lang['mysql_pass'].'</td><td><input type="password" name="mysql_pass"></td></tr>
<tr><td>'.$lang['mysql_charset'].'</td><td><input type="text" name="mysql_charset" value="cp1251"></td></tr>
<tr><td>'.$lang['cookie_secret'].'</td><td><input type="text" name="cookie_secret"></td></tr>
<tr><td colspan="2">'.$lang['forum_mysql_notice'].'</tr>
<tr><td>'.$lang['mysql_host'].'</td><td><input type="text" name="fmysql_host" value="localhost"></td></tr>
<tr><td>'.$lang['mysql_db'].'</td><td><input type="text" name="fmysql_db"></td></tr>
<tr><td>'.$lang['mysql_user'].'</td><td><input type="text" name="fmysql_user"></td></tr>
<tr><td>'.$lang['mysql_pass'].'</td><td><input type="password" name="fmysql_pass"></td></tr>
<tr><td>'.$lang['mysql_charset'].'</td><td><input type="text" name="fmysql_charset" value="cp1251"></td></tr>
<tr><td>'.$lang['mysql_forum_table_prefix'].'</td><td><input type="text" name="fprefix" value="ibf_"></td></tr>
<tr><td colspan="2"><input type="submit" value="'.$lang['continue'].'"></td></tr></table>';
}

elseif ($_GET['step'] == 3) {

	$mysql_host=$_POST['mysql_host'];
	$mysql_user=$_POST['mysql_user'];
	$mysql_db=$_POST['mysql_db'];
	$mysql_pass=$_POST['mysql_pass'];
	$mysql_charset=$_POST['mysql_charset'];
	$fmysql_host=$_POST['fmysql_host'];
	$fmysql_user=$_POST['fmysql_user'];
	$fmysql_db=$_POST['fmysql_db'];
	$fmysql_pass=$_POST['fmysql_pass'];
	$fmysql_charset=$_POST['fmysql_charset'];
	$fprefix =$_POST['fprefix'];
	$secret = $_POST['cookie_secret'];


	print($lang['testing_database_connection']);

	relconn();

	$strings = file(ROOT_PATH."install/database.sql");
	$query = '';
	foreach ($strings AS $string)
	{
		if (preg_match("/^\s?#/", $string) || !preg_match("/[^\s]/", $string))
		continue;
		else
		{
			$query .= $string;
			if (preg_match("/;\s?$/", $query))
			{
				mysql_query($query) or die($lang['mysql_error'].'['.$query.']['.mysql_errno().']: ' . mysql_error());
				$query = '';
			}
		}
	}
	print($lang['ok'].'<hr/>');
	$dbconfig = <<<HTML<?php
/**
 * Passwords. Just for fun
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

if(!defined('IN_TRACKER') && !defined('IN_ANNOUNCE')) die("Direct access to this page not allowed");

\$mysql_host = '$mysql_host';
\$mysql_user = '$mysql_user';
\$mysql_pass = '$mysql_pass';
\$mysql_db = '$mysql_db';
\$mysql_charset = '$mysql_charset';

\$fmysql_host = '$fmysql_host';
\$fmysql_user = '$fmysql_user';
\$fmysql_pass = '$fmysql_pass';
\$fmysql_db = '$fmysql_db';
\$fmysql_charset = '$fmysql_charset';
\$fprefix = '$fprefix';

define("COOKIE_SECRET",'$secret');
?>
HTML;
	print($lang['config_to_file']);

	if (!file_put_contents(ROOT_PATH.'include/secrets.php', $dbconfig))
	{ print($lang['invalid_rights'].' ('.ROOT_PATH.'include/config.php).'); footers(); die(); }

	print ($lang['ok']."<hr />");

	print ($lang['write_config_ok']);
	cont(4);
}

elseif($step==4) {
	dbconn();
	if (!isset($_GET['action'])){
		print $lang['main_settings'];
		print("Основные настройки Kinokpk.com releaser ".RELVERSION.'<hr/>');
		print('<form action="index.php?step=4&action=save" method="POST">');
		print('<table width="100%" border="1">');

		print('<tr><td align="center" colspan="2" class="colhead">Основные настройки</td></tr>');

		print('<tr><td>Адрес сайта (без /):</td><td><input type="text" name="defaultbaseurl" size="30" value="http://'.$_SERVER['HTTP_HOST'].'"> <br/>Например, "http://www.kinokpk.com"</td></tr>');
		print('<tr><td>Название сайта (title):</td><td><input type="text" name="sitename" size="80" value="'.$CACHEARRAY['sitename'].'"> <br/>Например, "Релизер японских тушканчиков"</td></tr>');
		print('<tr><td>Описание сайта (meta description):</td><td><input type="text" name="description" size="80" value="'.$CACHEARRAY['description'].'"> <br/>Например, "Самые шустрые тушканчики скачать тута"</td></tr>');
		print('<tr><td>Ключевые слова (meta keywords):</td><td><input type="text" name="keywords" size="80" value="'.$CACHEARRAY['keywords'].'"> <br/>Например, "скачать, тушканчики, япония, релизер"</td></tr>');
		print('<tr><td>Емайл, с которого будут отправляться сообщения сайта:</td><td><input type="text" name="siteemail" size="30" value="'.$CACHEARRAY['siteemail'].'"> <br/>Например, "bot@kinokpk.com"</td></tr>');
		print('<tr><td>Емайл для связи с администратором:</td><td><input type="text" name="adminemail" size="30" value="'.$CACHEARRAY['adminemail'].'"> <br/>Например, "admin@windows.lox"</td></tr>');
		print('<tr><td>Язык релизера по умолчанию (имя lang_%язык%):</td><td><input type="text" name="default_language" size="2" value="ru"></td></tr>');
		print('<tr><td>Использовать систему многоязычности (отключать не рекомендуется):</td><td><select name="use_lang"><option value="1" '.($CACHEARRAY['use_lang']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_lang']==0?"selected":"").'>Нет</option> Указываются только первые 2 буквы языка (ru,en)</select></td></tr>');
		print('<tr><td>Стандартная тема для гостей и регестрирующихся (themes/%тема%):</td><td><input type="text" name="default_theme" size="10" value="'.$CACHEARRAY['default_theme'].'"> По умолчанию "kinokpk"</td></tr>');
		print('<tr><td>Ваш копирайт для отображения внизу страницы:<br /><small>*вы можете использовать шаблон <b>{datenow}</b> для показа текущего года</small></td><td><input type="text" name="yourcopy" size="60" value="'.$CACHEARRAY['yourcopy'].'"> <br/>Например, "&copy; 2008-{datenow} Мой Мосх"</td></tr>');
		print('<tr><td>Использовать систему блоков (отключать не рекомендуется):</td><td><select name="use_blocks"><option value="1" '.($CACHEARRAY['use_blocks']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_blocks']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Использовать gzip сжатие для страниц:</td><td><select name="use_gzip"><option value="1" '.($CACHEARRAY['use_gzip']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_gzip']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Использовать систему банов по IP/Подсетям:</td><td><select name="use_ipbans"><option value="1" '.($CACHEARRAY['use_ipbans']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_ipbans']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Тип SMTP:</td><td><input type="text" name="smtptype" size="10" value="'.$CACHEARRAY['smtptype'].'"></td></tr>');
		print('<tr><td>Бинарный формат пиров в анонсере:</td><td><select name="announce_packed"><option value="1" '.($CACHEARRAY['announce_packed']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['announce_packed']==0?"selected":"").'>Нет</option></select> По умолчанию, Да</td></tr>');

		print('<tr><td align="center" colspan="2" class="colhead">Настройки интеграции с форумом IPB</td></tr>');

		print('<tr><td>Использовать интеграцию с форумом IPB:</td><td><select name="use_integration"><option value="1" '.($CACHEARRAY['use_integration']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_integration']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Пароль от форума важнее пароля от релизера:<br /><small>В этом случае при несовпадении паролей при входе на релизер пользователю будет выдана ошибка, иначе релизер автоматически сменит пароль на форуме</small></td><td><select name="ipb_password_priority"><option value="1" '.($CACHEARRAY['ipb_password_priority']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['ipb_password_priority']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Тип экспорта релизов на форум:<br /><small>*для использования функции экспорта в вики-секцию<br />установите интеграцию IPB и wikimedia с <a target="_blank" href="http://www.ipbwiki.com/">http://www.ipbwiki.com/</a></small></td><td><select name="exporttype"><option value="wiki" '.($CACHEARRAY['exporttype']=="wiki"?"selected":"").'>В вики-секцию</option><option value="post" '.($CACHEARRAY['exporttype']=="post"?"selected":"").'>Непосредственно в пост</option></select></td></tr>');
		print('<tr><td>Адрес форума (без /):</td><td><input type="text" name="forumurl" size="60" value="'.$CACHEARRAY['forumurl'].'"> <br/>Например, "http://forum.pdaprime.ru"</td></tr>');
		print('<tr><td>Название форума:</td><td><input type="text" name="forumname" size="60" value="'.$CACHEARRAY['forumname'].'"> <br/>Например, "pdaPRIME.ru"</td></tr>');
		print('<tr><td>Префикс форумных cookie:</td><td><input type="text" name="ipb_cookie_prefix" size="4" value="'.$CACHEARRAY['ipb_cookie_prefix'].'"> По умолчанию IPB, пусто</td></tr>');
		print('<tr><td>ID форума-корзины:</td><td><input type="text" name="forum_bin_id" size="3" value="'.$CACHEARRAY['forum_bin_id'].'"></td></tr>');
		print('<tr><td>Класс пользователей после экспорта на форум:</td><td><input type="text" name="defuserclass" size="1" value="'.$CACHEARRAY['defuserclass'].'"> По умолчанию IPB, "3"</td></tr>');
		print('<tr><td>ID форума для экспорта прочих релизов:<br /><small>*Это релизы, форум которых не совпадает с названием тегов, либо возникла ошибка при определении форума</small></td><td><input type="text" name="not_found_export_id" size="3" value="'.$CACHEARRAY['not_found_export_id'].'"></td></tr>');
		print('<tr><td>Папка со смайлами форума (без /):</td><td><input type="text" name="emo_dir" size="10" value="'.$CACHEARRAY['emo_dir'].'"> По умолчанию IPB, "default"</td></tr>');


		print('<tr><td align="center" colspan="2" class="colhead">Настройки регистрации</td></tr>');

		print('<tr><td>Запретить регистрацию:</td><td><select name="deny_signup"><option value="1" '.($CACHEARRAY['deny_signup']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['deny_signup']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Разрешить регистрацию по приглашениям:</td><td><select name="allow_invite_signup"><option value="1" '.($CACHEARRAY['allow_invite_signup']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['allow_invite_signup']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Временная зона при регистрации:</td><td>'.list_timezones('register_timezone',$CACHEARRAY['register_timezone']).'</td></tr>');
		print('<tr><td>Использовать активацию аккаунтов по e-mail:</td><td><select name="use_email_act"><option value="1" '.($CACHEARRAY['use_email_act']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_email_act']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Использовать капчу:<br /><small>*Вы должны зарегестрироваться на <a target="_blank" href="http://recaptcha.net">ReCaptcha.net</a> и получить приватный и публичный ключи для использования этой опции</small></td><td><select name="use_captcha"><option  value="1" '.($CACHEARRAY['use_captcha']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_captcha']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Публичный ключ капчи:</td><td><input type="text" name="re_publickey" size="80" value="'.$CACHEARRAY['re_publickey'].'"></td></tr>');
		print('<tr><td>Приватный ключ капчи:</td><td><input type="text" name="re_privatekey" size="80" value="'.$CACHEARRAY['re_privatekey'].'"></td></tr>');
		print('<tr><td>Стандартные уведомления (вспл.окно и/или ЛС):</td><td><input type="text" name="default_notifs" size="120" value="'.$CACHEARRAY['default_notifs'].'"></td></tr>');
		print('<tr><td>Стандартные уведомления в Email:</td><td><input type="text" name="default_emailnotifs" size="120" value="'.$CACHEARRAY['default_emailnotifs'].'"></td></tr>');
		print('<tr><td colspan="2"><small>*Все типы уведомлений в Kinokpk.com releaser '.RELVERSION.':<br/>unread,torrents,comments,pollcomments,newscomments,usercomments,reqcomments,rgcomments,pages,pagecomments,friends,users,reports,unchecked ; Подробнее - <a target="_blank" href="mynotifs.php?settings">настройки моих уведомлений</a></small></td></tr>');


		print('<tr><td align="center" colspan="2" class="colhead">Настройки ограничений</td></tr>');

		print('<tr><td>Максимальное количество пользователей:</td><td><input type="text" name="maxusers" size="6" value="'.$CACHEARRAY['maxusers'].'">пользователей, укажите 0 для отключения лимита</td></tr>');
		print('<tr><td>Максимальное количество сообщений в Личном ящике:</td><td><input type="text" name="pm_max" size="4" value="'.$CACHEARRAY['pm_max'].'">сообщений</td></tr>');
		print('<tr><td>Максимальная ширина автара:</td><td><input type="text" name="avatar_max_width" size="3" value="'.$CACHEARRAY['avatar_max_width'].'">пикселей</td></tr>');
		print('<tr><td>Максимальная высота автара:</td><td><input type="text" name="avatar_max_height" size="3" value="'.$CACHEARRAY['avatar_max_height'].'">пикселей</td></tr>');
		print('<tr><td>Запретить подключения с закрытыми портами:</td><td><select name="nc"><option value=1 '.($CACHEARRAY['nc']?"selected":"").'>Да</option><option value="0" '.(!$CACHEARRAY['nc']?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Максимальный размер торрент-файла в байтах:</td><td><input type="text" name="max_torrent_size" size="10" value="'.$CACHEARRAY['max_torrent_size'].'">байт</td></tr>');
		print('<tr><td>Максимальное количество картинок для релиза:</td><td><input type="text" name="max_images" size="2" value="'.$CACHEARRAY['max_images'].'"><br/>Например, "2"</td></tr>');
		print('<tr><td>Категории adult релизов:<br /><small>*Будут по умолчанию заменяться заглушкой "XXX релиз", пользователь может включить отображение этих категорий в профиле<br /><b>Если категорий больше, чем одна, указывайте их через запятую <u>без пробелов</u></b></small></td><td><input type="text" name="pron_cats" size="60" value="'.$CACHEARRAY['pron_cats'].'"><br/>Например, "13,14"</td></tr>');

		print('<tr><td align="center" colspan="2" class="colhead">Настройки безопасности</td></tr>');

		print('<tr><td>Флуд-интервал в секундах:</td><td><input type="text" name="as_timeout" size="10" value="'.$CACHEARRAY['as_timeout'].'">секунд</td></tr>');
		print('<tr><td>Использовать проверку последних 5 комментариев (антиспам):</td><td><select name="as_check_messages"><option value="1" '.($CACHEARRAY['as_check_messages']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['as_check_messages']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Дебаг-режим:</td><td><select name="debug_mode"><option value="1" '.($CACHEARRAY['debug_mode']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['debug_mode']==0?"selected":"").'>Нет</option></select></td></tr>');

		print('<tr><td align="center" colspan="2" class="colhead">Прочее</td></tr>');

		print('<tr><td>Попробовать автоматически получить трейлер фильма с кинопоиск.ру:<br/><small>*Работает только, если в описании релиза есть ссылка вида http://www.kinopoisk.ru/level/1/film/ID_фильма</small></td><td><select name="use_ttl"><option value="1" '.($CACHEARRAY['use_kinopoisk_trailers']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_kinopoisk_trailers']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Количество релизов в списке релизов на страницу:<br /><small>*при изменении этого параметра необходимо очистить кеш browse</small></td><td><input type="text" name="torrentsperpage" size="3" value="'.$CACHEARRAY['torrentsperpage'].'">релизов</td></tr>');
		print('<tr><td>Использовать TTL (авто удаление мертвых торрентов):</td><td><select name="use_ttl"><option value="1" '.($CACHEARRAY['use_ttl']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_ttl']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Использовать систему ограничения личеров по времени:</td><td><select name="use_wait"><option value="1" '.($CACHEARRAY['use_wait']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_wait']==0?"selected":"").'>Нет</option></select></td></tr>');

		print('<tr><td align="center" colspan="2"><input type="submit" value="Сохранить изменения"><input type="reset" value="Сбросить"></td></tr></table></form>');


	}

	elseif ($_GET['action'] == 'save'){
		$reqparametres = array('torrentsperpage','maxusers','max_torrent_size','max_images','defaultbaseurl','siteemail','adminemail','sitename','description','keywords',
'forumname','yourcopy','pm_max','default_language',
'avatar_max_width','avatar_max_height','default_theme','nc','deny_signup','allow_invite_signup',
'use_ttl','use_email_act','use_wait','use_lang','use_captcha','use_blocks','use_gzip','use_ipbans','smtptype',
'as_timeout','as_check_messages','use_integration','debug_mode','ipb_cookie_prefix','announce_packed','pron_cats','register_timezone');
		$int_param = array('exporttype','forumurl','forum_bin_id','defuserclass','not_found_export_id','emo_dir','ipb_password_priority');
		$captcha_param = array('re_publickey','re_privatekey');

		$updateset = array();

		foreach ($reqparametres as $param) {
			if (!isset($_POST[$param]) && ($param != 'forumname') && ($param != 'ipb_cookie_prefix') && ($param != 'pron_cats')) stderr($tracker_lang['error'],"Некоторые поля не заполнены ($param)");
			$updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
		}

		if ($_POST['use_integration'] == 1) {
			foreach ($int_param as $param) {
				if (!isset($_POST[$param])) stderr($tracker_lang['error'],"Некоторые поля для интеграции с форумом не заполнены");
				$updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
			}
		}
		if ($_POST['use_captcha'] == 1) {
			foreach ($captcha_param as $param) {
				if (!$_POST[$param] || !isset($_POST[$param])) stderr($tracker_lang['error'],"Приватный или публичный ключи капчи не определены");
				$updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
			}
		}

		foreach ($updateset as $query) sql_query($query);

		$CACHE->clearCache('system','config');
		print($lang['ok'].'<hr/>');


		cont(5);
	}
}
elseif ($step==5) {
	dbconn();
	$cronrow = sql_query("SELECT * FROM cron");

	while ($cronres = mysql_fetch_array($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];

	if (!isset($_POST['save']) && !isset($_POST['reset']) && !isset($_POST['recount'])){
		print $lang['main_settings'];
		print("Настройка cron-функций<hr/>");
		print('<form action="index.php?step=5" method="POST">');
		print('<table width="100%" border="1">');

		if ($CRON['in_remotecheck'] && $CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="red">Запрос на остановку подан, но скрипт еще выполняется. Подождите пожалуйста</font>';
		if (!$CRON['in_remotecheck'] && $CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">Функция остановлена</font>';
		if ($CRON['in_remotecheck'] && !$CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">Функция работает</font>';
		if (!$CRON['in_remotecheck'] && !$CRON['remotecheck_disabled']) $remotecheck_state .= '<font color="green">Функция в режиме ожидания</font>';

		print('<tr><td align="center" colspan="2" class="colhead">Настройки мультитрекерной части | »» <a href="retrackeradmin.php">К управлению ретрекером</a></td></tr>');
		print('<tr><td>Отключить функцию получения удаленных пиров:<br /><small>*Так как эта функция выполняется в фоновом режиме, на ее отключение может потребоваться некоторое время. Слева от значения указано текущее состояние функии.</small></td><td><select name="remotecheck_disabled"><option value="1" '.($CRON['remotecheck_disabled']==1?"selected":"").'>Да</option><option value="0" '.($CRON['remotecheck_disabled']==0?"selected":"").'>Нет</option></select> '.$remotecheck_state .'</td></tr>');
		print('<tr><td>Время перепроверки удаленных пиров:<br /><small>*После N секунд торренты ставятся на проверку заново.</small></td><td><input type="text" name="remotepeers_cleantime" size="3" value="'.$CRON['remotepeers_cleantime'].'"> <b>секунд</b></td></tr>');
		print('<tr><td>Сколько торрентов проверять за раз:<br/><small>*На больших трекерах, таких как torrentsbook.com, необходимо ограничить количество проверяемых торрентов. При <b>нуле</b> будут проверены все мульитрекерные торренты</small></td><td><input type="text" name="remote_torrents" size="5" value="'.$CRON['remote_torrents'].'">торрентов</td></tr>');
		print('<tr><td>Интервал между проверками:<br/><small>*При сильной нагрузке желательно увеличить этот параметр. При <b>нуле</b> скрипт будет выполняться постоянно</small></td><td><input type="text" name="remotecheck_interval" size="3" value="'.$CRON['remotecheck_interval'].'">секунд</td></tr>');


		print('<tr><td align="center" colspan="2" class="colhead">Настройки очистки</td></tr>');

		print('<tr><td>Количество дней, по прошествии которых удаляются неактивированные аккаунты:</td><td><input type="text" name="signup_timeout" size="2" value="'.$CRON['signup_timeout'].'">дней</td></tr>');
		print('<tr><td>Время в сек, после которых торрент считается мертвым:</td><td><input type="text" name="max_dead_torrent_time" size="3" value="'.$CRON['max_dead_torrent_time'].'">секунд</td></tr>');
		print('<tr><td>Время очистки БД в секундах:</td><td><input type="text" name="autoclean_interval" size="4" value="'.$CRON['autoclean_interval'].'">секунд</td></tr>');
		print('<tr><td>Количество дней для очистки личных сообщений от системы:</td><td><input type="text" name="pm_delete_sys_days" size="2" value="'.$CRON['pm_delete_sys_days'].'">дней</td></tr>');
		print('<tr><td>Количество дней для очистки личных сообщений от пользователя:</td><td><input type="text" name="pm_delete_user_days" size="2" value="'.$CRON['pm_delete_user_days'].'">дней</td></tr>');
		print('<tr><td>Время жизни мертвого торрента в днях:</td><td><input type="text" name="ttl_days" size="3" value="'.$CRON['ttl_days'].'">дней</td></tr>');


		print('<tr><td align="center" colspan="2" class="colhead">Параметры мульитрекерной рейтинговой системы</td></tr>');
		print('<tr><td>Рейтинговая система включена:<br /><small>*Эта опция отвечает только <b>автоматическое</b> изменение рейтинга системой и ограничения, связанные с ним. Пользователи в любом случае смогут оценивать действия друг друга, но эти оценки не будут влиять ни на что.</small></td><td><select name="rating_enabled"><option value="1" '.($CRON['rating_enabled']==1?"selected":"").'>Да</option><option value="0" '.($CRON['rating_enabled']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Время, в течении которого пользователь считается новичком (рейтинговая система на него не действует):</td><td><input type="text" name="rating_freetime" size="2" value="'.$CRON['rating_freetime'].'">дней</td></tr>');
		print('<tr><td>Интервал менжу пересчетом рейтинга для пользователей:</td><td><input type="text" name="rating_checktime" size="4" value="'.$CRON['rating_checktime'].'">минут</td></tr>');
		print('<tr><td>Количество рейтинга, даваемое пользователю за заливку релиза:</td><td><input type="text" size="3" name="rating_perrelease" value="'.$CRON['rating_perrelease'].'"></td></tr>');
		print('<tr><td>Количество рейтинга, даваемое пользователю за регистрацию приглашенного пользователя:</td><td><input type="text" size="3" name="rating_perinvite" value="'.$CRON['rating_perinvite'].'"></td></tr>');
		print('<tr><td>Количество рейтинга, даваемое пользователю за выполнение запроса:</td><td><input type="text" size="3" name="rating_perrequest" value="'.$CRON['rating_perrequest'].'"></td></tr>');
		print('<tr><td>Количество рейтинга, даваемое пользователю за сидирование:<br /><small>*Точная формула для конкретного пользователя указана в myrating.php</small></td><td><input type="text" size="3" name="rating_perseed" value="'.$CRON['rating_perseed'].'"></td></tr>');
		print('<tr><td>Количество рейтинга, отнимаемое у пользователя за отсуствие раздач:</td><td><input type="text" size="3" name="rating_perleech" value="'.$CRON['rating_perleech'].'"></td></tr>');
		print('<tr><td>Лимит запрета скачивания торрентов:</td><td><input type="text" size="4" name="rating_downlimit" value="'.$CRON['rating_downlimit'].'"></td></tr>');
		print('<tr><td>Лимит отключения аккаунта:</td><td><input type="text" size="4" name="rating_dislimit" value="'.$CRON['rating_dislimit'].'"></td></tr>');
		print('<tr><td>Максимальное количество рейтинга:</td><td><input type="text" size="4" name="rating_max" value="'.$CRON['rating_max'].'"></td></tr>');
		print('<tr><td>Сколько единиц рейтинга стоит 1 единица откупа:</td><td><input type="text" size="2" name="rating_discounttorrent" value="'.$CRON['rating_discounttorrent'].'"></td></tr>');


		print('<tr><td align="center" colspan="2" class="colhead">Прочие параметры</td></tr>');
		print('<tr><td>Интервал реанонса (обновления статистики в клиентах):</td><td><input type="text" size="5" name="announce_interval" value="'.$CRON['announce_interval'].'">минут</td></tr>');
		print('<tr><td>Интервал очистки данных о изменениии кармы/рейтинга:<br /><small>*После указанного времени пользователь сможет изменять рейтинг еще раз.<br />*Данное значение не может быть меньше интервала очистки, желательно, чтобы оно было кратно ему.<br />*Оставьте поле пустым, или 0, если хотите, чтобы рейтинг запоминался навсегда</td><td><input type="text" size="3" name="delete_votes" value="'.$CRON['delete_votes'].'">минут</td></tr>');

		print('<tr><td align="center" colspan="2"><input type="submit" name="save" value="Сохранить изменения"><input type="reset" value="Сбросить"><input type="submit" name="reset" value="Сбросить статистику cron" disabled><input type="submit" name="recount" value="Синхронизировать торренты и БД" disabled></td></tr>
<tr><td colspan="2"><small>*Сброс статистики cron необходим, если скрипты неверно отображают состояние cron-функций, следить за выполнением скриптов cron удобно через <a href="http://httpd.apache.org/docs/2.0/mod/mod_status.html">mod_status</a> для apache</small></td></tr></table></form>');

	}
	elseif (isset($_POST['save'])) {

		$reqparametres = array('max_dead_torrent_time','signup_timeout','autoclean_interval','pm_delete_sys_days','pm_delete_user_days','ttl_days','remotecheck_disabled','announce_interval','delete_votes','remote_torrents','rating_enabled','remotecheck_interval');

		$multi_param = array('remotepeers_cleantime');

		$rating_param = array('rating_freetime','rating_perseed','rating_perinvite','rating_perrequest','rating_checktime','rating_perrelease','rating_dislimit','rating_downlimit','rating_perleech','rating_discounttorrent','rating_max');

		$updateset = array();

		foreach ($reqparametres as $param) {
			if (!isset($_POST[$param]) && (($param != 'rating_enabled') || ($param != 'delete_votes') || ($param != 'remote_torrents')))  { stdmsg($tracker_lang['error'],"Некоторые поля не заполнены ($param)",'error'); stdfoot(); die; }
			$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
		}

		if ($_POST['remotecheck_disabled'] == 0) {
			foreach ($multi_param as $param) {
				if (!$_POST[$param] || !isset($_POST[$param])) { stdmsg($tracker_lang['error'],"Некоторые поля для мультитрекерности не заполнены",'error'); stdfoot(); die; }
				$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
			}
		}

		if ($_POST['rating_enabled']) {
			foreach ($rating_param as $param) {
				if (!$_POST[$param] || !isset($_POST[$param])) { stdmsg($tracker_lang['error'],"Некоторые поля для рейтинговой системы не заполнены",'error'); stdfoot(); die; }
				$updateset[] = "UPDATE cron SET cron_value=".sqlesc($_POST[$param])." WHERE cron_name='$param'";
			}
		}

		foreach ($updateset as $query) sql_query($query);
		print($lang['ok'].'<hr/>');

		print $lang['step6_descr'];

		cont(6);
	}
}
elseif ($step==6) {
	print $lang['install_complete'];
	print $lang['install_notice'];
	print $lang['donate'];
}


footers();
?>