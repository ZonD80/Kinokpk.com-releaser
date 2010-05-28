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

if(!defined("IN_TRACKER")) die("Direct access to this page not allowed");

# IMPORTANT: Do not edit below unless you know what you are doing!


function docleanup() {
	global $CACHEARRAY, $tracker_lang;

	@set_time_limit(0);
	@ignore_user_abort(1);
	
	do {
    $points_per_cleanup = $CACHEARRAY['points_per_hour']*($CACHEARRAY['autoclean_interval']/3600);
    
		$res = sql_query("SELECT id, filename FROM torrents") or sqlerr(__FILE__,__LINE__);
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
		if (count($delids)){
			sql_query("DELETE FROM torrents WHERE id IN (" . join(",", $delids) . ")") or sqlerr(__FILE__,__LINE__);
      sql_query("DELETE FROM descr_torrents WHERE torrent IN (" . join(",", $delids) . ")") or sqlerr(__FILE__,__LINE__);

      }
		$res = sql_query("SELECT torrent FROM peers GROUP BY torrent") or sqlerr(__FILE__,__LINE__);
		$delids = array();
		while ($row = mysql_fetch_array($res)) {
			$id = $row[0];
			if (isset($ar[$id]) && $ar[$id])
				continue;
			$delids[] = $id;
		}
		if (count($delids))
			sql_query("DELETE FROM peers WHERE torrent IN (" . join(",", $delids) . ")") or sqlerr(__FILE__,__LINE__);

		$res = sql_query("SELECT torrent FROM files GROUP BY torrent") or sqlerr(__FILE__,__LINE__);
		$delids = array();
		while ($row = mysql_fetch_array($res)) {
			$id = $row[0];
			if ($ar[$id])
				continue;
			$delids[] = $id;
		}
		if (count($delids))
			sql_query("DELETE FROM files WHERE torrent IN (" . join(", ", $delids) . ")") or sqlerr(__FILE__,__LINE__);
	} while (0);

	$deadtime = time() - floor($CACHEARRAY['announce_interval'] *60* 1.3);
	//print($deadtime."/".time());
	sql_query("DELETE FROM peers WHERE last_action < FROM_UNIXTIME($deadtime)") or sqlerr(__FILE__,__LINE__);

	sql_query("UPDATE snatched SET seeder = 'no' WHERE seeder = 'yes' AND last_action < FROM_UNIXTIME($deadtime)");

	$deadtime -= $CACHEARRAY['max_dead_torrent_time'];

  //sql_query("UPDATE torrents SET visible='no' WHERE visible='yes' AND last_action < FROM_UNIXTIME($deadtime) AND filename <> 'nofile'") or sqlerr(__FILE__,__LINE__);

	$torrents = array();
	$res = sql_query('SELECT torrent, seeder, COUNT(*) AS c FROM peers GROUP BY torrent, seeder');
	while ($row = mysql_fetch_array($res)) {
		if ($row['seeder'] == 'yes')
			$key = 'seeders';
		else
			$key = 'leechers';
		$torrents[$row['torrent']][$key] = $row['c'];
	}

	$res = sql_query('SELECT torrent, COUNT(*) AS c FROM comments GROUP BY torrent');
	while ($row = mysql_fetch_array($res)) 
		$torrents[$row['torrent']]['comments'] = $row['c'];

	$fields = explode(':', 'comments:leechers:seeders');
	$res = sql_query('SELECT id, seeders, leechers, comments FROM torrents');
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
			sql_query("UPDATE torrents SET " . implode(",", $update) . " WHERE id = $id");
	}

	/*	//delete inactive user accounts
		$secs = 31*86400;
		$dt = sqlesc(get_date_time(gmtime() - $secs));
		$maxclass = UC_POWER_USER;
		$res = sql_query("SELECT id FROM users WHERE parked='no' AND status='confirmed' AND class <= $maxclass AND last_access < $dt AND last_access <> '0000-00-00 00:00:00'") or sqlerr(__FILE__,__LINE__);
		while ($arr = mysql_fetch_assoc($res)) {
			sql_query("DELETE FROM users WHERE id = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM messages WHERE receiver = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM friends WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM friends WHERE friendid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM blocks WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM blocks WHERE blockid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM bookmarks WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM invites WHERE inviter = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM peers WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM simpaty WHERE fromuserid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM addedrequests WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM checkcomm WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
   sql_query("DELETE FROM messages WHERE sender = ".sqlesc($arr["id"])." AND saved = 'yes' AND location = '0'") or sqlerr(__FILE__,__LINE__);
			  @unlink(ROOT_PATH.$avatar);
		}

       //delete parked user accounts
       $secs = 175*86400; // change the time to fit your needs
       $dt = sqlesc(get_date_time(gmtime() - $secs));
       $maxclass = UC_POWER_USER;
       $res = sql_query("SELECT id FROM users WHERE parked='yes' AND status='confirmed' AND class <= $maxclass AND last_access < $dt");
       if (mysql_num_rows($res) > 0) {
       	while ($arr = mysql_fetch_array($res)) {
			sql_query("DELETE FROM users WHERE id = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM messages WHERE receiver = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM friends WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM friends WHERE friendid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM blocks WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM blocks WHERE blockid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM bookmarks WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM invites WHERE inviter = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM peers WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM simpaty WHERE fromuserid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM addedrequests WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM checkcomm WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM messages WHERE sender = ".sqlesc($arr["id"])." AND saved = 'yes' AND location = '0'") or sqlerr(__FILE__,__LINE__);
		}
	}
       */
       
