<?php

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

require_once "include/bittorrent.php";

dbconn();

loggedinorreturn();

if (get_user_class() < UC_SYSOP)

stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('access_denied'));

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")

{

	if ($HTTP_POST_VARS["username"] == "" || $HTTP_POST_VARS["invites"] == "" || $HTTP_POST_VARS["invites"] == "")

	stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('missing_data'));

	$username = sqlesc($HTTP_POST_VARS["username"]);

	$invites = sqlesc($HTTP_POST_VARS["invites"]);


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