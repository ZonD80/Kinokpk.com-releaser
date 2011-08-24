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

get_privilege('edit_users');

$action = (string)$_POST["action"];

if (($action == 'ownsupport') && get_privilege('ownsupport',false)) {
	$supportfor = ($_POST["support"]?htmlspecialchars((string)$_POST["supportfor"]):'');
	$updateset[] = "supportfor = " . sqlesc($supportfor);
	sql_query("UPDATE users SET " . implode(", ", $updateset) . " WHERE id = {$CURUSER['id']}") or sqlerr(__FILE__, __LINE__);
	safe_redirect($REL_SEO->make_link('my'),0);
	stderr($REL_LANG->say_by_key('success'),$REL_LANG->_('You successfully changed your support status'),'success');

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
	$dis_reason = htmlspecialchars((string)$_POST['disreason']);
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
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Invalid ID'));
	// check target user class
	$res = sql_query("SELECT warned, enabled, donor, username, class, modcomment, num_warned, avatar,id FROM users WHERE id = $userid") or sqlerr(__FILE__, __LINE__);
	$arr = mysql_fetch_assoc($res) or sqlerr(__FILE__,__LINE__);
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
		$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Access deined'));

	if ($curclass != $class) {
		// Notify user
		$msg = sqlesc($REL_LANG->_to($userid,'Your class was setted to "%s" by %s',get_user_class_name($class),make_user_link()));
		$added = sqlesc(time());
		$subject = sqlesc($REL_LANG->_to($userid,'Notification about class changing'));
		sql_query("INSERT INTO messages (sender, receiver, msg, added, subject) VALUES(0, $userid, $msg, $added, $subject)") or sqlerr(__FILE__, __LINE__);
		$updateset[] = "class = $class";
		$modcomment = date("Y-m-d") . ($REL_LANG->_to(0,'Class was setted to "%s" by %s',get_user_class_name($class),$CURUSER['username']))."\n". $modcomment;
	}

	$num_warned = 1 + $arr["num_warned"];
	if ($curwarned != $warned) {
		$updateset[] = "warned = 0";
		$updateset[] = "warneduntil = 0";
		$subject = sqlesc($REL_LANG->_to($userid,'Your warning has been disabled'));
		if (!$warned)
		{
			$modcomment = date("Y-m-d") . $REL_LANG->_to(0,'Warning has been disabled by %s',$CURUSER['username']) . ".\n". $modcomment;
			$msg = sqlesc($REL_LANG->_to($userid,'Your warning has been disabled by %s', make_user_link()));
		}
		$added = sqlesc(time());
		sql_query("INSERT INTO messages (sender, receiver, msg, added, subject) VALUES (0, $userid, $msg, $added, $subject)") or sqlerr(__FILE__, __LINE__);
	} elseif ($warnlength) {
		if (mb_strlen($warnpm) == 0)
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('You must specify a reason'));
		if ($warnlength == 255) {
			$modcomment = date("Y-m-d") . $REL_LANG->_to(0,'Warned by %s with the following reason: "%s"',$CURUSER['username'],$warnpm)."\n" . $modcomment;
			$msg = sqlesc($REL_LANG->_to($userid,'You got warned by %s for %s with the following reason: "%s"',make_user_link(),$REL_LANG->_('an unlimited time'),$warnpm));
			$updateset[] = "warneduntil = 0";
			$updateset[] = "num_warned = $num_warned";
		} else {
			$warneduntil = (time() + $warnlength * 604800);
			$dur = $warnlength . ($warnlength > 1 ? $REL_LANG->_('week') : $REL_LANG->_('weeks'));
			$modcomment = date("Y-m-d") . $REL_LANG->_to(0,'Warned by %s for %s with the following reason: "%s"',$CURUSER['username'],$dur,$warnpm)."\n" . $modcomment;
			$msg = sqlesc($REL_LANG->_to($userid,'You got warned by %s for %s with the following reason: "%s"',make_user_link(),$dur,$warnpm));
			$updateset[] = "warneduntil = $warneduntil";
			$updateset[] = "num_warned = $num_warned";
		}
		$added = sqlesc(time());
		$subject = sqlesc($REL_LANG->_to($userid,'You have got a warning!'));
		sql_query("INSERT INTO messages (sender, receiver, msg, added, subject) VALUES (0, $userid, $msg, $added, $subject)") or sqlerr(__FILE__, __LINE__);
		$updateset[] = "warned = 1";
	}

	if ($enabled != $curenabled) {
		$modifier = (int) $CURUSER['id'];
		if ($enabled) {
			$enareason = htmlspecialchars((string)$_POST["enareason"]);
			if (!$enareason) $REL_TPL->stderr($REL_LANG->_('Error'), $REL_LANG->_('You must specify a reason'));
			$modcomment = date("Y-m-d") . $REL_LANG->_to(0,'Enabled by user %s with the following reason: "%s"',$CURUSER['username'],$enareason)."\n" . $modcomment;

		} else   {
			if (empty($dis_reason)) $REL_TPL->stderr($REL_LANG->_('Error'), $REL_LANG->_('You must specify a reason'));
			$modcomment = date("Y-m-d") . $REL_LANG->_to(0,'Disabled by user %s with the following reason: "%s"',$CURUSER['username'],$dis_reason)."\n" . $modcomment;

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
	write_log($REL_LANG->_to(0,'User %s modify user %s, parameters:<br/><pre>%s</pre>',make_user_link(),make_user_link($arr),var_export($updateset,true)),'modtask');
	sql_query("UPDATE users SET	" . implode(", ", $updateset) . " $birthday WHERE id = $userid") or sqlerr(__FILE__, __LINE__);

	if (!empty($_POST["deluser"])) {
		$user = get_user($userid);
		delete_user($userid);
		@unlink(ROOT_PATH.$avatar);
		write_log($REL_LANG->_to(0,'User %s deleted user %s',make_user_link(),make_user_link($user)),'modtask');
		barf();
	} else {
		$returnto = makesafe((string)$_POST["returnto"]);
		safe_redirect($returnto);
		die;
	}
} elseif ($action == "confirmuser") {
	$userid = (int)$_POST["userid"];
	$confirm = (int)$_POST["confirm"];
	if (!is_valid_id($userid))
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
	$user = get_user($userid);
	$updateset[] = "confirmed = " . $confirm;
	write_log($REL_LANG->_to(0,'User %s confirmed user %s',make_user_link(),make_user_link($user)),'modtask');
	sql_query("UPDATE users SET " . implode(", ", $updateset) . " WHERE id = $userid") or sqlerr(__FILE__, __LINE__);
	$returnto = makesafe($_POST["returnto"]);

	safe_redirect($returnto);
}

$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Access denied'));

?>