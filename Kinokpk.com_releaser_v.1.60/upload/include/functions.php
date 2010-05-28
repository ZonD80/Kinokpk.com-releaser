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
if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

require_once($rootpath . 'include/functions_global.php');

function strip_magic_quotes($arr) {
	foreach ($arr as $k => $v) {
		if (is_array($v)) {
			$arr[$k] = strip_magic_quotes($v);
			} else {
			$arr[$k] = stripslashes($v);
			}
	}
	return $arr;
}

function local_user() {
	return $_SERVER["SERVER_ADDR"] == $_SERVER["REMOTE_ADDR"];
}

function sql_query($query) {
	global $queries, $query_stat, $querytime;
	$queries++;
	$query_start_time = timer(); // Start time
	$result = mysql_query($query);
	$query_end_time = timer(); // End time
	$query_time = ($query_end_time - $query_start_time);
	$querytime = $querytime + $query_time;
	$query_time = substr($query_time, 0, 8);
	$query_stat[] = array("seconds" => $query_time, "query" => $query);
	return $result;
}

function dbconn($autoclean = false, $lightmode = false) {
	global $mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_charset;

	if (!@mysql_connect($mysql_host, $mysql_user, $mysql_pass))
		die("[" . mysql_errno() . "] dbconn: mysql_connect: " . mysql_error());

	mysql_select_db($mysql_db)
		or die("dbconn: mysql_select_db: " + mysql_error());

#    mysql_query("SET NAMES $mysql_charset");
mysql_set_charset("$mysql_charset");

	userlogin($lightmode);

	if (basename($_SERVER['SCRIPT_FILENAME']) == 'index.php')
		register_shutdown_function("autoclean");

	register_shutdown_function("mysql_close");

}

function userlogin($lightmode = false) {
	global $SITE_ONLINE, $default_language, $tracker_lang, $use_lang, $use_ipbans;
	unset($GLOBALS["CURUSER"]);

	$ip = getip();
	$nip = ip2long($ip);

	if ($use_ipbans && !$lightmode) {
		$res = sql_query("SELECT * FROM bans WHERE $nip >= first AND $nip <= last") or sqlerr(__FILE__, __LINE__);
		if (mysql_num_rows($res) > 0) {
			$comment = mysql_fetch_assoc($res);
			$comment = $comment["comment"];
			header("HTTP/1.0 403 Forbidden");
			print("<html><body><h1>403 Forbidden</h1>Unauthorized IP address.</body></html>\n");
			die;
		}
	}

	if (!$SITE_ONLINE || empty($_COOKIE["uid"]) || empty($_COOKIE["pass"])) {
		if ($use_lang)
			include_once('languages/lang_' . $default_language . '/lang_main.php');
		user_session();
		return;
	}
	$id = 0 + $_COOKIE["uid"];
	if (!$id || strlen($_COOKIE["pass"]) != 32) {
		die("Cokie ID invalid or cookie pass hash problem.");
		/*if ($use_lang)
			include_once('languages/lang_' . $default_language . '/lang_main.php');
		user_session();
		return;*/
	}
	$res = sql_query("SELECT * FROM users WHERE id = $id AND enabled='yes' AND status = 'confirmed'");// or die(mysql_error());
	$row = mysql_fetch_array($res);
	if (!$row) {
		if ($use_lang)
			include_once('languages/lang_' . $default_language . '/lang_main.php');
		user_session();
		return;
	}
	$sec = hash_pad($row["secret"]);
	if ($_COOKIE["pass"] !== $row["passhash"]) {
		if ($use_lang)
			include_once('languages/lang_' . $default_language . '/lang_main.php');
		user_session();
		return;
	}
	sql_query("UPDATE users SET last_access = ".sqlesc(get_date_time()).", ip = ".sqlesc($ip)." WHERE id=" . $row["id"]);// or die(mysql_error());
	$row['ip'] = $ip;

	if ($row['override_class'] < $row['class'])
		$row['class'] = $row['override_class']; // Override class and save in GLOBAL array below.

	$GLOBALS["CURUSER"] = $row;
	if ($use_lang)
		include_once('languages/lang_' . $row['language'] . '/lang_main.php');

	if (!$lightmode)
		user_session();

}

function get_server_load() {
	global $tracker_lang, $phpver;
	if (strtolower(substr(PHP_OS, 0, 3)) === 'win') {
		return 0;
	} elseif (@file_exists("/proc/loadavg")) {
		$load = @file_get_contents("/proc/loadavg");
		$serverload = explode(" ", $load);
		$serverload[0] = round($serverload[0], 4);
		if(!$serverload) {
			$load = @exec("uptime");
			$load = split("load averages?: ", $load);
			$serverload = explode(",", $load[1]);
		}
	} else {
		$load = @exec("uptime");
		$load = split("load averages?: ", $load);
		$serverload = explode(",", $load[1]);
	}
	$returnload = trim($serverload[0]);
	if(!$returnload) {
		$returnload = $tracker_lang['unknown'];
	}
	return $returnload;
}

function user_session() {
	global $CURUSER, $use_sessions;

	if (!$use_sessions)
		return;

	$ip = getip();
	$url = getenv("REQUEST_URI");

	if (!$CURUSER) {
		$uid = -1;
		$username = '';
		$class = -1;
	} else {
		$uid = $CURUSER['id'];
		$username = $CURUSER['username'];
		$class = $CURUSER['class'];
	}

	$past = time() - 300;
	$sid = session_id();
	$where = array();
	$updateset = array();
	if ($sid)
		$where[] = "sid = ".sqlesc($sid);
	elseif ($uid)
		$where[] = "uid = $uid";
	else
		$where[] = "ip = ".sqlesc($ip);
	//sql_query("DELETE FROM sessions WHERE ".implode(" AND ", $where));
	$ctime = time();
	$agent = $_SERVER["HTTP_USER_AGENT"];
	$updateset[] = "sid = ".sqlesc($sid);
	$updateset[] = "uid = ".sqlesc($uid);
	$updateset[] = "username = ".sqlesc($username);
	$updateset[] = "class = ".sqlesc($class);
	$updateset[] = "ip = ".sqlesc($ip);
	$updateset[] = "time = ".sqlesc($ctime);
	$updateset[] = "url = ".sqlesc($url);
	$updateset[] = "useragent = ".sqlesc($agent);
	if (count($updateset))
		sql_query("UPDATE sessions SET ".implode(", ", $updateset)." WHERE ".implode(" AND ", $where)) or sqlerr(__FILE__,__LINE__);
	if (mysql_modified_rows() < 1)
		sql_query("INSERT INTO sessions (sid, uid, username, class, ip, time, url, useragent) VALUES (".implode(", ", array_map("sqlesc", array($sid, $uid, $username, $class, $ip, $ctime, $url, $agent))).")") or sqlerr(__FILE__,__LINE__);
}

