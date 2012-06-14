<?php
/**
 * Annouce functions file
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

/**
 * Generates SQL error message sending notification to SYSOP
 * @param string $file File where error begins __FILE__
 * @param string $line Line where error begins __LINE__
 * @return void
 */
function sqlerr($file = '', $line = '')
{
    $err = mysql_error();
    $text = ("SQL error, mysql server said: " . $err . ($file != '' && $line != '' ? " file: $file, line: $line" : ""));
    write_log("Announce/scrape SQL ERROR: $text", 'sql_errors');
    err($text);
    return;
}

/**
 * Writes event to sitelog
 * @param stirng $text Message to be writed to log
 * @param string $type Type of log record, default 'tracker'
 * @return void
 */
function write_log($text, $type = "tracker")
{
    $type = sqlesc($type);
    $text = sqlesc($text);
    $added = time();
    mysql_query("INSERT INTO sitelog (added, txt, type) VALUES($added, $text, $type)") or sqlerr(__FILE__, __LINE__);
    return;
}

function err($msg)
{
    benc_resp(array("failure reason" => array(type => "string", value => $msg)));
    exit();
    return;
}

/**
 * Sets charset to database connection.
 * @param string $charset Charset to be set
 * @return void
 */
function my_set_charset($charset)
{
    if (!function_exists("mysql_set_charset") || !mysql_set_charset($charset)) mysql_query("SET NAMES $charset");
    return;
}

/**
 * Bencoded response (dictionary)
 * @param string $d Response
 * @return void
 */
function benc_resp($d)
{
    benc_resp_raw(benc(array(type => "dictionary", value => $d)));
    return;
}

/**
 * Bencoded response
 * @param string $x String to response
 */
function benc_resp_raw($x)
{
    header("Content-Type: text/plain");
    header("Pragma: no-cache");
    print($x);
    return;
}

/**
 * Validates client port
 * @param int $port Port to be validated
 * @return boolean
 */
function portblacklisted($port)
{
    if ($port >= 411 && $port <= 413)
        return true;
    if ($port >= 6881 && $port <= 6889)
        return true;
    if ($port == 1214)
        return true;
    if ($port >= 6346 && $port <= 6347)
        return true;
    if ($port == 4662)
        return true;
    if ($port == 6699)
        return true;
    return false;
}

/**
 * Validates client ip
 * @param string $ip Ip to be validated
 * @return boolean
 */
function validip($ip)
{
    if (!empty($ip) && $ip == long2ip(ip2long($ip))) {
        $reserved_ips = array(
            array('0.0.0.0', '2.255.255.255'),
            array('10.0.0.0', '10.255.255.255'),
            array('127.0.0.0', '127.255.255.255'),
            array('169.254.0.0', '169.254.255.255'),
            array('172.16.0.0', '172.31.255.255'),
            array('192.0.2.0', '192.0.2.255'),
            array('192.168.0.0', '192.168.255.255'),
            array('255.255.255.0', '255.255.255.255')
        );

        foreach ($reserved_ips as $r) {
            $min = ip2long($r[0]);
            $max = ip2long($r[1]);
            if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
        }
        return true;
    } else return false;
}

/**
 * Gets client ip
 * @return string Ip address
 */
function getip()
{
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && validip($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && validip($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR') && validip(getenv('HTTP_X_FORWARDED_FOR'))) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP') && validip(getenv('HTTP_CLIENT_IP'))) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else {
            $ip = getenv('REMOTE_ADDR');
        }
    }

    return $ip;
}

/**
 * Sets up a database connection
 * @return void
 */
function INIT()
{

    require_once(ROOT_PATH . 'include/secrets.php');
    /* @var database object */
    require_once(ROOT_PATH . 'classes/database/database.class.php');

    /* @var object general cache object */
    require_once(ROOT_PATH . 'classes/cache/cache.class.php');

    global $REL_DB, $REL_CACHE, $REL_CRON, $REL_CONFIG, $xbtconf;
    $REL_DB = new REL_DB($db);
    unset($db);

    $REL_CACHE = new Cache();
    if (REL_CACHEDRIVER == 'native') {
        require_once(ROOT_PATH . 'classes/cache/fileCacheDriver.class.php');
        $REL_CACHE->addDriver(NULL, new FileCacheDriver());
    } elseif (REL_CACHEDRIVER == 'memcached') {
        require_once(ROOT_PATH . 'classes/cache/MemCacheDriver.class.php');
        $REL_CACHE->addDriver(NULL, new MemCacheDriver());
    }

    /* @var array Array of releaser's configuration */
    $REL_CONFIG = $REL_CACHE->get('system', 'config');
//$REL_CONFIG=false;
    if ($REL_CONFIG === false) {

        $REL_CONFIG = array();

        $cacherow = $REL_DB->query("SELECT * FROM cache_stats");

        while ($cacheres = mysql_fetch_array($cacherow))
            $REL_CONFIG[$cacheres['cache_name']] = $cacheres['cache_value'];

        $REL_CACHE->set('system', 'config', $REL_CONFIG);
    }

    if ($REL_CONFIG['use_xbt']) err('Please redirect your users to XBT announce');

    if ($REL_CONFIG['use_ipbans']) {


        require_once(ROOT_PATH . 'classes/bans/ipcheck.class.php');

        $maskres = $REL_CACHE->get('bans', 'query');
        if ($maskres === false) {
            $res = $REL_DB->query("SELECT mask FROM bans");
            $maskres = array();

            while (list($mask) = mysql_fetch_array($res))
                $maskres[] = $mask;

            $REL_CACHE->set('bans', 'query', $maskres);
        }

        $BAN = new IPAddressSubnetSniffer($maskres);
        if ($BAN->ip_is_allowed($ip)) {
            //write_log("$ip attempted to access tracker",'bans');
            err("Sorry, you (or your subnet) are banned by IP and MAC addresses!");

        }

    }

}