//Удаляем системные прочтенные сообщения старше n дней
$secs_system = $CACHEARRAY['pm_delete_sys_days']*86400; // Количество дней
$dt_system = sqlesc(get_date_time(gmtime() - $secs_system)); // Сегодня минус количество дней
sql_query("DELETE FROM messages WHERE archived = 'no' AND unread = 'no' AND added < $dt_system") or sqlerr(__FILE__, __LINE__);
//Удаляем ВСЕ прочтенные сообщения старше n дней
$secs_all = $CACHEARRAY['pm_delete_user_days']*86400; // Количество дней
$dt_all = sqlesc(get_date_time(gmtime() - $secs_all)); // Сегодня минус количество дней
sql_query("DELETE FROM messages WHERE unread = 'no' AND archived = 'no' AND added < $dt_all") or sqlerr(__FILE__, __LINE__);


	// delete unconfirmed users if timeout.
	$deadtime = TIMENOW - ($CACHEARRAY['signup_timeout']*86400);
	$res = sql_query("SELECT id FROM users WHERE status = 'pending' AND added < FROM_UNIXTIME($deadtime) AND last_login < FROM_UNIXTIME($deadtime) AND last_access < FROM_UNIXTIME($deadtime)") or sqlerr(__FILE__,__LINE__);
	if (mysql_num_rows($res) > 0) {
		while ($arr = mysql_fetch_array($res)) {
			sql_query("DELETE FROM users WHERE id = ".sqlesc($arr["id"]));

		}
	}
