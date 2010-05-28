<?php
define('ROOT_PATH',str_replace('install','',dirname(__FILE__)));

require_once(ROOT_PATH.'include/bittorrent.php');

$step = (int)$_GET['step'];

if ($_GET['setlang']) {
	setcookie('lang',(string)$_GET['setlang']);
	headers();
	print('<a href="index.php">Продолжить / Continue</a>');
	footers();
	die();
}
function headers() {
	global $step;
	header("X-Powered-By: Kinokpk.com releaser ".RELVERSION);
	header("Cache-Control: no-cache, must-revalidate, max-age=0");
	//header("Expires:" . gmdate("D, d M Y H:i:s") . " GMT");
	header("Expires: 0");
	header("Pragma: no-cache");
	print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title>Kinokpk.com releaser 2.70 installer, step: '.$step.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /></head><body>');

	if (ini_get("register_globals")) die('<font color="red" size="20">Отключи register_globals, дурачок! / Turn off register_globals, noob!</font>');
}

function footers() {
	print('<hr /><div align="right">Kinokpk.com releaser 2.70 installer</div></body></html>');
}

function cont($step) {
	global $lang;
	print '<a href="index.php?step='.$step.'">'.$lang['continue'].'</a>';

}

headers();

if (!$_COOKIE['lang']) {
	print("<h1>Выберите язык / Choose a language: <a href=\"index.php?setlang=russian\">Русский</a>, <a href=\"index.php?setlang=english\">English</a></h1>");
	footers();
	die();
} else require_once(ROOT_PATH.'install/lang_'.$_COOKIE['lang'].'.php');

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
				mysql_query($query) or die($lang['mysql_error'].'['.mysql_errno().']: ' . mysql_error());
				$query = '';
			}
		}
	}
	print($lang['ok'].'<hr/>');
	$dbconfig = <<<HTML
<?

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

if(!defined('IN_TRACKER') && !defined('IN_ANNOUNCE')) die("Direct access to this page not allowed");

\$mysql_host = "{$mysql_host}";
\$mysql_user = "{$mysql_user}";
\$mysql_pass = "{$mysql_pass}";
\$mysql_db = "{$mysql_db}";
\$mysql_charset = "{$mysql_charset}";

\$fmysql_host = "{$fmysql_host}";
\$fmysql_user = "{$fmysql_user}";
\$fmysql_pass = "{$fmysql_pass}";
\$fmysql_db = "{$fmysql_db}";
\$fmysql_charset = "{$fmysql_charset}";
\$fprefix = "{$fprefix}";
?>
HTML;
	print($lang['config_to_file']);

	if (!file_put_contents(ROOT_PATH.'include/secrets.php', $dbconfig))
	{ print($lang['invalid_rights'].' ('.ROOT_PATH.'include/config.php).'); footers(); die(); }

	print ($lang['ok']."<hr />");

	print ($lang['write_config_ok']);
	cont(4);
}

