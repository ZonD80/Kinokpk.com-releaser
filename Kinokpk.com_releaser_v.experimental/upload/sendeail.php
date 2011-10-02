<?php
/**
 * User email sender
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
define("IN_CONTACT",true);
INIT();

if (!$CURUSER) {
	require_once('include/recaptchalib.php');
	$resp = recaptcha_check_answer ($REL_CONFIG['re_privatekey'],
	$_SERVER["REMOTE_ADDR"],
	$_POST["recaptcha_challenge_field"],
	$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('test_humanity'));
	}
}

$REL_TPL->stdhead($REL_LANG->say_by_key('send_email_admin'));
?>

<?php
$ip = getip();
$httpagent = $_SERVER['HTTP_USER_AGENT'];
$visitor = htmlspecialchars((string)$_POST['visitor']);
$visitormail = (string)$_POST['visitormail'];
$notes = htmlspecialchars((string)$_POST['notes']);
$subj = htmlspecialchars((string)$_POST['subj']);

if(!validemail($visitormail))
{
	$REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('check_address'), error);
	$REL_TPL->stdfoot();
	die();
}

if(empty($subj))
{
	$REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('not_subject'), error);
	$REL_TPL->stdfoot();
	die();
}

if(empty($visitor))
{
	$REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('not_name_sender'), error);
	$REL_TPL->stdfoot();
	die();
}

if(empty($notes))
{
	$REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('not_text_message'), error);
	$REL_TPL->stdfoot();
	die();

}

$to = $REL_CONFIG['adminemail'];
$notes = stripcslashes($notes);

$msg .= $REL_LANG->say_by_key('message_from')."                       $visitor<br />";
$msg .= $REL_LANG->say_by_key('ip_sender')."                     $ip<br />";
$msg .= $REL_LANG->say_by_key('email_sender')."                 $visitormail<br />";
$msg .= $REL_LANG->say_by_key('subject')."                     $subj<br />";
$msg .= $REL_LANG->say_by_key('message')."                          $notes<br /><br />";
$msg .= $REL_LANG->say_by_key('user_agent')."                         $httpagent";

$subject = $subj;
sent_mail($to, $subj.' | '.$REL_CONFIG['sitename'], $visitormail, $subject, $msg) or die('Mail error');

?>

<?php
$REL_TPL->stdmsg($REL_LANG->say_by_key('thanks'), $REL_LANG->say_by_key('message_sent'));
?>
<br />
<br />
<center><a href="<?php print $REL_SEO->make_link('index'); ?>"> <?php print $REL_LANG->say_by_key('home'); ?>
</a></center>
<?php
$REL_TPL->stdfoot();
?>