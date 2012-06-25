<?php
/**
 * Remote trackers checker
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
@ini_set('default_socket_timeout', 3);

define ("IN_ANNOUNCE", true);
define ("ROOT_PATH", dirname(__FILE__) . '/');
define('TIME', time());
$time = TIME;

require_once(ROOT_PATH . 'include/secrets.php');
// connection closed
/* @var database object */
require_once(ROOT_PATH . 'classes/database/database.class.php');
$REL_DB = new REL_DB($db);
unset($db);
//$REL_DB->debug();

require_once(ROOT_PATH . "include/benc.php");

/**
 * Writes event to sitelog
 * @param stirng $text Message to be writed to log
 * @param string $type Type of log record, default 'tracker'
 * @return void
 */
function write_log($text, $type = "tracker")
{
    $type = mysql_real_escape_string($type);
    $text = mysql_real_escape_string($text);
    $added = TIME;
    $REL_DB->query("INSERT INTO sitelog (added, txt, type) VALUES($added, '$text', '$type')");
    return;
}

/**
 * Executes script
 * @param string $url URL of script
 * @param array $params Array of POST parameters
 * @return boolean
 */
function exec_script($url, $params = array())
{
    $parts = parse_url($url);

    if (!$fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80)) {
        return false;
    }

    $data = http_build_query($params, '', '&');

    fwrite($fp, "POST " . (!empty($parts['path']) ? $parts['path'] : '/') . " HTTP/1.1\r\n");
    fwrite($fp, "Host: " . $parts['host'] . "\r\n");
    fwrite($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
    fwrite($fp, "Content-Length: " . strlen($data) . "\r\n");
    fwrite($fp, "Connection: Close\r\n\r\n");
    fwrite($fp, $data);
    fclose($fp);

    return true;
}

$id = (int)$_GET['id'];
if (!$id) $id = (int)$_POST['id'];

if ($id) {


    $anarray = $REL_DB->query("SELECT torrents.info_hash, trackers.tracker FROM trackers LEFT JOIN torrents ON torrents.id=trackers.torrent WHERE trackers.torrent=$id AND trackers.tracker<>'localhost'");

    while (list($infohash, $url) = mysql_fetch_array($anarray)) {
        $peers = get_remote_peers($url, $infohash);
        $REL_DB->query("UPDATE LOW_PRIORITY trackers SET seeders=" . (int)$peers['seeders'] . ", leechers=" . (int)$peers['leechers'] . ", check_start=" . TIME . ", lastchecked=" . TIME . ", method='{$peers['method']}', remote_method='{$peers['remote_method']}', state=" . $REL_DB->sqlesc($peers['state']) . " WHERE torrent=$id AND tracker=" . $REL_DB->sqlesc($url));
    }
    die(base64_decode("R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=="));
}

$cronrow = $REL_DB->query("SELECT * FROM cron WHERE cron_name IN ('remotecheck_disabled','remotepeers_cleantime','in_remotecheck','remote_trackers')");
while ($cronres = mysql_fetch_array($cronrow)) $REL_CRON[$cronres['cron_name']] = $cronres['cron_value'];

if (!$REL_CRON['remotecheck_disabled']) {

    $REL_CONFIGrow = $REL_DB->query("SELECT * FROM cache_stats WHERE cache_name IN ('defaultbaseurl')");

    while ($REL_CONFIGres = mysql_fetch_assoc($REL_CONFIGrow)) $REL_CONFIG[$REL_CONFIGres['cache_name']] = $REL_CONFIGres['cache_value'];

    $REL_CRON['remote_lastchecked'] = (int)$REL_CRON['remote_lastchecked'];
    $REL_CRON['remote_trackers'] = (int)$REL_CRON['remote_trackers'];

    $REL_DB->query("UPDATE cron SET cron_value=1 WHERE cron_name='in_remotecheck'");
    $REL_DB->query("UPDATE cron SET cron_value=" . $time . " WHERE cron_name='last_remotecheck'");
    $REL_DB->query("UPDATE cron SET cron_value=cron_value+1 WHERE cron_name='num_checked'");
    // delete stuck trackers
    $REL_DB->query("UPDATE trackers SET state='' WHERE check_start<" . ($time - $REL_CRON['remotepeers_cleantime'] - ($REL_CRON['remote_trackers'] * 3)) . " AND state = 'in_check'");

    $res = $REL_DB->query("SELECT torrent FROM trackers WHERE " . ($REL_CRON['remotepeers_cleantime'] ? "trackers.lastchecked<" . ($time - $REL_CRON['remotepeers_cleantime']) . " AND " : '') . "trackers.tracker<>'localhost' AND trackers.state<>'in_check' ORDER BY trackers.lastchecked ASC" . ($REL_CRON['remote_trackers'] ? " LIMIT {$REL_CRON['remote_trackers']}" : ''));

    while (list($id) = mysql_fetch_array($res)) {
        exec_script("{$REL_CONFIG['defaultbaseurl']}/remote_check.php", array('id' => $id));
    }

    $REL_DB->query("UPDATE cron SET cron_value=0 WHERE cron_name='in_remotecheck'");
}
print base64_decode("R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==");

?>