elseif ($_GET['step'] == 4) {
	dbconn();

	print $lang['main_settings'];
	if (!isset($_GET['action'])){

		print('<table width="100%" border="1">');
		print('<form action="index.php?step=4&action=save" method="POST">');
		print('<tr><td align="center" colspan="2" class="colhead">Основные настройки</td></tr>');

		print('<tr><td>Адрес сайта (без /):</td><td><input type="text" name="defaultbaseurl" size="30" value="http://'.$_SERVER['HTTP_HOST'].'"> Например, "http://www.kinokpk.com"</td></tr>');
		print('<tr><td>Название сайта (title):</td><td><input type="text" name="sitename" size="80" value="'.$CACHEARRAY['sitename'].'"> Например, "Релизер японских тушканчиков"</td></tr>');
		print('<tr><td>Описание сайта (meta description):</td><td><input type="text" name="description" size="80" value="'.$CACHEARRAY['description'].'"> Например, "Самые шустрые тушканчики скачать тута"</td></tr>');
		print('<tr><td>Ключевые слова (meta keywords):</td><td><input type="text" name="keywords" size="80" value="'.$CACHEARRAY['keywords'].'"> Например, "скачать, тушканчики, япония, релизер"</td></tr>');
		print('<tr><td>Емайл, с которого будут отправляться сообщения сайта:</td><td><input type="text" name="siteemail" size="30" value="'.$CACHEARRAY['siteemail'].'"> Например, "bot@kinokpk.com"</td></tr>');
		print('<tr><td>Емайл для связи с администратором:</td><td><input type="text" name="adminemail" size="30" value="'.$CACHEARRAY['adminemail'].'"> Например, "admin@windows.lox"</td></tr>');
		print('<tr><td>Язык релизера по умолчанию (имя lang_%язык%):</td><td><input type="text" name="default_language" size="10" value="'.$CACHEARRAY['default_language'].'"></td></tr>');
		print('<tr><td>Использовать систему многоязычности (отключать не рекомендуется):</td><td><select name="use_lang"><option value="1" '.($CACHEARRAY['use_lang']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_lang']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Стандартная тема для гостей и регестрирующихся (themes/%тема%):</td><td><input type="text" name="default_theme" size="10" value="'.$CACHEARRAY['default_theme'].'"> По умолчанию "kinokpk"</td></tr>');
		print('<tr><td>Ваш копирайт для отображения внизу страницы:<br /><small>*вы можете использовать шаблон <b>{datenow}</b> для показа текущего года</small></td><td><input type="text" name="yourcopy" size="60" value="'.$CACHEARRAY['yourcopy'].'"> Например, "&copy; 2008-{datenow} Мой Мосх"</td></tr>');
		print('<tr><td>Использовать систему блоков (отключать не рекомендуется):</td><td><select name="use_blocks"><option value="1" '.($CACHEARRAY['use_blocks']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_blocks']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Использовать gzip сжатие для страниц:</td><td><select name="use_gzip"><option value="1" '.($CACHEARRAY['use_gzip']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_gzip']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Использовать систему банов по IP/Подсетям:</td><td><select name="use_ipbans"><option value="1" '.($CACHEARRAY['use_ipbans']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_ipbans']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Использовать сессии:</td><td><select name="use_sessions"><option value="1" '.($CACHEARRAY['use_sessions']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_sessions']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Тип SMTP:</td><td><input type="text" name="smtptype" size="10" value="'.$CACHEARRAY['smtptype'].'"></td></tr>');
		print('<tr><td>Бинарный формат пиров в анонсере:</td><td><select name="announce_packed"><option value="1" '.($CACHEARRAY['announce_packed']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['announce_packed']==0?"selected":"").'>Нет</option></select> По умолчанию, Да</td></tr>');

		print('<tr><td align="center" colspan="2" class="colhead">Настройки интеграции с форумом IPB</td></tr>');

		print('<tr><td>Использовать интеграцию с форумом IPB:</td><td><select name="use_integration"><option value="1" '.($CACHEARRAY['use_integration']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_integration']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Тип экспорта релизов на форум:<br /><small>*для использования функции экспорта в вики-секцию<br />установите интеграцию IPB и wikimedia с <a target="_blank" href="http://www.ipbwiki.com/">http://www.ipbwiki.com/</a></small></td><td><select name="exporttype"><option value="wiki" '.($CACHEARRAY['exporttype']=="wiki"?"selected":"").'>В вики-секцию</option><option value="post" '.($CACHEARRAY['exporttype']=="post"?"selected":"").'>Непосредственно в пост</option></select></td></tr>');
		print('<tr><td>Адрес форума (без /):</td><td><input type="text" name="forumurl" size="60" value="'.$CACHEARRAY['forumurl'].'"> Например, "http://forum.pdaprime.ru"</td></tr>');
		print('<tr><td>Названи форума:</td><td><input type="text" name="forumname" size="60" value="'.$CACHEARRAY['forumname'].'"> Например, "pdaPRIME.ru"</td></tr>');
		print('<tr><td>Префикс форумных cookie:</td><td><input type="text" name="ipb_cookie_prefix" size="4" value="'.$CACHEARRAY['ipb_cookie_prefix'].'"> По умолчанию IPB, пусто</td></tr>');
		print('<tr><td>ID форума-корзины:</td><td><input type="text" name="forum_bin_id" size="3" value="'.$CACHEARRAY['forum_bin_id'].'"></td></tr>');
		print('<tr><td>Класс пользователей после экспорта на форум:</td><td><input type="text" name="defuserclass" size="1" value="'.$CACHEARRAY['defuserclass'].'"> По умолчанию IPB, "3"</td></tr>');
		print('<tr><td>ID форума для экспорта прочих релизов:<br /><small>*Это релизы, форум которых не совпадает с названием тегов, либо возникла ошибка при определении форума</small></td><td><input type="text" name="not_found_export_id" size="3" value="'.$CACHEARRAY['not_found_export_id'].'"></td></tr>');
		print('<tr><td>Папка со смайлами форума (без /):</td><td><input type="text" name="emo_dir" size="10" value="'.$CACHEARRAY['emo_dir'].'"> По умолчанию IPB, "default"</td></tr>');


		print('<tr><td align="center" colspan="2" class="colhead">Настройки регистрации</td></tr>');

		print('<tr><td>Запретить регистрацию:</td><td><select name="deny_signup"><option value="1" '.($CACHEARRAY['deny_signup']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['deny_signup']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Разрешить регистрацию по приглашениям:</td><td><select name="allow_invite_signup"><option value="1" '.($CACHEARRAY['allow_invite_signup']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['allow_invite_signup']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Сколько прибавлять аплоада, если пользователь зарегестриовался по приглашению:</td><td><input type="text" size="6" name="upload_per_invite" value="'.$CACHEARRAY['upload_per_invite'].'"/><b>мега</b>байт</td></tr>');
		print('<tr><td>Использовать активацию аккаунтов по e-mail:</td><td><select name="use_email_act"><option value="1" '.($CACHEARRAY['use_email_act']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_email_act']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Использовать капчу:<br /><small>*Вы должны зарегестрироваться на <a target="_blank" href="http://recaptcha.net">ReCaptcha.net</a> и получить приватный и публичный ключи для использования этой опции</small></td><td><select name="use_captcha"><option  value="1" '.($CACHEARRAY['use_captcha']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_captcha']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Публичный ключ капчи:</td><td><input type="text" name="re_publickey" size="80" value="'.$CACHEARRAY['re_publickey'].'"></td></tr>');
		print('<tr><td>Приватный ключ капчи:</td><td><input type="text" name="re_privatekey" size="80" value="'.$CACHEARRAY['re_privatekey'].'"></td></tr>');

		print('<tr><td align="center" colspan="2" class="colhead">Настройки ограничений</td></tr>');

		print('<tr><td>Максимальное количество пользователей:</td><td><input type="text" name="maxusers" size="6" value="'.$CACHEARRAY['maxusers'].'">пользователей</td></tr>');
		print('<tr><td>Максимальное количество сообщений в Личном ящике:</td><td><input type="text" name="pm_max" size="4" value="'.$CACHEARRAY['pm_max'].'">сообщений</td></tr>');
		print('<tr><td>Максимальная ширина автара:</td><td><input type="text" name="avatar_max_width" size="3" value="'.$CACHEARRAY['avatar_max_width'].'">пикселей</td></tr>');
		print('<tr><td>Максимальная высота автара:</td><td><input type="text" name="avatar_max_height" size="3" value="'.$CACHEARRAY['avatar_max_height'].'">пикселей</td></tr>');
		print('<tr><td>Запретить подключения с закрытыми портами:</td><td><select name="nc"><option value=1 '.($CACHEARRAY['nc']?"selected":"").'>Да</option><option value="0" '.(!$CACHEARRAY['nc']?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Максимальный размер торрент-файла в байтах:</td><td><input type="text" name="max_torrent_size" size="10" value="'.$CACHEARRAY['max_torrent_size'].'">байт</td></tr>');
		print('<tr><td>Максимальное количество картинок для релиза:</td><td><input type="text" name="max_images" size="2" value="'.$CACHEARRAY['max_images'].'">Например, "2"</td></tr>');

		print('<tr><td align="center" colspan="2" class="colhead">Настройки безопасности</td></tr>');

		print('<tr><td>Флуд-интервал в секундах:</td><td><input type="text" name="as_timeout" size="10" value="'.$CACHEARRAY['as_timeout'].'">секунд</td></tr>');
		print('<tr><td>Использовать проверку последних 5 комментариев (антиспам):</td><td><select name="as_check_messages"><option value="1" '.($CACHEARRAY['as_check_messages']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['as_check_messages']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Дебаг-режим:</td><td><select name="debug_mode"><option value="1" '.($CACHEARRAY['debug_mode']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['debug_mode']==0?"selected":"").'>Нет</option></select></td></tr>');

		print('<tr><td align="center" colspan="2" class="colhead">Прочее</td></tr>');

		print('<tr><td>Количество релизов в списке релизов на страницу:<br /><small>*при изменении этого параметра необходимо очистить кеш browse</small></td><td><input type="text" name="torrentsperpage" size="3" value="'.$CACHEARRAY['torrentsperpage'].'">релизов</td></tr>');
		print('<tr><td>Использовать TTL (авто удаление мертвых торрентов):</td><td><select name="use_ttl"><option value="1" '.($CACHEARRAY['use_ttl']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_ttl']==0?"selected":"").'>Нет</option></select></td></tr>');
		print('<tr><td>Использовать систему ограничения личеров по времени:</td><td><select name="use_wait"><option value="1" '.($CACHEARRAY['use_wait']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_wait']==0?"selected":"").'>Нет</option></select></td></tr>');

		print('<tr><td align="center" colspan="2"><input type="submit" value="Сохранить изменения"><input type="reset" value="Сбросить"></td></tr></table>');
	}

	elseif ($_GET['action'] == 'save'){
		$reqparametres = array('torrentsperpage','maxusers','max_torrent_size','max_images','defaultbaseurl','siteemail','adminemail','sitename','description','keywords',
'forumname','yourcopy','pm_max','default_language',
'avatar_max_width','avatar_max_height','default_theme','nc','deny_signup','allow_invite_signup',
'use_ttl','use_email_act','use_wait','use_lang','use_captcha','use_blocks','use_gzip','use_ipbans','use_sessions','smtptype',
'as_timeout','as_check_messages','use_integration','debug_mode','ipb_cookie_prefix','announce_packed','upload_per_invite');
		$int_param = array('exporttype','forumurl','forum_bin_id','defuserclass','not_found_export_id','emo_dir');
		$captcha_param = array('re_publickey','re_privatekey');

		$updateset = array();

		foreach ($reqparametres as $param) {
			if (!isset($_POST[$param]) && ($param != 'forumname') && ($param != 'ipb_cookie_prefix')) stderr($tracker_lang['error'],"Некоторые поля не заполнены ($param)");
			$updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
		}

		if ($_POST['use_integration'] == 1) {
			foreach ($int_param as $param) {
				if (!$_POST[$param] || !isset($_POST[$param])) stderr($tracker_lang['error'],"Некоторые поля для интеграции с форумом не заполнены");
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
			
		print($lang['settings_saved'].$lang['ok']);
		print($lang['settings_notice'].'<hr />');
		cont(5);
	}
}
elseif ($step==5) {
	print $lang['install_complete'];
	print $lang['install_notice'];
	print $lang['donate'];
}


footers();
?>
