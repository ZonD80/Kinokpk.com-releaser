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

if ($HTTP_SERVER_VARS["REQUEST_METHOD"] != "POST")
 stderr($tracker_lang['error'], "Шутник!");

if (get_user_class() < UC_MODERATOR)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);

$sender_id = ($_POST['sender'] == 'system' ? 0 : $CURUSER['id']);
$dt = sqlesc(get_date_time());
$msg = $_POST['msg'];
if (!$msg)
stderr($tracker_lang['error'],"Пожалуста, введите сообщение!");

$subject = $_POST['subject'];
if (!$subject)
stderr($tracker_lang['error'],"Пожалуста, введите тему!");

$clases = $_POST['clases'];
if (!$_POST['clases'])
	stderr($tracker_lang['error'],"Выберите 1 или более классов для отправки сообщения.");

/*$query = sql_query("SELECT id FROM users WHERE class IN (".implode(", ", array_map("sqlesc", $clases)).")");

while ($dat=mysql_fetch_assoc($query)) {
	sql_query("INSERT INTO messages (sender, receiver, added, msg, subject) VALUES ($sender_id, $dat[id], '" . get_date_time() . "', " . sqlesc($msg) .", " . sqlesc($subject) .")") or sqlerr(__FILE__,__LINE__);
}*/

write_log("Массовое сообщение от пользователя $CURUSER[username]","FFAE00","tracker");

sql_query("INSERT INTO messages (sender, receiver, added, msg, subject) SELECT $sender_id, id, NOW(), ".sqlesc($msg).", ".sqlesc($subject)." FROM users WHERE class IN (".implode(", ", array_map("sqlesc", $clases)).")") or sqlerr(__FILE__,__LINE__);
$counter = mysql_affected_rows();

header("Refresh: 2; url=staffmess.php");

stderr("Успешно", "Отправлено $counter сообщений.");

?>