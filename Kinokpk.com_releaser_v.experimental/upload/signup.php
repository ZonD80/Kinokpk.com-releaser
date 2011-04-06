<?php
/**
 * Signup script
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
INIT();


//echo "<body><META http-equiv=\"refresh\" content=\"3; URL=$REL_CONFIG['forumurl']/index.php?act=Reg&CODE=00\"></body>";
//stderr($REL_LANG->say_by_key('error'), "Наш проект осуществляется совместно с форумом $REL_CONFIG['forumname'], пройдите регистрацию на нем, а затем просто войдите под своим логином и паролем. Через 3 секунды вы будете перенаправлены.");

if ($REL_CONFIG['deny_signup'] && !$REL_CONFIG['allow_invite_signup'])
stderr($REL_LANG->say_by_key('error'), $REL_LANG->_("Registration disabled by administration."));

if ($CURUSER)
stderr($REL_LANG->say_by_key('error'), sprintf($REL_LANG->say_by_key('signup_already_registered'), $REL_CONFIG['sitename']));

if ($REL_CONFIG['maxusers']) {$users = get_row_count("users");
if ($users >= $REL_CONFIG['maxusers'])
stderr($REL_LANG->say_by_key('error'), sprintf($REL_LANG->say_by_key('signup_users_limit'), number_format($REL_CONFIG['maxusers'])));
}
if (!$_POST["agree"]) {
	$REL_TPL->stdhead("Правила трекера");
	?>
<form method="post" action="<?=$REL_SEO->make_link('signup')?>">
<div align="center">
<fieldset class="fieldset"><legend><?=$REL_LANG->_("Site rules");?></legend>
<table cellpadding="4" cellspacing="0" border="0" style="width: 100%"
	class="tableinborder">
	<tr>
		<td class="tablea"><?=$REL_LANG->_("To proceed registration you must agreee the following terms");?>:</td>
	</tr>
	<tr>
		<td class="tablea"
			style="font-size: 11px; font-style: normal; font-variant: normal; font-weight: normal; font-family: verdana, geneva, lucida, 'lucida grande', arial, helvetica, sans-serif">
		<div class="page"
			style="border-right: thin inset; padding-right: 6px; border-top: thin inset; padding-left: 6px; padding-bottom: 6px; overflow: auto; border-left: thin inset; padding-top: 1px; border-bottom: thin inset; height: 275px">
		<p><strong>Правила <?=$REL_CONFIG['sitename']?></strong></p>
		<p>Регистрация на трекере абсолютно бесплатна! Настоятельно
		рекомендуем ознакомиться с правилами нашего проекта. Если вы согласны
		со всеми условиями, поставьте галочку рядом с 'Я согласен' и нажмите
		'Регистрация'. Если вы передумали регистрироваться, нажмите <a
			href="<?=$REL_CONFIG['defaultbaseurl'];?>">здесь</a>, чтобы вернуться
		на главную страницу.</p>
		<p>Хотя модераторы и администраторы, обслуживающие сайт, стараются
		удалять все оскорбительные и некорректные сообщения из трекера, все
		равно все сообщения просмотреть невозможно. Сообщения отражают точку
		зрения только автора, но не администрации трекера, соответственно
		только автор несет ответственность за содержание сообщения.</p>
		<p>Соглашаясь с нашими правилами, вы обязуетесь выполнять требования
		трекера в целом, а также требования законодательства РФ.</p>
		<p>Администрация трекера оставляет за собой право удалять, изменять,
		переносить или закрывать любую тему или сообщение по своему
		усмотрению.</p>
		</div>
		</td>
	</tr>
	<tr>
		<td class="tablea">
		<div><label> <input class="tablea" type="checkbox" name="agree"
			value="1"> <input type="hidden" name="do" value="register"> <strong><?=$REL_LANG->_("I agree with this rules");?></strong>
		</label></div>
		</td>
	</tr>
</table>
</fieldset>
<p><input class="tableinborder" type="submit" value="Регистрация"></p>
</div>
</form>
	<?
	$REL_TPL->stdfoot();
	die;
}

$REL_TPL->stdhead($REL_LANG->say_by_key('signup_signup'));

?>
<span style="color: red; font-weight: bold;"><?=$REL_LANG->say_by_key('signup_use_cookies');?></span>

<?
if ($REL_CONFIG['deny_signup'] && $REL_CONFIG['allow_invite_signup'])
stdmsg($REL_LANG->_("Attention"), $REL_LANG->_("Only invite registrations are allowed!"));
?>
<form method="post" action="<?=$REL_SEO->make_link('takesignup');?>">
<table border="1" cellspacing=0 cellpadding="10">
	<tr valign=top>
		<td align="right" class="heading"><?=$REL_LANG->say_by_key('signup_email');?></td>
		<td align=left><input type="text" size="40" name="email" />
		<table width=250 border=0 cellspacing=0 cellpadding=0>
			<tr>
				<td class=embedded><font class=small><?=$REL_LANG->_("This email must be used to login this site.").($REL_CONFIG['use_email_act']?$REL_LANG->_("<br/>Confirmation letter will be sent to this address"):'')?></font></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td align="right" class="heading"><?=$REL_LANG->_('Nickname');?></td>
		<td align=left><input type="text" size="40" name="wantusername" /></td>
	</tr>
	<tr>
		<td align="right" class="heading"><?=$REL_LANG->say_by_key('signup_password');?></td>
		<td align=left><input type="password" size="40" name="wantpassword" /></td>
	</tr>
	<tr>
		<td align="right" class="heading"><?=$REL_LANG->say_by_key('signup_password_again');?></td>
		<td align=left><input type="password" size="40" name="passagain" /></td>
	</tr>
	<?php
	if ($REL_CONFIG['use_captcha']) {

		require_once('include/recaptchalib.php');
		tr($REL_LANG->_("Are you a human?"),recaptcha_get_html($REL_CONFIG['re_publickey']),1,1);

	}

	if ($REL_CONFIG['allow_invite_signup']) {
		tr($REL_LANG->_("Invite code"), "<p>{$REL_LANG->_("If you have an invite code, past it into field below")}</p><input type=\"text\" name=\"invite\" maxlength=\"32\" size=\"32\" />", 1);
	}

	$returnto = trim((string)$_GET['returnto']);
	if (isset($returnto))
	print("<input type=\"hidden\" name=\"returnto\" value=\"" . urlencode(strip_tags($returnto)) . "\" />\n");

	?>
	<tr>
		<td colspan="2" align="center"><input type="submit"
			value="<?=$REL_LANG->_("Registrer now!");?>" style='height: 25px' /></td>
	</tr>
</table>
</form>

	<?php
	$REL_TPL->stdfoot();

	?>