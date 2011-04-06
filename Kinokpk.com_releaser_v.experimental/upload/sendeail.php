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
define("IN_CONTACT",true);
INIT();

if (!$CURUSER) {
	require_once('include/recaptchalib.php');
	$resp = recaptcha_check_answer ($REL_CONFIG['re_privatekey'],
	$_SERVER["REMOTE_ADDR"],
	$_POST["recaptcha_challenge_field"],
	$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('test_humanity'));
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
	stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('check_address'), error);
	$REL_TPL->stdfoot();
	die();
}

if(empty($subj))
{
	stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('not_subject'), error);
	$REL_TPL->stdfoot();
	die();
}

if(empty($visitor))
{
	stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('not_name_sender'), error);
	$REL_TPL->stdfoot();
	die();
}

if(empty($notes))
{
	stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('not_text_message'), error);
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

<?
stdmsg($REL_LANG->say_by_key('thanks'), $REL_LANG->say_by_key('message_sent'));
?>
<br />
<br />
<center><a href="<?=$REL_SEO->make_link('index');?>"> <?=$REL_LANG->say_by_key('home')?>
</a></center>
<?
$REL_TPL->stdfoot();
?>