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

if (get_user_class() < UC_SYSOP)
stderr($tracker_lang['error'], "Нет доступа.");

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] == "POST")
{
$username = trim($_POST["username"]);

if (!$username)
  stderr($tracker_lang['error'], "Пожалуста заполняйте форму корректно.");

$res = sql_query("SELECT * FROM users WHERE username=" . sqlesc($username)) or sqlerr(__FILE__, __LINE__);
if (mysql_num_rows($res) != 1)
  stderr($tracker_lang['error'], "Неверное имя пользователя. Проверьте введеные данные.");
$arr = mysql_fetch_assoc($res);

$id = $arr['id'];
$res = sql_query("DELETE FROM users WHERE id = $id") or sqlerr(__FILE__, __LINE__);
if (mysql_affected_rows() != 1)
  stderr($tracker_lang['error'], "Невозможно удалить аккаунт.");
sql_query("DELETE FROM messages WHERE receiver = $id") or sqlerr(__FILE__,__LINE__);
sql_query("DELETE FROM friends WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
sql_query("DELETE FROM friends WHERE friendid = $id") or sqlerr(__FILE__,__LINE__);
sql_query("DELETE FROM blocks WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
sql_query("DELETE FROM blocks WHERE blockid = $id") or sqlerr(__FILE__,__LINE__);
sql_query("DELETE FROM bookmarks WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
sql_query("DELETE FROM invites WHERE inviter = $id") or sqlerr(__FILE__,__LINE__);
sql_query("DELETE FROM peers WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
sql_query("DELETE FROM readtorrents WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
sql_query("DELETE FROM simpaty WHERE fromuserid = $id") or sqlerr(__FILE__,__LINE__);
sql_query("DELETE FROM addedrequests WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
sql_query("DELETE FROM checkcomm WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
sql_query("DELETE FROM offervotes WHERE userid = $id") or sqlerr(__FILE__,__LINE__);
sql_query("DELETE FROM sessions WHERE uid = $id") or sqlerr(__FILE__,__LINE__);
stderr($tracker_lang['success'], "Аккаунт <b>$username</b> удален.");
}
stdhead("Удалить аккаунт");
?>
<h1>Удалить аккаунт</h1>
<table border=1 cellspacing=0 cellpadding=5>
<form method=post action=delacctadmin.php>
<tr><td class=rowhead>Пользователь</td><td><input size=40 name=username></td></tr>

<tr><td colspan=2><input type=submit class=btn value='Удалить'></td></tr>
</form>
</table>
<?
stdfoot();
?>