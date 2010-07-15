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

require_once "include/bittorrent.php";

dbconn();

loggedinorreturn();

if (get_user_class() < UC_MODERATOR) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('access_denied'));
// delete items older than a week
if (isset($_GET['truncate']) && (get_user_class() >= UC_SYSOP)) {
	sql_query("TRUNCATE TABLE sitelog") or sqlerr(__FILE__,__LINE__);
	stderr($REL_LANG->say_by_key('success'),'Логи очищены <a href="'.$REL_SEO->make_link('log').'">К логам</a>','success');

} elseif (isset($_GET['truncate'])) 	stderr($REL_LANG->say_by_key('error'),'Логи могут быть очищены только системным администратором');
stdhead($REL_LANG->say_by_key('logs'));

$type = htmlspecialchars(trim((string)$_GET["type"]));
if(!$type) {
	print ('<h1><a href="'.$REL_SEO->make_link('log','truncate','').'">Очистить логи</a></h1>');
	print("<p align=center>");
	$logs = sql_query("SELECT type FROM sitelog GROUP BY type");
	while (list($logt) = mysql_fetch_array($logs)) {
		print (' |<a href="'.$REL_SEO->make_link('log','type',$logt).'">'.$logt.'</a>| ');
	}
	print '</p>';
	stdfoot();
	die();
}

$res = sql_query("SELECT txt, added FROM `sitelog` WHERE type = ".sqlesc($type)." ORDER BY `added` DESC $limit") or sqlerr(__FILE__, __LINE__);
print("<h1>".$REL_LANG->say_by_key('logs')."| <a href=\"".$REL_SEO->make_link('log')."\">к типам логов</a></h1>\n");
if (mysql_num_rows($res) == 0)
print("<b>".$REL_LANG->say_by_key('log_file_empty')."</b>\n");
else
{
	print("<table border=1 cellspacing=0 cellpadding=5>\n");
	print("<tr><td class=colhead align=left>".$REL_LANG->say_by_key('time')."</td><td class=colhead align=left>".$REL_LANG->say_by_key('event')."</td></tr>\n");
	while ($arr = mysql_fetch_assoc($res))
	{
		$time = mkprettytime($arr['added']);
		print("<tr><td>$time</td><td align=left>".format_comment($arr[txt])."</td></tr>\n");
	}
	print("</table>");
}
stdfoot();
?>
