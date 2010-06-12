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
dbconn();
getlang('sendeail');
if (!$CURUSER) {
	require_once('include/recaptchalib.php');
	$resp = recaptcha_check_answer ($CACHEARRAY['re_privatekey'],
	$_SERVER["REMOTE_ADDR"],
	$_POST["recaptcha_challenge_field"],
	$_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		stderr($tracker_lang['error'], $tracker_lang['test_humanity']);
	}
}

stdhead($tracker_lang['send_email_admin']);
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
	stdmsg($tracker_lang['error'], $tracker_lang['check_address'], error);
	stdfoot();
	die();
}

if(empty($subj))
{
	stdmsg($tracker_lang['error'], $tracker_lang['not_subject'], error);
	stdfoot();
	die();
}

if(empty($visitor))
{
	stdmsg($tracker_lang['error'], $tracker_lang['not_name_sender'], error);
	stdfoot();
	die();
}

if(empty($notes))
{
	stdmsg($tracker_lang['error'], $tracker_lang['not_text_message'], error);
	stdfoot();
	die();

}

$to = $CACHEARRAY['adminemail'];
$notes = stripcslashes($notes);

$msg .= $tracker_lang['message_from']."                       $visitor<br />";
$msg .= $tracker_lang['ip_sender']."                     $ip<br />";
$msg .= $tracker_lang['email_sender']."                 $visitormail<br />";
$msg .= $tracker_lang['subject']."                     $subj<br />";
$msg .= $tracker_lang['message']."                          $notes<br /><br />";
$msg .= $tracker_lang['user_agent']."                         $httpagent";

$subject = $subj;
sent_mail($to, $subj.' | '.$CACHEARRAY['sitename'], $visitormail, $subject, $msg) or die('Mail error');

?>

<?
stdmsg($tracker_lang['thanks'], $tracker_lang['message_sent']);
?>
<br />
<br />
<center><a href="index.php"> <?=$tracker_lang['home']?> </a></center>
<?
stdfoot();
?>