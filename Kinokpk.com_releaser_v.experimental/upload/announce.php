<?php
/**
 * Annouce of tracker
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

define ('IN_ANNOUNCE', true);
define("ROOT_PATH", dirname(__FILE__) . '/');
@error_reporting(E_ALL & ~E_NOTICE);
@ini_set('error_reporting', E_ALL & ~E_NOTICE);
@ini_set('display_errors', '1');
@ini_set('display_startup_errors', '1');
@ini_set('ignore_repeated_errors', '0');
@ignore_user_abort(1);
@set_time_limit(0);
date_default_timezone_set('UTC');
require_once(ROOT_PATH . 'include/benc.php');

require_once(ROOT_PATH . 'include/functions_announce.php');

gzip();

foreach (array('passkey', 'info_hash', 'peer_id', 'event', 'port', 'left') as $x) {
    if (isset($_GET[$x]))
        $GLOBALS[$x] = (string)$_GET[$x];
}

$port = intval($port);
$left = intval($left);

foreach (array('passkey', 'info_hash', 'peer_id', 'port') as $x)
    if (!isset($GLOBALS[$x])) err('Missing key: ' . $x);
foreach (array('info_hash', 'peer_id') as $x)
    if (strlen($GLOBALS[$x]) != 20) //20
        err('Invalid ' . $x . ' (' . strlen($GLOBALS[$x]) . ' - ' . urlencode($GLOBALS[$x]) . ')');
if (strlen($passkey) != 32)
    err('Invalid passkey (' . strlen($passkey) . " - $passkey)");
$ip = getip();
//$announce_wait = 30;
foreach (array('num want', 'numwant', 'num_want') as $k) {
    $rsize = (int)$_GET[$k];
    if ($rsize) break;
}

if ($rsize > 50) $rsize = 50;
elseif ($rsize <= 0) $rsize = 50;

if (!$port || $port > 0xffff || portblacklisted($port))
    err("Invalid or blacklisted port");
if (!isset($event))
    $event = '';
$seeder = ($left == 0) ? 1 : 0;

checkclient($peer_id);

define('TIME', time());

INIT();

//$ucls = $REL_DB->query("SELECT id,enabled,passkey_ip FROM users WHERE passkey = " . $REL_DB->sqlesc($passkey)) or sqlerr(__FILE__,__LINE__);

$ucls = $REL_DB->query_row("SELECT id,enabled FROM users LEFT JOIN xbt_users ON users.id = xbt_users.uid WHERE AND torrent_pass = " . $REL_DB->sqlesc($passkey));
if (!$ucls)
    err('Invalid passkey! Re-download the .torrent from ' . $REL_CONFIG['defaultbaseurl']);

if (!$ucls['enabled']) err('Your account disabled');
$userid = $ucls['id'];
$info_hash = $REL_DB->sqlesc($info_hash);
$torrent = $REL_DB->query_row('SELECT torrents.id, banned FROM torrents WHERE info_hash=' . $info_hash);

if (!$torrent) err('Release not registered with tracker');
elseif ($torrent['banned']) err("This release was banned");

//$fields = 'seeder, ip, port, userid, last_action';
$fields = 'ipa AS ip, port, xbt_announce_log.mtime AS last_action';
//$limit = '';
//$selfwhere = "torrent = $torrentid AND userid=$userid";
$selfwhere = "fid = {$torrent['id']} AND uid=$userid";
$selfwhere_ann = "info_hash=$info_hash AND uid=$userid";

$announce_interval = @mysql_result($REL_DB->query("SELECT value FROM xbt_config WHERE name='announce_interval'"), 0);


$limit = ' ORDER BY RAND() LIMIT ' . $rsize;

//$res = $REL_DB->query('SELECT '.$fields.' FROM peers WHERE torrent = '.$torrentid.' AND userid<>'.$userid.$limit)  or sqlerr(__FILE__,__LINE__);
$res = $REL_DB->query("SELECT ipa AS ip, port, xbt_announce_log.mtime AS last_action FROM xbt_announce_log  INNER JOIN xbt_files_users ON xbt_announce_log.uid=xbt_files_users.uid WHERE fid={$torrent['id']} AND xbt_files_users.uid<>" . $userid . $limit) or sqlerr(__FILE__, __LINE__);
$resp = 'd' . benc_str('interval') . 'i' . $announce_interval . 'e' . benc_str('peers');
$no_peer_id = ((int)$_GET['no_peer_id'] == 1);

while ($row = mysql_fetch_array($res)) {
    $peer_ip = explode('.', $row["ip"]);
    $plist .= pack("C*", $peer_ip[0], $peer_ip[1], $peer_ip[2], $peer_ip[3]) . pack("n*", (int)$row["port"]);
}

$updateset = array();

//$selfsql = $REL_DB->query('SELECT seeder FROM peers WHERE '.$selfwhere) or sqlerr(__FILE__,__LINE__);
$self = $REL_DB->query_row("SELECT active,`left` FROM xbt_files_users WHERE " . $selfwhere);

if ($event == 'stopped') {
    //$REL_DB->query('DELETE FROM peers WHERE '.$selfwhere) or sqlerr(__FILE__,__LINE__);
    $REL_DB->query("UPDATE LOW_PRIORITY xbt_files_announce active=0 WHERE " . $selfwhere) or sqlerr(__FILE__, __LINE__);
    if (mysql_affected_rows()) {
        if ($self['active'] && $self['left'] == 0)
            $trupdateset[] = 'seeders = IF(seeders > 0, seeders - 1, 0)';
        else
            $trupdateset[] = 'leechers = IF(leechers > 0, leechers - 1, 0)';
    }
}

if ($self['active'] != $seeder) {
    if ($seeder) {
        $trupdateset[] = 'seeders = seeders + 1';
        $trupdateset[] = 'leechers = IF(leechers > 0, leechers - 1, 0)';
    } else {
        $trupdateset[] = 'leechers = leechers + 1';
        $trupdateset[] = 'seeders = IF(seeders > 0, seeders - 1, 0)';
    }
}


//
//INSERT or UPDATE peers/seeds data
//
//$ret = $REL_DB->query("INSERT LOW_PRIORITY INTO peers (torrent, peer_id, ip, port, seeder, userid, last_action) VALUES ($torrentid, " . $REL_DB->sqlesc($peer_id) . ", " . $REL_DB->sqlesc($ip) . ", $port, $seeder, $userid,$time)");// or sqlerr(__FILE__,__LINE__);
$ret = $REL_DB->query("INSERT LOW_PRIORITY INTO xbt_announce_log (peer_id, ipa, port, info_hash,`left0`, uid, mtime) VALUES (" . $REL_DB->sqlesc($peer_id) . "," . ip2long($ip) . ", " . $REL_DB->sqlesc($port) . "," . $REL_DB->sqlesc($info_hash) . "," . $REL_DB->sqlesc($left) . ", " . $REL_DB->sqlesc($userid) . "," . $REL_DB->sqlesc($time) . ")"); // or sqlerr(__FILE__,__LINE__);
$ret2 = $REL_DB->query("INSERT LOW_PRIORITY INTO xbt_files_users (fid, uid, active, left, mtime) VALUES (" . $REL_DB->sqlesc($torrentid) . "," . $REL_DB->sqlesc($userid) . ",'1'," . $REL_DB->sqlesc($left) . "," . $REL_DB->sqlesc($time) . ")");
if ($ret2) {
    if ($seeder)
        $trupdateset[] = 'seeders = seeders + 1';
    else
        $trupdateset[] = 'leechers = leechers + 1';
} else {
    //$REL_DB->query("UPDATE LOW_PRIORITY peers SET port = $port, ip = '$ip', seeder = $seeder, last_action=$time WHERE $selfwhere") or sqlerr(__FILE__,__LINE__);
    $REL_DB->query("UPDATE LOW_PRIORITY xbt_announce_log SET peer_id=" . $REL_DB->sqlesc($peer_id) . ", ipa = " . ip2long($ip) . ", port = " . $REL_DB->sqlesc($port) . ", `left0`=" . $REL_DB->sqlesc($left) . ", mtime=" . $REL_DB->sqlesc($time) . " WHERE $selfwhere_ann") or sqlerr(__FILE__, __LINE__);
    $REL_DB->query("UPDATE LOW_PRIORITY xbt_files_users SET fid=" . $REL_DB->sqlesc($torrentid) . ", uid=" . $REL_DB->sqlesc($userid) . ", active='1', `left`=" . $REL_DB->sqlesc($left) . ", mtime=$time WHERE $selfwhere") or sqlerr(__FILE__, __LINE__);
}

if ($seeder) {
    $updateset[] = 'visible = 1';
    $updateset[] = 'last_action = ' . $time;
}
if ($trupdateset) {
    $trupdateset[] = 'lastchecked = ' . $time;
    $REL_DB->query('UPDATE LOW_PRIORITY trackers SET ' . join(", ", $trupdateset) . ' WHERE torrent = ' . $REL_DB->sqlesc($torrentid) . ' AND tracker="localhost"') or sqlerr(__FILE__, __LINE__);
}

if ($updateset)
    $REL_DB->query('UPDATE LOW_PRIORITY torrents SET ' . join(", ", $updateset) . ' WHERE id = ' . $REL_DB->sqlesc($torrentid)) or sqlerr(__FILE__, __LINE__);

if ($snatch_updateset)
    $REL_DB->query('UPDATE LOW_PRIORITY snatched SET ' . join(", ", $snatch_updateset) . ' WHERE torrent = ' . $REL_DB->sqlesc($torrentid) . ' AND userid = ' . $REL_DB->sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
;

//$REL_DB->query("UPDATE LOW_PRIORITY users SET last_announced = $time WHERE id=$userid") or sqlerr(__FILE__,__LINE__);


if ($_SERVER["HTTP_ACCEPT_ENCODING"] == "gzip" && $REL_CONFIG['use_gzip']) {
    header("Content-Encoding: gzip");
    echo gzencode(benc_resp_raw($resp), 2, FORCE_GZIP);
} else
    benc_resp_raw($resp);

?>