<?php
/**
 * Email sender to administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();
loggedinorreturn();

if (!is_valid_id($_GET["id"]))
$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

$id = (int) $_GET["id"];

$res = $REL_DB->query("SELECT username, class, email FROM users WHERE id=$id");
$arr = mysql_fetch_assoc($res) or $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Invalid ID'));
$username = $arr["username"];

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$to = $arr["email"];

	$from = substr(trim((string)$_POST["from"]), 0, 80);
	if ($from == "") $from = $REL_LANG->_('Anonymous');

	$from_email = substr(trim((string)$_POST["from_email"]), 0, 80);
	if ($from_email == "") $from_email = $REL_CONFIG['siteemail'];
	if (!validemail($from_email)) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Sorry, but you entered invalid e-mail. Please <a href="javascript:history.go(-1);">try again</a>.'));

	$from = "$from <$from_email>";

	$subject = substr(trim((string)$_POST["subject"]), 0, 80);
	if ($subject == "") $subject = $REL_LANG->_('No subject');

	$message = trim((string)$_POST["message"]);
	if ($message == "") $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Message is missing'));

	$message = "{$REL_LANG->_to(0,'This message sent from %s at %s',$CURUSER['username'],date("Y-m-d H:i:s"))}.\n" .
		"{$REL_LANG->_to(0,'If you want to reply to this mail, you will show your ip address')}.\n" .
		"---------------------------------------------------------------------\n\n" .
	$message . "\n\n" .
		"---------------------------------------------------------------------\n{$REL_CONFIG['sitename']}\n";

	$success = sent_mail($to, $REL_CONFIG['sitename'], $REL_CONFIG['siteemail'], $subject, $message);

	if ($success)
	$REL_TPL->stderr($REL_LANG->say_by_key('success'), $REL_LANG->_('E-mail successfully sent'));
	else
	$REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Error while sending email. Please contact site administration.'));
}

$REL_TPL->stdhead($REL_LANG->_('Send e-mail'));
?>
<table border=1 cellspacing=0 cellpadding=5>
	<tr>
		<td class=colhead colspan=2><?php print $REL_LANG->_('Send e-mail to %s',$username); ?></td>
	</tr>
	<form method=post
		action="<?php print $REL_SEO->make_link('email-gateway','id',$id); ?>">
	<tr>
		<td class=rowhead><?php print $REL_LANG->_('Username'); ?></td>
		<td><input type=text name=from size=80 value=<?php print $CURUSER["username"]; ?>
			disabled></td>
	</tr>
	<tr>
		<td class=rowhead>e-mail</td>
		<td><input type=text name=from_email size=80
			value=<?php print $CURUSER["email"]; ?> disabled></td>
	</tr>
	<tr>
		<td class=rowhead><?php print $REL_LANG->_('Subject'); ?></td>
		<td><input type=text name=subject size=80></td>
	</tr>
	<tr>
		<td class=rowhead><?php print $REL_LANG->_('Message'); ?></td>
		<td><textarea name=message cols=80 rows=20></textarea></td>
	</tr>
	<tr>
		<td colspan=2 align=center><input type=submit value="<?php print $REL_LANG->_('Send'); ?>"
			class=btn></td>
	</tr>
	</form>
</table>
<p><font class=small><?php print $REL_LANG->_('Attention! Your IP address will be recorded due to security. Please verify that you correctly filled your e-mail if you expecting an answer.'); ?></font>
</p>
<?php $REL_TPL->stdfoot(); ?>