/**
 * Starts gzip comperssion
 * @return void
 */
function gzip()
{
    if (@extension_loaded('zlib') && @ini_get('zlib.output_compression') != '1' && @ini_get('output_handler') != 'ob_gzhandler') {
        @ob_start('ob_gzhandler');
    }
    return;
}

/**
 * Checks that user client was not banned. Dies on false
 * @param string $peer_id Peer_id of client
 * @return void
 */
function checkclient($peer_id)
{
    $agent = $_SERVER['HTTP_USER_AGENT'];

    $headers = getallheaders();
    if (isset($headers['Cookie']) || isset($headers['Accept-Language']) || isset($headers['Accept-Charset'])) err('Вы не можете использовать этот клиент. Возможно вы читер.');

    //check by agent
    $banned = array();
    $banned[] = "FUTB";
    $banned[] = "ABC";
    $banned[] = "Opera";
    $banned[] = "Mozilla";
    $banned[] = "Rufus";
    // $banned[]= "Deluge";
    $banned[] = "BinTorrent";
    $banned[] = "TorrentStorm";
    $banned[] = "Burst!";
    $banned[] = "BitBuddy";
    $banned[] = "Shareaza";
    $banned[] = "TurboBT";
    $banned[] = "eXeem";
    $banned[] = "RAZA";
    $banned[] = "AG";
    $banned[] = "MLDonkey";
    $banned[] = "Ares";
    $banned[] = "Red Swoosh";
    $banned[] = "FDM";
    $banned[] = "SHAD0W";


    for ($i = 0; $i < sizeof($banned); $i++) {
        if (strpos($agent, $banned[$i]) !== false) err("Извините, клиент " . $banned[$i] . " запрещен на нашем трекере.");
    }


    if (strpos($agent, "uTorrent") !== false && strpos($agent, "B") !== false) err("Бета-версии uTorrent запрещены на нашем трекере, используйте стабильные релизы.");

    #    //check by peer_id

    if (substr($peer_id, 0, 6) == "exbc\08") err("Клиент BitComet 0.56 запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 4) == "FUTB") err("Клиент FUTB запрещен на нашем трекере."); //patched version of BitComet 0.57 (FUTB- Fuck U TorrentBits)
    elseif (substr($peer_id, 1, 2) == 'BC' && substr($peer_id, 5, 2) != 70 && substr($peer_id, 5, 2) != 63 && substr($peer_id, 5, 2) != 77 && substr($peer_id, 5, 2) >= 59 /* && substr($peer_id, 5, 2) <= 88*/) err("BitComet " . substr($peer_id, 5, 2) . " is banned.");
    elseif (preg_match("/^0P3R4H/", $peer_id)) err("Клиент Opera запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 7) == "exbc\0L") err("Клиент BitLord 1.0 запрещен на нашем трекере..");
    elseif (substr($peer_id, 0, 7) == "exbcL") err("Клиент BitLord 1.1 запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 3) == "-TS") err("Клиент TorrentStorm запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 5) == "Mbrst") err("Клиент Burst! запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 3) == "-BB") err("Клиент BitBuddy запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 3) == "-SZ") err("Клиент Shareaza запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 5) == "turbo") err("Клиент TurboBT запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 4) == "T03A") err("Клиет BitTornado установленной у вас версии забанен, пожалуйста, обновите клиент.");
    elseif (substr($peer_id, 0, 4) == "T03B") err("Клиет BitTornado установленной у вас версии забанен, пожалуйста, обновите клиент.");
    elseif (substr($peer_id, 0, 3) == "FRS") err("Клиент Rufus запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 2) == "eX") err("Клиент eXeem запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 8) == "-TR0005-") err("Клиент Transmission/0.5 запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 8) == "-TR0006-") err("Клиент Transmission/0.6 запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 8) == "-XX0025-") err("Клиент Transmission/0.6 запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 1) == ",") err("Клиент RAZA запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 3) == "-AG") err("Ваш битторрент-клиент запрещен на нашем трекере.");
    elseif (substr($peer_id, 0, 3) == "R34") err("Клиент BTuga/Revolution-3.4 запрещен на нашем трекере.");
    elseif (preg_match("/MLDonkey\/([0-9]+).([0-9]+).([0-9]+)*/", $peer_id, $matches)) err("MLDonkey не является битторрент-клиентом.");
    elseif (preg_match("/ed2k_plugin v([0-9]+\\.[0-9]+).*/", $peer_id, $matches)) err("eDonkey не является битторрент-клиентом.");
    elseif (substr($peer_id, 0, 4) == "exbc") err("Это версия BitComet заблокирована.");
    elseif (substr($peer_id, 0, 3) == '-FG') err("FlashGet запрещен на нашем трекере.");

    elseif (substr($peer_id, 1, 2) == 'UT') {
        $UTVersion = (int)substr($peer_id, 3, 4);
        if ($UTVersion < 1610) err("uTorrent версии ниже " . $UTVersion . " запрещен на нашем трекере.");
        //elseif($UTVersion>1610 AND $UTVersion<1810)err("uTorrent версии ".$UTVersion." запрещен на нашем трекере.");
    }


    //exbcLORD         BitLord 1.1
    //-UT1750            uTorrent 1750
    //-UT1610-           uTorrent 1610
    //-BC0070-\tiB       BitTorrent/3.4.2
    //-TR1110-6lzvmrvc7i06 Transmission/1.11 (5504)
    //-lt0C00            rtorrent/0.8.0/0.12.0
    //T03I                BitTornado/T-0.3.18

    //check by agent and version (not all versions are banned)

}

?>