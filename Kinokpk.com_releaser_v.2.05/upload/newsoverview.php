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

if (!is_valid_id($_GET["id"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
//$action = $_GET["action"];
$newsid = $_GET['id'];
//$returnto = $_GET["returnto"];

if (get_user_class() < UC_USER)
  stderr($tracker_lang['error'], "Нет доступа.");
  
if (!is_valid_id($_GET['id'])) {
  stderr($tracker_lang['error'], "Неверный ID");
}
 
stdhead("Комментирование новости");


if (isset($_GET['id'])) {
 
$sql = sql_query("SELECT * FROM news WHERE id = {$newsid} ORDER BY id DESC") or sqlerr(__FILE__, __LINE__);



print("<h1>Обзор Новости</h1>");
print("<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\"><tr>\n" .
"<td class=\"colhead\">Добавлена</td><td class=\"colhead\">Заглавие</td><td class=\"colhead\">Содержание</td></tr>\n");

if (mysql_num_rows($sql) == 0) {
 print("<tr><td colspan=2>Извините...Нет новости с таким ID!</td></tr></table>");
 stdfoot();
 exit;
 }
 
while ($news = mysql_fetch_assoc($sql))
{

 
 $added = date("Y-m-d h-i-s",strtotime($news['added'])) . " GMT (" . (get_elapsed_time(sql_timestamp_to_unix_timestamp($news["added"]))) . " назад)";
 print("<tr><td width=\"100px\">{$added}</td><td width=\"200px\">{$news['subject']}</td><td>".format_comment($news['body'])."</td></tr>\n");
 
}

print("</table><br />\n");

$subres = mysql_query("SELECT COUNT(*) FROM newscomments WHERE news = ".$_GET['id']);
        $subrow = mysql_fetch_array($subres);
        $count = $subrow[0];

        $limited = 10;

if (!$count) {

  print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
  print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
  print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев к новости</div>");
  print("<div align=\"right\"><a href=#comments class=altlink_white>Добавить комментарий</a></div>");
  print("</td></tr><tr><td align=\"center\">");
  print("Комментариев нет. <a href=#comments>Желаете добавить?</a>");
  print("</td></tr></table><br>");

        }
        else {
                list($pagertop, $pagerbottom, $limit) = pager($limited, $count, "newsoverview.php?id=".$_GET['id']."&", array(lastpagedefault => 1));

                $subres = sql_query("SELECT nc.id, nc.ip, nc.text, nc.user, nc.added, nc.editedby, nc.editedat, u.avatar, u.warned, ".
                  "u.username, u.title, u.class, u.donor, u.downloaded, u.uploaded, u.gender, u.last_access, e.username AS editedbyname FROM newscomments AS nc LEFT JOIN users AS u ON nc.user = u.id LEFT JOIN users AS e ON nc.editedby = e.id WHERE news = " .
                  "".$newsid." ORDER BY nc.id $limit") or sqlerr(__FILE__, __LINE__);
                $allrows = array();

                while ($subrow = mysql_fetch_array($subres))
                        $allrows[] = $subrow;




         print("<table class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
         print("<tr><td class=\"colhead\" align=\"center\" >");
         print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
         print("<div align=\"right\"><a href=#comments class=altlink_white>Добавить комментарий</a></div>");
         print("</td></tr>");

         print("<tr><td>");
         print($pagertop);
         print("</td></tr>");
         print("<tr><td>");
                 commenttable($allrows,"newscomment");
         print("</td></tr>");
         print("<tr><td>");
         print($pagerbottom);
         print("</td></tr>");
         print("</table>");
        }



 print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
  print("<tr><td class=colhead align=\"left\" colspan=\"2\">  <a name=comments>&nbsp;</a><b>:: Добавить комментарий к опросу</b></td></tr>");
  print("<tr><td width=\"100%\" align=\"center\" >");
  //print("Ваше имя: ");
  //print("".$CURUSER['username']."<p>");
  print("<form name=news method=\"post\" action=\"newscomment.php?action=add\">");
  print("<center><table border=\"0\"><tr><td class=\"clear\">");
  print("<div align=\"center\">". textbbcode("news","text","", 1) ."</div>");
  print("</td></tr></table></center>");
  print("</td></tr><tr><td  align=\"center\" colspan=\"2\">");
  print("<input type=\"hidden\" name=\"nid\" value=\"".$newsid."\"/>");
  print("<input type=\"submit\" class=btn value=\"Разместить комментарий\" />");
  print("</td></tr></form></table>");

}

stdfoot();
?>