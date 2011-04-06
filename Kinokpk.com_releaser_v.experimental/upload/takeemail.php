<?php

/**
 * Email sender for sysops
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
INIT();

loggedinorreturn();

get_privilege('send_emails');

$res = sql_query("SELECT email FROM users") or sqlerr(__FILE__,__LINE__);
$counter = mysql_affected_rows();
while ($a = mysql_fetch_assoc($res))
{

	$subject = htmlspecialchars((string)$_POST['subject']);
	if (!$subject)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('enter_topic'));

	$msg = cleanhtml((string)$_POST['msg']);
	if (!$msg)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('enter_message'));

	$message = <<<EOD

	$msg

EOD;
	sent_mail($a["email"], $REL_CONFIG['sitename'], $REL_CONFIG['siteemail'], $subject, $message);
}
$REL_TPL->stdhead($REL_LANG->say_by_key('bulk_email'));
stdmsg($REL_LANG->say_by_key('success'), "".$REL_LANG->say_by_key('mailer_seccessful')." $counter ".$REL_LANG->say_by_key('messages')."");
$REL_TPL->stdfoot();
?>