<?php
/**
 * Delete user accounts via admincp
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";
INIT();

loggedinorreturn();

get_privilege('delete_site_users');

httpauth();

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
	$email = htmlspecialchars(trim((string)$_POST["email"]));

	if (!$email)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('fill_form'));

	$res = sql_query("SELECT * FROM users WHERE email=" . sqlesc($email)) or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($res) != 1)
	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_email'));
	$arr = mysql_fetch_assoc($res);

	$id = $arr['id'];
	$avatar = $arr['avatar'];
	$class = $arr['class'];
	if ($class >= $CURUSER['class']) stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('cant_dell_acc'));
	delete_user($id);
	@unlink(ROOT_PATH.$avatar);
	stderr($REL_LANG->say_by_key('success'), "".$REL_LANG->say_by_key('account')." <b>$email</b> ".$REL_LANG->say_by_key('removed')."");
}
$REL_TPL->stdhead($REL_LANG->say_by_key('delete_account'));
?>
<h1><?=$REL_LANG->say_by_key('delete_account')?></h1>
<table border=1 cellspacing=0 cellpadding=5>
	<form method=post action="<?=$REL_SEO->make_link('delacctadmin')?>">
	<tr>
		<td class=rowhead><?=$REL_LANG->say_by_key('email')?></td>
		<td><input size=40 name=email></td>
	</tr>

	<tr>
		<td colspan=2><input type=submit class=btn
			value='<?=$REL_LANG->say_by_key('remove')?>'></td>
	</tr>
	</form>
</table>
<?
$REL_TPL->stdfoot();
?>
