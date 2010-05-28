<?php
require_once("include/bittorrent.php");

if (!isset($_GET['step'])) {
print "Вас приветствует скрипт обновления Kinokpk.com releaser 2.15 до Kinokpk.com releaser 2.40. Следуйте инструкциям и все будет нормально.<hr/>";
print "Greetings for you from Kinokpk.com releaser 2.15 to Kinokpk.com releaser 2.40 update script. Follow the instructions and all will be OK.<hr/>";

print '<a href="update.php?step=1">Продолжить/Continue</a>';

}

elseif ($_GET['step'] == 1) {
      print('<hr/>Апдейт базы данных / Updating database: ');
  relconn(false);

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
 
print ('БД обновлена, в следующем шаге перенесутся картинки к торрентам<hr/>');
print ('Database updated, next step will transfer torrents\' images<hr/>');
print '<a href="update.php?step=2">Продолжить/Continue</a>';
}

elseif ($_GET['step'] ==2) {
dbconn();
$query = mysql_query('SELECT id,image1,image2 FROM torrents ORDER BY id ASC');

while ($row = mysql_fetch_array($query)) {
  $id = $row['id'];
  if ($row['image1']) $images[] = $row['image1'];
  if ($row['image2']) $images[] = $row['image2'];
  if ($images) {
  $imgs = implode(',',$images);
  mysql_query ("UPDATE torrents SET images = '$imgs' WHERE id = $id");
  unset($images);
  }
}
sql_query('ALTER TABLE  `torrents`  DROP COLUMN  `image1`');
sql_query('ALTER TABLE  `torrents`  DROP COLUMN  `image2`');

print 'Картинки успешно перенесены, сейчас скрипт очистит announce-url торрентов для нормального функционирования мультитрекерности.<hr/>';
print 'Images were sucessfully transfered, now script will clean announce-url of torrents for normal multi-tracker feature functionality.<hr/>';
print '<a href="update.php?step=3">Продолжить/Continue</a>';
}

elseif ($_GET['step'] == 3) {
require_once(ROOT_PATH.'include/benc.php');
dbconn();

$res = sql_query('SELECT id FROM torrents ORDER BY id DESC');
while (list($id) = mysql_fetch_array($res)){

$fn = ROOT_PATH."/torrents/$id.torrent";
if (is_readable($fn)) {
$dict = bdec_file($fn, (1024*1024));
unlink($fn);
unset($dict['value']['announce']);
unset($dict['value']['nodes']); // remove cached peers (Bitcomet & Azareus)
unset($dict['value']['azureus_properties']); // remove azureus properties
unset($dict['value']['comment']);
unset($dict['value']['created by']);
unset($dict['value']['publisher']);
unset($dict['value']['publisher.utf-8']);
unset($dict['value']['publisher-url']);
unset($dict['value']['publisher-url.utf-8']);

	$fp = fopen(ROOT_PATH."torrents/$id.torrent", "w");
	if ($fp) {
	    @fwrite($fp, benc($dict), strlen(benc($dict)));
	    fclose($fp);
	    @chmod($fp, 0644);
	}
  print ("$id - <font color=\"green\">OK, announce deleted</font><br/>");
}

else {

print ("$id - NO TORRENT <br/>");
}
}

print 'Announce-url усешно очищены, теперь скрипт очистит кеш.<hr/>';
print 'Announce-url wer successfuly cleanet, now script will clear cache.<hr/>';
print '<a href="update.php?step=4">Продолжить/Continue</a>';
}

elseif ($_GET['step'] == 4) {
  dbconn();
if (!defined("CACHE_REQUIRED")) {
  	require_once ROOT_PATH.'classes/cache/cache.class.php';
	require_once ROOT_PATH.'classes/cache/fileCacheDriver.class.php';
}

  		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());

  $cache->clearAllCache();
print "Переход на Kinokpk.com releaser 2.40 успешно завершен! НЕ ЗАБЫВАЙТЕ УДАЛИТЬ ЭТОТ ФАЙЛ И update.sql С ВАШЕГО СЕРВЕРА, А ТАКЖЕ ПРОВЕРИТЬ КОНФИГУРАЦИЮ НА НОВЫЕ ВОЗМОЖНОСТИ.<hr/>";
print "You have successfully transfered to Kinokpk.com releaser 2.40! DO NOT FORGET TO DELETE THIS FILE AND update.sql FROM YOUR SERVER, ALSO DON'T FORGET TO CHECK MAIN CONFIGURATION FOR NEW FEATURES.<hr/>";
print '<a href="javascript:self.close();" >Закрыть окно/Close this window</a><hr/>';
print '<script language="javascript">alert(\'Спасибо за выбор Kinokpk.com releaser 2.40/Thank you for choosing Kinokpk.com releaser 2.40\');</script>';
}

?>