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

dbconn(false);

if (get_user_class() < UC_SYSOP)
  die('Access denied');


if (is_valid_id($_GET['remove']))
{
  $remove = $_GET['remove'];
  sql_query("DELETE FROM bans WHERE id=$remove") or sqlerr(__FILE__, __LINE__);
  	sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='bans_lastupdate'");
  }

if ($_SERVER["REQUEST_METHOD"] == "POST" && get_user_class() >= UC_SYSOP)
{
  $mask = trim($_POST['mask']);
	if (!$mask)
		stderr($tracker_lang['error'], $tracker_lang['missing_form_data']);
  $mask = sqlesc(htmlspecialchars($mask));
	sql_query("INSERT INTO bans (mask) VALUES($mask)") or sqlerr(__FILE__, __LINE__);
	sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='bans_lastupdate'");
	header("Location: $DEFAULTBASEURL$_SERVER[REQUEST_URI]");
	die;
}

gzip();

$res = sql_query("SELECT * FROM bans ORDER BY id DESC") or sqlerr(__FILE__, __LINE__);

stdhead("Забаненые IP/Подсети маски провайдеров");

if (mysql_num_rows($res) == 0)
  print("<p align=\"center\"><b>".$tracker_lang['nothing_found']."</b></p>\n");

else
{
  //print("<table border=1 cellspacing=0 cellpadding=5>\n");
  print('<table width="100%" border="1">');
  print("<tr><td class=\"colhead\" colspan=\"6\">Забаненые IP/Подсети маски провайдеров</td></tr>\n");
  print("<tr><td class=\"colhead\" align=\"left\">Маска/IP</td><td class=\"colhead\">Удалить</td></tr>\n");

  while ($arr = mysql_fetch_assoc($res))
  {

 	  print("<tr><td  class=\"row1\" align=\"left\">$arr[mask]</td>".
 	    "<td  class=\"row1\"><a href=\"bans.php?remove=$arr[id]\">Удалить</a></td></tr>\n");
  }
  print('</table>');
}

  print("<br />\n");
  print("<form method=\"post\" action=\"bans.php\">\n");
  print('<table width="100%" border="1">');
	print("<tr><td class=\"colhead\" colspan=\"2\">Добавить бан</td></tr>");
	print("<tr><td class=\"rowhead\">Маска</td><td class=\"row1\"><input type=\"text\" name=\"mask\" size=\"40\"/></td></tr>\n");
	print("<tr><td class=\"row1\" align=\"center\" colspan=\"2\"><input type=\"submit\" value=\"Добавить\" class=\"btn\"/></td></tr>\n");
  print('</table>');
	print("</form>\n");

stdfoot();

?>