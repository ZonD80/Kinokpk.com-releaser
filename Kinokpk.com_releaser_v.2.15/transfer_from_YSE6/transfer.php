<?php
require_once("include/bittorrent.php");

if (!isset($_GET['step'])) {
print "Вас приветствует скрипт переноса TBDEV YSE 6 на Kinokpk.com releaser 2.15. Следуйте инструкциям и все будет нормально.<br/>
Шаблоны релизов взяты из <a href=\"http://dev.kinokpk.com/viewtopic.php?t=39\">Этого топика</a> на форуме разработки релизера.<br/>
Если вы хотите поправить шаблоны релизов, то вам придется править в этом скрипте параметры \$descr_typeid (ID детали шаблона) и descr_type (ID шаблона) (в SQL запросе) в соответствие с вашим редактированием.<hr/>";
print "Greetings for you from TBDEV YSE 6 to Kinokpk.com releaser 2.15 transfer script. Follow the instructions and all will be OK.<br/>
Releases' templates are taken from <a href=\"http://dev.kinokpk.com/viewtopic.php?t=39\">This thread</a> of developer's forum.<br/>
If you want to change default parametres, you shall edit \$descr_typeid (ID template's detail) and descr_type (ID of template) (in SQL query) according to your editing.<hr/>";

print '<a href="transfer.php?step=1">Продолжить/Continue</a>';

}

elseif ($_GET['step'] ==1) {

      print('<hr/>Апдейт базы данных / Updating database: ');
  relconn();
  $strings = file("update.sql");
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
           mysql_query($query) or die('<font color="red">FAIL</font>, Ошибка MySQL / MySQL error ['.mysql_errno().']: ' . mysql_error());
           $query = '';
      }
  }
}
 print('<font color="green">OK</font><hr/>');
 
print "База успешно обновлена, следующий шаг запустит конфигуратор и перенесет описания релизов<hr/>";
print "Your database was successfully updated, next step will execute main configuration script and transfer releases' descriptions<hr/>";
print '<a href="transfer.php?step=2">Продолжить/Continue</a>';
}

