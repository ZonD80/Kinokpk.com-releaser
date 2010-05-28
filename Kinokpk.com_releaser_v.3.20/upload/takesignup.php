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

dbconn();

if ($REL_CONFIG['use_captcha']){

	require_once('include/recaptchalib.php');
	$resp = recaptcha_check_answer ($REL_CONFIG['re_privatekey'],
	$_SERVER["REMOTE_ADDR"],
	$_POST["recaptcha_challenge_field"],
	$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		stderr($REL_LANG->say_by_key('error'), "Вы не прошли проверку на человечность, попробуйте еще раз.");
	}

}

if ($REL_CONFIG['deny_signup'] && !$REL_CONFIG['allow_invite_signup'])
stderr($REL_LANG->say_by_key('error'), "Извините, но регистрация отключена администрацией.");

if ($CURUSER)
stderr($REL_LANG->say_by_key('error'), sprintf($REL_LANG->say_by_key('signup_already_registered'), $REL_CONFIG['sitename']));

$users = get_row_count("users");

if ($REL_CONFIG['maxusers']) {
	if ($users >= $REL_CONFIG['maxusers'])
	stderr($REL_LANG->say_by_key('error'), sprintf($REL_LANG->say_by_key('signup_users_limit'), number_format($REL_CONFIG['maxusers'])));
}
if ($REL_CONFIG['deny_signup'] && $REL_CONFIG['allow_invite_signup']) {
	if (empty($_POST["invite"]))
	stderr($REL_LANG->say_by_key('error'), "Для регистрации вам нужно ввести код приглашения!");
}

if (!empty($_POST['invite'])) {
	if (strlen($_POST["invite"]) != 32)
	stderr($REL_LANG->say_by_key('error'), "Вы ввели не правильный код приглашения.");
	$invitecheck = sql_query("SELECT invites.inviter, invites.id, users.username, users.class FROM invites LEFT JOIN users ON inviter=users.id WHERE invite = ".sqlesc($_POST["invite"])) or sqlerr(__FILE__,__LINE__);
	list($inviter, $invid, $invname,$invclass) = @mysql_fetch_array($invitecheck);
	if (!$inviter)
	stderr($REL_LANG->say_by_key('error'), "Код приглашения введенный вами не рабочий."); else
	list($invitedroot) = mysql_fetch_row(sql_query("SELECT invitedroot FROM users WHERE id = $inviter"));
}

function bark($msg) {
	global $REL_LANG;
	stderr($REL_LANG->say_by_key('error'), $msg, 'error');
	exit;
}

function barkdb($msg) {
	global $REL_LANG;
	relconn();
	stderr($REL_LANG->say_by_key('error'), $msg, 'error');
	exit;
}


if (!is_numeric($_POST["icq"]) && (intval($_POST["icq"]) > 999999999))
bark("Жаль, Номер icq слишком длинный , или это не цифра (Макс - 999999999)");
$icq = (int) $_POST["icq"];

$msn = htmlspecialchars(unesc($_POST["msn"]));
if (strlen($msn) > 30)
bark("Жаль, Ваш msn слишком длинный  (Макс - 30)");

$aim = unesc($_POST["aim"]);
if (strlen($aim) > 30)
bark("Жаль, Ваш aim слишком длинный  (Макс - 30)");

$yahoo = htmlspecialchars(unesc($_POST["yahoo"]));
if (strlen($yahoo) > 30)
bark("Жаль, Ваш yahoo слишком длинный  (Макс - 30)");

$mirc = htmlspecialchars(unesc($_POST["mirc"]));
if (strlen($mirc) > 30)
bark("Жаль, Ваш mirc слишком длинный  (Макс - 30)");


$skype = htmlspecialchars(unesc($_POST["skype"]));
if (strlen($skype) > 20)
bark("Жаль, Ваш skype слишком длинный  (Макс - 20)");

$website = htmlspecialchars(unesc($_POST["website"]));
if (strlen($website) > 50)
bark("Жаль, Адрес вашего сайта слишком длинный  (Макс - 50)");

$wantusername = unesc($_POST["wantusername"]);
$email = unesc($_POST["email"]);

