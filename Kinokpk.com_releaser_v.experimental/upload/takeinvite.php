<?php
/**
 * Invites processor
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

INIT();

loggedinorreturn();

function bark($msg) {
	$REL_TPL->stdhead();
	stdmsg($REL_LANG->say_by_key('error'), $msg);
	$REL_TPL->stdfoot();
	die;
}

$id = (int) $_GET["id"];
if (!$id) $id = (int) $_POST['id'];
if (!$id)
stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

if (get_privilege('approve_invites',false))
$id = $CURUSER["id"];

$hash  = md5(mt_rand(1, 1000000));
if ($REL_CONFIG['use_captcha']){

	require_once('include/recaptchalib.php');
	$resp = recaptcha_check_answer ($REL_CONFIG['re_privatekey'],
	$_SERVER["REMOTE_ADDR"],
	$_POST["recaptcha_challenge_field"],
	$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		stderr($REL_LANG->say_by_key('error'), "Введенный код подтверждения неверный. <a href=\"javascript:history.go(-1);\">Попробуйте еще раз</a>");
	}

}
$email =  trim((string)$_POST['email']);
if (!validemail($email)) stderr($REL_LANG->say_by_key('error'),'Email адрес введен неверно');

$res = sql_query("SELECT 1 FROM users WHERE email='$email'");
$check = @mysql_result($res,0);
if ($check) stderr($REL_LANG->say_by_key('error'),'Такой email уже зарегестрирован!');

$subject = "Приглашение на {$REL_CONFIG['sitename']}";
$body = "Ваш друг или подруга с ником {$CURUSER['username']} пригласили вас зарегестрироваться на {$REL_CONFIG['sitename']}<br/>
Для регистрации пройдите по этой ссылке:
<a href=\"{$REL_SEO->make_link('signup')}\">{$REL_SEO->make_link('signup')}</a><br/>
Используйте следующий код приглашения:<b>$hash</b><hr/>
Спасибо за внимание, с уважением {$REL_CONFIG['sitename']}";

sql_query("INSERT INTO invites (inviter, invite, time_invited) VALUES (" . implode(", ", array_map("sqlesc", array($id, $hash, time()))) . ")") or sqlerr(__FILE__,__LINE__);

sql_query("INSERT INTO cron_emails (emails, subject, body) VALUES (".sqlesc($email).",".sqlesc($subject).",".sqlesc($body).")") or sqlerr(__FILE__,__LINE__);

safe_redirect($REL_SEO->make_link('invite','id',$id));

?>