function unesc($x) {
	if (get_magic_quotes_gpc())
		return stripslashes($x);
	return $x;
}

function gzip() {
	if (@extension_loaded('zlib') && @ini_get('zlib.output_compression') != '1' && @ini_get('output_handler') != 'ob_gzhandler' && $use_gzip) {
		@ob_start('ob_gzhandler');
	}
}

// IP Validation
function validip($ip) {
	if (!empty($ip) && $ip == long2ip(ip2long($ip)))
	{
		// reserved IANA IPv4 addresses
		// http://www.iana.org/assignments/ipv4-address-space
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

		foreach ($reserved_ips as $r) {
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

function autoclean() {
	global $autoclean_interval, $rootpath;

	$now = time();
	$docleanup = 0;

	$res = sql_query("SELECT value_u FROM avps WHERE arg = 'lastcleantime'");
	$row = mysql_fetch_array($res);
	if (!$row) {
		sql_query("INSERT INTO avps (arg, value_u) VALUES ('lastcleantime',$now)");
		return;
	}
	$ts = $row[0];
	if ($ts + $autoclean_interval > $now)
		return;
	sql_query("UPDATE avps SET value_u=$now WHERE arg='lastcleantime' AND value_u = $ts");
	if (!mysql_affected_rows())
		return;

	require_once($rootpath . 'include/cleanup.php');

	docleanup();
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

function mksizeint($bytes) {
		$bytes = max(0, $bytes);
		if ($bytes < 1000)
				return floor($bytes) . " B";
		elseif ($bytes < 1000 * 1024)
				return floor($bytes / 1024) . " kB";
		elseif ($bytes < 1000 * 1048576)
				return floor($bytes / 1048576) . " MB";
		elseif ($bytes < 1000 * 1073741824)
				return floor($bytes / 1073741824) . " GB";
		else
				return floor($bytes / 1099511627776) . " TB";
}

function deadtime() {
	global $announce_interval;
	return time() - floor($announce_interval * 1.3);
}

function mkprettytime($s) {
    if ($s < 0)
	$s = 0;
    $t = array();
    foreach (array("60:sec","60:min","24:hour","0:day") as $x) {
		$y = explode(":", $x);
		if ($y[0] > 1) {
		    $v = $s % $y[0];
		    $s = floor($s / $y[0]);
		} else
		    $v = $s;
	$t[$y[1]] = $v;
    }

    if ($t["day"])
	return $t["day"] . "d " . sprintf("%02d:%02d:%02d", $t["hour"], $t["min"], $t["sec"]);
    if ($t["hour"])
	return sprintf("%d:%02d:%02d", $t["hour"], $t["min"], $t["sec"]);
	return sprintf("%d:%02d", $t["min"], $t["sec"]);
}

function mkglobal($vars) {
	if (!is_array($vars))
		$vars = explode(":", $vars);
	foreach ($vars as $v) {
		if (isset($_GET[$v]))
			$GLOBALS[$v] = unesc($_GET[$v]);
		elseif (isset($_POST[$v]))
			$GLOBALS[$v] = unesc($_POST[$v]);
		else
			return 0;
	}
	return 1;
}

function tr($x, $y, $noesc=0, $prints = true, $width = "", $relation = '') {
	if ($noesc)
		$a = $y;
	else {
		$a = htmlspecialchars_uni($y);
		$a = str_replace("\n", "<br />\n", $a);
	}
	if ($prints) {
	  $print = "<td width=\"". $width ."\" class=\"heading\" valign=\"top\" align=\"right\">$x</td>";
	  $colpan = "align=\"left\"";
	} else {
		$colpan = "colspan=\"2\"";
	}

	print("<tr".( $relation ? " relation=\"$relation\"" : "").">$print<td valign=\"top\" $colpan>$a</td></tr>\n");
}

function validfilename($name) {
	return preg_match('/^[^\0-\x1f:\\\\\/?*\xff#<>|]+$/si', $name);
}

function validemail($email) {
	if (ereg("^([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$", $email)) 
		return true;
	else
		return false;
}

function sent_mail($to,$fromname,$fromemail,$subject,$body,$multiple=false,$multiplemail='') {
	global $SITENAME,$SITEEMAIL,$smtptype,$smtp,$smtp_host,$smtp_port,$smtp_from,$smtpaddress,$accountname,$accountpassword,$rootpath;
	# Sent Mail Function v.05 by xam (This function to help avoid spam-filters.)
	$result = true;
	if ($smtptype == 'default') {
		@mail($to, $subject, $body, "From: $SITEEMAIL") or $result = false;
	} elseif ($smtptype == 'advanced') {
	# Is the OS Windows or Mac or Linux?
	if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
		$eol="\r\n";
		$windows = true;
	}
	elseif (strtoupper(substr(PHP_OS,0,3)=='MAC'))
		$eol="\r";
	else
		$eol="\n";
	$mid = md5(getip() . $fromname);
	$name = $_SERVER["SERVER_NAME"];
	$headers .= "From: $fromname <$fromemail>".$eol;
	$headers .= "Reply-To: $fromname <$fromemail>".$eol;
	$headers .= "Return-Path: $fromname <$fromemail>".$eol;
	$headers .= "Message-ID: <$mid thesystem@$name>".$eol;
	$headers .= "X-Mailer: PHP v".phpversion().$eol;
    $headers .= "MIME-Version: 1.0".$eol;
    $headers .= "Content-type: text/plain; charset=windows-1251".$eol;
    $headers .= "X-Sender: PHP".$eol;
    if ($multiple)
    	$headers .= "Bcc: $multiplemail.$eol";
	if ($smtp == "yes") {
		ini_set('SMTP', $smtp_host);
		ini_set('smtp_port', $smtp_port);
		if ($windows)
			ini_set('sendmail_from', $smtp_from);
		}

    	@mail($to, $subject, $body, $headers) or $result = false;

    	ini_restore(SMTP);
		ini_restore(smtp_port);
		if ($windows)
			ini_restore(sendmail_from);
	} elseif ($smtptype == 'external') {
		require_once($rootpath . 'include/smtp/smtp.lib.php');
		$mail = new smtp;
		$mail->debug(true);
		$mail->open($smtp_host, $smtp_port);
		if (!empty($accountname) && !empty($accountpassword))
			$mail->auth($accountname, $accountpassword);
		$mail->from($SITEEMAIL);
		$mail->to($to);
		$mail->subject($subject);
		$mail->body($body);
		$result = $mail->send();
		$mail->close();
	} else
		$result = false;

	return $result;
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

function sqlwildcardesc($x) {
	return str_replace(array("%","_"), array("\\%","\\_"), mysql_real_escape_string($x));
}
function sqlforum($x) {

	return mysql_real_escape_string(unesc($x));
}

function urlparse($m) {
	$t = $m[0];
	if (preg_match(',^\w+://,', $t))
		return "<a href=\"$t\">$t</a>";
	return "<a href=\"http://$t\">$t</a>";
}

function parsedescr($d, $html) {
	if (!$html) {
	  $d = htmlspecialchars_uni($d);
	  $d = str_replace("\n", "\n<br>", $d);
	}
	return $d;
}

function stdhead($title = "", $msgalert = true) {

	global $CURUSER, $SITE_ONLINE, $FUNDS, $SITENAME, $KEYWORDS, $DESCRIPTION, $DEFAULTBASEURL, $FORUMURL, $FORUMNAME, $ss_uri, $tracker_lang, $default_theme;
header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);
header ("X-Powered-by: Kinokpk.com releaser - http://www.kinokpk.com - http://dev.kinokpk.com");
header ("Cache-Control: no-cache");
header ("Pragma: no-cache");
//++++++++++++++++++++++++++++++++++
//******** Мод Сайт закрыт *********
//++++++++++++++++++++++++++++++++++

$res = sql_query("SELECT * FROM siteonline") or sqlerr(__FILE__, __LINE__);//Выбираем значения из базы
$row = mysql_fetch_array($res);

//Переменные для уведомления о закрытии сайта (см stdhead.php)
if ($row["onoff"] !=1){ 
$my_siteoff = 1;
$my_siteopenfor = $row['class_name'];
}
//

//========================================================================================//
//$row["onoff"] = 1;//АВАРИЙНЫЙ ВХОД: Раскомментировать строку, если не можете войти !!! //
//======================================================================================//

if (($row["onoff"] !=1) && (!$CURUSER)){                                               //Проверяем: закрыт ли сайт и, если гость:
        die("<title>Сайт закрыт!</title>
        <table width='100%' height='100%' style='border: 8px ridge #FF0000'><tr><td align='center'>
        <h1 style='color: #CC3300;'>".$row['reason']."</h1>
        <h1 style='color: #CC3300;'>
        Пожалуйста, зайдите позже...</h1>
        <br><center><form method='post' action='takesiteofflogin.php'>
        <table border='1' cellspacing='1' id='table1' cellpadding='3' style='border-collapse: collapse'>
        <tr><td colspan='2' align='center' bgcolor='#CC3300'>
        <font color='#FFFFFF'><b>Вход для пользователей:</b></font></td></tr>
        <tr><td><b>Имя:</b></td>
        <td><input type='text' size=20 name='username'></td></tr><tr>
        <td><b>Пароль:</b></td>
        <td><input type='password' size=20 name='password'></td>
        </tr><tr>
        <td colspan='2' align='center'>
        <input type='submit' value='Войти!'></td>
        </tr></table>
        </form></center>
        </td></tr></table>");
}
elseif (($row["onoff"] !=1) and (($CURUSER["class"] < $row["class"]) && ($CURUSER["id"] != 1))){ //Проверяем: закрыт ли сайт, класс юзера меньше,
                                                                                                 //чем допустимый и не являетесь ли Вы Админом (ID=1)
    die("<title>Сайт закрыт!</title>                                                         
        <table width='100%' height='100%' style='border: 8px ridge #FF0000'><tr><td align='center'>
        <h1 style='color: #CC3300;'>".$row['reason']."</h1>
        <h1 style='color: #CC3300;'>
        Пожалуйста, зайдите позже...</h1></td></tr></table>");
}
//++++++++++++++++++++++++++++++++++
//******** Мод Сайт закрыт *********
//++++++++++++++++++++++++++++++++++

	if (!$SITE_ONLINE)
		die("Site is down for maintenance, please check back again later... thanks<br />");
			$title = $SITENAME. " :: " . htmlspecialchars_uni($title);
			
		if (isset($_GET['styleid']) && $CURUSER) {
			$styleid = $_GET['styleid'];
			if (is_numeric($styleid)) {
				sql_query("UPDATE users SET stylesheet = $styleid WHERE id=" . $CURUSER["id"]) or sqlerr(__FILE__, __LINE__);
				   header("Location: $DEFAULTBASEURL/");
				//$CURUSER["stylesheet"] = $styleid;
			} else {
				die("Сука, ты чего ломаешь?");
			}
		}

	if ($CURUSER) {
		$ss_a = @mysql_fetch_array(@sql_query("SELECT uri FROM stylesheets WHERE id = " . $CURUSER["stylesheet"]));
		if ($ss_a)
			$ss_uri = $ss_a["uri"];
		else
			$ss_uri = $default_theme;
	} else
		$ss_uri = $default_theme;

	  if ($msgalert && $CURUSER) {
		$res = sql_query("SELECT COUNT(*) FROM messages WHERE receiver = " . $CURUSER["id"] . " AND unread='yes'") or die("OopppsY!");
		$arr = mysql_fetch_row($res);
		$unread = $arr[0];
	  }

	require_once("themes/" . $ss_uri . "/template.php");
	require_once("themes/" . $ss_uri . "/stdhead.php");

} // stdhead

function stdfoot() {
	global $CURUSER, $ss_uri, $tracker_lang, $queries, $tstart, $query_stat, $querytime;

	require_once("themes/" . $ss_uri . "/template.php");
	require_once("themes/" . $ss_uri . "/stdfoot.php");
	if ((DEBUG_MODE) && count($query_stat)) {
		foreach ($query_stat as $key => $value) {
			print("<div>[".($key+1)."] => <b>".($value["seconds"] > 0.01 ? "<font color=\"red\" title=\"Рекомендуется оптимизировать запрос. Время исполнения превышает норму.\">".$value["seconds"]."</font>" : "<font color=\"green\" title=\"Запрос не нуждается в оптимизации. Время исполнения допустимое.\">".$value["seconds"]."</font>" )."</b> [$value[query]]</div>\n");
		}
		print("Warning! Debug mode active!<br />");
	}
}

function genbark($x,$y) {
	stdhead($y);
	print("<h2>" . htmlspecialchars_uni($y) . "</h2>\n");
	print("<p>" . htmlspecialchars_uni($x) . "</p>\n");
	stdfoot();
	exit();
}

function mksecret($length = 20) {
$set = array("a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J","k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T","u","U","v","V","w","W","x","X","y","Y","z","Z","1","2","3","4","5","6","7","8","9");
	$str;
	for($i = 1; $i <= $length; $i++)
	{
		$ch = rand(0, count($set)-1);
		$str .= $set[$ch];
	}
	return $str;
}

function httperr($code = 404) {
	$sapi_name = php_sapi_name();
	if ($sapi_name == 'cgi' OR $sapi_name == 'cgi-fcgi') {
		header('Status: 404 Not Found');
	} else {
		header('HTTP/1.1 404 Not Found');
	}
	exit;
}

function gmtime() {
	return strtotime(get_date_time());
}

function logincookie($id, $passhash, $updatedb = 1, $expires = 0x7fffffff) {
		setcookie("uid", $id, $expires, "/");
		setcookie("pass", $passhash, $expires, "/");

	if ($updatedb)
		sql_query("UPDATE users SET last_login = NOW() WHERE id = $id");
}

function logoutcookie() {
	setcookie("uid", "", 0x7fffffff, "/");
	setcookie("pass", "", 0x7fffffff, "/");
}

function loggedinorreturn($nowarn = false) {
	global $CURUSER, $DEFAULTBASEURL;
	if (!$CURUSER) {
		header("Location: $DEFAULTBASEURL/login.php?returnto=" . urlencode(basename($_SERVER["REQUEST_URI"])).($nowarn ? "&nowarn=1" : ""));
		exit();
	}
}

function deletetorrent($id) {
	global $torrent_dir;
	sql_query("DELETE FROM torrents WHERE id = $id");
	sql_query("DELETE FROM bookmarks WHERE id = $id");
	foreach(explode(".","snatched.descr_torrents.peers.files.comments.ratings") as $x)
		sql_query("DELETE FROM $x WHERE torrent = $id");
	@unlink("$torrent_dir/$id.torrent");
}

function pager($rpp, $count, $href, $opts = array()) {
	$pages = ceil($count / $rpp);

	if (!$opts["lastpagedefault"])
		$pagedefault = 0;
	else {
		$pagedefault = floor(($count - 1) / $rpp);
		if ($pagedefault < 0)
			$pagedefault = 0;
	}

	if (isset($_GET["page"])) {
		$page = 0 + $_GET["page"];
		if ($page < 0)
			$page = $pagedefault;
	}
	else
		$page = $pagedefault;

	   $pager = "<td class=\"pager\">Страницы:</td><td class=\"pagebr\">&nbsp;</td>";

	$mp = $pages - 1;
	$as = "<b>«</b>";
	if ($page >= 1) {
		$pager .= "<td class=\"pager\">";
		$pager .= "<a href=\"{$href}page=" . ($page - 1) . "\" style=\"text-decoration: none;\">$as</a>";
		$pager .= "</td><td class=\"pagebr\">&nbsp;</td>";
	}

	$as = "<b>»</b>";
	if ($page < $mp && $mp >= 0) {
		$pager2 .= "<td class=\"pager\">";
		$pager2 .= "<a href=\"{$href}page=" . ($page + 1) . "\" style=\"text-decoration: none;\">$as</a>";
		$pager2 .= "</td>$bregs";
	}else	 $pager2 .= $bregs;

	if ($count) {
		$pagerarr = array();
		$dotted = 0;
		$dotspace = 3;
		$dotend = $pages - $dotspace;
		$curdotend = $page - $dotspace;
		$curdotstart = $page + $dotspace;
		for ($i = 0; $i < $pages; $i++) {
			if (($i >= $dotspace && $i <= $curdotend) || ($i >= $curdotstart && $i < $dotend)) {
				if (!$dotted)
				   $pagerarr[] = "<td class=\"pager\">...</td><td class=\"pagebr\">&nbsp;</td>";
				$dotted = 1;
				continue;
			}
			$dotted = 0;
			$start = $i * $rpp + 1;
			$end = $start + $rpp - 1;
			if ($end > $count)
				$end = $count;

			 $text = $i+1;
			if ($i != $page)
				$pagerarr[] = "<td class=\"pager\"><a title=\"$start&nbsp;-&nbsp;$end\" href=\"{$href}page=$i\" style=\"text-decoration: none;\"><b>$text</b></a></td><td class=\"pagebr\">&nbsp;</td>";
			else
				$pagerarr[] = "<td class=\"highlight\"><b>$text</b></td><td class=\"pagebr\">&nbsp;</td>";

				  }
		$pagerstr = join("", $pagerarr);
		$pagertop = "<table class=\"main\"><tr>$pager $pagerstr $pager2</tr></table>\n";
		$pagerbottom = "Всего $count на $i страницах по $rpp на каждой странице.<br /><br /><table class=\"main\">$pager $pagerstr $pager2</table>\n";
	}
	else {
		$pagertop = $pager;
		$pagerbottom = $pagertop;
	}

	$start = $page * $rpp;

	return array($pagertop, $pagerbottom, "LIMIT $start,$rpp");
}

function downloaderdata($res) {
	$rows = array();
	$ids = array();
	$peerdata = array();
	while ($row = mysql_fetch_assoc($res)) {
		$rows[] = $row;
		$id = $row["id"];
		$ids[] = $id;
		$peerdata[$id] = array(downloaders => 0, seeders => 0, comments => 0);
	}

	if (count($ids)) {
		$allids = implode(",", $ids);
		$res = sql_query("SELECT COUNT(*) AS c, torrent, seeder FROM peers WHERE torrent IN ($allids) GROUP BY torrent, seeder");
		while ($row = mysql_fetch_assoc($res)) {
			if ($row["seeder"] == "yes")
				$key = "seeders";
			else
				$key = "downloaders";
			$peerdata[$row["torrent"]][$key] = $row["c"];
		}
		$res = sql_query("SELECT COUNT(*) AS c, torrent FROM comments WHERE torrent IN ($allids) GROUP BY torrent");
		while ($row = mysql_fetch_assoc($res)) {
			$peerdata[$row["torrent"]]["comments"] = $row["c"];
		}
	}

	return array($rows, $peerdata);
}

function commenttable($rows, $redaktor = "comment") {
	global $CURUSER, $avatar_max_width;

	$count = 0;
	foreach ($rows as $row)	{
			    if ($row["downloaded"] > 0) {
			    	$ratio = $row['uploaded'] / $row['downloaded'];
			    	$ratio = number_format($ratio, 2);
			    } elseif ($row["uploaded"] > 0) {
			    	$ratio = "Inf.";
			    } else {
			    	$ratio = "---";
			    }
			     if (strtotime($row["last_access"]) > gmtime() - 600) {
			     	$online = "online";
			     	$online_text = "В сети";
			     } else {
			     	$online = "offline";
			     	$online_text = "Не в сети";
			     }

	   print("<table class=maibaugrand width=100% border=1 cellspacing=0 cellpadding=3>");
	   print("<tr><td class=colhead align=\"left\" colspan=\"2\" height=\"24\">");

    if (isset($row["username"]))
		{
			$title = $row["title"];
			if ($title == ""){
				$title = get_user_class_name($row["class"]);
			}else{
				$title = htmlspecialchars_uni($title);
			}
		   print(":: <img src=\"pic/buttons/button_".$online.".gif\" alt=\"".$online_text."\" title=\"".$online_text."\" style=\"position: relative; top: 2px;\" border=\"0\" height=\"14\">"
		       ." <a name=comm". $row["id"]." href=userdetails.php?id=" . $row["user"] . " class=altlink_white><b>". get_user_class_color($row["class"], htmlspecialchars_uni($row["username"])) . "</b></a> ::"
		       .($row["donor"] == "yes" ? "<img src=pic/star.gif alt='Donor'>" : "") . ($row["warned"] == "yes" ? "<img src=\"/pic/warned.gif\" alt=\"Warned\">" : "") . " $title ::\n")
		       ." <img src=\"pic/upl.gif\" alt=\"upload\" border=\"0\" width=\"12\" height=\"12\"> ".mksize($row["uploaded"]) ." :: <img src=\"pic/down.gif\" alt=\"download\" border=\"0\" width=\"12\" height=\"12\"> ".mksize($row["downloaded"])." :: <font color=\"".get_ratio_color($ratio)."\">$ratio</font> :: ";

	       } else {
			print("<a name=\"comm" . $row["id"] . "\"><i>[Anonymous]</i></a>\n");
	       }

	$avatar = ($CURUSER["avatars"] == "yes" ? htmlspecialchars_uni($row["avatar"]) : "");
	if (!$avatar){$avatar = "pic/default_avatar.gif"; }
	$text = format_comment($row["text"]);

	if ($row["editedby"]) {
	       //$res = mysql_fetch_assoc(sql_query("SELECT * FROM users WHERE id = $row[editedby]")) or sqlerr(__FILE__,__LINE__);
	       $text .= "<p><font size=1 class=small>Последний раз редактировалось <a href=userdetails.php?id=$row[editedby]><b>$row[editedbyname]</b></a> в $row[editedat]</font></p>\n";
	 }
		print("</td></tr>");
		print("<tr valign=top>\n");
		print("<td style=\"padding: 0px; width: 5%;\" align=\"center\"><img src=$avatar width=\"$avatar_max_width\"> </td>\n");
		print("<td width=100% class=text>");
		//print("<span style=\"float: right\"><a href=\"#top\"><img title=\"Top\" src=\"pic/top.gif\" alt=\"Top\" border=\"0\" width=\"15\" height=\"13\"></a></span>");
		print("$text</td>\n");
		print("</tr>\n");
		print("<tr><td class=colhead align=\"center\" colspan=\"2\">");
		print"<div style=\"float: left; width: auto;\">"
			.($CURUSER ? " [<a href=\"".$redaktor.".php?action=quote&amp;cid=$row[id]\" class=\"altlink_white\">Цитата</a>]" : "")
			.($row["user"] == $CURUSER["id"] || get_user_class() >= UC_MODERATOR ? " [<a href=".$redaktor.".php?action=edit&amp;cid=$row[id] class=\"altlink_white\">Изменить</a>]" : "")
		    .(get_user_class() >= UC_MODERATOR ? " [<a href=\"".$redaktor.".php?action=delete&amp;cid=$row[id]\" class=\"altlink_white\">Удалить</a>]" : "")
		    .($row["editedby"] && get_user_class() >= UC_MODERATOR ? " [<a href=\"".$redaktor.".php?action=vieworiginal&amp;cid=$row[id]\" class=\"altlink_white\">Оригинал</a>]" : "")
		    .(get_user_class() >= UC_MODERATOR ? " IP: ".($row["ip"] ? "<a href=\"usersearch.php?ip=$row[ip]\" class=\"altlink_white\">".$row["ip"]."</a>" : "Неизвестен" ) : "")
		    ."</div>";

		print("<div align=\"right\"><!--<font size=1 class=small>-->Комментарий добавлен: ".$row["added"]." GMT<!--</font>--></td></tr>");
		print("</table><br>");
  }

}

function searchfield($s) {
	return preg_replace(array('/[^a-z0-9]/si', '/^\s*/s', '/\s*$/s', '/\s+/s'), array(" ", "", "", " "), $s);
}

function genrelist() {
	$ret = array();
	$res = sql_query("SELECT id, name FROM categories ORDER BY sort ASC");
	while ($row = mysql_fetch_array($res))
		$ret[] = $row;
	return $ret;
}

function linkcolor($num) {
	if (!$num)
		return "red";
//	if ($num == 1)
//		return "yellow";
	return "green";
}

function ratingpic($num) {
	global $pic_base_url, $tracker_lang;
	$r = round($num * 2) / 2;
	if ($r < 1 || $r > 5)
		return;
	return "<img src=\"$pic_base_url$r.gif\" border=\"0\" alt=\"".$tracker_lang['rating'].": $num / 5\" />";
}

function writecomment($userid, $comment) {
	$res = sql_query("SELECT modcomment FROM users WHERE id = '$userid'") or sqlerr(__FILE__, __LINE__);
	$arr = mysql_fetch_assoc($res);

	$modcomment = date("d-m-Y") . " - " . $comment . "" . ($arr[modcomment] != "" ? "\n" : "") . "$arr[modcomment]";
	$modcom = sqlesc($modcomment);

	return sql_query("UPDATE users SET modcomment = $modcom WHERE id = '$userid'") or sqlerr(__FILE__, __LINE__);
}

function torrenttable($res, $variant = "index", $returnto) {
  $owned = $moderator = 0;
        if (get_user_class() >= UC_MODERATOR)
                $owned = $moderator = 1;
        elseif ($CURUSER["id"] == $row["owner"])
                $owned = 1;
		global $pic_base_url, $CURUSER, $use_wait, $use_ttl, $ttl_days, $tracker_lang;

  if ($use_wait)
  if (($CURUSER["class"] < UC_VIP) && $CURUSER) {
		  $gigs = $CURUSER["uploaded"] / (1024*1024*1024);
		  $ratio = (($CURUSER["downloaded"] > 0) ? ($CURUSER["uploaded"] / $CURUSER["downloaded"]) : 0);
		  if ($ratio < 0.5 || $gigs < 5) $wait = 48;
		  elseif ($ratio < 0.65 || $gigs < 6.5) $wait = 24;
		  elseif ($ratio < 0.8 || $gigs < 8) $wait = 12;
		  elseif ($ratio < 0.95 || $gigs < 9.5) $wait = 6;
		  else $wait = 0;
  }

print("<tr>\n");

// sorting by MarkoStamcar

$count_get = 0;

foreach ($_GET as $get_name => $get_value) {

$get_name = mysql_escape_string(strip_tags(str_replace(array("\"","'"),array("",""),$get_name)));

$get_value = mysql_escape_string(strip_tags(str_replace(array("\"","'"),array("",""),$get_value)));

if ($get_name != "sort" && $get_name != "type") {
if ($count_get > 0) {
$oldlink = $oldlink . "&" . $get_name . "=" . $get_value;
} else {
$oldlink = $oldlink . $get_name . "=" . $get_value;
}
$count_get++;
}

}

if ($count_get > 0) {
$oldlink = $oldlink . "&";
}


if ($_GET['sort'] == "1") {
if ($_GET['type'] == "desc") {
$link1 = "asc";
} else {
$link1 = "desc";
}
}

if ($_GET['sort'] == "2") {
if ($_GET['type'] == "desc") {
$link2 = "asc";
} else {
$link2 = "desc";
}
}

if ($_GET['sort'] == "3") {
if ($_GET['type'] == "desc") {
$link3 = "asc";
} else {
$link3 = "desc";
}
}

if ($_GET['sort'] == "4") {
if ($_GET['type'] == "desc") {
$link4 = "asc";
} else {
$link4 = "desc";
}
}

if ($_GET['sort'] == "5") {
if ($_GET['type'] == "desc") {
$link5 = "asc";
} else {
$link5 = "desc";
}
}

if ($_GET['sort'] == "7") {
if ($_GET['type'] == "desc") {
$link7 = "asc";
} else {
$link7 = "desc";
}
}

if ($_GET['sort'] == "8") {
if ($_GET['type'] == "desc") {
$link8 = "asc";
} else {
$link8 = "desc";
}
}

if ($_GET['sort'] == "9") {
if ($_GET['type'] == "desc") {
$link9 = "asc";
} else {
$link9 = "desc";
}
}

if ($_GET['sort'] == "10") {
if ($_GET['type'] == "desc") {
$link10 = "asc";
} else {
$link10 = "desc";
}
}

if ($link1 == "") { $link1 = "asc"; } // for torrent name
if ($link2 == "") { $link2 = "desc"; }
if ($link3 == "") { $link3 = "desc"; }
if ($link4 == "") { $link4 = "desc"; }
if ($link5 == "") { $link5 = "desc"; }
if ($link7 == "") { $link7 = "desc"; }
if ($link8 == "") { $link8 = "desc"; }
if ($link9 == "") { $link9 = "desc"; }
if ($link10 == "") { $link10 = "desc"; }

?>
<td class="colhead" align="center"><?=$tracker_lang['type'];?></td>
<td class="colhead" align="center">Постер</td>
<td class="colhead" align="left"><a href="browse.php?<? print $oldlink; ?>sort=1&type=<? print $link1; ?>" class="altlink_white"><?=$tracker_lang['name'];?></a> / <a href="browse.php?<? print $oldlink; ?>sort=4&type=<? print $link4; ?>" class="altlink_white"><?=$tracker_lang['added'];?></a></td>
<!--<td class="heading" align="left">DL</td>-->
<?
if ($wait)
	print("<td class=\"colhead\" align=\"center\">".$tracker_lang['wait']."</td>\n");

if ($variant == "mytorrents")
	print("<td class=\"colhead\" align=\"center\">".$tracker_lang['visible']."</td>\n");


?>
<td class="colhead" align="center"><a href="browse.php?<? print $oldlink; ?>sort=2&type=<? print $link2; ?>" class="altlink_white"><?=$tracker_lang['files'];?></a></td>
<td class="colhead" align="center"><a href="browse.php?<? print $oldlink; ?>sort=3&type=<? print $link3; ?>" class="altlink_white"><?=$tracker_lang['comments'];?></a></td>
<? if ($use_ttl) {
?>
	<td class="colhead" align="center"><?=$tracker_lang['ttl'];?></td>
<?
}
?>
<td class="colhead" align="center"><a href="browse.php?<? print $oldlink; ?>sort=5&type=<? print $link5; ?>" class="altlink_white"><?=$tracker_lang['size'];?></a></td>
<!--
<td class="colhead" align="right">Views</td>
<td class="colhead" align="right">Hits</td>
-->
<td class="colhead" align="center"><a href="browse.php?<? print $oldlink; ?>sort=7&type=<? print $link7; ?>" class="altlink_white"><?=$tracker_lang['seeds'];?></a>|<a href="browse.php?<? print $oldlink; ?>sort=8&type=<? print $link8; ?>" class="altlink_white"><?=$tracker_lang['leechers'];?></a></td>
<?

if ($variant == "index" || $variant == "bookmarks")
	print("<td class=\"colhead\" align=\"center\"><a href=\"browse.php?{$oldlink}sort=9&type={$link9}\" class=\"altlink_white\">".$tracker_lang['uploadeder']."</a></td>\n");

if ((get_user_class() >= UC_MODERATOR) && $variant == "index")
	print("<td class=\"colhead\" align=\"center\"><a href=\"browse.php?{$oldlink}sort=10&type={$link10}\" class=\"altlink_white\">Изменен</td>");

if ($variant == "bookmarks")
	print("<td class=\"colhead\" align=\"center\">".$tracker_lang['delete']."</td>\n");

print("</tr>\n");

print("<tbody id=\"highlighted\">");

	if ($variant == "bookmarks")
		print ("<form method=\"post\" action=\"takedelbookmark.php\">");

	while ($row = mysql_fetch_assoc($res)) {
		$id = $row["id"];
		print("<tr".($row["sticky"] == "yes" ? " class=\"highlight\"" : "").">\n");
    print("<td align=\"center\" style=\"padding: 0pc\">");
    				if (isset($row["cat_name"]))
		print("<a href=\"browse.php?cat=" . $row["category"] . "\">".$row['cat_name']."</a>\n");
		print("</td>\n");
		
		print("<td align=\"center\" style=\"padding: 0px\">");

		if (isset($row["name"])) {
			print("<a href=\"details.php?id=" . $id . "&amp;hit=1\">");
			if (isset($row["image1"]) && $row["image1"] != "")
				print("<img border=\"0\" src=\"thumbnail.php?image=" . $row["image1"] . "&for=browse\" alt=\"" . $row["name"] . "\" />");
			else
				print($row["name"]);
			print("</a>");
		}
		else
			print("-");
		print("</td>\n");

		$dispname = $row["name"];
		$thisisfree = ($row[free]=="yes" ? "<img src=\"pic/freedownload.gif\" title=\"".$tracker_lang['golden']."\" alt=\"".$tracker_lang['golden']."\">" : "");
		print("<td align=\"left\">".($row["sticky"] == "yes" ? "Важный: " : "")."<a href=\"details.php?");
		if ($variant == "mytorrents")
			print("returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;");
		print("id=$id");
		if ($variant == "index" || $variant == "bookmarks")
			print("&amp;hit=1");
		print("\"><b>$dispname</b></a> $thisisfree\n");

			if ($variant != "bookmarks" && $CURUSER)
				print("<a href=\"bookmark.php?torrent=$row[id]\"><img border=\"0\" src=\"pic/bookmark.gif\" alt=\"".$tracker_lang['bookmark_this']."\" title=\"".$tracker_lang['bookmark_this']."\" /></a>\n");

			print("<a href=\"download.php?id=$id&amp;name=" . rawurlencode($row["filename"]) . "\"><img src=\"pic/download.gif\" border=\"0\" alt=\"".$tracker_lang['download']."\" title=\"".$tracker_lang['download']."\"></a>\n");

		if ($CURUSER["id"] == $row["owner"] || get_user_class() >= UC_MODERATOR)
			$owned = 1;
		else
			$owned = 0;

				if ($owned)
			print("<a href=\"edit.php?id=$row[id]\"><img border=\"0\" src=\"pic/pen.gif\" alt=\"".$tracker_lang['edit']."\" title=\"".$tracker_lang['edit']."\" /></a>\n");

			   if ($row["readtorrent"] == 0 && $variant == "index")
				   print ("<b><font color=\"red\" size=\"1\">[новый]</font></b>");

			print("<br /><i>".$row["added"]."</i> ");
////////////////////////////////////////////////////////////////////////////////////
                                                 $s = "";
                                  if (!isset($row["rating"])) {
                        if ($minvotes > 1) {
                                $s = sprintf($tracker_lang['not_enough_votes'], $minvotes);
                                if ($row["numratings"])
                                        $s .= sprintf($tracker_lang['only_votes'], $row["numratings"]);
                                else
                                        $s .= $tracker_lang['none_voted'];
                                $s .= ")";
                        }
                        else
                                $s .= $tracker_lang['no_votes'];
                }
                else {
                        $rpic = ratingpic($row["rating"]);
                        if (!isset($rpic))
                                $s .= "invalid?";
                        else
                                $s .= "$rpic (" . $row["rating"] . " ".$tracker_lang['from']." 5)";
                }
                $s .= "\n";
                if (!isset($CURUSER))
                        $s .= "(<a href=\"login.php?returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;nowarn=1\">Log in</a> to rate it)";
                else {
                        $ratings = array(
                                        5 => $tracker_lang['vote_5'],
                                        4 => $tracker_lang['vote_4'],
                                        3 => $tracker_lang['vote_3'],
                                        2 => $tracker_lang['vote_2'],
                                        1 => $tracker_lang['vote_1'],
                        );
                        if (!$owned || $moderator) {
                                $xres = sql_query("SELECT rating, added FROM ratings WHERE torrent = $id AND user = " . $CURUSER["id"]);
                                $xrow = mysql_fetch_array($xres);
                                if ($xrow)
                                        $s .= "(".$tracker_lang['you_have_voted_for_this_torrent']." \"" . $xrow["rating"] . " - " . $ratings[$xrow["rating"]] . "\")";
                                else {
                                       $s .= "<form method=\"post\" action=\"takerate.php?returnto=".urlencode(basename($_SERVER["REQUEST_URI"]))."\"><input type=\"hidden\" name=\"id\" value=\"$id\" />\n";
                                        $s .= "<select name=\"rating\">\n";
                                        $s .= "<option value=\"0\">".$tracker_lang['vote']."</option>\n";
                                        foreach ($ratings as $k => $v) {
                                                $s .= "<option value=\"$k\">$k - $v</option>\n";
                                        }
                                        $s .= "</select>\n";
                                        $s .= "<input type=\"submit\" value=\"".$tracker_lang['vote']."!\" />";
                                        $s .= "</form>\n";
                                }
                        }
                }
                print ($tracker_lang['rating']." : ".$s);
                
								if ($wait)
								{
								  $elapsed = floor((gmtime() - strtotime($row["added"])) / 3600);
				if ($elapsed < $wait)
				{
				  $color = dechex(floor(127*($wait - $elapsed)/48 + 128)*65536);
				  print("<td align=\"center\"><nobr><a href=\"faq.php#dl8\"><font color=\"$color\">" . number_format($wait - $elapsed) . " h</font></a></nobr></td>\n");
				}
				else
				  print("<td align=\"center\"><nobr>".$tracker_lang['no']."</nobr></td>\n");
		}

	print("</td>\n");

		if ($variant == "mytorrents") {
			print("<td align=\"right\">");
			if ($row["visible"] == "no")
				print("<font color=\"red\"><b>".$tracker_lang['no']."</b></font>");
			else
				print("<font color=\"green\">".$tracker_lang['yes']."</font>");
			print("</td>\n");
		}

		if ($row["type"] == "single")
			print("<td align=\"right\">" . $row["numfiles"] . "</td>\n");
		else {
			if ($variant == "index")
				print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;hit=1&amp;filelist=1\">" . $row["numfiles"] . "</a></b></td>\n");
			else
				print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;filelist=1#filelist\">" . $row["numfiles"] . "</a></b></td>\n");
		}

		if (!$row["comments"])
			print("<td align=\"right\">" . $row["comments"] . "</td>\n");
		else {
			if ($variant == "index")
				print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;hit=1&amp;tocomm=1\">" . $row["comments"] . "</a></b></td>\n");
			else
				print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;page=0#startcomments\">" . $row["comments"] . "</a></b></td>\n");
		}

//		print("<td align=center><nobr>" . str_replace(" ", "<br />", $row["added"]) . "</nobr></td>\n");
				$ttl = ($ttl_days*24) - floor((gmtime() - sql_timestamp_to_unix_timestamp($row["added"])) / 3600);
				if ($ttl == 1) $ttl .= " час"; else $ttl .= "&nbsp;часов";
		if ($use_ttl)
			print("<td align=\"center\">$ttl</td>\n");
		print("<td align=\"center\">" . str_replace(" ", "<br />", mksize($row["size"])) . "</td>\n");
//		print("<td align=\"right\">" . $row["views"] . "</td>\n");
//		print("<td align=\"right\">" . $row["hits"] . "</td>\n");

		print("<td align=\"center\">");
               if ($row["filename"] != 'nofile') {
		if ($row["seeders"]) {
			if ($variant == "index")
			{
			   if ($row["leechers"]) $ratio = $row["seeders"] / $row["leechers"]; else $ratio = 1;
				print("<b><a href=\"details.php?id=$id&amp;hit=1&amp;toseeders=1\"><font color=" .
				  get_slr_color($ratio) . ">" . $row["seeders"] . "</font></a></b>\n");
			}
			else
				print("<b><a class=\"" . linkcolor($row["seeders"]) . "\" href=\"details.php?id=$id&amp;dllist=1#seeders\">" .
				  $row["seeders"] . "</a></b>\n");
		}
		else
			print("<span class=\"" . linkcolor($row["seeders"]) . "\">" . $row["seeders"] . "</span>");

		print(" | ");

		if ($row["leechers"]) {
			if ($variant == "index")
				print("<b><a href=\"details.php?id=$id&amp;hit=1&amp;todlers=1\">" .
				   number_format($row["leechers"]) . ($peerlink ? "</a>" : "") .
				   "</b>\n");
			else
				print("<b><a class=\"" . linkcolor($row["leechers"]) . "\" href=\"details.php?id=$id&amp;dllist=1#leechers\">" .
				  $row["leechers"] . "</a></b>\n");
		}
		else
			print("0\n");
                } else print("<b>N/A</b>\n");
		print("</td>");

		if ($variant == "index" || $variant == "bookmarks")
			print("<td align=\"center\">" . (isset($row["username"]) ? ("<a href=\"userdetails.php?id=" . $row["owner"] . "\"><b>" . get_user_class_color($row["class"], htmlspecialchars_uni($row["username"])) . "</b></a>") : "<i>(unknown)</i>") . "</td>\n");

		if ($variant == "bookmarks")
			print ("<td align=\"center\"><input type=\"checkbox\" name=\"delbookmark[]\" value=\"" . $row[bookmarkid] . "\" /></td>");

		if ((get_user_class() >= UC_MODERATOR) && $variant == "index") {
			if ($row["moderated"] == "no")
				print("<td align=\"center\"><font color=\"red\"><b>Нет</b></font></td>\n");
			else
				print("<td align=\"center\"><a href=\"userdetails.php?id=$row[moderatedby]\"><font color=\"green\"><b>Да</b></font></a></td>\n");
		}

	print("</tr>\n");

	}

	print("</tbody>");

	if ($variant == "index" && $CURUSER)
		print("<tr><td class=\"colhead\" colspan=\"12\" align=\"center\"><a href=\"markread.php\" class=\"altlink_white\">Все торренты прочитаны</a></td></tr>");

	//print("</table>\n");

	if ($variant == "bookmarks")
		print("<tr><td colspan=\"12\" align=\"right\"><input type=\"submit\" value=\"".$tracker_lang['delete']."\"></td></tr>\n");

	if ($variant == "index" || $variant == "bookmarks") {
		if (get_user_class() >= UC_MODERATOR) {
			print("</form>\n");
		}
	}

	return $rows;
}

function hash_pad($hash) {
	return str_pad($hash, 20);
}

function hash_where($name, $hash) {
	$shhash = preg_replace('/ *$/s', "", $hash);
	return "($name = " . sqlesc($hash) . " OR $name = " . sqlesc($shhash) . ")";
}

function get_user_icons($arr, $big = false) {
		if ($big) {
				$donorpic = "starbig.gif";
				$warnedpic = "warnedbig.gif";
				$disabledpic = "disabledbig.gif";
				$style = "style='margin-left: 4pt'";
		} else {
				$donorpic = "star.gif";
				$warnedpic = "warned.gif";
				$disabledpic = "disabled.gif";
				$parkedpic = "parked.gif";
				$style = "style=\"margin-left: 2pt\"";
		}
		$pics = $arr["donor"] == "yes" ? "<img src=\"pic/$donorpic\" alt='Donor' border=\"0\" $style>" : "";
		if ($arr["enabled"] == "yes")
				$pics .= $arr["warned"] == "yes" ? "<img src=pic/$warnedpic alt=\"Warned\" border=0 $style>" : "";
		else
				$pics .= "<img src=\"pic/$disabledpic\" alt=\"Disabled\" border=\"0\" $style>\n";
		$pics .= $arr["parked"] == "yes" ? "<img src=pic/$parkedpic alt=\"Parked\" border=\"0\" $style>" : "";
		return $pics;
}

function parked() {
	   global $CURUSER;
	   if ($CURUSER["parked"] == "yes")
		  stderr($tracker_lang['error'], "Ваш аккаунт припаркован.");
}

function mysql_modified_rows () {
	$info_str = mysql_info();
	$a_rows = mysql_affected_rows();
	ereg("Rows matched: ([0-9]*)", $info_str, $r_matched);
	return ($a_rows < 1)?($r_matched[1]?$r_matched[1]:0):$a_rows;
}

define ("BETA_NOTICE", "\n<br />This isn't complete release of source!");
define("RELVERSION","1.60");
// This is original copyright, please leave it alone. Developers worked high for weeks to present this source. Don't be evil (C) Google
// Не удаляйте оригинальный копирайт. Помните, что разработчики неделями трудились над этим движком. Не будьте дьяволом (С) Гугл
define ("TBVERSION", (YOURCOPY?YOURCOPY.". ":"")."Powered by <a href=\"http://www.kinokpk.com\">Kinokpk.com</a> releaser v. ".RELVERSION." &copy; 2008-".date("Y").". <a href=\"http://dev.kinokpk.com\">Developer's corner</a> of this source.");

?>