if (empty($wantusername) || empty($_POST['wantpassword']) || empty($email) || !is_valid_id($_POST["gender"]) || !is_valid_id($_POST["country"]))
bark("Все поля обязательны для заполнения.");

$gender = unesc($_POST["gender"]);
$country = unesc($_POST["country"]);

if (strlen($wantusername) > 12)
bark("Извините, имя пользователя слишком длинное (максимум 12 символов)");

if ($_POST['wantpassword'] != $_POST['passagain'])
bark("Пароли не совпадают! Похоже вы ошиблись. Попробуйте еще.");

if (strlen($_POST['wantpassword']) < 6)
bark("Извините, пароль слишком коротки (минимум 6 символов)");

if (strlen($_POST['wantpassword']) > 40)
bark("Извините, пароль слишком длинный (максимум 40 символов)");

if ($_POST['wantpassword'] == $wantusername)
bark("Извините, пароль не может быть такой-же как имя пользователя.");

if (!validemail($email))
bark("Это не похоже на реальный email адрес.");

if (!validusername($wantusername))
bark("Неверное имя пользователя.");

if (!is_valid_id($_POST['year']) || !is_valid_id($_POST['month']) || !is_valid_id($_POST['day']))
stderr($REL_LANG->say_by_key('error'),"Похоже вы указали неверную дату рождения");

$year = (int)$_POST['year'];
$month = (int)$_POST['month'];
$day = (int)$_POST['day'];
$birthday = @date("$year.$month.$day");

// make sure user agrees to everything...
if (!$_POST["rulesverify"] || !$_POST["faqverify"] || !$_POST["ageverify"])
stderr($REL_LANG->say_by_key('error'), "Извините, вы не подходите для того что-бы стать членом этого сайта.");

// check if email addy is already in use
$a = (@mysql_fetch_row(@sql_query("SELECT SUM(1) FROM users WHERE email=".sqlesc($email)))) or die(mysql_error());
if ($a[0] != 0)
bark("E-mail адрес $email уже зарегистрирован в системе.");

check_banned_emails($email);

if ($REL_CONFIG['use_integration']) {
	// check IPB email and USER ///////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// connecting to IPB DB
	forumconn();
	//connection opened

	$a = (@mysql_fetch_row(@sql_query("SELECT SUM(1) FROM ".$fprefix."members_converge WHERE converge_email=".sqlesc($email)))) or die(mysql_error());
	if ($a[0] != 0)
	barkdb("E-mail адрес $email уже зарегистрирован на форуме {$REL_CONFIG['forumname']}.");

	$a = (@mysql_fetch_row(@sql_query("SELECT SUM(1) FROM ".$fprefix."members WHERE name=".sqlesc($wantusername)))) or die(mysql_error());
	if ($a[0] != 0)
	barkdb("Пользователь с ником $wantusername уже зарегистрирован на форуме {$REL_CONFIG['forumname']}.");

	// closing IPB DB connection
	relconn();
	// connection closed

	/////////////////////////////////////////////////////////////////
}

$ip = getip();

if (isset($_COOKIE["uid"]) && is_numeric($_COOKIE["uid"]) && $users) {
	$cid = intval($_COOKIE["uid"]);
	$c = sql_query("SELECT enabled FROM users WHERE id = $cid ORDER BY id DESC LIMIT 1");
	$co = @mysql_fetch_row($c);
	if (!$co[0]) {
		sql_query("UPDATE users SET ip = '$ip', last_access = ".time()." WHERE id = $cid");
		bark("Вы уже заререстированы на {$REL_CONFIG['defaultbaseurl']}");
	} else
	bark("Вы уже заререстированы на {$REL_CONFIG['defaultbaseurl']}");
}

$secret = mksecret();
$wantpasshash = md5($secret . $_POST['wantpassword'] . $secret);
$editsecret = (!$users?"":mksecret());

if ((!$users) || (!$REL_CONFIG['use_email_act'] == true))
$status = 1;
else
$status = 0;

$pron = ($_POST['pron']?1:0);

