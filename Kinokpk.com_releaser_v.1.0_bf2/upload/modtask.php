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

require "include/bittorrent.php";

dbconn(false);

loggedinorreturn();

function puke($text = "You have forgotten here someting?") {
	global $tracker_lang;
	stderr($tracker_lang['error'], $text);
}

function barf($text = "Пользователь удален") {
	global $tracker_lang;
	stderr($tracker_lang['success'], $text);
}

if (get_user_class() < UC_MODERATOR)
	puke($tracker_lang['access_denied']);

$action = $_POST["action"];

if ($action == "edituser") {
	$userid = $_POST["userid"];
	$title = $_POST["title"];
	$avatar = $_POST["avatar"];
	// Check remote avatar size
	if ($avatar) {
		if (!preg_match('#^((http)|(ftp):\/\/[a-zA-Z0-9\-]+?\.([a-zA-Z0-9\-]+\.)+[a-zA-Z]+(:[0-9]+)*\/.*?\.(gif|jpg|jpeg|png)$)#is', $avatar))
						stderr($tracker_lang['error'], $tracker_lang['avatar_adress_invalid']);
		if(!(list($width, $height) = getimagesize($avatar)))
						stderr($tracker_lang['error'], $tracker_lang['avatar_adress_invalid']);
		if ($width > $avatar_max_width || $height > $avatar_max_height)
						stderr($tracker_lang['error'], sprintf($tracker_lang['avatar_is_too_big'], $avatar_max_width, $avatar_max_height));
	}
// Check remote avatar size
	$resetb = $_POST["resetb"];
	$birthday = ($resetb=='yes'?", birthday = '0000-00-00'":"");
	$enabled = $_POST["enabled"];
	$warned = $_POST["warned"];
	$warnlength = 0 + $_POST["warnlength"];
	$warnpm = $_POST["warnpm"];
	$donor = $_POST["donor"];
	$uploadtoadd = $_POST["amountup"];
	$downloadtoadd=  $_POST["amountdown"];
	$formatup = $_POST["formatup"];
	$formatdown = $_POST["formatdown"];
	$mpup = $_POST["upchange"];
	$mpdown = $_POST["downchange"];
	$support = $_POST["support"];
	$supportfor = htmlspecialchars($_POST["supportfor"]);
	$modcomm = htmlspecialchars($_POST["modcomm"]);
	$deluser = $_POST["deluser"];

	$class = 0 + $_POST["class"];
	if (!is_valid_id($userid) || !is_valid_user_class($class))
		stderr($tracker_lang['error'], "Неверный идентификатор пользователя или класса.");
	// check target user class
 $res = sql_query("SELECT warned, enabled, username, class, modcomment, uploaded, downloaded, num_warned FROM users WHERE id = $userid") or sqlerr(__FILE__, __LINE__);
	$arr = mysql_fetch_assoc($res) or puke("Ошибка MySQL: " . mysql_error());
	$curenabled = $arr["enabled"];
	$curclass = $arr["class"];
	$curwarned = $arr["warned"];
	if (get_user_class() == UC_SYSOP)
		$modcomment = $_POST["modcomment"];
	else
		$modcomment = $arr["modcomment"];
	// User may not edit someone with same or higher class than himself!
	if ($curclass >= get_user_class() || $class >= get_user_class())
		puke("Так нельзя делать!");

	if($uploadtoadd > 0) {
		if ($mpup == "plus")
			$newupload = $arr["uploaded"] + ($formatup == mb ? ($uploadtoadd * 1048576) : ($uploadtoadd * 1073741824));
		else
			$newupload = $arr["uploaded"] - ($formatup == mb ? ($uploadtoadd * 1048576) : ($uploadtoadd * 1073741824));
		if ($newupload < 0)
			stderr($tracker_lang['error'], "Вы хотите отнять у пользователя отданого больше чем у него есть!");
		$updateset[] = "uploaded = $newupload";
		$modcomment = date("Y-m-d") . " - Пользователь $CURUSER[username] ".($mpup == "plus" ? "добавил " : "отнял ").$uploadtoadd.($formatup == mb ? " MB" : " GB")." к раздаче.\n". $modcomment;
	}

	if($downloadtoadd > 0) {
		if ($mpdown == "plus")
			$newdownload = $arr["downloaded"] + ($formatdown == mb ? ($downloadtoadd * 1048576) : ($downloadtoadd * 1073741824));
		else
			$newdownload = $arr["downloaded"] - ($formatdown == mb ? ($downloadtoadd * 1048576) : ($downloadtoadd * 1073741824));
		if ($newdownload < 0)
			stderr($tracker_lang['error'], "Вы хотите отнять у пользователя скачаного больше чем у него есть!");
		$updateset[] = "downloaded = $newdownload";
		$modcomment = date("Y-m-d") . " - Пользователь $CURUSER[username] ".($mpdown == "plus" ? "добавил " : "отнял ").$downloadtoadd.($formatdown == mb ? " MB" : " GB")." к скачаному.\n". $modcomment;
	}

	if ($curclass != $class) {
		// Notify user
		$what = ($class > $curclass ? "повышены" : "понижены");
		$msg = sqlesc("Вы были $what до класса \"" . get_user_class_name($class) . "\" пользователем $CURUSER[username].");
		$added = sqlesc(get_date_time());
		$subject = sqlesc("Вы были $what");
		sql_query("INSERT INTO messages (sender, receiver, msg, added, subject) VALUES(0, $userid, $msg, $added, $subject)") or sqlerr(__FILE__, __LINE__);
		$updateset[] = "class = $class";
		$what = ($class > $curclass ? "Повышен" : "Пониженен");
 		$modcomment = date("Y-m-d") . " - $what до класса \"" . get_user_class_name($class) . "\" пользователем $CURUSER[username].\n". $modcomment;
	}

	// some Helshad fun
	// $fun = ($CURUSER['id'] == 277) ? " Tremble in fear, mortal." : "";
  $num_warned = 1 + $arr["num_warned"]; //мод кол-ва предупреждений
	if ($warned && $curwarned != $warned) {
		$updateset[] = "warned = " . sqlesc($warned);
		$updateset[] = "warneduntil = '0000-00-00 00:00:00'";
		$subject = sqlesc("Ваше предупреждение снято");
		if ($warned == 'no')
		{
			$modcomment = date("Y-m-d") . " - Предупреждение снял пользователь " . $CURUSER['username'] . ".\n". $modcomment;
			$msg = sqlesc("Ваше предупреждение снял пользователь " . $CURUSER['username'] . ".");
		}
		$added = sqlesc(get_date_time());
		sql_query("INSERT INTO messages (sender, receiver, msg, added, subject) VALUES (0, $userid, $msg, $added, $subject)") or sqlerr(__FILE__, __LINE__);
	} elseif ($warnlength) {
		if (strlen($warnpm) == 0)
			stderr($tracker_lang['error'], "Вы должны указать причину по которой ставите предупреждение!");
		if ($warnlength == 255) {
			$modcomment = date("Y-m-d") . " - Предупрежден пользователем " . $CURUSER['username'] . ".\nПричина: $warnpm\n" . $modcomment;
			$msg = sqlesc("Вы получили [url=rules.php#warning]предупреждение[/url] на неограниченый срок от $CURUSER[username]" . ($warnpm ? "\n\nПричина: $warnpm" : ""));
			$updateset[] = "warneduntil = '0000-00-00 00:00:00'";
			$updateset[] = "num_warned = '$num_warned'";
		} else {
			$warneduntil = get_date_time(gmtime() + $warnlength * 604800);
			$dur = $warnlength . " недел" . ($warnlength > 1 ? "и" : "ю");
			$msg = sqlesc("Вы получили [url=rules.php#warning]предупреждение[/url] на $dur от пользователя " . $CURUSER['username'] . ($warnpm ? "\n\nПричина: $warnpm" : ""));
			$modcomment = date("Y-m-d") . " - Предупрежден на $dur пользователем " . $CURUSER['username'] .	".\nПричина: $warnpm\n" . $modcomment;
			$updateset[] = "warneduntil = '$warneduntil'";
			$updateset[] = "num_warned = '$num_warned'";
		}
 		$added = sqlesc(get_date_time());
 		$subject = sqlesc("Вы получили предупреждение");
		sql_query("INSERT INTO messages (sender, receiver, msg, added, subject) VALUES (0, $userid, $msg, $added, $subject)") or sqlerr(__FILE__, __LINE__);
		$updateset[] = "warned = 'yes'";
	}

	if ($enabled != $curenabled) {
		$modifier = (int) $CURUSER['id'];
		if ($enabled == 'yes') {
			$nowdate = sqlesc(get_date_time());
			if (!isset($_POST["enareason"]) || empty($_POST["enareason"]))
				puke("Введите причину почему вы включаете пользователя!");
			$enareason = htmlspecialchars($_POST["enareason"]);
			$modcomment = date("Y-m-d") . " - Включен пользователем " . $CURUSER['username'] . ".\nПричина: $enareason\n" . $modcomment;

			}
	}

	$updateset[] = "enabled = " . sqlesc($enabled);
	$updateset[] = "donor = " . sqlesc($donor);
	$updateset[] = "supportfor = " . sqlesc($supportfor);
	$updateset[] = "support = " . sqlesc($support);
	$updateset[] = "avatar = " . sqlesc($avatar);
	$updateset[] = "title = " . sqlesc($title);
	if (!empty($modcomm))
		$modcomment = date("Y-m-d") . " - Заметка от $CURUSER[username]: $modcomm\n" . $modcomment;
	$updateset[] = "modcomment = " . sqlesc($modcomment);
	if ($_POST['resetkey']) {
		$passkey = md5($CURUSER['username'].get_date_time().$CURUSER['passhash']);
		$updateset[] = "passkey = " . sqlesc($passkey);
	}
	sql_query("UPDATE users SET	" . implode(", ", $updateset) . " $birthday WHERE id = $userid") or sqlerr(__FILE__, __LINE__);
	if (!empty($_POST["deluser"])) {
		$res=@sql_query("SELECT * FROM users WHERE id = $userid") or sqlerr(__FILE__, __LINE__);
		$user = mysql_fetch_array($res);
		$username = $user["username"];
		$email=$user["email"];
		sql_query("DELETE FROM users WHERE id = $userid") or sqlerr(__FILE__, __LINE__);
		sql_query("DELETE FROM messages WHERE receiver = $userid") or sqlerr(__FILE__,__LINE__);
		sql_query("DELETE FROM friends WHERE userid = $userid") or sqlerr(__FILE__,__LINE__);
		sql_query("DELETE FROM friends WHERE friendid = $userid") or sqlerr(__FILE__,__LINE__);
		sql_query("DELETE FROM blocks WHERE userid = $userid") or sqlerr(__FILE__,__LINE__);
		sql_query("DELETE FROM blocks WHERE blockid = $userid") or sqlerr(__FILE__,__LINE__);
		sql_query("DELETE FROM bookmarks WHERE userid = $userid") or sqlerr(__FILE__,__LINE__);
		sql_query("DELETE FROM invites WHERE inviter = $userid") or sqlerr(__FILE__,__LINE__);
		sql_query("DELETE FROM peers WHERE userid = $userid") or sqlerr(__FILE__,__LINE__);
		sql_query("DELETE FROM readtorrents WHERE userid = $userid") or sqlerr(__FILE__,__LINE__);
		sql_query("DELETE FROM simpaty WHERE fromuserid = $userid") or sqlerr(__FILE__,__LINE__);
		sql_query("DELETE FROM addedrequests WHERE userid = $userid") or sqlerr(__FILE__,__LINE__);
		sql_query("DELETE FROM checkcomm WHERE userid = $userid") or sqlerr(__FILE__,__LINE__);
		sql_query("DELETE FROM offervotes WHERE userid = $userid") or sqlerr(__FILE__,__LINE__);
		sql_query("DELETE FROM sessions WHERE uid = $userid") or sqlerr(__FILE__,__LINE__);
		$deluserid=$CURUSER["username"];
		write_log("Пользователь $username был удален пользователем $deluserid");
		barf();
	} else {
		$returnto = htmlentities($_POST["returnto"]);
		header("Refresh: 0; url=$DEFAULTBASEURL/$returnto");
		die;
	}
} elseif ($action == "confirmuser") {
	$userid = $_POST["userid"];
	$confirm = $_POST["confirm"];
	if (!is_valid_id($userid))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	$updateset[] = "status = " . sqlesc($confirm);
	$updateset[] = "last_login = ".sqlesc(get_date_time());
	$updateset[] = "last_access = ".sqlesc(get_date_time());
	//print("UPDATE users SET " . implode(", ", $updateset) . " WHERE id=$userid");
	sql_query("UPDATE users SET " . implode(", ", $updateset) . " WHERE id = $userid") or sqlerr(__FILE__, __LINE__);
	$returnto = htmlentities($_POST["returnto"]);

	header("Location: $DEFAULTBASEURL/$returnto");
}

puke();

?>