elseif ($_GET['step'] ==2) {
dbconn();
if (!isset($_GET['action'])){


print("Основные настройки Kinokpk.com releaser ".RELVERSION."<hr/>");
print('<table width="100%" border="1">');
print('<form action="transfer.php?action=save&step=2" method="POST">');
print('<tr><td>Максимальное количество пользователей:</td><td><input type="text" name="maxusers" size="6" value="'.$CACHEARRAY['maxusers'].'">пользователей</td></tr>');
print('<tr><td>Время в сек, после которых торрент считается мертвым:</td><td><input type="text" name="max_dead_torrent_time" size="3" value="'.$CACHEARRAY['max_dead_torrent_time'].'">секунд</td></tr>');
print('<tr><td>Минимальное количество голосов для отображения рейтинга торрента:</td><td><input type="text" name="minvotes" size="2" value="'.$CACHEARRAY['minvotes'].'">голосов</td></tr>');
print('<tr><td>Количество дней, по прошествии которых удаляются неактивированные аккаунты:</td><td><input type="text" name="signup_timeout" size="2" value="'.$CACHEARRAY['signup_timeout'].'">дней</td></tr>');
print('<tr><td>Интервал реанонса (обновлений статистики в клиентах) в минутах:</td><td><input type="text" name="announce_interval" size="2" value="'.$CACHEARRAY['announce_interval'].'">минут</td></tr>');
print('<tr><td>Максимальный размер торрет-файла в байтах:</td><td><input type="text" name="max_torrent_size" size="10" value="'.$CACHEARRAY['max_torrent_size'].'">байт</td></tr>');
print('<tr><td>Адрес сайта (без /):</td><td><input type="text" name="defaultbaseurl" size="30" value="'.$CACHEARRAY['defaultbaseurl'].'"> Например, "http://www.kinokpk.com"</td></tr>');
print('<tr><td>Емайл, с которого будут отправляться сообщения сайта:</td><td><input type="text" name="siteemail" size="30" value="'.$CACHEARRAY['siteemail'].'"> Например, bot@kinokpk.com</td></tr>');
print('<tr><td>Емайл для связи с администратором:</td><td><input type="text" name="adminemail" size="30" value="'.$CACHEARRAY['adminemail'].'"> Например, "admin@windows.lox"</td></tr>');
print('<tr><td>Название сайта (title):</td><td><input type="text" name="sitename" size="80" value="'.$CACHEARRAY['sitename'].'"> Например, "Релизер японских тушканчиков"</td></tr>');
print('<tr><td>Описание сайта (meta description):</td><td><input type="text" name="description" size="80" value="'.$CACHEARRAY['description'].'"> Например, "Самые шустрые тушканчики скачать тута"</td></tr>');
print('<tr><td>Ключевые слова (meta keywords):</td><td><input type="text" name="keywords" size="80" value="'.$CACHEARRAY['keywords'].'"> Например, "скачать, тушканчики, япония, релизер"</td></tr>');
print('<tr><td>Время очистки в секундах:</td><td><input type="text" name="autoclean_interval" size="4" value="'.$CACHEARRAY['autoclean_interval'].'">секунд</td></tr>');
print('<tr><td>Ваш копирайт для отображения внизу страницы:<br/><small>*вы можете использовать шаблон <b>{datenow}</b> для показа текущего года</small></td><td><input type="text" name="yourcopy" size="60" value="'.$CACHEARRAY['yourcopy'].'"> Например, "&copy; 2008-{datenow} Мой Мосх"</td></tr>');
print('<tr><td>Количество дней для очистки личных сообщений от системы:</td><td><input type="text" name="pm_delete_sys_days" size="2" value="'.$CACHEARRAY['pm_delete_sys_days'].'">дней</td></tr>');
print('<tr><td>Количество дней для очистки личных сообщений от пользователя:</td><td><input type="text" name="pm_delete_user_days" size="2" value="'.$CACHEARRAY['pm_delete_user_days'].'">дней</td></tr>');
print('<tr><td>Максимальное количество сообщений в Личном ящике:</td><td><input type="text" name="pm_max" size="4" value="'.$CACHEARRAY['pm_max'].'">сообщений</td></tr>');
print('<tr><td>Время жизни мертвого торрента в днях:</td><td><input type="text" name="ttl_days" size="3" value="'.$CACHEARRAY['ttl_days'].'">дней</td></tr>');
print('<tr><td>Язык релизера по умолчанию (имя lang_%язык%):</td><td><input type="text" name="default_language" size="10" value="'.$CACHEARRAY['default_language'].'"></td></tr>');
print('<tr><td>Максимальная ширина автара:</td><td><input type="text" name="avatar_max_width" size="3" value="'.$CACHEARRAY['avatar_max_width'].'">пикселей</td></tr>');
print('<tr><td>Максимальная высота автара:</td><td><input type="text" name="avatar_max_height" size="3" value="'.$CACHEARRAY['avatar_max_height'].'">пикселей</td></tr>');
print('<tr><td>Сколько пользователию давать ботнусов в час для сидирования 1 торрента:</td><td><input type="text" name="points_per_hour" size="2" value="'.$CACHEARRAY['points_per_hour'].'">бонусов</td></tr>');
print('<tr><td>Стандартная тема для гостей и регестрирующихся (themes/%тема%):</td><td><input type="text" name="default_theme" size="10" value="'.$CACHEARRAY['default_theme'].'"> По умолчанию "kinokpk"</td></tr>');
print('<tr><td>Запретить подключения с закрытыми портами:</td><td><select name="nc"><option value="yes" '.($CACHEARRAY['nc']=="yes"?"selected":"").'>Да</option><option value="no" '.($CACHEARRAY['nc']=="no"?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Запретить регистрацию:</td><td><select name="deny_signup"><option value="1" '.($CACHEARRAY['deny_signup']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['deny_signup']==0?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Разрешить регистрацию по приглашениям:</td><td><select name="allow_invite_signup"><option value="1" '.($CACHEARRAY['allow_invite_signup']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['allow_invite_signup']==0?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Использовать TTL (авто удаление мертвых торрентов):</td><td><select name="use_ttl"><option value="1" '.($CACHEARRAY['use_ttl']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_ttl']==0?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Использовать активацию аккаунтов по e-mail:</td><td><select name="use_email_act"><option value="1" '.($CACHEARRAY['use_email_act']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_email_act']==0?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Использовать систему ограничения личеров по времени:</td><td><select name="use_wait"><option value="1" '.($CACHEARRAY['use_wait']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_wait']==0?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Использовать систему многоязычности (отключать не рекомендуется):</td><td><select name="use_lang"><option value="1" '.($CACHEARRAY['use_lang']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_lang']==0?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Использовать капчу:<br/><small>*Вы должны зарегестрироваться на <a target="_blank" href="http://recaptcha.net">ReCaptcha.net</a> и получить приватный и публичный ключи для использования этой опции</small></td><td><select name="use_captcha"><option  value="1" '.($CACHEARRAY['use_captcha']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_captcha']==0?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Использовать систему блоков (отключать не рекомендуется):</td><td><select name="use_blocks"><option value="1" '.($CACHEARRAY['use_blocks']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_blocks']==0?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Использовать gzip сжатие для страниц:</td><td><select name="use_gzip"><option value="1" '.($CACHEARRAY['use_gzip']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_gzip']==0?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Использовать систему банов по IP/Подсетям:</td><td><select name="use_ipbans"><option value="1" '.($CACHEARRAY['use_ipbans']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_ipbans']==0?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Использовать сессии:</td><td><select name="use_sessions"><option value="1" '.($CACHEARRAY['use_sessions']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_sessions']==0?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Тип SMTP:</td><td><input type="text" name="smtptype" size="10" value="'.$CACHEARRAY['smtptype'].'"></td></tr>');
print('<tr><td>Флуд-интервал в секундах:</td><td><input type="text" name="as_timeout" size="10" value="'.$CACHEARRAY['as_timeout'].'">секунд</td></tr>');
print('<tr><td>Использовать проверку последних 5 комментариев (антиспам):</td><td><select name="as_check_messages"><option value="1" '.($CACHEARRAY['as_check_messages']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['as_check_messages']==0?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Использовать интеграцию с форумом IPB:</td><td><select name="use_integration"><option value="1" '.($CACHEARRAY['use_integration']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['use_integration']==0?"selected":"").'>Нет</option></select></td></tr>');
print('<tr><td>Тип экспорта релизов на форум:</td><td><select name="exporttype"><option value="wiki" '.($CACHEARRAY['exporttype']=="wiki"?"selected":"").'>В вики-секцию</option><option value="post" '.($CACHEARRAY['exporttype']=="post"?"selected":"").'>Непосредственно в пост</option></select></td></tr>');
print('<tr><td>Адрес форума (без /):</td><td><input type="text" name="forumurl" size="60" value="'.$CACHEARRAY['forumurl'].'"> Например, "http://forum.pdaprime.ru"</td></tr>');
print('<tr><td>Названи форума:</td><td><input type="text" name="forumname" size="60" value="'.$CACHEARRAY['forumname'].'"> Например, "pdaPRIME.ru"</td></tr>');
print('<tr><td>ID форума-корзины:</td><td><input type="text" name="forum_bin_id" size="3" value="'.$CACHEARRAY['forum_bin_id'].'"></td></tr>');
print('<tr><td>Класс пользователей после экспорта на форум:</td><td><input type="text" name="defuserclass" size="1" value="'.$CACHEARRAY['defuserclass'].'"> По умолчанию IPB, "3"</td></tr>');
print('<tr><td>ID форума для экспорта прочих релизов<br/><small>*Это релизы, форум которых не совпадает с названием тегов, либо возникла ошибка при определении форума</small>:</td><td><input type="text" name="not_found_export_id" size="3" value="'.$CACHEARRAY['not_found_export_id'].'"></td></tr>');
print('<tr><td>Папка со смайлами форума (без /):</td><td><input type="text" name="emo_dir" size="10" value="'.$CACHEARRAY['emo_dir'].'"> По умолчанию IPB, "default"</td></tr>');
print('<tr><td>Публичный ключ капчи:</td><td><input type="text" name="re_publickey" size="80" value="'.$CACHEARRAY['re_publickey'].'"></td></tr>');
print('<tr><td>Приватный ключ капчи:</td><td><input type="text" name="re_privatekey" size="80" value="'.$CACHEARRAY['re_privatekey'].'"></td></tr>');
print('<tr><td>Дебаг-режим:</td><td><select name="debug_mode"><option value="1" '.($CACHEARRAY['debug_mode']==1?"selected":"").'>Да</option><option value="0" '.($CACHEARRAY['debug_mode']==0?"selected":"").'>Нет</option></select></td></tr>');

print('<tr><td colspan="2"><input type="submit" value="Сохранить изменения"><input type="reset" value="Сбросить"></td></tr></table>');

}

elseif ($_GET['action'] == 'save'){
       $reqparametres = array('maxusers','max_dead_torrent_time','minvotes','signup_timeout','announce_interval','max_torrent_size','defaultbaseurl','siteemail','adminemail','sitename','description','keywords','autoclean_interval','yourcopy','pm_delete_sys_days','pm_delete_user_days','pm_max','ttl_days','default_language','avatar_max_width','avatar_max_height','points_per_hour','default_theme','nc','deny_signup','allow_invite_signup','use_ttl','use_email_act','use_wait','use_lang','use_captcha','use_blocks','use_gzip','use_ipbans','use_sessions','smtptype','as_timeout','as_check_messages','use_integration','debug_mode');
       $int_param = array('exporttype','forumurl','forumname','forum_bin_id','defuserclass','not_found_export_id','emo_dir');
       $captcha_param = array('re_publickey','re_privatekey');

       $updateset = array();

       foreach ($reqparametres as $param) {
         if (!isset($_POST[$param])) die("Некоторые поля не заполнены ($param)");
       $updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
       }

       if ($_POST['use_integration'] == 1) {
         foreach ($int_param as $param) {
         if (!$_POST[$param] || !isset($_POST[$param])) die("Некоторые поля для интеграции с форумом не заполнены");
       $updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
       }
     }

            if ($_POST['use_captcha'] == 1) {
         foreach ($captcha_param as $param) {
         if (!$_POST[$param] || !isset($_POST[$param])) die("Приватный или публичный ключи капчи не определены");
       $updateset[] = "UPDATE cache_stats SET cache_value=".sqlesc($_POST[$param])." WHERE cache_name='$param'";
       }
     }

     foreach ($updateset as $query) sql_query($query);

     header("Location: transfer.php?step=3");

}
}