$ret = sql_query("INSERT INTO users (username, passhash, secret, editsecret, pron, notifs, emailnotifs, gender, country, icq, msn, aim, yahoo, skype, mirc, website, email, confirmed, ". (!$users?"class, ":"") ."added, birthday, language, invitedby, invitedroot) VALUES (" .
implode(",", array_map("sqlesc", array($wantusername, $wantpasshash, $secret, $editsecret, $pron, $REL_CONFIG['default_notifs'], $REL_CONFIG['default_emailnotifs'], $gender, $country, $icq, $msn, $aim, $yahoo, $skype, $mirc, $website, strtolower($email), $status))).
		", ". (!$users?UC_SYSOP.", ":"").time().", ".sqlesc($birthday).", '{$REL_CONFIG['default_language']}', ".(int)$inviter.", ".(int)$invitedroot.")");// or sqlerr(__FILE__, __LINE__);

if (!$ret) {
	if (mysql_errno() == 1062)
	bark("Пользователь $wantusername уже зарегистрирован на {$REL_CONFIG['sitename']}!");
	bark("Неизвестная ошибка. Ответ от сервера mySQL: ".htmlspecialchars(mysql_error()));
}

$id = mysql_insert_id();

sql_query("INSERT INTO notifs (checkid, type, userid) VALUES ($id,'usercomments',$id)");

if ($inviter) {
	sql_query("UPDATE invites SET inviteid=$id WHERE inviter=$inviter AND id=$invid") or sqlerr(__FILE__,__LINE__);
	write_sys_msg($id,sprintf($REL_LANG->say_by_key('invite_notice'),"<a href=\"".$REL_SEO->make_link('userdetails','id',$inviter,'username',translit($invname))."\">".get_user_class_color($invclass,$invname)."</a>"),$REL_LANG->say_by_key('welcome_back').strip_tags($wantusername));
	write_sys_msg($inviter,sprintf($REL_LANG->say_by_key('invite_notice_reg'),"<a href=\"".$REL_SEO->make_link('userdetails','id',$id,'username',translit(strip_tags($wantusername)))."\">".get_user_class_color(UC_USER,strip_tags($wantusername))."</a>"),$REL_LANG->say_by_key('invite_system'));
}

register_ipb_user($wantusername,$_POST['wantpassword'], $email, $gender, $year, $month, $day, $aim, $icq, $website, $yahoo, $msn);

write_log("Зарегистрирован новый пользователь $wantusername","tracker");
if ($REL_CONFIG['use_integration']) {
	write_log("Зарегистрирован новый пользователь на форуме {$REL_CONFIG['forumname']}  $wantusername","9693FF","tracker");
}


$psecret = md5($editsecret);

$body = <<<EOD
Вы зарегистрировались на {$REL_CONFIG['sitename']} и указали этот адрес как обратный ($email).

Если это были не вы, пожалуста проигнорируйте это письмо. Персона которая ввела ваш E-Mail адресс имеет IP адрес {$_SERVER["REMOTE_ADDR"]}. Пожалуста, не отвечайте.

Для подтверждения вашей регистрации, вам нужно пройти по следующей ссылке:

{$REL_CONFIG['defaultbaseurl']}/{$REL_SEO->make_link('confirm','id',$id,'secret',$psecret)}

После того как вы это сделаете, вы сможете использовать ваш аккаунт. Если вы этого не сделаете,
 ваш новый аккаунт будет удален через пару дней. Мы рекомендуем вам прочитать правила
и ЧаВо прежде чем вы начнете использовать {$REL_CONFIG['sitename']}.
EOD;

if($REL_CONFIG['use_email_act'] && $users) {
	if (!sent_mail($email,$REL_CONFIG['sitename'],$REL_CONFIG['siteemail'],"Подтверждение регистрации на {$REL_CONFIG['sitename']}",$body)) {
		stderr($REL_LANG->say_by_key('error'), "Невозможно отправить E-Mail. Попробуйте позже");
	}
} else {
	logincookie($id, $wantpasshash, $REL_CONFIG['default_language']);
}

send_notifs('users');
safe_redirect($REL_SEO->make_link('ok','type',(!$users ? "sysop" : ("signup&email=" . urlencode($email)))));
?>