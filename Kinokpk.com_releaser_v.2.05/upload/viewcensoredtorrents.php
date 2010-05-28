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

require_once("include/bittorrent.php");

gzip();

dbconn();

loggedinorreturn();

stdhead("Запрещенные релизы");

if ($_GET["delt"])
{
        if (get_user_class() >= UC_MODERATOR) {
                if (empty($_GET["delt"]))
                    stderr($tracker_lang['error'],$tracker_lang['no_fileds_blank']);
                sql_query("DELETE FROM censoredtorrents WHERE id IN (" . implode(", ", array_map("sqlesc", $_GET["delt"])) . ")");
                 sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='censoredtorrents_lastupdate'");
                   stderr($tracker_lang['success'], "Запрет успешно удален.<br /><a href=viewcensoredtorrents.php>К списку запретов</a>");
                   stdfoot();
                   die;
        }
        else  {
                stderr($tracker_lang['error'], "У вас нет прав для удаления запретов.");
                stdfoot();
                die;
                }
}

if (get_user_class() >= UC_MODERATOR) $moder=1;

print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\" >");
print("<tr><td class=\"colhead\" align=\"center\" colspan=\"15\">Запрещенные релизы [по убыванию]</td></tr>");
$res = sql_query("SELECT * FROM censoredtorrents ORDER BY id DESC");
print ("<tr>");
print("<tr><td class=\"colhead\" align=\"center\">Название</td><td class=\"colhead\" align=\"center\">Причина запрета</td></tr>");
while ($row = mysql_fetch_array($res)) {
  if ($moder) $name = $row['name'] . "<br /><a href=\"censoredtorrents.php?id=".$row['id']."\">Подробнее</a>"; else $name = $row['name'];
  
  
  print("<tr><td align=\"center\">".$name."</td><td align=\"center\">".format_comment($row['reason'])."</td></tr>");
}

print("</table>");
if ($moder)
print("<div align=\"center\"><a href=censoredtorrents.php?action=new>[<b>Добавить запрет</b>]</a></div>");
stdfoot();
//die;

?>