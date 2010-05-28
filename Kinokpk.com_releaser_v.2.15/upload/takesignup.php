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

if ($CACHEARRAY['use_captcha']){

require_once('include/recaptchalib.php');
$resp = recaptcha_check_answer ($CACHEARRAY['re_privatekey'],
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
	stderr($tracker_lang['error'], "Вы не прошли проверку на человечность, попробуйте еще раз.");
}

}

if ($CACHEARRAY['deny_signup'] && !$CACHEARRAY['allow_invite_signup'])
	stderr($tracker_lang['error'], "Извините, но регистрация отключена администрацией.");

if ($CURUSER)
	stderr($tracker_lang['error'], sprintf($tracker_lang['signup_already_registered'], $CACHEARRAY['sitename']));

$users = get_row_count("users");
if ($users >= $CACHEARRAY['maxusers'])
	stderr($tracker_lang['error'], sprintf($tracker_lang['signup_users_limit'], number_format($CACHEARRAY['maxusers'])));

if ($CACHEARRAY['deny_signup'] && $CACHEARRAY['allow_invite_signup']) {
	if (empty($_POST["invite"]))
		stderr("Ошибка", "Для регистрации вам нужно ввести код приглашения!");
	if (strlen($_POST["invite"]) != 32)
		stderr("Ошибка", "Вы ввели не правильный код приглашения.");
	list($inviter) = mysql_fetch_row(sql_query("SELECT inviter FROM invites WHERE invite = ".sqlesc($_POST["invite"])));
	if (!$inviter)
		stderr("Ошибка", "Код приглашения введенный вами не рабочий.");
	list($invitedroot) = mysql_fetch_row(sql_query("SELECT invitedroot FROM users WHERE id = $inviter"));
}

function bark($msg) {
	global $tracker_lang;
	stderr($tracker_lang['error'], $msg, 'error');
	exit;
}

function barkdb($msg) {
	global $tracker_lang;
	relconn();
	stderr($tracker_lang['error'], $msg, 'error');
	exit;
}

function validusername($username)
{
	if ($username == "")
	  return false;

	// The following characters are allowed in user names
	$allowedchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_ ".
		"абвгдеёжзиклмнопрстуфхшщэюяьъАБВГДЕЁЖЗИКЛМНОПРСТУФХШЩЭЮЯЬЪ";

	for ($i = 0; $i < strlen($username); ++$i)
	  if (strpos($allowedchars, $username[$i]) === false)
	    return false;

	return true;
}


if (!is_numeric($_POST["icq"]) && (intval($_POST["icq"]) > 999999999))
    bark("Жаль, Номер icq слишком длинный , или это не цифра (Макс - 999999999)");
$icq = $_POST["icq"];

$msn = unesc($_POST["msn"]);
if (strlen($msn) > 30)
    bark("Жаль, Ваш msn слишком длинный  (Макс - 30)");

$aim = unesc($_POST["aim"]);
if (strlen($aim) > 30)
    bark("Жаль, Ваш aim слишком длинный  (Макс - 30)");

$yahoo = unesc($_POST["yahoo"]);
if (strlen($yahoo) > 30)
    bark("Жаль, Ваш yahoo слишком длинный  (Макс - 30)");

$mirc = unesc($_POST["mirc"]);
if (strlen($mirc) > 30)
    bark("Жаль, Ваш mirc слишком длинный  (Макс - 30)");


$skype = unesc($_POST["skype"]);
if (strlen($skype) > 20)
    bark("Жаль, Ваш skype слишком длинный  (Макс - 20)");

$website = unesc($_POST["website"]);
if (strlen($skype) > 50)
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
        stderr($tracker_lang['error'],"Похоже вы указали неверную дату рождения");
        
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];
	$birthday = date("$year.$month.$day");

// make sure user agrees to everything...
if ($_POST["rulesverify"] != "yes" || $_POST["faqverify"] != "yes" || $_POST["ageverify"] != "yes")
	stderr($tracker_lang['error'], "Извините, вы не подходите для того что-бы стать членом этого сайта.");

