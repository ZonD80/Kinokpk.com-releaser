<?php

/**
 * Password changer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
INIT();

loggedinorreturn();
get_privilege('change_user_passwords');

httpauth();

$REL_TPL->stdhead($REL_LANG->say_by_key('change_user_pass'));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$updateset = array();
	$iname = (string)$_POST['iname'];
	$ipass = (string)$_POST['ipass'];
	$imail = (string)$_POST['imail'];
	if (!empty($ipass)) {
		$secret = mksecret();
		$hash = md5($secret.$ipass.$secret);
		$updateset[] = "secret = ".sqlesc($secret);
		$updateset[] = "passhash = ".sqlesc($hash);
	}
	if (!empty($imail) && validemail($imail))
	$updateset[] = "email = ".sqlesc($imail);
	if (count($updateset)) {
		$class = @mysql_result(sql_query("SELECT class FROM users WHERE username = ".sqlesc($iname)),0);
		if (get_class_priority(get_user_class()) <= get_class_priority($class)) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('your_class_is_lower'),'error'); $REL_TPL->stdfoot(); die(); }
		$res = sql_query("UPDATE users SET ".implode(", ", $updateset)." WHERE username = ".sqlesc($iname)) or sqlerr(__FILE__,__LINE__);

	}
	stdmsg($REL_LANG->say_by_key('change_usr_succ'), $REL_LANG->say_by_key('username').$iname.(!empty($hash) ? $REL_LANG->say_by_key('new_password').$ipass : "").(!empty($imail) ? $REL_LANG->say_by_key('new_email').$imail : ""));
} else {
	echo "<form method=\"post\" action=\"".$REL_SEO->make_link('passwordadmin')."\">"
	."<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\">"
	."<tr><td align=\"center\" class=\"colhead\" colspan=\"2\">".$REL_LANG->say_by_key('change_password')."</td></tr>"
	."<tr>"
	."<td><b>".$REL_LANG->say_by_key('username')."</b></td>"
	."<td><input name=\"iname\" type=\"text\"></td>"
	."</tr>"
	."<tr>"
	."<td><b>".$REL_LANG->say_by_key('new_password')."</b></td>"
	."<td><input name=\"ipass\" type=\"password\"></td>"
	."</tr>"
	."<tr>"
	."<td><b>".$REL_LANG->say_by_key('new_email')."</b></td>"
	."<td><input name=\"imail\" type=\"text\"></td>"
	."</tr>"
	."<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"isub\" value=\"".$REL_LANG->say_by_key('change')."\"></td></tr>"
	."</table>"
	."</form>";
}

$REL_TPL->stdfoot();
?>