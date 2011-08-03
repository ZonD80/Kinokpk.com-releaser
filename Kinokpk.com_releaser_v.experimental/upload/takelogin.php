<?php
/**
 * Login parser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

if (!mkglobal("email:password"))
die();

INIT();



function bark($text = '')
{
	global $REL_LANG;
	if (!$text) $text = $REL_LANG->_("E-mail or password is invalid");
	stderr($REL_LANG->_("Login error"), $text);
}

$email = (string)$email;
if (!validemail($email) && !validusername($email)) bark($REL_LANG->_("Invalid e-mail or username format"));

//var_dump($password);
$res = sql_query("SELECT * FROM users WHERE email = " . sqlesc($email)." OR username = ". sqlesc($email));
$row = @mysql_fetch_array($res);



if (!$row) {
	stderr($REL_LANG->_('Error'),$REL_LANG->_('You have not registered on this site yet, or this combination of e-mail and password is invalid. You can <a href="%s">Register now</a> or <a href="javascript:history.go(-1);">Try again</a>.',$REL_SEO->make_link("signup")));
}


if (!$row["confirmed"])
bark('You account did not activated yet. Activate it frist then try to log in. You can <a href="%s">Resend activation letter</a>.',$REL_SEO->make_link('signup','resend','1'));

if ($row["passhash"] != md5($row["secret"] . $password . $row["secret"]))
bark();

logincookie($row["id"], $row["passhash"], $row["language"]);

if (!$row["enabled"])
bark($REL_LANG->_("Your account was disabled due reason: %s",$row[dis_reason]));

// Array creation
$row['notifs'] = explode(',',$row['notifs']);
$row['emailnotifs'] = explode(',',$row['emailnotifs']);
$row['custom_privileges'] = explode(',',$row['custom_privileges']);

$CURUSER = $row;

$returnto = strip_tags(trim((string)$_POST['returnto']));

$REL_TPL->stdhead($REL_LANG->_("Successful login"));
if ($returnto)
stdmsg($REL_LANG->_("Successful login"),"<a href=\"".$returnto."\">{$REL_LANG->_("Continue")}</a>");
else
stdmsg($REL_LANG->_("Successful login"),"<a href=\"".$REL_CONFIG['defaultbaseurl']."\">{$REL_LANG->_("Continue")}</a>");
$REL_TPL->stdfoot();

?>