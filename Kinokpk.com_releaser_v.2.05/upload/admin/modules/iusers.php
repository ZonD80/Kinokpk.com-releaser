<?php
if (!defined("ADMIN_FILE")) die("Illegal File Access");

function iUsers($iname, $ipass, $imail) {
	global $admin_file;
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$updateset = array();
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
		if (mysql_modified_rows() < 1)
			stdmsg("Ошибка", "Смена пароля завершилась неудачей! Возможно указано несуществующее имя пользователя.", "error");
		else
			stdmsg("Изменения пользователя прошло успешно", "Имя пользователя: ".$iname.(!empty($hash) ? "<br />Новый пароль: ".$ipass : "").(!empty($imail) ? "<br />Новая почта: ".$imail : ""));
	} else {
		echo "<form method=\"post\" action=\"".$admin_file.".php?op=iUsers\">"
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
		."<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"isub\" value=\"Сделать\"></td></tr>"
		."</table>"
		."<input type=\"hidden\" name=\"op\" value=\"iUsers\" />"
		."</form>";
	}
}

switch ($op) {
	case "iUsers":
	iUsers($iname, $ipass, $imail);
	break;
}

?>