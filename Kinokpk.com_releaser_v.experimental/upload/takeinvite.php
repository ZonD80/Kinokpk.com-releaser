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

function bark($msg)
{
    $REL_TPL->stdhead();
    $REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $msg);
    $REL_TPL->stdfoot();
    die;
}

$id = (int)$_GET["id"];
if (!$id) $id = (int)$_POST['id'];
if (!$id)
    $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

if (get_privilege('approve_invites', false))
    $id = $CURUSER["id"];

$hash = md5(mt_rand(1, 1000000));
if ($REL_CONFIG['use_captcha']) {

    require_once('include/recaptchalib.php');
    $resp = recaptcha_check_answer($REL_CONFIG['re_privatekey'],
        $_SERVER["REMOTE_ADDR"],
        $_POST["recaptcha_challenge_field"],
        $_POST["recaptcha_response_field"]);

    if (!$resp->is_valid) {
        $REL_TPL->stderr($REL_LANG->say_by_key('error'), "{$REL_LANG->_('Invation code is wrong. Please try again')}. <a href=\"javascript:history.go(-1);\">{$REL_LANG->_('Go back')}</a>");
    }

}
$email = trim((string)$_POST['email']);
if (!validemail($email)) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Sorry, but you entered invalid e-mail. Please <a href="javascript:history.go(-1);">try again</a>.'));

$res = $REL_DB->query("SELECT 1 FROM users WHERE email='$email'");
$check = @mysql_result($res, 0);
if ($check) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Sorry, but your email (%s) is already registered. Please <a href="javascript:history.go(-1);">try again</a>.', $email));

$subject = $REL_LANG->_to(0, 'Invation to %s', $REL_CONFIG['sitename']);
$body = $REL_LANG->_to(0, 'Hello! Your friend (%s) just invited you to %s. If you want to join us, please coutinue registration by <a href="%s">visiting this link</a>. Thanks!', make_user_link(), $REL_CONFIG['sitename'], $REL_SEO->make_link('signup', 'h', $hash));

$REL_DB->query("INSERT INTO invites (inviter, invite, time_invited) VALUES (" . implode(", ", array_map("sqlesc", array($id, $hash, TIME))) . ")");

$REL_DB->query("INSERT INTO cron_emails (emails, subject, body) VALUES (" . sqlesc($email) . "," . sqlesc($subject) . "," . sqlesc($body) . ")");

safe_redirect($REL_SEO->make_link('invite', 'id', $id));

?>