// check if email addy is already in use
$a = (@mysql_fetch_row(@sql_query("SELECT COUNT(*) FROM users WHERE email=".sqlesc($email)))) or die(mysql_error());
if ($a[0] != 0)
	bark("E-mail адрес $email уже зарегистрирован в системе.");

check_banned_emails($email);

			if ($CACHEARRAY['use_integration']) {
// check IPB email and USER ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// connecting to IPB DB
forumconn();
//connection opened

$a = (@mysql_fetch_row(@sql_query("SELECT COUNT(*) FROM ".$fprefix."members_converge WHERE converge_email=".sqlesc($email)))) or die(mysql_error());
if ($a[0] != 0)
	barkdb("E-mail адрес $email уже зарегистрирован на форуме {$CACHEARRAY['forumname']}.");
	
$a = (@mysql_fetch_row(@sql_query("SELECT COUNT(*) FROM ".$fprefix."members WHERE name=".sqlesc($wantusername)))) or die(mysql_error());
if ($a[0] != 0)
	barkdb("Пользователь с ником $wantusername уже зарегистрирован на форуме {$CACHEARRAY['forumname']}.");
	
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
    if ($co[0] == 'no') {
		sql_query("UPDATE users SET ip = '$ip', last_access = NOW() WHERE id = $cid");
		bark("Вы уже заререстированы на {$CACHEARRAY['defaultbaseurl']}");
    } else
		bark("Вы уже заререстированы на {$CACHEARRAY['defaultbaseurl']}");
} else {
    $b = (@mysql_fetch_row(@sql_query("SELECT enabled, id FROM users WHERE ip LIKE '$ip' ORDER BY last_access DESC LIMIT 1")));
    if ($b[0] == 'no') {
		$banned_id = $b[1];
        setcookie("uid", $banned_id, "0x7fffffff", "/");
		bark("Вы уже заререстированы на {$CACHEARRAY['defaultbaseurl']}");
    }
}

$secret = mksecret();
$wantpasshash = md5($secret . $_POST['wantpassword'] . $secret);
$editsecret = (!$users?"":mksecret());

if ((!$users) || (!$CACHEARRAY['use_email_act'] == true))
	$status = 'confirmed';
else
	$status = 'pending';

$ret = sql_query("INSERT INTO users (username, passhash, secret, editsecret, gender, country, icq, msn, aim, yahoo, skype, mirc, website, email, status, ". (!$users?"class, ":"") ."added, birthday, language, invitedby, invitedroot, last_checked) VALUES (" .
		implode(",", array_map("sqlesc", array($wantusername, $wantpasshash, $secret, $editsecret, $gender, $country, $icq, $msn, $aim, $yahoo, $skype, $mirc, $website, $email, $status))).
		", ". (!$users?UC_SYSOP.", ":""). "'". get_date_time() ."', ".sqlesc($birthday).", '{$CACHEARRAY['default_language']}', ".sqlesc($inviter).", ".sqlesc($invitedroot).",'". get_date_time() ."')");// or sqlerr(__FILE__, __LINE__);

if (!$ret) {
	if (mysql_errno() == 1062)
		bark("Пользователь $wantusername уже зарегистрирован на {$CACHEARRAY['sitename']}!");
	bark("Неизвестная ошибка. Ответ от сервера mySQL: ".htmlspecialchars(mysql_error()));
}

