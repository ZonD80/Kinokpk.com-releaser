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

if (isset($_GET["cat"]) && !is_valid_id($_GET["cat"])) stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
$cat = 0+$_GET["cat"];
if (!is_valid_id($_GET["from"])) stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
$from = 0+$_GET["from"];

if(!$from || $_GET["to"] <> "next" && $_GET["to"] <> "pre")
   stderr("Ошибка","Как вы сюда попали? <a href=\"javascript:history.go(-1)\">Назад</a>");


$pass = ($_GET["to"] == "next" ? "> " .$from. " ".($cat ? "AND category = " .$cat : "")." ORDER BY id ASC" : "< " .$from. " ".($cat ? "AND category = " .$cat : "")." ORDER BY id DESC");
$err = ($_GET["to"] == "next" ? "Вы уже были на последнем релизе" .($cat ? " этой категории" : "").". <a href=\"javascript:history.go(-1)\">Назад</a>" : "Вы были на первом релизе" .($cat ? " этой категории" : "").". <a href=\"javascript:history.go(-1)\">Назад</a>");


$to = sql_query("SELECT id FROM torrents WHERE id " .$pass. " LIMIT 1");
$to = mysql_fetch_assoc($to);

$to = $to["id"];

if(!$to)
   stderr($tracker_lang['error'], $err);

header("Location: $DEFAULTBASEURL/details.php?id=" .$to);

?>