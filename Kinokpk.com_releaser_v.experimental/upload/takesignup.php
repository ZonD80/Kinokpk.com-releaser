<?php
/**
 * Signup form parser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

INIT();

$time = time();

if ($REL_CONFIG['use_captcha']){

	require_once('include/recaptchalib.php');
	$resp = recaptcha_check_answer ($REL_CONFIG['re_privatekey'],
	$_SERVER["REMOTE_ADDR"],
	$_POST["recaptcha_challenge_field"],
	$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Sorry, but you entered invalid CAPTCHA. Please <a href="javascript:history.go(-1);">try again</a>.'));
	}

}

if ($REL_CONFIG['deny_signup'] && !$REL_CONFIG['allow_invite_signup'])
stderr($REL_LANG->say_by_key('error'), $REL_LANG->_("Registration disabled by administration."));

if ($CURUSER)
stderr($REL_LANG->say_by_key('error'), sprintf($REL_LANG->say_by_key('signup_already_registered'), $REL_CONFIG['sitename']));

$users = get_row_count("users");

if ($REL_CONFIG['maxusers']) {
	if ($users >= $REL_CONFIG['maxusers'])
	stderr($REL_LANG->say_by_key('error'), sprintf($REL_LANG->say_by_key('signup_users_limit'), number_format($REL_CONFIG['maxusers'])));
}
if ($REL_CONFIG['deny_signup'] && $REL_CONFIG['allow_invite_signup']) {
	if (empty($_POST["invite"]))
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->_("You must provide invite code to register. Sorry."));
}

if (!empty($_POST['invite'])) {
	if (mb_strlen($_POST["invite"]) != 32)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Sorry, but you entered invalid invite code. Please <a href="javascript:history.go(-1);">try again</a>.'));
	$invitecheck = sql_query("SELECT invites.inviter, invites.id, users.username, users.class FROM invites LEFT JOIN users ON inviter=users.id WHERE invite = ".sqlesc($_POST["invite"])) or sqlerr(__FILE__,__LINE__);
	list($inviter, $invid, $invname,$invclass) = @mysql_fetch_array($invitecheck);
	if (!$inviter)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Sorry, but you entered invalid invite code. Please <a href="javascript:history.go(-1);">try again</a>.')); else
	list($invitedroot) = mysql_fetch_row(sql_query("SELECT invitedroot FROM users WHERE id = $inviter"));
}

function bark($msg) {
	global $REL_LANG;
	stderr($REL_LANG->say_by_key('error'), $msg, 'error');
	exit;
}

$wantusername = unesc($_POST["wantusername"]);
$email = unesc($_POST["email"]);

if (empty($wantusername) || empty($_POST['wantpassword']) || empty($email))
bark($REL_LANG->_('Sorry, you missed some fields. Please <a href="javascript:history.go(-1);">try again</a>.'));

if (mb_strlen($wantusername) > 12)
bark($REL_LANG->_('Sorry, but your username is too big. It must be < 12 symbols. Please <a href="javascript:history.go(-1);">try again</a>.'));

if ($_POST['wantpassword'] != $_POST['passagain'])
bark($REL_LANG->_('Sorry, but your passwords are not the same. Please <a href="javascript:history.go(-1);">try again</a>.'));

if (mb_strlen($_POST['wantpassword']) < 6)
bark($REL_LANG->_('Sorry, but your password is too short. It must be > 6 symbols. Please <a href="javascript:history.go(-1);">try again</a>.'));

if (mb_strlen($_POST['wantpassword']) > 40)
bark($REL_LANG->_('Sorry, but your password is too big. It must be < 40 symbols. Please <a href="javascript:history.go(-1);">try again</a>.'));

if ($_POST['wantpassword'] == $wantusername)
bark($REL_LANG->_('Sorry, but username and password can not be the same. Please <a href="javascript:history.go(-1);">try again</a>.'));

if (!validemail($email))
bark($REL_LANG->_('Sorry, but you entered invalid e-mail. Please <a href="javascript:history.go(-1);">try again</a>.'));

if (!validusername($wantusername))
bark($REL_LANG->_('Sorry, but you entered invalid username. Allowed characters are: a-Z0-9_. Please <a href="javascript:history.go(-1);">try again</a>.'));

// check if email addy is already in use
$a = (@mysql_fetch_row(@sql_query("SELECT SUM(1) FROM users WHERE email=".sqlesc($email)))) or die(mysql_error());
if ($a[0] != 0)
bark($REL_LANG->_('Sorry, but your email (%s) is already registered. Please <a href="javascript:history.go(-1);">try again</a>.',$email));

$a = (@mysql_fetch_row(@sql_query("SELECT SUM(1) FROM users WHERE username=".sqlesc($wantusername)))) or die(mysql_error());
if ($a[0] != 0)
bark($REL_LANG->_('Sorry, but your username (%s) is already in use. Please <a href="javascript:history.go(-1);">try again</a> and select another one.',$wantusername));

check_banned_emails($email);

$ip = getip();

if (isset($_COOKIE["uid"]) && is_numeric($_COOKIE["uid"]) && $users) {
	$cid = intval($_COOKIE["uid"]);
	$c = sql_query("SELECT enabled FROM users WHERE id = $cid ORDER BY id DESC LIMIT 1");
	$co = @mysql_fetch_row($c);
	if (!$co[0]) {
		sql_query("UPDATE users SET ip = '$ip', last_access = ".time()." WHERE id = $cid");
		bark($REL_LANG->_('Sorry, but looks like you already registered long time ago. Please <a href="javascript:history.go(-1);">try again</a>.'));
	} else
	bark($REL_LANG->_('Sorry, but looks like you already registered long time ago. Please <a href="javascript:history.go(-1);">try again</a>.'));
}

$secret = mksecret();
$wantpasshash = md5($secret . $_POST['wantpassword'] . $secret);
$editsecret = (!$users?"":mksecret());

if ((!$users) || (!$REL_CONFIG['use_email_act'] == true))
$status = 1;
else
$status = 0;

$classes = init_class_array();

$ret = sql_query("INSERT INTO users (username, passhash, secret, editsecret, notifs, emailnotifs, email, confirmed, class, added, last_login, last_access, language, timezone, invitedby, invitedroot,custom_privileges) VALUES (" .
implode(",", array_map("sqlesc", array($wantusername, $wantpasshash, $secret, $editsecret, $REL_CONFIG['default_notifs'], $REL_CONFIG['default_emailnotifs'], strtolower($email), $status))).
		", ". (!$users?$classes['sysop']:$classes['reg']).",".$time.",".$time.",".$time.", '{$REL_CONFIG['default_language']}', {$REL_CONFIG['register_timezone']} , ".(int)$inviter.", ".(int)$invitedroot.",'". (!$users?'all':'')."')");// or sqlerr(__FILE__, __LINE__);

if (!$ret) {
	if (mysql_errno() == 1062)
	bark($REL_LANG->_('Sorry, but looks like you already registered long time ago. Please <a href="javascript:history.go(-1);">try again</a>.'));
	bark("SQL ERROR, contact admin: ".htmlspecialchars(mysql_error()));
}

$id = mysql_insert_id();

$REL_DB->query("INSERT INTO xbt_users (uid) VALUES ($id)");

if ($REL_CRON['rating_enabled'])
$msg = $REL_LANG->_('Hello dear new user. You have just registered on our site. Please check <a href="%s">Your rating stats</a> to be happy on our site.<br/><i>Best regards, site team.</i>',$REL_SEO->make_link('myrating'));
else
$msg = $REL_LANG->_('Hello dear new user. You have just registered on our site. Feel free to be happy on our site.<br/><i>Best regards, site team.</i>');
sql_query("INSERT INTO notifs (checkid, type, userid) VALUES ($id,'usercomments',$id)");

write_sys_msg($id,$msg,$REL_LANG->_("Welcome"));

if ($inviter) {
	sql_query("UPDATE invites SET inviteid=$id WHERE inviter=$inviter AND id=$invid") or sqlerr(__FILE__,__LINE__);
	write_sys_msg($id,sprintf($REL_LANG->say_by_key_to($id,'invite_notice'),"<a href=\"".$REL_SEO->make_link('userdetails','id',$inviter,'username',translit($invname))."\">".get_user_class_color($invclass,$invname)."</a>"),$REL_LANG->say_by_key_to($id,'welcome_back').strip_tags($wantusername));
	write_sys_msg($inviter,sprintf($REL_LANG->say_by_key_to($inviter,'invite_notice_reg'),"<a href=\"".$REL_SEO->make_link('userdetails','id',$id,'username',translit(strip_tags($wantusername)))."\">".get_user_class_color($classes['reg'],strip_tags($wantusername))."</a>"),$REL_LANG->say_by_key_to($inviter,'invite_system'));
}

write_log($REL_LANG->_("New user registered (%s)",$wantusername),"tracker");


$psecret = md5($editsecret);

$body = <<<EOD
Вы зарегистрировались на {$REL_CONFIG['sitename']} и указали этот адрес как обратный ($email).

Если это были не вы, пожалуста проигнорируйте это письмо. Персона которая ввела ваш E-Mail адресс имеет IP адрес {$_SERVER["REMOTE_ADDR"]}. Пожалуста, не отвечайте.

Для подтверждения вашей регистрации, вам нужно пройти по следующей ссылке:

{$REL_SEO->make_link('confirm','id',$id,'secret',$psecret)}

После того как вы это сделаете, вы сможете использовать ваш аккаунт. Если вы этого не сделаете,
 ваш новый аккаунт будет удален через пару дней. Мы рекомендуем вам прочитать правила
и ЧаВо прежде чем вы начнете использовать {$REL_CONFIG['sitename']}.
EOD;

if($REL_CONFIG['use_email_act'] && $users) {
	if (!sent_mail($email,$REL_CONFIG['sitename'],$REL_CONFIG['siteemail'],$REL_LANG->_("Registration approval on %s",$REL_CONFIG['sitename']),$body)) {
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('System can not send you a mail Please <a href="javascript:history.go(-1);">try again</a>'));
	}
} else {
	logincookie($id, $wantpasshash, $REL_CONFIG['default_language']);
}

send_notifs('users');
safe_redirect($REL_SEO->make_link("my"),3);
$REL_TPL->stdhead($REL_LANG->_("Signup successful"));
stdmsg($REL_LANG->_("Signup successful"),($REL_CONFIG['use_email_act'] ? sprintf($REL_LANG->say_by_key('confirmation_mail_sent'), htmlspecialchars($email)) : sprintf($REL_LANG->say_by_key('thanks_for_registering'), $REL_CONFIG['sitename']).' '.$REL_LANG->_('Now you will be redirected to <a href="%s">your profile</a> to add additional data for your account.',$REL_SEO->make_link("my"))));
$REL_TPL->stdfoot();
?>
