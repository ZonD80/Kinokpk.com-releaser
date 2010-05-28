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

require_once("include/bittorrent.php");

function bark($msg) {
	stderr("Произошла ошибка", $msg);
}

dbconn();

loggedinorreturn();

if (!mkglobal("email:oldpassword:chpassword:passagain"))
bark("missing form data");

// $set = array();

$updateset = array();
$changedemail = 0;

if ($chpassword != "") {
	if (strlen($chpassword) > 40)
	bark("Извините, ваш пароль слишком длинный (максимум 40 символов)");
	if ($chpassword != $passagain)
	bark("Пароли не совпадают. Попробуйте еще раз.");
	if ($CURUSER["passhash"] != md5($CURUSER["secret"] . $oldpassword . $CURUSER["secret"]))
	bark("Вы ввели неправильный старый пароль.");

	$sec = mksecret();

	$passhash = md5($sec . $chpassword . $sec);
	$updateset[] = "secret = " . sqlesc($sec);
	$updateset[] = "passhash = " . sqlesc($passhash);

	logincookie($CURUSER["id"], $passhash, $CURUSER['language']);
	$passupdated = 1;
}
if ($email != $CURUSER["email"]) {
	if (!validemail($email))
	bark("Это не похоже на настоящий E-Mail.");
	$r = sql_query("SELECT id FROM users WHERE email=" . sqlesc($email)) or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($r) > 0)
	bark("Этот e-mail адрес уже используется одним из пользователей трекера. (<b>$email</b>)");
	$changedemail = 1;
}

$acceptpms = (string) $_POST["acceptpms"];
$deletepms = ($_POST["deletepms"] ? 1 : 0);
$savepms = ($_POST["savepms"] ? 1 : 0);
$pmnotif = (string) $_POST["pmnotif"];
$emailnotif = $_POST["emailnotif"];
$notifs = ($pmnotif ? "[pm]" : "");
$notifs .= ($emailnotif ? "[email]" : "");
$r = sql_query("SELECT id FROM categories") or sqlerr(__FILE__, __LINE__);
$rows = mysql_num_rows($r);
for ($i = 0; $i < $rows; ++$i)
{
	$a = mysql_fetch_assoc($r);
	if ($_POST["cat$a[id]"])
	$notifs .= "[cat$a[id]]";
}
$avatars = ($_POST["avatars"]? 1 : 0);
$extra_ef = ($_POST["extra_ef"]? 1 : 0);
$parked = ($_POST["parked"] ? 1 : 0);
$updateset[] = "parked = " . $parked;

if (!is_valid_id($_POST["gender"])) stderr($tracker_lang['error'],"Какой же у вас пол?");
$gender = (int)$_POST["gender"];
$updateset[] = "gender =  " . $gender;

///////////////// BIRTHDAY MOD /////////////////////

$birthday = @date("{$_POST["year"]}.{$_POST["month"]}.{$_POST["day"]}");
if (!$birthday) stderr($tracker_lang['error'],"Вы указали неверную дату рождения");

///////////////// BIRTHDAY MOD /////////////////////
$updateset[] = "birthday = " . sqlesc($birthday);

if ($_POST['resetpasskey'])
$updateset[] = "passkey=''";

$updateset[] = "passkey_ip = ".($_POST["passkey_ip"] != "" ? sqlesc(getip()) : "''");

$info = ((string)$_POST["info"]);
if (!is_valid_id($_POST["stylesheet"])) stderr($tracker_lang['error'],"Неверно выбран стиль оформления");
$stylesheet = $_POST["stylesheet"];
if (!is_valid_id($_POST['country'])) stderr($tracker_lang['error'],"Неверно выбрана страна");
$country = (int)$_POST["country"];

$updateset[] = "language = " . sqlesc($_POST["language"]);

$icq =  unesc((int)$_POST["icq"]);
if (strlen($icq) > 10)
bark("Жаль, Номер icq слишком длинный  (Макс - 10)");
$updateset[] = "icq = " . sqlesc($icq);

$msn = unesc($_POST["msn"]);
if (strlen($msn) > 30)
bark("Жаль, Ваш msn слишком длинный  (Макс - 30)");
$updateset[] = "msn = " . sqlesc(htmlspecialchars($msn));

$aim = unesc($_POST["aim"]);
if (strlen($aim) > 30)
bark("Жаль, Ваш aim слишком длинный  (Макс - 30)");
$updateset[] = "aim = " . sqlesc(htmlspecialchars($aim));

$yahoo = unesc($_POST["yahoo"]);
if (strlen($yahoo) > 30)
bark("Жаль, Ваш yahoo слишком длинный  (Макс - 30)");
$updateset[] = "yahoo = " . sqlesc(htmlspecialchars($yahoo));

$mirc = unesc($_POST["mirc"]);
if (strlen($mirc) > 30)
bark("Жаль, Ваш mirc слишком длинный  (Макс - 30)");
$updateset[] = "mirc = " . sqlesc(htmlspecialchars($mirc));

$skype = unesc($_POST["skype"]);
if (strlen($skype) > 20)
bark("Жаль, Ваш skype слишком длинный  (Макс - 20)");
$updateset[] = "skype = " . sqlesc(htmlspecialchars($skype));

/*
 if ($privacy != "normal" && $privacy != "low" && $privacy != "strong")
 bark("whoops");

 $updateset[] = "privacy = '$privacy'";
 */

$website = unesc($_POST["website"]);
$updateset[] = "website = " . sqlesc(htmlspecialchars($website));

$updateset[] = "stylesheet = $stylesheet";
$updateset[] = "country = $country";

//$updateset[] = "timezone = $timezone";
//$updateset[] = "dst = '$dst'";
$updateset[] = "info = " . sqlesc($info);
$updateset[] = "acceptpms = " . sqlesc($acceptpms);
$updateset[] = "deletepms = '$deletepms'";
$updateset[] = "savepms = '$savepms'";
$updateset[] = "notifs = '$notifs'";
$updateset[] = "avatars = '$avatars'";
$updateset[] = "extra_ef = '$extra_ef'";

/* ****** */

if ($passupdated) {
	$string = change_ipb_password($chpassword,$CURUSER['username']);
}

if ($changedemail) {
	$sec = mksecret();
	$hash = md5($sec . $email . $sec);
	$obemail = urlencode($email);
	$updateset[] = "editsecret = " . sqlesc($sec);
	$body = <<<EOD
Вы подали запрос на изменения e-mail для пользователя {$CURUSER["username"]}
на {$CACHEARRAY['defaultbaseurl']}. Новым адресом станет:$email.

Если вы НЕ совершали действий, указанных в этом письме, то проигнорируйте это письмо.

Если вы действительно хотите изменить e-mail, то проследуйте по следующей ссылке:
	{$CACHEARRAY['defaultbaseurl']}/confirmemail.php?id={$CURUSER["id"]}&confirmcode=$hash&email=$obemail

EOD;

	sent_mail($email, $CACHEARRAY['sitename'], $CACHEARRAY['siteemail'], "{$CACHEARRAY['defaultbaseurl']} подтверждение изменения профиля", $body);
	$string.= "<br /><h2>".$tracker_lang['my_mail_sent']."</h2>";
}

sql_query("UPDATE users SET " . implode(",", $updateset) . " WHERE id = " . $CURUSER["id"]) or sqlerr(__FILE__,__LINE__);

setcookie("lang", (string) trim($_POST["language"]), 0x7fffffff, "/");
stderr($tracker_lang['success'],$tracker_lang['my_updated'].$string,'success');

?>