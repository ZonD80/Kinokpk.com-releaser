<?php
/**
 * CRONJOB cleanup script
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
header("Content-Type: image/gif");

@set_time_limit(0);
@ignore_user_abort(1);
date_default_timezone_set('UTC');

define ("IN_TRACKER",true);
define ("ROOT_PATH",dirname(__FILE__).'/');
require_once(ROOT_PATH.'include/secrets.php');
require_once(ROOT_PATH.'include/classes.php');
require_once(ROOT_PATH.'include/functions.php');
$time = time();

// connection closed
	/* @var database object */
	require_once(ROOT_PATH . 'classes/database/database.class.php');
	$REL_DB = new REL_DB($mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_charset);


$cronrow = sql_query("SELECT * FROM cron WHERE cron_name IN ('in_cleanup','autoclean_interval','max_dead_torrent_time','pm_delete_sys_days','pm_delete_user_days','signup_timeout','ttl_days','announce_interval','delete_votes','rating_freetime','rating_enabled','rating_perleech','rating_perseed','rating_checktime','rating_dislimit','promote_rating','rating_max')");

while ($cronres = mysql_fetch_assoc($cronrow)) $REL_CRON[$cronres['cron_name']] = $cronres['cron_value'];

if ($REL_CRON['in_cleanup']) die('Cleanup already running');
sql_query("UPDATE cron SET cron_value=".time()." WHERE cron_name='last_cleanup'");

sql_query("UPDATE cron SET cron_value=1 WHERE cron_name='in_cleanup'");

// Recount torrents now in cronadmin.php

$deadtime = $time - $REL_CRON['announce_interval']*120;
//print($deadtime."/".time());
sql_query("DELETE FROM peers WHERE last_action < $deadtime") or sqlerr(__FILE__,__LINE__);

$deadtime -= $REL_CRON['max_dead_torrent_time'];

//sql_query("UPDATE torrents SET visible=0 WHERE visible=1 AND last_action < $deadtime AND filename <> 'nofile'") or sqlerr(__FILE__,__LINE__);

$torrents = array();
$res = sql_query('SELECT torrent, seeder, SUM(1) AS c FROM peers GROUP BY torrent, seeder');
while ($row = mysql_fetch_array($res)) {
	if ($row['seeder'])
	$key = 'seeders';
	else
	$key = 'leechers';
	$torrents[$row['torrent']][$key] = $row['c'];
}

$peerssql = sql_query("SELECT torrent FROM trackers WHERE tracker='localhost'") or sqlerr(__FILE__,__LINE__);
while (list($id) = mysql_fetch_array($peerssql))
sql_query("UPDATE trackers SET seeders = ".(int)$torrents[$id]['seeders'].", leechers = ".(int)$torrents[$id]['leechers'].", lastchecked = $time WHERE torrent = $id AND tracker='localhost'") or sqlerr(__FILE__,__LINE__);
/*	//delete inactive user accounts
 $secs = 31*86400;
 $dt = time() - $secs;
 $maxclass = UC_POWER_USER;
 $res = sql_query("SELECT id,avatar FROM users WHERE confirmed=1 AND class <= $maxclass AND last_access < $dt AND last_access <> 0") or sqlerr(__FILE__,__LINE__);
 while ($arr = mysql_fetch_assoc($res)) {
 $avatar = $arr['avatar'];
 delete_user($arr['id']);
 @unlink(ROOT_PATH.$avatar);
 }

 */

//Удаляем системные прочтенные сообщения старше n дней
$secs_system = $REL_CRON['pm_delete_sys_days']*86400; // Количество дней
$dt_system = time() - $secs_system; // Сегодня минус количество дней
sql_query("DELETE FROM messages WHERE archived = 0 AND archived_receiver = 0 AND unread = 0 AND added < $dt_system") or sqlerr(__FILE__, __LINE__);
//Удаляем ВСЕ прочтенные сообщения старше n дней
$secs_all = $REL_CRON['pm_delete_user_days']*86400; // Количество дней
$dt_all = time() - $secs_all; // Сегодня минус количество дней
sql_query("DELETE FROM messages WHERE unread = 0 AND archived = 0 AND archived_receiver = 0 AND added < $dt_all") or sqlerr(__FILE__, __LINE__);


