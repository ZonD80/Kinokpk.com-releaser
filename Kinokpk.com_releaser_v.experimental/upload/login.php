<?php
/**
 * Login form
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

INIT();


if ($CURUSER)
stderr($REL_LANG->say_by_key('error'), "Вы уже вошли на {$REL_CONFIG['sitename']}!");

$REL_TPL->stdhead("Вход");

$returnto = strip_tags(trim((string)$_GET['returnto']));

if ($returnto)
if (!$_GET["nowarn"]) {
	$error = "<table style=\"margin: 0 auto\"><tr class=\"error_login\"><td colspan=\"2\" style=\"border:none\"><img src=\"pic/attention_login.gif\" alt=\"attention\"/></td><td colspan=\"2\" style=\"border:none; vertical-align: middle;\">{$REL_LANG->_("Sorry, but the page you required can only be accessed by <b>logged in users</b>.<br />Please log in to the system, and we will reditect you to this page after this.")}</td></tr></table>";
	//print("<h1>Не авторизированы!</h1>\n");
	//print("<p><b>Ошибка:</b> Страница, которую вы пытаетесь посмотреть, доступна только зарегистрированым.</p>\n");
}

if (isset($error)) {
	echo $error;
}
?>
<div align="center">
<form method="post" action="<?=$REL_SEO->make_link('takelogin')?>">
<p><b><?=$REL_LANG->_("Attention")?></b>: <?=$REL_LANG->_("For successfull login cookies must be enabled.")?></p>
<table border="0" cellpadding="5" width="450px"
	style="border: none; background: url(./pic/login.gif) no-repeat center; height: 150px;">
	<tr style="height: 30px">
		<td style="border: none;" class="rowhead"></td>
	</tr>
	<tr style="height: 30px">
		<td style="border: none; vertical-align: middle;" class="rowhead"><?=$REL_LANG->_("E-mail or nickname");?>:</td>
		<td align="left" style="border: none; vertical-align: bottom;"
			width="275px"><input style="border: 1px solid gray" name="email"
			value="<?=$REL_LANG->_("E-mail or nickname");?>" type="text"
			class="searchtextbox"
			onblur="if(this.value=='') this.value='<?=$REL_LANG->_("E-mail or nickname");?>';"
			onfocus="if(this.value=='<?=$REL_LANG->_("E-mail or nickname");?>') this.value='';" /></td>
	</tr>
	<tr style="height: 30px">
		<td class="rowhead" style="border: none; vertical-align: middle;"><?=$REL_LANG->_("Password");?>:</td>
		<td align="left" style="border: none;"><input
			style="border: 1px solid gray" name="password" value="password"
			type="password" class="searchtextbox"
			onblur="if(this.value=='') this.value='password';"
			onfocus="if(this.value=='password') this.value='';" /></td>
	</tr>
	<tr>
		<td colspan="2" align="center"
			style="border: none; vertical-align: top;"><input type="submit"
			value="<?=$REL_LANG->_("Login");?>" class="btn" /></td>
	</tr>
</table>
<?

if (isset($returnto))
print("<input type=\"hidden\" name=\"returnto\" value=\"" . ($returnto) . "\" />\n");

?></form>
<?=$REL_LANG->_('<p>If you forgot your password, try to recover it on <a href="%s">Password recovery page</a></p><p>You did not register yet? You can <a href="%s">Register now!</a></p>',$REL_SEO->make_link("recover"),$REL_SEO->make_link("signup"))?>
<?php if ($REL_CONFIG['use_email_act'])
print '<p>'.$REL_LANG->_('If you did not receive confirmation letter, you can <a href="%s">Resend</a> it.',$REL_SEO->make_link("signup","resend")).'</p>';
?></div>
<?

$REL_TPL->stdfoot();

?>