<?php

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
if(!defined('IN_ANNOUNCE'))
  die('Hacking attempt!');
define ('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']."/");
require_once(ROOT_PATH . 'include/secrets.php');

function err($msg) {
	benc_resp(array("failure reason" => array(type => "string", value => $msg)));
	exit();
}

function benc_resp($d) {
	benc_resp_raw(benc(array(type => "dictionary", value => $d)));
}

function benc_resp_raw($x) {
	header("Content-Type: text/plain");
	header("Pragma: no-cache");
	print($x);
}

function get_date_time($timestamp = 0) {
	if ($timestamp)
		return date("Y-m-d H:i:s", $timestamp);
	else
		return date("Y-m-d H:i:s");
}

function gmtime() {
    return strtotime(get_date_time());
}

function strip_magic_quotes($arr) {
	foreach ($arr as $k => $v)
	{
	 if (is_array($v))
	  { $arr[$k] = strip_magic_quotes($v); }
	 else
	  { $arr[$k] = stripslashes($v); }
	}

	return $arr;
}

function mksize($bytes) {
	if ($bytes < 1000 * 1024)
		return number_format($bytes / 1024, 2) . " kB";
	elseif ($bytes < 1000 * 1048576)
		return number_format($bytes / 1048576, 2) . " MB";
	elseif ($bytes < 1000 * 1073741824)
		return number_format($bytes / 1073741824, 2) . " GB";
	else
		return number_format($bytes / 1099511627776, 2) . " TB";
}

function emu_getallheaders() {
   foreach($_SERVER as $name => $value)
	   if(substr($name, 0, 5) == 'HTTP_')
		   $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
   return $headers;
}

