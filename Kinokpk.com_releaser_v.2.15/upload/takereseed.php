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

require "include/bittorrent.php";

dbconn();
loggedinorreturn();

$id = (int) $_GET["torrent"];

$res = sql_query("SELECT torrents.seeders, torrents.banned, torrents.leechers, torrents.name, torrents.filename, torrents.times_completed, torrents.id, UNIX_TIMESTAMP(torrents.last_reseed) AS lr, categories.name AS cat_name FROM torrents LEFT JOIN categories ON torrents.category = categories.id WHERE torrents.id = $id") or sqlerr(__FILE__, __LINE__);
$row = mysql_fetch_array($res);

if (!$row || $row["banned"] == "yes")
	stderr($tracker_lang['error'], $tracker_lang['no_torrent_with_such_id']);

if ($row["times_completed"] == 0)
	stderr($tracker_lang['error'], "Извините, но этот торрент еще никем не скачан.");

if ($row["leechers"] == 0)
	stderr($tracker_lang['error'], "На этой раздаче не нужна помощь т.к. ее никто не качает.");

$dt = time() - 24*3600;

if ($row["lr"] > $dt && get_date_time($row["lr"]) != "0000-00-00 00:00:00")
	stderr($tracker_lang['error'], "Извините, но еще не прошли сутки с прошлого запроса вернутся на раздачу.");

$subject = sqlesc("Помогите раздать {$row["name"]}");

$msg = sqlesc("Здравствуйте!

Ваша помощь необходима в раздаче [url=details.php?id={$id}]{$row["cat_name"]} :: {$row["name"]}[/url]
Если вы решили помочь, но уже удалили торрент-файл, можете скачать его [url=download.php?id=$id&name=" . rawurlencode($row["filename"]) . "]здесь[/url].

Надеюсь на вашу помощь!");

sql_query("INSERT INTO messages (sender, receiver, poster, added, subject, msg) SELECT $CURUSER[id], userid, 0, NOW(), $subject, $msg FROM snatched WHERE torrent = $id AND userid != $CURUSER[id] AND finished = 'yes'") or sqlerr(__FILE__, __LINE__);

sql_query("UPDATE torrents SET last_reseed = NOW() WHERE id = $id") or sqlerr(__FILE__, __LINE__);

header("Refresh: 2; url=details.php?id=$id");

stdhead("Позвать скачавших на торрент $row[name]");

stdmsg("Успешно", "Ваш запрос на призыв скачавших выполнен. Ждите результатов в течение суток, иначе повторите запрос.");

stdfoot();

?>