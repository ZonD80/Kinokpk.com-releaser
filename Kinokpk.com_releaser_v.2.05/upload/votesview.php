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

loggedinorreturn();

if ($_GET[requestid])
{
        $requestid = (int) $_GET[requestid];
        $res2 = sql_query("SELECT COUNT(addedrequests.id) FROM addedrequests INNER JOIN users ON addedrequests.userid = users.id INNER JOIN requests ON addedrequests.requestid = requests.id WHERE addedrequests.requestid = ".sqlesc($requestid)) or die(mysql_error());
        $row = mysql_fetch_array($res2);
        $count = $row[0];
        $perpage = 50;
         list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] ."?" );
         $res = sql_query("SELECT users.id as userid,users.username, users.downloaded, users.uploaded, users.class, requests.id as requestid, requests.request FROM addedrequests INNER JOIN users ON addedrequests.userid = users.id INNER JOIN requests ON addedrequests.requestid = requests.id WHERE addedrequests.requestid =$requestid $limit") or sqlerr(__FILE__, __LINE__);
         stdhead("Голосовавшие");
         $res2 = sql_query("SELECT request FROM requests WHERE id=$requestid");
         $arr2 = mysql_fetch_assoc($res2);

         print("<h1>Голосовавшие для <a href=requests.php?id=$requestid><b>$arr2[request]</b></a></h1>");
         print("<p>Голосовать за этот <a href=requests.php?action=vote&voteid=$requestid><b>запрос</b></a></p>");

         echo $pagertop;

         if (mysql_num_rows($res) == 0)
                 print("<p align=center><b>Ничего не найдено</b></p>\n");
         else
         {
                 print("<table border=1 cellspacing=0 cellpadding=5>\n");
                 print("<tr><td class=colhead>Имя</td><td class=colhead align=left>Загрузил</td><td class=colhead align=left>Скачал</td></tr>\n");
                 while ($arr = mysql_fetch_assoc($res))
                 {
                         if ($arr["downloaded"] > 0)
                         {
                                 $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2);
                                 $ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
                         }
                         elseif ($arr["uploaded"] > 0)
                                 $ratio = "Inf.";
                         else
                                 $ratio = "---";
                         $uploaded =mksize($arr["uploaded"]);
                         $downloaded = mksize($arr["downloaded"]);

                         print("<tr><td><a href=userdetails.php?id=$arr[userid]><b>" . get_user_class_color($arr["class"],$arr["username"]) . "($ratio)</b></a></td><td align=left>$uploaded</td><td align=left>$downloaded</td></tr>\n");
                 }
                 print("</table>\n");
         }
         stdfoot();
}


die("Direct access to this file not allowed.");

?>