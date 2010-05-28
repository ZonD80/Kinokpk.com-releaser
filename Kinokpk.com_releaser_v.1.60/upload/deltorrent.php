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

gzip();

dbconn();

loggedinorreturn();

if (get_user_class() < UC_MODERATOR)
	stderr($tracker_lang["error"], $tracker_lang["access_denied"]);

stdhead("Удалить торрент");
begin_main_frame();

$mode = $_GET["mode"];

if ($mode == "delete") {
	$res = sql_query("SELECT id, name FROM torrents WHERE id IN (" . implode(", ", array_map("sqlesc", $_POST["delete"])) . ")");
	echo "Следующие торренты удалены:<br><br>";
	while ($row = mysql_fetch_array($res)) {
		echo "ID: $row[id] - $row[name]<br>";
		$reasonstr = "Старый или не подходил под правила.";
		$text = "Торрент $row[id] ($row[name]) был удален пользователем $CURUSER[username]. Причина: $reasonstr\n";
		write_log($text);
	}
	sql_query("DELETE FROM torrents WHERE id IN (" . implode(", ", array_map("sqlesc", $_POST["delete"])) . ")") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM snatched WHERE torrent IN (" . implode(", ", array_map("sqlesc", $_POST["delete"])) . ")") or sqlerr(__FILE__,__LINE__);	
	sql_query("DELETE FROM readtorrents WHERE torrentid IN (" . implode(", ", array_map("sqlesc", $_POST["delete"])) . ")") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM ratings WHERE torrent IN (" . implode(", ", array_map("sqlesc", $_POST["delete"])) . ")") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM checkcomm WHERE checkid IN (" . implode(", ", array_map("sqlesc", $_POST["delete"])) . ") AND torrent = 1") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM bookmarks WHERE torrentid IN (" . implode(", ", array_map("sqlesc", $_POST["delete"])) . ")") or sqlerr(__FILE__,__LINE__);
	sql_query("DELETE FROM files WHERE torrent IN (" . implode(", ", array_map("sqlesc", $_POST["delete"])) . ")") or sqlerr(__FILE__,__LINE__);
} else
	echo "Unknown mode...";

end_main_frame();
stdfoot();

?>