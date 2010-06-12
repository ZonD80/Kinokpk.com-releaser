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
dbconn();
getlang('delacctadmin');
loggedinorreturn();

if (get_user_class() < UC_ADMINISTRATOR)
stderr($tracker_lang['error'], $tracker_lang['no_access']);

httpauth();

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
	$username = htmlspecialchars(trim((string)$_POST["username"]));

	if (!$username)
	stderr($tracker_lang['error'], $tracker_lang['fill_form']);

	$res = sql_query("SELECT * FROM users WHERE username=" . sqlesc($username)) or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($res) != 1)
	stderr($tracker_lang['error'], $tracker_lang['invalid_username']);
	$arr = mysql_fetch_assoc($res);

	$id = $arr['id'];
	$avatar = $arr['avatar'];
	$class = $arr['class'];
	if ($class >= $CURUSER['class']) stderr($tracker_lang['error'], $tracker_lang['cant_dell_acc']);
	delete_ipb_user($arr["username"]);
	delete_user($id);
	@unlink(ROOT_PATH.$avatar);
	stderr($tracker_lang['success'], "".$tracker_lang['account']." <b>$username</b> ".$tracker_lang['removed']."");
}
stdhead($tracker_lang['delete_account']);
?>
<h1><?=$tracker_lang['delete_account']?></h1>
<table border=1 cellspacing=0 cellpadding=5>
	<form method=post action=delacctadmin.php>
	<tr>
		<td class=rowhead><?=$tracker_lang['username']?></td>
		<td><input size=40 name=username></td>
	</tr>

	<tr>
		<td colspan=2><input type=submit class=btn
			value='<?=$tracker_lang['remove']?>'></td>
	</tr>
	</form>
</table>
<?
stdfoot();
?>
