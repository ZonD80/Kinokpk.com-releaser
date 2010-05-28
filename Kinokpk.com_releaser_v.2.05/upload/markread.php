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
dbconn(false);
loggedinorreturn();
ob_start();

stdhead();


if (sql_query("UPDATE users SET last_checked = " .sqlesc(get_date_time()). " WHERE id = " .sqlesc($CURUSER["id"])) && setcookie("torrents_vis", ""))
   stdmsg("Успешно", "Новые торренты отмечены как прочитаные.");
else
   stdmsg("Ошибка", "Отметка новых торрентов произошла с ошибкой: ".mysql_error());


stdfoot();

header("Refresh: 5; url=browse.php");
?>