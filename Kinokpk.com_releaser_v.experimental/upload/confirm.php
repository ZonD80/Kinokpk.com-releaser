<?php
/**
 * Email confirmation script
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

$md5 = $_GET["secret"];


INIT();

if (!is_valid_id($_GET["id"])) 			stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
$id = (int) $_GET["id"];

$res = sql_query("SELECT passhash, editsecret, confirmed, language FROM users WHERE id = $id");
$row = mysql_fetch_array($res);

if (!$row)
stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

if ($row["confirmed"]) {
	stderr($REL_LANG->_("Error"),$REL_LANG->_("Your account was already confirmed."));
}

$sec = hash_pad($row["editsecret"]);
if ($md5 != md5($sec))
stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('Invalid confirmation code'));

sql_query("UPDATE users SET confirmed=1, editsecret='' WHERE id=$id AND confirmed=0");

if (!mysql_affected_rows())
stderr($REL_LANG->say_by_key('error'),$REL_LANG->_('Error changing confirm status. Contact site admin please.'));


logincookie($id, $row["passhash"],$row['language']);

safe_redirect($REL_SEO->make_link("my"),3);
stdmsg($REL_LANG->_("Signup successful"),($REL_CONFIG['use_email_act'] ? sprintf($REL_LANG->say_by_key('confirmation_mail_sent'), htmlspecialchars($email)) : sprintf($REL_LANG->say_by_key('thanks_for_registering'), $REL_CONFIG['sitename']).' '.$REL_LANG->_('Now you will be redirected to <a href="%s">your profile</a> to add additional data for your account.',$REL_SEO->make_link("my"))));
$REL_TPL->stdfoot();


?>