function portblacklisted($port) {
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

function validip($ip) {
	if (!empty($ip) && $ip == long2ip(ip2long($ip)))
	{
				$reserved_ips = array (
				array('0.0.0.0','2.255.255.255'),
				array('10.0.0.0','10.255.255.255'),
				array('127.0.0.0','127.255.255.255'),
				array('169.254.0.0','169.254.255.255'),
				array('172.16.0.0','172.31.255.255'),
				array('192.0.2.0','192.0.2.255'),
				array('192.168.0.0','192.168.255.255'),
				array('255.255.255.0','255.255.255.255')
		);

		foreach ($reserved_ips as $r)
		{
				$min = ip2long($r[0]);
				$max = ip2long($r[1]);
				if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
		}
		return true;
	}
	else return false;
}

function getip() {
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

function dbconn() {
        global $mysql_host, $mysql_user, $mysql_pass, $mysql_db, $CACHEARRAY;
        if (!@mysql_connect($mysql_host, $mysql_user, $mysql_pass))
    {
                err('dbconn: mysql_connect: ' . mysql_error());
    }

    mysql_select_db($mysql_db) or err('dbconn: mysql_select_db: ' + mysql_error());
    mysql_set_charset($mysql_charset);
    mysql_query("SET @@collation_connection = @@collation_database");
    // caching begin
  $cacherow = mysql_query("SELECT * FROM cache_stats");
  while ($cacheres = mysql_fetch_array($cacherow))
  $CACHEARRAY[$cacheres['cache_name']] = $cacheres['cache_value'];
  //caching end
        register_shutdown_function("mysql_close");

}

function sqlesc($value) {
    // Stripslashes
   /*if (get_magic_quotes_gpc()) {
       $value = stripslashes($value);
   }*/
   // Quote if not a number or a numeric string
   if (!is_numeric($value)) {
       $value = "'" . mysql_real_escape_string($value) . "'";
   }
   return $value;
}

function hash_pad($hash) {
    return str_pad($hash, 20);
}

function hash_where($name, $hash) {
    $shhash = preg_replace('/ *$/s', "", $hash);
    return "($name = " . sqlesc($hash) . " OR $name = " . sqlesc($shhash) . ")";
}

function unesc($x) {
	if (get_magic_quotes_gpc())
		return stripslashes($x);
	return $x;
}

function gzip() {
	if (@extension_loaded('zlib') && @ini_get('zlib.output_compression') != '1' && @ini_get('output_handler') != 'ob_gzhandler') {
		@ob_start('ob_gzhandler');
	}
}

function checkclient($peer_id){
    $agent = $_SERVER['HTTP_USER_AGENT'];
    //die($peer_id);
    //return true;
    //check by headers
    if (function_exists('getallheaders')){
        $headers = getallheaders();
    }else{
        $headers = emu_getallheaders();
    }
    if (isset($headers['Cookie']) || isset($headers['Accept-Language']) || isset($headers['Accept-Charset']))err('Вы не можете использовать этот клиент. Возможно вы читер.');

    //check by agent
    $banned = array();
    $banned[]= "FUTB";
    $banned[]= "ABC";
    $banned[]= "Opera";
    $banned[]= "Mozilla";
    $banned[]= "Rufus";
    $banned[]= "Deluge";
    $banned[]= "BinTorrent";
    $banned[]= "TorrentStorm";
    $banned[]= "Burst!";
    $banned[]= "BitBuddy";
    $banned[]= "Shareaza";
    $banned[]= "TurboBT";
    $banned[]= "eXeem";
    $banned[]= "RAZA";
    $banned[]= "AG";
    $banned[]= "MLDonkey";
    $banned[]= "Ares";
    $banned[]= "Red Swoosh";
    $banned[]= "FDM";
    $banned[]= "SHAD0W";


    for($i=0;$i<sizeof($banned);$i++){
        if(strpos($agent, $banned[$i]) !== false) err("Извините, клиент ".$banned[$i]." запрещен на нашем трекере.");
    }




#    //check by peer_id

    if(substr($peer_id, 0, 6) == "exbc\08") err("Клиент BitComet 0.56 запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 4) == "FUTB") err("Клиент FUTB запрещен на нашем трекере."); //patched version of BitComet 0.57 (FUTB- Fuck U TorrentBits)
    elseif(substr($peer_id, 1, 2) == 'BC' && substr($peer_id, 5, 2) != 70 && substr($peer_id, 5, 2) != 63 && substr($peer_id, 5, 2) != 77 && substr($peer_id, 5, 2) >= 59/* && substr($peer_id, 5, 2) <= 88*/) err("BitComet ".substr($peer_id, 5, 2)." is banned.");
    elseif(ereg("^0P3R4H", $peer_id)) err("Клиент Opera запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 7) == "exbc\0L") err("Клиент BitLord 1.0 запрещен на нашем трекере..");
    elseif(substr($peer_id, 0, 7) == "exbcL") err("Клиент BitLord 1.1 запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 3) == "-TS") err("Клиент TorrentStorm запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 5) == "Mbrst") err("Клиент Burst! запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 3) == "-BB") err("Клиент BitBuddy запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 3) == "-SZ") err("Клиент Shareaza запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 5) == "turbo") err("Клиент TurboBT запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 4) == "T03A") err("Клиет BitTornado установленной у вас версии забанен, пожалуйста, обновите клиент.");
    elseif(substr($peer_id, 0, 4) == "T03B") err("Клиет BitTornado установленной у вас версии забанен, пожалуйста, обновите клиент.");
    elseif(substr($peer_id, 0, 3 ) == "FRS") err("Клиент Rufus запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 2 ) == "eX") err("Клиент eXeem запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 8 ) == "-TR0005-") err("Клиент Transmission/0.5 запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 8 ) == "-TR0006-") err("Клиент Transmission/0.6 запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 8 ) == "-XX0025-") err("Клиент Transmission/0.6 запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 1 ) == ",") err ("Клиент RAZA запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 3 ) == "-AG") err("Ваш битторрент-клиент запрещен на нашем трекере.");
    elseif(substr($peer_id, 0, 3 ) == "R34") err("Клиент BTuga/Revolution-3.4 запрещен на нашем трекере.");
    elseif(preg_match("/MLDonkey\/([0-9]+).([0-9]+).([0-9]+)*/", $peer_id, $matches)) err("MLDonkey не является битторрент-клиентом.");
    elseif(preg_match("/ed2k_plugin v([0-9]+\\.[0-9]+).*/", $peer_id, $matches)) err("eDonkey не является битторрент-клиентом.");
    elseif(substr($peer_id, 0, 4) == "exbc") err("Это версия BitComet заблокирована.");
    elseif(substr($peer_id, 0, 3) == '-FG') err("FlashGet запрещен на нашем трекере.");

    elseif(substr($peer_id, 1, 2) == 'UT'){
        $UTVersion = (int) substr($peer_id, 3, 4);
        if($UTVersion<1610)err("uTorrent версии ниже ".$UTVersion." запрещен на нашем трекере.");
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
    if(strpos($agent, "uTorrent") !== false && strpos($agent, "B") !== false) err("Бета-версии uTorrent запрещены на нашем трекере, используйте стабильные релизы.");


}

?>