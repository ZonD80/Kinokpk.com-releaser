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

require_once("include/bittorrent.php");
dbconn();
$REL_LANG->load('passwordadmin');
loggedinorreturn();
httpauth();

if (get_user_class() < UC_ADMINISTRATOR) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('access_denied'));

stdhead($REL_LANG->say_by_key('change_user_pass'));

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
		if (get_user_class() <= $class) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('your_class_is_lower'),'error'); stdfoot(); die(); }
		$res = sql_query("UPDATE users SET ".implode(", ", $updateset)." WHERE username = ".sqlesc($iname)) or sqlerr(__FILE__,__LINE__);

		change_ipb_password($ipass,$iname);
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

stdfoot();
?>