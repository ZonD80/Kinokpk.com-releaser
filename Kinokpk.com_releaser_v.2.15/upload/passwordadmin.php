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

if (get_user_class() < UC_ADMINISTRATOR) die('Access denied. You\'re not SYSOP');

stdhead("Сменить пароль пользователю");

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$updateset = array();
  $iname = $_POST['iname'];
  $ipass = $_POST['ipass'];
  $imail = $_POST['imail'];
		if (!empty($ipass)) {
			$secret = mksecret();
			$hash = md5($secret.$ipass.$secret);
			$updateset[] = "secret = ".sqlesc($secret);
			$updateset[] = "passhash = ".sqlesc($hash);
		}
		if (!empty($imail) && validemail($imail))
			$updateset[] = "email = ".sqlesc($imail);
		if (count($updateset))
			$res = sql_query("UPDATE users SET ".implode(", ", $updateset)." WHERE username = ".sqlesc($iname)) or sqlerr(__FILE__,__LINE__);
			stdmsg("Изменения пользователя прошло успешно", "Имя пользователя: ".$iname.(!empty($hash) ? "<br />Новый пароль: ".$ipass : "").(!empty($imail) ? "<br />Новая почта: ".$imail : ""));
	} else {
		echo "<form method=\"post\" action=\"passwordadmin.php\">"
		."<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\">"
		."<tr><td class=\"colhead\" colspan=\"2\">Смена пароля</td></tr>"
		."<tr>"
		."<td><b>Пользователь</b></td>"
		."<td><input name=\"iname\" type=\"text\"></td>"
		."</tr>"
		."<tr>"
		."<td><b>Новый пароль</b></td>"
		."<td><input name=\"ipass\" type=\"password\"></td>"
		."</tr>"
		."<tr>"
		."<td><b>Новая почта</b></td>"
		."<td><input name=\"imail\" type=\"text\"></td>"
		."</tr>"
		."<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"isub\" value=\"Сменить\"></td></tr>"
		."</table>"
		."</form>";
	}

stdfoot();
?>