// delete unconfirmed users if timeout.
$deadtime = time() - ($REL_CRON['signup_timeout']*86400);
$res = sql_query("SELECT id FROM users WHERE confirmed=0 AND added < $deadtime AND last_login < $deadtime AND last_access < $deadtime") or sqlerr(__FILE__,__LINE__);
if (mysql_num_rows($res) > 0) {
	while ($arr = mysql_fetch_array($res)) {
		delete_user($arr['id']);


	}
}
//отключение предупрежденных пользователей (у тех у кого 5 звезд)
$res = sql_query("SELECT id, username, modcomment FROM users WHERE num_warned > 4 AND enabled = 1 ") or sqlerr(__FILE__,__LINE__);
$num = mysql_num_rows($res);
while ($arr = mysql_fetch_assoc($res)) {
	$modcom = sqlesc(date("Y-m-d") . " - Отключен системой (5 и более предупреждений) " . "\n". $arr[modcomment]);
	sql_query("UPDATE users SET enabled = 0, dis_reason = 'Отключен системой (5 и более предупреждений)' WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
	sql_query("UPDATE users SET modcomment = $modcom WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
	write_log("Пользователь $arr[username] был отключен системой (5 и более предупреждений)","tracker");
}

// Update user ratings
if ($REL_CRON['rating_enabled']) {
	$useridssql = sql_query("SELECT peers.userid AS id, users.discount FROM peers LEFT JOIN users ON peers.userid=users.id WHERE (".time()."-added)>".($REL_CRON['rating_freetime']*86400)." AND users.class<> ".UC_VIP." AND enabled=1 AND (".time()."-last_checked)>".($REL_CRON['rating_checktime']*60));
	while ($urow = mysql_fetch_assoc($useridssql)) {
		$uidsar[] = $urow['id'];
		$urating[$urow['id']]=array('discount'=>$urow['discount'],'seeding'=>0,'downloaded'=>0);
	}
	if ($uidsar) {
		$uidsar=@implode(',',$uidsar);
		$seederssql = sql_query("SELECT SUM(1) AS seeding, userid AS id FROM peers WHERE seeder=1 AND userid IN (".$uidsar.") GROUP BY userid");
		while ($srow = mysql_fetch_assoc($seederssql)) {
			$urating[$srow['id']]['seeding']=$srow['seeding'];
		}
		$downsql = sql_query("SELECT SUM(1) AS downloaded, userid AS id FROM snatched LEFT JOIN torrents ON snatched.torrent=torrents.id WHERE snatched.finished=1 AND torrents.free=0 AND NOT FIND_IN_SET(torrents.freefor,userid) AND userid IN (".$uidsar.") AND torrents.owner<>snatched.userid GROUP BY userid");
		while ($drow = mysql_fetch_assoc($downsql)) {
			$urating[$drow['id']]['downloaded']=$drow['downloaded'];
			//if ($drow['downloaded']) print '<h1>Yahooo! '.$drow['id'].'</h1>';
		}
		// var_dump(($urating));
		//print "<hr>";
		foreach ($urating AS $uid=>$value) {
			//print($value['discount'].'<br>');
			if (!$value['downloaded'] && !($value['seeding']+$value['discount'])) continue;
			elseif ($value['downloaded']>($value['seeding']+$value['discount'])) $rateup = -$REL_CRON['rating_perleech'];
			else {
				$upcount = @round(($value['seeding']+$value['discount'])/$value['downloaded']);
				if (!$upcount) $upcount=1;
				$rateup = $REL_CRON['rating_perseed']*$upcount;
			}
			sql_query("UPDATE LOW_PRIORITY users SET ratingsum = CASE WHEN ((ratingsum+$rateup>{$REL_CRON['rating_max']}) AND $rateup>0 AND ratingsum<{$REL_CRON['rating_max']}) THEN {$REL_CRON['rating_max']} WHEN ($rateup>0 AND ratingsum>{$REL_CRON['rating_max']}) THEN ratingsum ELSE ratingsum+$rateup END, last_checked=".time()." WHERE id=$uid");
		}
	}
	sql_query("UPDATE users SET enabled=0, dis_reason='Your rating was too low.' WHERE enabled=1 AND ratingsum<".$REL_CRON['rating_dislimit']);
	sql_query("UPDATE users SET enabled=1, dis_reason='' WHERE enabled=0 AND dis_reason='Your rating was too low.' AND ratingsum>=".$REL_CRON['rating_dislimit']);

}
$REL_CONFIGrow = sql_query("SELECT * FROM cache_stats WHERE cache_name IN ('sitename','defaultbaseurl','siteemail','default_language','smtptype')");

while ($REL_CONFIGres = mysql_fetch_assoc($REL_CONFIGrow)) $REL_CONFIG[$REL_CONFIGres['cache_name']] = $REL_CONFIGres['cache_value'];
$REL_CONFIG['lang'] = $REL_CONFIG['default_language'];
/* @var object links parser/adder/changer for seo */
require_once(ROOT_PATH . 'classes/seo/seo.class.php');
$REL_SEO = new REL_SEO();

require_once(ROOT_PATH . 'classes/cache/cache.class.php');
require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
/* @var object general cache object */
$REL_CACHE=new Cache();
$REL_CACHE->addDriver(NULL, new FileCacheDriver());
/* @var object language system */
require_once(ROOT_PATH . 'classes/lang/lang.class.php');
$REL_LANG = new REL_LANG($REL_CONFIG);
//remove expired warnings
$now = time();
$modcomment = sqlesc(date("Y-m-d") . " - Предупреждение снято системой по таймауту.\n");
$msg = sqlesc("Ваше предупреждение снято по таймауту. Постарайтесь больше не получать предупреждений и следовать правилам.\n");
sql_query("INSERT INTO messages (sender, receiver, added, msg, poster) SELECT 0, id, $now, $msg, 0 FROM users WHERE warned=1 AND warneduntil < ".time()." AND warneduntil <> 0") or sqlerr(__FILE__,__LINE__);
sql_query("UPDATE users SET warned=0, warneduntil = 0, modcomment = CONCAT($modcomment, modcomment) WHERE warned=1 AND warneduntil < ".time()." AND warneduntil <> 0") or sqlerr(__FILE__,__LINE__);

// promote power users
if ($REL_CRON['rating_enabled']) {
	$msg = sqlesc("Наши поздравления, вы были авто-повышены до ранга <b>Опытный пользовать</b>.");
	$subject = sqlesc("Вы были повышены");
	$modcomment = sqlesc(date("Y-m-d") . " - Повышен до уровня \"".$REL_LANG->say_by_key("class_power_user")."\" системой.\n");
	sql_query("UPDATE users SET class = ".UC_POWER_USER.", modcomment = CONCAT($modcomment, modcomment) WHERE class = ".UC_USER." AND ratingsum>={$REL_CRON['promote_rating']}") or sqlerr(__FILE__,__LINE__);
	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, id, $now, $msg, 0, $subject FROM users WHERE class = ".UC_USER." AND ratingsum>={$REL_CRON['promote_rating']}") or sqlerr(__FILE__,__LINE__);

	// demote power users
	$msg = sqlesc("Вы были авто-понижены с ранга <b>Опытный пользователь</b> до ранга <b>Пользователь</b> потому-что ваш рейтинг упал ниже <b>+{$REL_CRON['promote_rating']}</b>.");
	$subject = sqlesc("Вы были понижены");
	$modcomment = sqlesc(date("Y-m-d") . " - Понижен до уровня \"".$REL_LANG->say_by_key("class_user")."\" системой.\n");
	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, id, $now, $msg, 0, $subject FROM users WHERE class = 1 AND ratingsum<{$REL_CRON['promote_rating']}") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE users SET class = ".UC_USER.", modcomment = CONCAT($modcomment, modcomment) WHERE class = ".UC_POWER_USER." AND ratingsum<{$REL_CRON['promote_rating']}") or sqlerr(__FILE__,__LINE__);
}
// delete old torrents
if ($REL_CRON['use_ttl']) {
	$dt = time() - ($REL_CRON['ttl_days'] * 86400);
	$res = sql_query("SELECT id, name FROM torrents WHERE last_action < $dt") or sqlerr(__FILE__,__LINE__);
	while ($arr = mysql_fetch_assoc($res))
	{
		deletetorrent($arr['id']);
		write_log("Торрент $arr[id] ($arr[name]) был удален системой (старше чем {$REL_CRON['ttl_days']} дней)","torrent");
	}
}

// session update moved to include/functions.php
if ($REL_CRON['delete_votes']) {
	$secs = $REL_CRON['delete_votes']*60;
	$dt = time() - $secs;
	sql_query("DELETE FROM ratings WHERE added < $dt");
}
//$REL_CONFIG['defaultbaseurl'] = mysql_result(sql_query("SELECT cache_value FROM cache_stats WHERE cache_name='defaultbaseurl'"),0);

require_once(ROOT_PATH . "include/createsitemap.php");

// sending emails

$emails = sql_query("SELECT * FROM cron_emails");

while ($message = mysql_fetch_assoc($emails)) {
	sent_mail($message['email'], $message['subject'].' | '.$REL_CONFIG['sitename'], $REL_CONFIG['siteemail'], $message['subject'], $message['body']) or write_log("Sending mail to {$message['email']} <font color=\"red\">FAILED</font>",'emailnotifs_cron');
}

sql_query("TRUNCATE TABLE cron_emails");
// delete expiried relgroups subsribes
sql_query("DELETE FROM rg_subscribes WHERE valid_until<$time AND valid_until<>0");

sql_query("UPDATE cron SET cron_value=cron_value+1 WHERE cron_name='num_cleaned'");
sql_query("UPDATE cron SET cron_value=0 WHERE cron_name='in_cleanup'");
//$REL_CACHE->clearCache('system','cat_tags');
print base64_decode("R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==");

?>