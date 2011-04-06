<?php

/**
 * Invites adder
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";

INIT();

loggedinorreturn();

get_privilege('add_invites');

if ($_SERVER["REQUEST_METHOD"] == "POST")

{

	if ($_POST["username"] == "" || $_POST["invites"] == "" || $_POST["invites"] == "")

	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('missing_data'));

	$username = sqlesc((string)$_POST["username"]);

	$invites = sqlesc((string)$_POST["invites"]);


	sql_query("UPDATE users SET invites=$invites WHERE username=$username") or sqlerr(__FILE__, __LINE__);

	$res = sql_query("SELECT id FROM users WHERE username=$username");

	$arr = mysql_fetch_row($res);

	if (!$arr)

	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('un_upd_acc'));

	safe_redirect($REL_SEO->make_link('userdetails','id',$arr[0],'username',translit($username)));

	die;

}

$REL_TPL->stdhead($REL_LANG->say_by_key('upd_users_inv_amn'));

?>

<h1><?=$REL_LANG->say_by_key('upd_users_inv_amn')?></h1>

<form method=post action="<?=$REL_SEO->make_link('inviteadd')?>">

<table border=1 cellspacing=0 cellpadding=5>

	<tr>
		<td class=rowhead><?=$REL_LANG->say_by_key('user_name')?></td>
		<td><input type=text name=username size=40></td>
	</tr>

	<tr>
		<td class=rowhead><?=$REL_LANG->say_by_key('invites')?></td>
		<td><input name=invites size=5></td>
	</tr>

	<tr>
		<td colspan=2 align=center><input type=submit value="Okay" class=btn></td>
	</tr>

</table>

</form>

<? $REL_TPL->stdfoot(); ?>