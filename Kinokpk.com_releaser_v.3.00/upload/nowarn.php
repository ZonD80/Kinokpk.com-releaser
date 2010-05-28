<?php
/**
 * Unwarn users by moderators and upper
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */



require_once("include/bittorrent.php");
function bark($msg) {
	global $tracker_lang;
	stdhead();
	stdmsg($tracker_lang['error'], $msg);
	stdfoot();
	exit;
}
dbconn();
getlang('nowarn');
loggedinorreturn();
if(isset($_POST["nowarned"])&&($_POST["nowarned"]=="nowarned")){
	//if (get_user_class() >= UC_SYSOP) {
	if (get_user_class() < UC_MODERATOR)
	stderr($tracker_lang['error'], $tracker_lang['access_denied']);
	{
		if (empty($_POST["usernw"]) && empty($_POST["desact"]) && empty($_POST["delete"]))
		bark($tracker_lang['select_user']);

		if (!empty($_POST["usernw"]) && is_array($_POST['usernw']))
		{
			$msg = sqlesc($tracker_lang['you_warning_removed'] . $CURUSER['username'] . ".");
			$added = sqlesc(time());
			$userid = implode(", ", array_map('sqlesc', $_POST['usernw']));
			//sql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES (0, $userid, $msg, $added)") or sqlerr(__FILE__, __LINE__);

			$r = sql_query("SELECT modcomment FROM users WHERE id IN (" . implode(", ", array_map('sqlesc', $_POST['usernw'])) . ")")or sqlerr(__FILE__, __LINE__);
			$user = mysql_fetch_array($r);
			$exmodcomment = $user["modcomment"];
			$modcomment = date("Y-m-d") . $tracker_lang['warning_removed'] . $CURUSER['username'] . ".\n". $modcomment . $exmodcomment;
			sql_query("UPDATE users SET modcomment=" . sqlesc($modcomment) . " WHERE id IN (" . implode(", ", array_map('sqlesc', $_POST['usernw'])) . ")") or sqlerr(__FILE__, __LINE__);


			$do="UPDATE users SET warned=0, warneduntil=0 WHERE id IN (" . implode(", ", array_map('sqlesc', $_POST['usernw'])) . ")";
			$res=sql_query($do);}

			if (!empty($_POST["desact"]) && is_array($_POST['desact'])) {
				$do="UPDATE users SET enabled=0 WHERE id IN (" . implode(", ", array_map('sqlesc', $_POST['desact']) ). ")";
				$res=sql_query($do);}
	}
}
safe_redirect("warned.php");
?>