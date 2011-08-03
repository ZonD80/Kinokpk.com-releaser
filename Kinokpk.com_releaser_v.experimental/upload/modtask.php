<?php
/**
 * Profile edit parser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";

INIT();

loggedinorreturn();

function puke($text = "You have forgotten here someting?") {
	global $REL_LANG;
	stderr($REL_LANG->say_by_key('error'), $text);
}

function barf($text = "Пользователь удален") {
	global $REL_LANG;
	stderr($REL_LANG->say_by_key('success'), $text);
}

get_privilege('edit_users');

$action = (string)$_POST["action"];

if (($action == 'ownsupport') && get_privilege('ownsupport',false)) {
	$supportfor = ($_POST["support"]?htmlspecialchars((string)$_POST["supportfor"]):'');
	$updateset[] = "supportfor = " . sqlesc($supportfor);
	sql_query("UPDATE users SET " . implode(", ", $updateset) . " WHERE id = {$CURUSER['id']}") or sqlerr(__FILE__, __LINE__);
	safe_redirect($REL_SEO->make_link('my'),0);
	stderr($REL_LANG->say_by_key('success'),'Вы успешно сменили себе статус поддержки','success');

}
elseif (($action == 'delnick') && get_privilege('ownsupport',false)) {
	$nid = (int)$_POST['id'];
	$REL_DB->query("DELETE FROM nickhistory WHERE id=$nid");
	$REL_TPL->stderr($REL_LANG->_('Success'),$REL_LANG->_('Nick deleted from history'));
}
elseif ($action == "edituser") {
	$userid = (int) $_POST["userid"];
	$CLASS = @mysql_result(sql_query("SELECT class FROM users WHERE id = $userid"),0);
	if (get_class_priority($CLASS) >= get_class_priority(get_user_class())) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('access_denied'));

	$title = $_POST["title"];
	$avatar = (int)$_POST["avatar"];
	$resetb = $_POST["resetb"];
	$birthday = ($resetb?", birthday = '0000-00-00'":"");
	$enabled = ((!isset($_POST["enabled"]) || $_POST["enabled"])?1:0);
	$dis_reason = htmlspecialchars($_POST['disreason']);
	$warned = $_POST["warned"]?1:0;
	$warnlength = (int) $_POST["warnlength"];
	$warnpm = $_POST["warnpm"];
	$donor = $_POST["donor"];
	$uploadtoadd = (int)$_POST["amountup"];
	$downloadtoadd=  (int)$_POST["amountdown"];
	$ratingtoadd=  (int)$_POST["amountrating"];
	$discounttoadd=  (int)$_POST["amountdiscount"];
	$formatup = $_POST["formatup"];
	$formatdown = $_POST["formatdown"];
	$mpup = $_POST["upchange"];
	$rch = $_POST["ratingchange"];
	$dch = $_POST['discountchange'];
	$mpdown = $_POST["downchange"];
	$supportfor = ($_POST["support"]?htmlspecialchars($_POST["supportfor"]):'');
	$deluser = $_POST["deluser"];

	$privileges = (array)$_POST['privileges'];

	$priority = get_class_priority();
	$priority2 = get_class_priority($userid);
	$classes = init_class_array();
	foreach ($classes as $cid=>$cl)
	if ($cl['priority']<=$priority2||$cl['priority']>=$priority||!is_int($cid)) unset($classes[$cid]); else $classes[$cid] = "FIND_IN_SET($cid,classes_allowed)";

	$privsdata = $REL_DB->query_return("SELECT name FROM privileges WHERE ".implode(' OR ',$classes));
	foreach ($privsdata as $p) {
		$privs[] = $p['name'];
	}
	foreach ($privileges as $pid=>$priv) {
		if (!in_array($priv,$privs)) unset ($privileges[$pid]);
	}
	
	$updateset[] = "custom_privileges = ".sqlesc(implode(',',$privileges));


	if ($ratingtoadd > 0) $updateset[] = 'ratingsum = ratingsum'.($rch=='plus'?'+':'-').$ratingtoadd;
	if ($discounttoadd > 0) $updateset[] = 'discount = discount'.($dch=='plus'?'+':'-').$discounttoadd;

	$class = (int) $_POST["class"];
	if (!is_valid_id($userid) || !is_valid_user_class($class))
	stderr($REL_LANG->say_by_key('error'), "Неверный идентификатор пользователя или класса.");
	// check target user class
	$res = sql_query("SELECT warned, enabled, donor, username, class, modcomment, num_warned, avatar,id FROM users WHERE id = $userid") or sqlerr(__FILE__, __LINE__);
	$arr = mysql_fetch_assoc($res) or puke("Ошибка MySQL: " . mysql_error());
	if ($avatar)
	{
		@unlink (ROOT_PATH."avatars/".$arr['avatar']);
		$updateset[] = "avatar = ''";

	}
	$curenabled = $arr["enabled"];
	$curclass = $arr["class"];
	$curwarned = $arr["warned"];
	if (get_privilege('add_comments_to_user',false))
	$modcomment = (string)($_POST["modcomment"]);
	else
	$modcomment = $arr["modcomment"];
	// User may not edit someone with same or higher class than himself!
	if (get_class_priority($curclass) >= get_class_priority(get_user_class()) || get_class_priority($class) >= get_class_priority(get_user_class()))
	puke("Так нельзя делать!");

	if ($curclass != $class) {
		// Notify user
		$what = ($class > $curclass ? "повышены" : "понижены");
		$msg = sqlesc("Вы были $what до класса \"" . get_user_class_name($class) . "\" пользователем $CURUSER[username].");
		$added = sqlesc(time());
		$subject = sqlesc("Вы были $what");
		sql_query("INSERT INTO messages (sender, receiver, msg, added, subject) VALUES(0, $userid, $msg, $added, $subject)") or sqlerr(__FILE__, __LINE__);
		$updateset[] = "class = $class";
		$what = ($class > $curclass ? "Повышен" : "Пониженен");
		$modcomment = date("Y-m-d") . " - $what до класса \"" . get_user_class_name($class) . "\" пользователем $CURUSER[username].\n". $modcomment;
	}

	// some Helshad fun
	// $fun = ($CURUSER['id'] == 277) ? " Tremble in fear, mortal." : "";
	$num_warned = 1 + $arr["num_warned"]; //мод кол-ва предупреждений
	if ($curwarned != $warned) {
		$updateset[] = "warned = 0";
		$updateset[] = "warneduntil = 0";
		$subject = sqlesc("Ваше предупреждение снято");
		if (!$warned)
		{
			$modcomment = date("Y-m-d") . " - Предупреждение снял пользователь " . $CURUSER['username'] . ".\n". $modcomment;
			$msg = sqlesc("Ваше предупреждение снял пользователь " . $CURUSER['username'] . ".");
		}
		$added = sqlesc(time());
		sql_query("INSERT INTO messages (sender, receiver, msg, added, subject) VALUES (0, $userid, $msg, $added, $subject)") or sqlerr(__FILE__, __LINE__);
	} elseif ($warnlength) {
		if (mb_strlen($warnpm) == 0)
		stderr($REL_LANG->say_by_key('error'), "Вы должны указать причину по которой ставите предупреждение!");
		if ($warnlength == 255) {
			$modcomment = date("Y-m-d") . " - Предупрежден пользователем " . $CURUSER['username'] . ".\nПричина: $warnpm\n" . $modcomment;
			$msg = sqlesc("Вы получили <a href=\"".$REL_SEO->make_link('rules')."#warning\">предупреждение</a> на неограниченый срок от $CURUSER[username]" . ($warnpm ? "\n\nПричина: $warnpm" : ""));
			$updateset[] = "warneduntil = 0";
			$updateset[] = "num_warned = $num_warned";
		} else {
			$warneduntil = (time() + $warnlength * 604800);
			$dur = $warnlength . " недел" . ($warnlength > 1 ? "и" : "ю");
			$msg = sqlesc("Вы получили <a href=\"".$REL_SEO->make_link('rules')."#warning\">предупреждение</a> на $dur от пользователя " . $CURUSER['username'] . ($warnpm ? "\n\nПричина: $warnpm" : ""));
			$modcomment = date("Y-m-d") . " - Предупрежден на $dur пользователем " . $CURUSER['username'] .	".\nПричина: $warnpm\n" . $modcomment;
			$updateset[] = "warneduntil = $warneduntil";
			$updateset[] = "num_warned = $num_warned";
		}
		$added = sqlesc(time());
		$subject = sqlesc("Вы получили предупреждение");
		sql_query("INSERT INTO messages (sender, receiver, msg, added, subject) VALUES (0, $userid, $msg, $added, $subject)") or sqlerr(__FILE__, __LINE__);
		$updateset[] = "warned = 1";
	}

	if ($enabled != $curenabled) {
		$modifier = (int) $CURUSER['id'];
		if ($enabled) {
			if (!isset($_POST["enareason"]) || empty($_POST["enareason"]))
			puke("Введите причину почему вы включаете пользователя!");
			$enareason = htmlspecialchars($_POST["enareason"]);
			$modcomment = date("Y-m-d") . " - Включен пользователем " . $CURUSER['username'] . ".\nПричина: $enareason\n" . $modcomment;

		} else   {
			if (empty($dis_reason))
			puke("Введите причину почему вы отключаете пользователя!");
			$modcomment = date("Y-m-d") . " - Выключен пользователем " . $CURUSER['username'] . ".\nПричина: $dis_reason\n" . $modcomment;

		}
	}

	$updateset[] = "enabled = " . $enabled;
	if ($dis_reason) $updateset[] = "dis_reason = ".sqlesc($dis_reason);
	$updateset[] = "donor = " . sqlesc($donor);
	$updateset[] = "supportfor = " . sqlesc($supportfor);
	//$updateset[] = "support = " . sqlesc($support);
	$updateset[] = "title = " . sqlesc($title);
	$updateset[] = "modcomment = " . sqlesc($modcomment);
	if ($_POST['resetkey']) {
		$passkey = md5($CURUSER['username'].time().$CURUSER['passhash']);
		$REL_DB->query("UPDATE xbt_users SET torrent_pass='' WHERE uid=".sqlesc($CURUSER[id]));
	}
	write_log("Пользователь {$CURUSER['username']} произвел действия над пользователем ".make_user_link($arr).", параметры:<br/> <pre>".var_export($updateset,true)."</pre>",'modtask');
	sql_query("UPDATE users SET	" . implode(", ", $updateset) . " $birthday WHERE id = $userid") or sqlerr(__FILE__, __LINE__);

	if (!empty($_POST["deluser"])) {
		$res=@sql_query("SELECT * FROM users WHERE id = $userid") or sqlerr(__FILE__, __LINE__);
		$user = mysql_fetch_array($res);
		$username = $user["username"];
		$avatar = $user['avatar'];
		$email=$user["email"];
		delete_user($userid);
		@unlink(ROOT_PATH.$avatar);
		$deluserid=$CURUSER["username"];
		write_log("Пользователь $username был удален пользователем $deluserid",'modtask');
		barf();
	} else {
		$returnto = makesafe($_POST["returnto"]);
		safe_redirect("$returnto");
		die;
	}
} elseif ($action == "confirmuser") {
	$userid = (int)$_POST["userid"];
	$confirm = (int)$_POST["confirm"];
	if (!is_valid_id($userid))
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$updateset[] = "confirmed = " . $confirm;
	write_log("Пользователь {$CURUSER['username']} произвел действия над пользователем c ID $userid, параметры:<br/> <pre>".var_export($updateset,true)."</pre>",'modtask');
	sql_query("UPDATE users SET " . implode(", ", $updateset) . " WHERE id = $userid") or sqlerr(__FILE__, __LINE__);
	$returnto = makesafe($_POST["returnto"]);

	safe_redirect($returnto);
}

puke();

?>