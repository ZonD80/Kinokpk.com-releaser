<?php
/**
 * Profile edit parser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require_once("include/bittorrent.php");

$SETlang = substr(trim((string)$_POST["language"]),0,2);
setcookie("lang", $SETlang, 0x7fffffff);
INIT();

loggedinorreturn();

$email = (string)$_POST['email'];
$oldpassword = (string)$_POST['oldpassword'];
$chpassword = (string)$_POST['chpassword'];
$passagain = (string)$_POST['passagain'];

// $set = array();

$updateset = array();
$changedemail = 0;

if ($chpassword != "") {
	if (mb_strlen($chpassword) > 40)
	$REL_TPL->stderr($REL_LANG->_("Error"),$REL_LANG->_('Sorry, password is too long (maximus is 40 chars)'));
	if ($chpassword != $passagain)
	$REL_TPL->stderr($REL_LANG->_("Error"),$REL_LANG->_('Sorry, new password and confirmation are not the same'));
	
	if ($CURUSER["passhash"] != md5($CURUSER["secret"] . $oldpassword . $CURUSER["secret"]))
	$REL_TPL->stderr($REL_LANG->_("Error"),$REL_LANG->_('Sorry, you entered wrong old password'));
	

	$sec = mksecret();

	$passhash = md5($sec . $chpassword . $sec);
	$updateset[] = "secret = " . sqlesc($sec);
	$updateset[] = "passhash = " . sqlesc($passhash);

	logincookie($CURUSER["id"], $passhash, $CURUSER['language']);
	$passupdated = 1;
}
if ($email != $CURUSER["email"]) {
	if (!validemail($email))
	$REL_TPL->stderr($REL_LANG->_("Error"),$REL_LANG->_('Sorry, email format is invailid'));
	
	$r = $REL_DB->query("SELECT id FROM users WHERE email=" . sqlesc($email));
	if (mysql_num_rows($r) > 0)
	$REL_TPL->stderr($REL_LANG->_("Error"),$REL_LANG->_('Sorry, this email is already in use'));
	
	$changedemail = 1;
}

$username = trim((string)$_POST['username']);

if ($username&&($username<>$CURUSER['username'])) {
	get_privilege('change_nick');
	if (mb_strlen($username) > 12)
	$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Sorry, but your username is too big. It must be < 12 symbols. Please <a href="javascript:history.go(-1);">try again</a>.'));

	if (!validusername($username))
	$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Sorry, but you entered invalid username. Allowed characters are: a-Z0-9_. Please <a href="javascript:history.go(-1);">try again</a>.'));

	$check = @mysql_result($REL_DB->query("SELECT 1 FROM users WHERE username=".sqlesc($username)),0);
	if ($check)
	$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Sorry, but this username is already in use. Please <a href="javascript:history.go(-1);">try again</a> and select another.'));

	$username = sqlesc($username);
	$updateset[] = "username = ".$username;

	$REL_DB->query("INSERT INTO nickhistory (userid,nick,date) VALUES ({$CURUSER['id']},$username,".time().")");
}
$acceptpms = (string) $_POST["acceptpms"];
$deletepms = ($_POST["deletepms"] ? 1 : 0);
$savepms = ($_POST["savepms"] ? 1 : 0);
$avatars = ($_POST["avatars"]? 1 : 0);
$extra_ef = ($_POST["extra_ef"]? 1 : 0);

if (isset($_POST['timezone'])) $updateset[] = "timezone = ".(int)$_POST['timezone'];

if (!is_valid_id($_POST["gender"])) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('You are not man or woman?'));
$gender = (int)$_POST["gender"];
$updateset[] = "gender =  " . $gender;

///////////////// BIRTHDAY MOD /////////////////////

$birthday = @date("{$_POST["year"]}.{$_POST["month"]}.{$_POST["day"]}");
if (!$birthday) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('Enter your birthday please'));

///////////////// BIRTHDAY MOD /////////////////////
$updateset[] = "birthday = " . sqlesc($birthday);

if ($_POST['resetpasskey'])
$REL_DB->query("UPDATE xbt_users SET torrent_pass='' WHERE uid=".sqlesc($CURUSER[id]));

$info = trim((string)$_POST["info"]);
if (!is_valid_id($_POST["stylesheet"])) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('Invalid stylesheet selection'));
$stylesheet = $_POST["stylesheet"];
if (!is_valid_id($_POST['country'])) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('Invalid country selection'));
$country = (int)$_POST["country"];

$updateset[] = "language = " . sqlesc($SETlang);

$icq =  unesc((int)$_POST["icq"]);
if (mb_strlen($icq) > 10)
	$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('ICQ UIN is too long (max is 10 numbers)'));
$updateset[] = "icq = " . sqlesc($icq);

$msn = unesc($_POST["msn"]);
if (mb_strlen($msn) > 30)
	$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('MSN username is too long (max is 30 chars)'));
$updateset[] = "msn = " . sqlesc(htmlspecialchars($msn));

$aim = unesc($_POST["aim"]);
if (mb_strlen($aim) > 30)
	$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('AIM username is too long (max is 30 chars'));
$updateset[] = "aim = " . sqlesc(htmlspecialchars($aim));

$yahoo = unesc($_POST["yahoo"]);
if (mb_strlen($yahoo) > 30)
	$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Yahoo username is too long (max is 30 chars'));
$updateset[] = "yahoo = " . sqlesc(htmlspecialchars($yahoo));

$mirc = unesc($_POST["mirc"]);
if (mb_strlen($mirc) > 30)
	$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('mIRC username is too long (max is 30 chars)'));
$updateset[] = "mirc = " . sqlesc(htmlspecialchars($mirc));

$skype = unesc($_POST["skype"]);
if (mb_strlen($skype) > 20)
	$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Skype username is too long (max is 30 chars)'));
$updateset[] = "skype = " . sqlesc(htmlspecialchars($skype));

$privacy = (string)$_POST['privacy'];
if ($privacy != "normal" && $privacy != "highest" && $privacy != "strong")
$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_("Privacy level is unknown"));

$updateset[] = "privacy = '$privacy'";

$updateset[] = "stylesheet = $stylesheet";
$updateset[] = "country = $country";

//$updateset[] = "timezone = $timezone";
//$updateset[] = "dst = '$dst'";
$updateset[] = "info = " . sqlesc(cleanhtml(substr($info,0,$REL_CONFIG['sign_length'])));
$updateset[] = "acceptpms = " . sqlesc($acceptpms);
$updateset[] = "deletepms = '$deletepms'";
$updateset[] = "savepms = '$savepms'";
$updateset[] = "pron = ".($_POST['pron']?1:0);
$updateset[] = "avatars = '$avatars'";
$updateset[] = "extra_ef = '$extra_ef'";


if ($changedemail) {
	$sec = mksecret();
	$hash = md5($sec . $email . $sec);
	$obemail = urlencode($email);
	$updateset[] = "editsecret = " . sqlesc($sec);
	$body = <<<EOD
Вы подали запрос на изменения e-mail для пользователя {$CURUSER["username"]}
на {$REL_CONFIG['defaultbaseurl']}. Новым адресом станет:$email.

Если вы НЕ совершали действий, указанных в этом письме, то проигнорируйте это письмо.

Если вы действительно хотите изменить e-mail, то проследуйте по следующей ссылке:
	{$REL_CONFIG['defaultbaseurl']}/{$REL_SEO->make_link('confirmemail','id',$CURUSER['id'],'confirmcode',$hash,'email',$obemail)}

EOD;

	sent_mail($email, $REL_CONFIG['sitename'], $REL_CONFIG['siteemail'], "{$REL_CONFIG['defaultbaseurl']} подтверждение изменения профиля", $body);
	$string.= "<br /><h2>".$REL_LANG->say_by_key('my_mail_sent')."</h2>";
}

$REL_DB->query("UPDATE users SET " . implode(",", $updateset) . " WHERE id = " . $CURUSER["id"]);

setcookie("lang", (string) trim($_POST["language"]), 0x7fffffff, "/");
safe_redirect($REL_SEO->make_link('my'),1);
$REL_TPL->stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('my_updated').$string,'success');

?>