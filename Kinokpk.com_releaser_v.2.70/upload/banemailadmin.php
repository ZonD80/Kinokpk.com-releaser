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
loggedinorreturn();
gzip();
httpauth();

if (get_user_class() < UC_ADMINISTRATOR)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);



if (isset($_GET['remove']) && is_valid_id($_GET['remove']))
{
	$remove = (int) $_GET['remove'];

	sql_query("DELETE FROM bannedemails WHERE id = '$remove'") or sqlerr(__FILE__, __LINE__);
	write_log("Бан $remove был снят пользавателям $CURUSER[username]");
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$email = trim($_POST["email"]);
	$comment = trim($_POST["comment"]);
	if (!$email || !$comment)
	stderr("Error", "Missing form data.");
	sql_query("INSERT INTO bannedemails (added, addedby, comment, email) VALUES(".sqlesc(time()).", $CURUSER[id], ".sqlesc($comment).", ".sqlesc($email).")") or sqlerr(__FILE__, __LINE__);
	header("Location: $_SERVER[REQUEST_URI]");
	die;
}

$res = sql_query("SELECT * FROM bannedemails ORDER BY added DESC") or sqlerr(__FILE__, __LINE__);

stdhead("Бан Емайлов");

print("<h1>Список банов</h1>\n");

if (mysql_num_rows($res) == 0)
print("<p align=center><b>Пусто</b></p>\n");
else
{
	print("<table border=1 cellspacing=0 cellpadding=5>\n");
	print("<tr><td class=colhead>Поставлен</td><td class=colhead align=left>Email</td>".
        "<td class=colhead align=left>Кем</td><td class=colhead align=left>Коментарий</td><td class=colhead>Снять</td></tr>\n");

	while ($arr = mysql_fetch_assoc($res))
	{
		$r2 = mysql_query("SELECT username FROM users WHERE id = $arr[addedby]") or sqlerr(__FILE__, __LINE__);
		$a2 = mysql_fetch_assoc($r2);
		print("<tr><td>$arr[added]</td><td align=left>$arr[email]</td><td align=left><a href=userdetails.php?id=$arr[addedby]>$a2[username]".
                "</a></td><td align=left>$arr[comment]</td><td><a href=banemailadmin.php?remove=$arr[id]>Снять бан</a></td></tr>\n");
	}
	print("</table>\n");
}

print("<h2>Забанить</h2>\n");
print("<table border=1 cellspacing=0 cellpadding=5>\n");
print("<form method=\"post\" action=\"banemailadmin.php\">\n");
print("<tr><td class=rowhead>Email</td><td><input type=\"text\" name=\"email\" size=\"40\"></td>\n");
print("<tr><td class=rowhead>Коментарий</td><td><input type=\"text\" name=\"comment\" size=\"40\"></td>\n");
print("<tr><td colspan=2>Изпользуйте *@email.com чтобы забанить весь домейн</td></tr>\n");
print("<tr><td colspan=2><input type=\"submit\" value=\"Забанить\" class=\"btn\"></td></tr>\n");
print("</form>\n</table>\n");

stdfoot();

?>