elseif ($_GET['step'] ==3) {
  dbconn();
$descr_typeid = 242;
$torrent = sql_query("SELECT id,descr FROM torrents ORDER BY id ASC");
while ($t = mysql_fetch_array($torrent)) {
  mysql_query("INSERT INTO descr_torrents (torrent,typeid,value) VALUES (".$t['id'].",".$descr_typeid.",".sqlesc($t['descr']).")") or die(mysql_error());

}
print "Описания успешно перенесены, теперь необходимо обновить шаблоны описаний для релизов<hr/>";
print "Descriptions were sucessfully transfered, now script will update releases' description templates<hr/>";
print '<a href="transfer.php?step=4">Продолжить/Continue</a>';
}

elseif ($_GET['step'] == 4) {
  dbconn();
       sql_query("UPDATE torrents SET descr_type = 25") or die('<font color="red">FAIL</font>, Ошибка MySQL / MySQL error ['.mysql_errno().']: ' . mysql_error());

print "Шаблоны описаний релизов успешно обновлены, вы можете проверить функционирование релизера в другом окне браузера, следующий шаг приведет к чистке уже не нужных описаний<hr/>";
print "Releases' description templates were successfully updated, you can check releaser's functionality in another browser's window, next step will make cleanup<hr/>";
print '<a href="transfer.php?step=5">Продолжить/Continue</a>';
}
elseif ($_GET['step'] == 5) {
  dbconn();
  sql_query("ALTER TABLE  `torrents`  DROP COLUMN  `descr`") or die('<font color="red">FAIL</font>, Ошибка MySQL / MySQL error ['.mysql_errno().']: ' . mysql_error());

  sql_query("ALTER TABLE  `torrents`  DROP COLUMN  `ori_descr`") or die('<font color="red">FAIL</font>, Ошибка MySQL / MySQL error ['.mysql_errno().']: ' . mysql_error());

print "Переход на Kinokpk.com releaser 2.15 успешно завершен! НЕ ЗАБЫВАЙТЕ УДАЛИТЬ ЭТОТ ФАЙЛ И update.sql С ВАШЕГО СЕРВЕРА<hr/>";
print "You have successfully transfered to Kinokpk.com releaser 2.15! DO NOT FORGET TO DELETE THIS FILE AND update.sql FROM YOUR SERVER<hr/>";
print '<a href="javascript:self.close();" >Закрыть окно/Close this window</a><hr/>';
print '<script language="javascript">alert(\'Спасибо за выбор Kinokpk.com releaser 2.15/Thank you for choosing Kinokpk.com releaser 2.15\');</script>';

}

?>