//отключение предупрежденных пользователей (у тех у кого 5 звезд)
        $res = sql_query("SELECT id, username, modcomment FROM users WHERE num_warned > 4 AND enabled = 'yes' ") or sqlerr(__FILE__,__LINE__);
        $num = mysql_num_rows($res);
        while ($arr = mysql_fetch_assoc($res)) {
         $modcom = sqlesc(date("Y-m-d") . " - Отключен системой (5 и более предупреждений) " . "\n". $arr[modcomment]);
        sql_query("UPDATE users SET enabled = 'no', dis_reason = 'Отключен системой (5 и более предупреждений)' WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
        sql_query("UPDATE users SET modcomment = $modcom WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
        write_log("Пользователь $arr[username] был отключен системой (5 и более предупреждений)","CCCCCC","tracker");
        }
        
	// Update seed bonus
	sql_query("UPDATE users SET bonus = bonus + $points_per_cleanup WHERE users.id IN (SELECT userid FROM peers WHERE seeder = 'yes')") or sqlerr(__FILE__,__LINE__);

	//remove expired warnings
	$now = sqlesc(get_date_time());
	$modcomment = sqlesc(date("Y-m-d") . " - Предупреждение снято системой по таймауту.\n");
	$msg = sqlesc("Ваше предупреждение снято по таймауту. Постарайтесь больше не получать предупреждений и следовать правилам.\n");
	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster) SELECT 0, id, $now, $msg, 0 FROM users WHERE warned='yes' AND warneduntil < NOW() AND warneduntil <> '0000-00-00 00:00:00'") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE users SET warned='no', warneduntil = '0000-00-00 00:00:00', modcomment = CONCAT($modcomment, modcomment) WHERE warned='yes' AND warneduntil < NOW() AND warneduntil <> '0000-00-00 00:00:00'") or sqlerr(__FILE__,__LINE__);

	// promote power users
	$limit = 25*1024*1024*1024;
	$minratio = 1.05;
	$maxdt = sqlesc(get_date_time(gmtime() - 86400*28));
	$now = sqlesc(get_date_time());
	$msg = sqlesc("Наши поздравления, вы были авто-повышены до ранга [b]Опытный пользовать[/b].");
	$subject = sqlesc("Вы были повышены");
	$modcomment = sqlesc(date("Y-m-d") . " - Повышен до уровня \"".$tracker_lang["class_power_user"]."\" системой.\n");
	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, id, $now, $msg, 0, $subject FROM users WHERE class = 0 AND uploaded >= $limit AND uploaded / downloaded >= $minratio AND added < $maxdt") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE users SET class = ".UC_POWER_USER.", modcomment = CONCAT($modcomment, modcomment) WHERE class = ".UC_USER." AND uploaded >= $limit AND uploaded / downloaded >= $minratio AND added < $maxdt") or sqlerr(__FILE__,__LINE__);

	// demote power users
	$minratio = 0.95;
	$now = sqlesc(get_date_time());
	$msg = sqlesc("Вы были авто-понижены с ранга [b]Опытный пользователь[/b] до ранга [b]Пользователь[/b] потому-что ваш рейтинг упал ниже [b]{$minratio}[/b].");
	$subject = sqlesc("Вы были понижены");
	$modcomment = sqlesc(date("Y-m-d") . " - Понижен до уровня \"".$tracker_lang["class_user"]."\" системой.\n");
	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, id, $now, $msg, 0, $subject FROM users WHERE class = 1 AND uploaded / downloaded < $minratio") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE users SET class = ".UC_USER.", modcomment = CONCAT($modcomment, modcomment) WHERE class = ".UC_POWER_USER." AND uploaded / downloaded < $minratio") or sqlerr(__FILE__,__LINE__);

	// delete old torrents
	if ($CACHEARRAY['use_ttl']) {
		$dt = sqlesc(get_date_time(gmtime() - ($CACHEARRAY['ttl_days'] * 86400)));
		$res = sql_query("SELECT id, name FROM torrents WHERE added < $dt") or sqlerr(__FILE__,__LINE__);
	while ($arr = mysql_fetch_array($res))
	{
		@unlink(ROOT_PATH.'torrents/'.$arr['id'].'.torrent');
			sql_query("DELETE FROM torrents WHERE id=$arr[id]") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM snatched WHERE torrent=$arr[id]") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM peers WHERE torrent=$arr[id]") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM comments WHERE torrent=$arr[id]") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM files WHERE torrent=$arr[id]") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM ratings WHERE torrent=$arr[id]") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM checkcomm WHERE checkid=$arr[id] AND torrent = 1") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM bookmarks WHERE id=$arr[id]") or sqlerr(__FILE__,__LINE__);
			write_log("Торрент $arr[id] ($arr[name]) был удален системой (старше чем {$CACHEARRAY['ttl_days']} дней)","","torrent");
		}
	}

	$secs = 1 * 3600;
	$dt = time() - $secs;
	sql_query("DELETE FROM sessions WHERE time < $dt") or sqlerr(__FILE__,__LINE__);

// update tags
sql_query('UPDATE tags AS t SET t.howmuch = (SELECT COUNT(*) FROM torrents AS ts WHERE ts.tags LIKE CONCAT(\'%\', t.name, \'%\') AND ts.category = t.category)');
//sql_query('DELETE FROM tags WHERE howmuch = 0;');

}
//GENERATE SITEMAP
require_once(ROOT_PATH . "include/createsitemap.php");
?>