$id = mysql_insert_id();

			if ($CACHEARRAY['use_integration']) {
// REGISTERING IPB USER /////////////////////////////////////////////////////////////////////////////////////////////////

// connecting to IPB DB
forumconn();
//connection opened

$time = time();

$passhash  =  generate_compiled_passhash( $salt, md5($_POST['wantpassword']) );
$gs = generate_auto_log_in_key();
/////END OF PASSWORD GENERATOR/////
/*function insert_db($table_name, $arr){
    sql_query("INSERT INTO ".$prefix.$table_name.$arr."");
*/
////register////

$first = sql_query("INSERT INTO ".$fprefix."members_converge (converge_email,converge_joined,converge_pass_hash,converge_pass_salt)
            VALUES (" .implode(",", array_map("sqlesc", array($email,$time,$passhash,$salt))).")");

$idf = mysql_insert_id();

$second = sql_query("INSERT INTO ".$fprefix."members (id,name,email,mgroup,joined,ip_address,members_display_name,members_l_display_name,members_l_username,member_login_key,bday_day,bday_month,bday_year)
            VALUES (" .implode(",", array_map("sqlesc", array($idf,$wantusername,$email,$CACHEARRAY['defuserclass'],$time,$ip,$wantusername,$wantusername,$wantusername,$gs,$day,$month,$year))).")");

$icqint = intval($icq);
$third = sql_query("INSERT INTO ".$fprefix."member_extra (id,notes,links,bio,ta_size,photo_type,photo_location,photo_dimensions,aim_name,icq_number,website,yahoo,interests,msnname,vdirs,location,signature,avatar_location,avatar_size,avatar_type) VALUES (".sqlesc($idf).", NULL, NULL, NULL, NULL, '', '', '', ".sqlesc($aim).", ".sqlesc($icqint).", ".sqlesc($website).", ".sqlesc($yahoo).", '', ".sqlesc($msn).", '', '', '', '', '', 'local')");

if ($gender == 1) $forumgender = 'male';
if ($gender == 2) $forumgender = 'female';
if ($gender == 3) $forumgender = '';

$fourth = sql_query("INSERT INTO ".$fprefix."profile_portal (pp_member_id,pp_gender) VALUES (".$idf.",'".$forumgender."')");

 // updating forum caches
 $statcache = sql_query("SELECT cs_value FROM ".$fprefix."cache_store WHERE cs_key = 'stats'");
 $statcache = mysql_result($statcache,0);
 $statcache = unserialize($statcache);
 $statcache['mem_count']++;
 $statcache['last_mem_name'] = $wantusername;
 $statcache['last_mem_id'] = $idf;
 $statcache = serialize($statcache);
 sql_query("UPDATE ".$fprefix."cache_store SET cs_value='".$statcache."' WHERE cs_key='stats'");
	 // closing IPB DB connection
relconn();
 // connection closed


 //////////END IPB REGISTRATION! //////////////////////////////////////////////////////////////////////////////////////
}
sql_query("DELETE FROM invites WHERE invite = ".sqlesc($_POST["invite"]));

write_log("Зарегистрирован новый пользователь $wantusername","FFFFFF","tracker");
			if ($CACHEARRAY['use_integration']) {
write_log("Зарегистрирован новый пользователь на форуме {$CACHEARRAY['forumname']}  $wantusername","9693FF","tracker");
 }


$psecret = md5($editsecret);

$body = <<<EOD
Вы зарегистрировались на {$CACHEARRAY['sitename']} и указали этот адрес как обратный ($email).

Если это были не вы, пожалуста проигнорируйте это письмо. Персона которая ввела ваш E-Mail адресс имеет IP адрес {$_SERVER["REMOTE_ADDR"]}. Пожалуста, не отвечайте.

Для подтверждения вашей регистрации, вам нужно пройти по следующей ссылке:

{$CACHEARRAY['defaultbaseurl']}/confirm.php?id=$id&secret=$psecret

После того как вы это сделаете, вы сможете использовать ваш аккаунт. Если вы этого не сделаете,
 ваш новый аккаунт будет удален через пару дней. Мы рекомендуем вам прочитать правила
и ЧаВо прежде чем вы начнете использовать {$CACHEARRAY['sitename']}.
EOD;

if($CACHEARRAY['use_email_act'] && $users) {
	if (!sent_mail($email,$CACHEARRAY['sitename'],$CACHEARRAY['siteemail'],"Подтверждение регистрации на {$CACHEARRAY['sitename']}",$body,false)) {
		stderr($tracker_lang['error'], "Невозможно отправить E-Mail. Попробуйте позже");
	}
} else {
	logincookie($id, $wantpasshash, $CACHEARRAY['default_language']);
}
header("Refresh: 0; url=ok.php?type=". (!$users?"sysop":("signup&email=" . urlencode($email))));

?>