<?php
/**
 * Password recovery
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";

INIT();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if ($REL_CONFIG['use_captcha']) {
		require_once(ROOT_PATH.'include/recaptchalib.php');
		$resp = recaptcha_check_answer ($REL_CONFIG['re_privatekey'],
		$_SERVER["REMOTE_ADDR"],
		$_POST["recaptcha_challenge_field"],
		$_POST["recaptcha_response_field"]);

		if (!$resp->is_valid) stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('test_humanity'));
	}

	$email = trim(htmlspecialchars((string)$_POST["email"]));
	if (!$email || !validemail($email))
	stderr($REL_LANG->say_by_key('error'), "Вы должны ввести email адрес");
	$res = sql_query("SELECT * FROM users WHERE email=" . sqlesc($email) . " LIMIT 1") or sqlerr(__FILE__, __LINE__);
	$arr = mysql_fetch_array($res) or stderr($REL_LANG->say_by_key('error'), "Email адрес не найден в базе данных.\n");

	$sec = mksecret();

	sql_query("UPDATE users SET editsecret=" . sqlesc($sec) . " WHERE id=" . $arr["id"]) or sqlerr(__FILE__, __LINE__);
	if (!mysql_affected_rows())
	stderr($REL_LANG->say_by_key('error'), "Ошибка базы данных. Свяжитесь с администратором относительно этой ошибки.");

	$hash = md5($sec . $email . $arr["passhash"] . $sec);

	$body = nl2br("
Вы, или кто-то другой, запросили новый пароль к аккаунту связаному с этим адресом ($email).

Если это были НЕ вы, проигнорируйте это письмо. Пожалуста не отвечайте.

Если вы подтверждаете этот запрос, перейдите по следующей ссылке:

{$REL_SEO->make_link('recover','confirm',1,'id',$arr["id"],'secret',$hash)}


После того как вы это сделаете, ваш пароль будет сброшен и новый пароль будет отправлен вам на E-Mail.

--
{$REL_CONFIG['sitename']}
");

if (sent_mail($arr['email'], $REL_CONFIG['sitename'], $REL_CONFIG['siteemail'],  "{$REL_CONFIG['defaultbaseurl']} восстановление пароля",  wordwrap($body,70))==false) stderr($REL_LANG->say_by_key('error'),"Ошибка при отправке письма");

stderr($REL_LANG->say_by_key('success'), "Подтверждающее письмо было отправлено.\n" .
		" Через несколько минут (обычно сразу) вам прийдет письмо с дальнейшими указаниями.",'success');
}
elseif(isset($_GET['confirm']))
{

	if (!is_valid_id($_GET["id"]))
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

	$id = (int) $_GET["id"];
	$md5 = $_GET["secret"];

	$res = sql_query("SELECT username, email, passhash, editsecret FROM users WHERE id = $id");
	$arr = mysql_fetch_array($res) or stderr($REL_LANG->say_by_key('error'),"Нет пользователя с таким ID");

	$email = $arr["email"];

	$sec = hash_pad($arr["editsecret"]);
	if (preg_match('/^ *$/s', $sec))
	stderr($REL_LANG->say_by_key('error'),"Ошибка вычисления кода подтверждения");
	if ($md5 != md5($sec . $email . $arr["passhash"] . $sec))
	stderr($REL_LANG->say_by_key('error'),"Код подтверждения неверен");

	// generate new password;
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

	$newpassword = "";
	for ($i = 0; $i < 10; $i++)
	$newpassword .= $chars[mt_rand(0, mb_strlen($chars) - 1)];

	$sec = mksecret();

	$newpasshash = md5($sec . $newpassword . $sec);
	//$username = @mysql_result(sql_query("SELECT username FROM users WHERE id=$id"),0);

	sql_query("UPDATE users SET secret=" . sqlesc($sec) . ", editsecret='', passhash=" . sqlesc($newpasshash) . " WHERE id=$id AND editsecret=" . sqlesc($arr["editsecret"]));


	if (!mysql_affected_rows())
	stderr($REL_LANG->say_by_key('error'), "Невозможно обновить данные пользователя. Пожалуста свяжитесь с администратором относительно этой ошибки.");

	$body = nl2br("
По вашему запросу на восстановление пароля, вы сгенерировали вам новый пароль.

Вот ваши новые данные для этого аккаунта:

    Пользователь: {$arr["username"]}
    Пароль:       $newpassword

Вы можете войти на сайт тут: {$REL_SEO->make_link('login')}

--
{$REL_CONFIG['sitename']}
");

$mail_sent = sent_mail($email,$REL_CONFIG['sitename'],$REL_CONFIG['siteemail'], "{$REL_CONFIG['defaultbaseurl']} данные аккаунта", $body);
if (!$mail_sent) stderr($REL_LANG->say_by_key('error'),'Mail not sent, configure smtp/sendmail or contact site admin');
stderr($REL_LANG->say_by_key('success'), "Новые данные по аккаунту отправлены на E-Mail <b>$email</b>.\n" .
    "Через несколько минут (обычно сразу) вы получите ваши новые данные.",'success');
}
else
{
	$REL_TPL->stdhead("Восстановление пароля");
	?>
<form method="post" action="<?=$REL_SEO->make_link('recover');?>">
<table border="1" cellspacing="0" cellpadding="5">
	<tr>
		<td class="colhead" colspan="2">Восстановление имени пользователя или
		пароля</td>
	</tr>
	<tr>
		<td colspan="2">Используйте форму ниже для востановления пароля<br />
		и ваши данные будут отправлены вам на почту.<br />
		<br />
		Вы долны будете подтвердить запрос.</td>
	</tr>
	<tr>
		<td class="rowhead">Зарегистрированый email</td>
		<td><input type="text" size="40" name="email"></td>
	</tr>
	<?php
	if ($REL_CONFIG['use_captcha']) {
		require_once(ROOT_PATH.'include/recaptchalib.php');
		print '<tr><td colspan="2" align="center">'.$REL_LANG->say_by_key('you_people').'</td></tr>';
		print '<tr><td colspan="2" align="center">'.recaptcha_get_html($REL_CONFIG['re_publickey']).'</td></tr>';
	}
	?>
	<tr>
		<td colspan="2" align="center"><input type="submit"
			value="Восстановить"></td>
	</tr>
</table>
	<?
	$REL_TPL->stdfoot();
}

?>