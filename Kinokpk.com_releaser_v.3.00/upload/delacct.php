<?

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

require "include/bittorrent.php";
dbconn();
getlang('delacct');

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$username = trim((string)$_POST["username"]);
	$password = trim((string)$_POST["password"]);
	if (!$username || !$password)
	stderr($tracker_lang['error'], $tracker_lang['fill_form']);
	$res = sql_query("SELECT * FROM users WHERE username=" . sqlesc($username) .
  " AND passhash=md5(concat(secret,concat(" . sqlesc($password) . ",secret)))") or sqlerr(__FILE__, __LINE__);
	if (mysql_num_rows($res) != 1)
	stderr($tracker_lang['error'], $tracker_lang['invalid_username']);
	$arr = mysql_fetch_assoc($res);
	if ($arr['class']>=UC_MODERATOR) stderr($tracker_lang['error'],$tracker_lang['cant_del_acc']);
	$id = $arr['id'];
	$avatar = $arr['avatar'];
	delete_ipb_user($arr["username"]);
	delete_user($id);
	@unlink(ROOT_PATH.$avatar);
	// if (mysql_affected_rows() != 1)     stderr($tracker_lang['error'], $tracker_lang['cant_del_acc']);
	stderr($tracker_lang['success'], $tracker_lang['account_deleted']);
}
stdhead($tracker_lang['delete_account']);
?>
<h1></h1>
<table border="1" cellspacing="0" cellpadding="5">
	<form method="post" action="delacct.php">
	<tr>
		<td class="colhead" colspan="2">
		<center><?=$tracker_lang['delete_account']?></center>
		</td>
	</tr>
	<tr>
		<td class="rowhead"><?=$tracker_lang['username']?></td>
		<td><input size="40" name="username"></td>
	</tr>
	<tr>
		<td class="rowhead"><?=$tracker_lang['password']?></td>
		<td><input type="password" size="40" name="password"></td>
	</tr>
	<tr>
		<td colspan="2" align="center"><input type="submit" class="btn"
			value="<?=$tracker_lang['remove']?>"></td>
	</tr>
	</form>
</table>
<?
stdfoot();
?>