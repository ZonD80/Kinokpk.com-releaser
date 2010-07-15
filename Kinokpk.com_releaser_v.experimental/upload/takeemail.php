<?
require_once("include/bittorrent.php");
dbconn();
$REL_LANG->load('takeemail');
loggedinorreturn();

if (get_user_class() < UC_SYSOP)
stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('access_denied'));

$res = sql_query("SELECT email FROM users") or sqlerr(__FILE__,__LINE__);
$counter = mysql_affected_rows();
while ($a = mysql_fetch_assoc($res))
{

	$subject = $_POST['subject'];
	if (!$subject)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('enter_topic'));

	$msg = $_POST['msg'];
	if (!$msg)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('enter_message'));

	$message = <<<EOD

	$msg

EOD;
	sent_mail($a["email"], $REL_CONFIG['sitename'], $REL_CONFIG['siteemail'], $subject, $message);
}
stdhead($REL_LANG->say_by_key('bulk_email'));
stdmsg($REL_LANG->say_by_key('success'), "".$REL_LANG->say_by_key('mailer_seccessful')." $counter ".$REL_LANG->say_by_key('messages')."");
stdfoot();
?>