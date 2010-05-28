<?
require_once("include/bittorrent.php");
dbconn();
getlang('takeemail');
loggedinorreturn();

if (get_user_class() < UC_SYSOP)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);

$res = sql_query("SELECT email FROM users") or sqlerr(__FILE__,__LINE__);
$counter = mysql_affected_rows();
while ($a = mysql_fetch_assoc($res))
{

	$subject = $_POST['subject'];
	if (!$subject)
	stderr($tracker_lang['error'], $tracker_lang['enter_topic']);

	$msg = $_POST['msg'];
	if (!$msg)
	stderr($tracker_lang['error'], $tracker_lang['enter_message']);

	$message = <<<EOD

	$msg

EOD;
	sent_mail($a["email"], $CACHEARRAY['sitename'], $CACHEARRAY['siteemail'], $subject, $message, false);
}
stdhead($tracker_lang['bulk_email']);
stdmsg($tracker_lang['success'], "".$tracker_lang['mailer_seccessful']." $counter ".$tracker_lang['messages']."");
stdfoot();
?>