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

# IMPORTANT: Do not edit below unless you know what you are doing!
@set_time_limit(0);
@ignore_user_abort(1);
date_default_timezone_set('UTC');

define ("IN_TRACKER",true);
define ("ROOT_PATH",dirname(__FILE__).'/');
require_once(ROOT_PATH.'include/secrets.php');
require_once(ROOT_PATH.'include/classes.php');
require_once(ROOT_PATH.'include/functions.php');

// connection closed
$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
or die ('Not connected : ' . mysql_error());
mysql_select_db ($mysql_db, $db);

my_set_charset($mysql_charset);

$CHECK = mysql_result(sql_query("SELECT cron_value FROM cron WHERE cron_name='in_cleanup'"),0);
if ($CHECK) die('Cleanup is already running');

$cronrow = mysql_query("SELECT * FROM cron WHERE cron_name IN ('in_cleanup','points_per_hour','autoclean_interval','max_dead_torrent_time','pm_delete_sys_days','pm_delete_user_days','signup_timeout','cleanup_restart','ttl_days','announce_interval','delete_votes')");

while ($cronres = mysql_fetch_assoc($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];

if ($CRON['cleanup_restart']) {mysql_query("UPDATE cron SET cron_value=0 WHERE cron_name IN ('in_cleanup','cleanup_restart','num_cleaned')"); break; }

mysql_query("UPDATE cron SET cron_value=1 WHERE cron_name='in_cleanup'");

$points_per_cleanup = (float)$CRON['points_per_hour']*($CRON['autoclean_interval']/3600);
/*do {

$res = mysql_query("SELECT id, filename FROM torrents") or sqlerr(__FILE__,__LINE__);
$ar = array();
while ($row = mysql_fetch_array($res)) {
$id = $row[0];
$ar[$id] = 1;
$far[$id] = $row[1];
}

if (!count($ar))
break;

$dp = @opendir(ROOT_PATH."torrents");
if (!$dp)
break;

$ar2 = array();
while (($file = @readdir($dp)) !== false) {
if (!preg_match('/^(\d+)\.torrent$/', $file, $m))
continue;
$id = $m[1];
$ar2[$id] = 1;
if (isset($ar[$id]) && $ar[$id])
continue;
$ff = ROOT_PATH.'torrents/'.$file;
@unlink($ff);
}
@closedir($dp);

if (!count($ar2))
break;

$delids = array();
foreach (array_keys($ar) as $k) {
if (isset($ar2[$k]) && $ar2[$k])
continue;
if ($far[$k] != 'nofile')
$delids[] = $k;
unset($ar[$k]);
}
if ($delids) {
foreach ($delids as $did) deletetorrent($did);
}
} while (0);*/

$deadtime = time() - floor($CRON['announce_interval'] *60* 1.3);
//print($deadtime."/".time());
mysql_query("DELETE FROM peers WHERE last_action < $deadtime") or sqlerr(__FILE__,__LINE__);

mysql_query("UPDATE snatched SET seeder = 0 WHERE seeder = 1 AND last_action < $deadtime");

$deadtime -= $CRON['max_dead_torrent_time'];

//mysql_query("UPDATE torrents SET visible=0 WHERE visible=1 AND last_action < $deadtime AND filename <> 'nofile'") or sqlerr(__FILE__,__LINE__);

$torrents = array();
$res = mysql_query('SELECT torrent, seeder, COUNT(*) AS c FROM peers GROUP BY torrent, seeder');
while ($row = mysql_fetch_array($res)) {
	if ($row['seeder'])
	$key = 'seeders';
	else
	$key = 'leechers';
	$torrents[$row['torrent']][$key] = $row['c'];
}

$res = mysql_query('SELECT torrent, COUNT(*) AS c FROM comments GROUP BY torrent');
while ($row = mysql_fetch_array($res))
$torrents[$row['torrent']]['comments'] = $row['c'];

$fields = explode(':', 'comments:leechers:seeders');
$res = mysql_query('SELECT id, seeders, leechers, comments FROM torrents');
while ($row = mysql_fetch_array($res)) {
	$id = $row['id'];
	$torr = $torrents[$id];
	foreach ($fields as $field) {
		if (!isset($torr[$field]))
		$torr[$field] = 0;
	}
	$update = array();
	foreach ($fields as $field) {
		if ($torr[$field] != $row[$field])
		$update[] = $field . ' = ' . $torr[$field];
	}
	if (count($update))
	mysql_query("UPDATE torrents SET " . implode(",", $update) . " WHERE id = $id");
}

/*	//delete inactive user accounts
 $secs = 31*86400;
 $dt = time() - $secs;
 $maxclass = UC_POWER_USER;
 $res = mysql_query("SELECT id,avatar FROM users WHERE parked=0 AND confirmed=1 AND class <= $maxclass AND last_access < $dt AND last_access <> 0") or sqlerr(__FILE__,__LINE__);
 while ($arr = mysql_fetch_assoc($res)) {
 $avatar = $arr['avatar'];
 mysql_query("DELETE FROM users WHERE id = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM messages WHERE receiver = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM friends WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM friends WHERE friendid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM bookmarks WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM invites WHERE inviter = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM peers WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM addedrequests WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM checkcomm WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM messages WHERE sender = ".sqlesc($arr["id"])." AND saved = 1 AND location = '0'") or sqlerr(__FILE__,__LINE__);
 @unlink(ROOT_PATH.$avatar);
 }

 //delete parked user accounts
 $secs = 175*86400; // change the time to fit your needs
 $dt = time() - $secs;
 $maxclass = UC_POWER_USER;
 $res = mysql_query("SELECT id,avatar FROM users WHERE parked=1 AND confirmed=1 AND class <= $maxclass AND last_access < $dt");
 if (mysql_num_rows($res) > 0) {
 while ($arr = mysql_fetch_assoc($res)) {
 $avatar = $arr['avatar'];
 mysql_query("DELETE FROM users WHERE id = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM messages WHERE receiver = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM friends WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM friends WHERE friendid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM bookmarks WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM invites WHERE inviter = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM peers WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM addedrequests WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM checkcomm WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
 mysql_query("DELETE FROM messages WHERE sender = ".sqlesc($arr["id"])." AND saved = 1 AND location = '0'") or sqlerr(__FILE__,__LINE__);
 @unlink(ROOT_PATH.$avatar);
 }
 }
 */

//Удаляем системные прочтенные сообщения старше n дней
$secs_system = $CRON['pm_delete_sys_days']*86400; // Количество дней
$dt_system = time() - $secs_system; // Сегодня минус количество дней
mysql_query("DELETE FROM messages WHERE archived = 0 AND archived_receiver = 0 AND unread = 0 AND added < $dt_system") or sqlerr(__FILE__, __LINE__);
//Удаляем ВСЕ прочтенные сообщения старше n дней
$secs_all = $CRON['pm_delete_user_days']*86400; // Количество дней
$dt_all = time() - $secs_all; // Сегодня минус количество дней
mysql_query("DELETE FROM messages WHERE unread = 0 AND archived = 0 AND archived_receiver = 0 AND added < $dt_all") or sqlerr(__FILE__, __LINE__);


// delete unconfirmed users if timeout.
$deadtime = time() - ($CRON['signup_timeout']*86400);
$res = mysql_query("SELECT id FROM users WHERE confirmed=0 AND added < $deadtime AND last_login < $deadtime AND last_access < $deadtime") or sqlerr(__FILE__,__LINE__);
if (mysql_num_rows($res) > 0) {
	while ($arr = mysql_fetch_array($res)) {
		$ids[] = $arr['id'];

	}
	mysql_query("DELETE FROM users WHERE id IN(".sqlesc(implode(',',$ids)).")");
}
//отключение предупрежденных пользователей (у тех у кого 5 звезд)
$res = mysql_query("SELECT id, username, modcomment FROM users WHERE num_warned > 4 AND enabled = 1 ") or sqlerr(__FILE__,__LINE__);
$num = mysql_num_rows($res);
while ($arr = mysql_fetch_assoc($res)) {
	$modcom = sqlesc(date("Y-m-d") . " - Отключен системой (5 и более предупреждений) " . "\n". $arr[modcomment]);
	mysql_query("UPDATE users SET enabled = 0, dis_reason = 'Отключен системой (5 и более предупреждений)' WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
	mysql_query("UPDATE users SET modcomment = $modcom WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
	write_log("Пользователь $arr[username] был отключен системой (5 и более предупреждений)","CCCCCC","tracker");
}

// Update seed bonus
mysql_query("UPDATE users LEFT JOIN peers ON peers.userid = users.id SET users.bonus = users.bonus + $points_per_cleanup WHERE peers.seeder = 1") or sqlerr(__FILE__,__LINE__);

//remove expired warnings
$now = time();
$modcomment = sqlesc(date("Y-m-d") . " - Предупреждение снято системой по таймауту.\n");
$msg = sqlesc("Ваше предупреждение снято по таймауту. Постарайтесь больше не получать предупреждений и следовать правилам.\n");
mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster) SELECT 0, id, $now, $msg, 0 FROM users WHERE warned=1 AND warneduntil < ".time()." AND warneduntil <> 0") or sqlerr(__FILE__,__LINE__);
mysql_query("UPDATE users SET warned=0, warneduntil = 0, modcomment = CONCAT($modcomment, modcomment) WHERE warned=1 AND warneduntil < ".time()." AND warneduntil <> 0") or sqlerr(__FILE__,__LINE__);

// promote power users
$limit = 25*1024*1024*1024;
$minratio = 1.05;
$maxdt = time() - 86400*28;
$now = time();
$msg = sqlesc("Наши поздравления, вы были авто-повышены до ранга <b>Опытный пользовать</b>.");
$subject = sqlesc("Вы были повышены");
$modcomment = sqlesc(date("Y-m-d") . " - Повышен до уровня \"".$tracker_lang["class_power_user"]."\" системой.\n");
mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, id, $now, $msg, 0, $subject FROM users WHERE class = 0 AND uploaded >= $limit AND uploaded / downloaded >= $minratio AND added < $maxdt") or sqlerr(__FILE__,__LINE__);
mysql_query("UPDATE users SET class = ".UC_POWER_USER.", modcomment = CONCAT($modcomment, modcomment) WHERE class = ".UC_USER." AND uploaded >= $limit AND uploaded / downloaded >= $minratio AND added < $maxdt") or sqlerr(__FILE__,__LINE__);

// demote power users
$minratio = 0.95;
$now = sqlesc(time());
$msg = sqlesc("Вы были авто-понижены с ранга <b>Опытный пользователь</b> до ранга <b>Пользователь</b> потому-что ваш рейтинг упал ниже <b>{$minratio}</b>.");
$subject = sqlesc("Вы были понижены");
$modcomment = sqlesc(date("Y-m-d") . " - Понижен до уровня \"".$tracker_lang["class_user"]."\" системой.\n");
mysql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, id, $now, $msg, 0, $subject FROM users WHERE class = 1 AND uploaded / downloaded < $minratio") or sqlerr(__FILE__,__LINE__);
mysql_query("UPDATE users SET class = ".UC_USER.", modcomment = CONCAT($modcomment, modcomment) WHERE class = ".UC_POWER_USER." AND uploaded / downloaded < $minratio") or sqlerr(__FILE__,__LINE__);

// delete old torrents
if ($CRON['use_ttl']) {
	$dt = time() - ($CRON['ttl_days'] * 86400);
	$res = mysql_query("SELECT id, name FROM torrents WHERE last_action < $dt") or sqlerr(__FILE__,__LINE__);
	while ($arr = mysql_fetch_assoc($res))
	{
		deletetorrent($arr['id']);
		write_log("Торрент $arr[id] ($arr[name]) был удален системой (старше чем {$CRON['ttl_days']} дней)","","torrent");
	}
}

$secs = 1 * 3600;
$dt = time() - $secs;
mysql_query("DELETE FROM sessions WHERE time < $dt") or sqlerr(__FILE__,__LINE__);

if ($CRON['delete_votes']) {
	$secs = $CRON['delete_votes']*60;
	$dt = time() - $secs;
	mysql_query("DELETE FROM ratings WHERE added < $dt");
}
$CACHEARRAY['defaultbaseurl'] = mysql_result(mysql_query("SELECT cache_value FROM cache_stats WHERE cache_name='defaultbaseurl'"),0);

require_once(ROOT_PATH . "include/createsitemap.php");

mysql_query("UPDATE cron SET cron_value=".time()." WHERE cron_name='last_cleanup'");
mysql_query("UPDATE cron SET cron_value=cron_value+1 WHERE cron_name='num_cleaned'");
mysql_query("UPDATE cron SET cron_value=0 WHERE cron_name='in_cleanup'");
?>