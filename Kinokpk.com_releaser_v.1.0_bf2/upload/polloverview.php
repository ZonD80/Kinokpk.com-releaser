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


if (!is_numeric($_GET['id'])) stderr($tracker_lang['error'], "Неверный ID");
 
//$action = $_GET["action"];
$pollid = $_GET['id'];
//$returnto = $_GET["returnto"];

if (get_user_class() < UC_USER)
  stderr($tracker_lang['error'], "Нет доступа.");
  
if (!is_valid_id($_GET['id'])) {
  stderr($tracker_lang['error'], "Неверный ID");
}
 
stdhead("Обзор опросов");

if (isset($_GET['id'])) {
 
$sql = sql_query("SELECT * FROM polls WHERE id = {$pollid} ORDER BY id DESC") or sqlerr(__FILE__, __LINE__);



print("<h1>Обзор опроса</h1>\n");

print("<p><table width=100% border=1 cellspacing=0 cellpadding=5><tr>\n" .
"<td class=colhead align=center>ID</td><td class=colhead>Добавлен</td><td class=colhead>Вопрос</td></tr>\n");

if (mysql_num_rows($sql) == 0) {
 print("<tr><td colspan=2>Извините...Нет опроса с таким ID!</td></tr></table>");
 stdfoot();
 exit;
 }
 
while ($poll = mysql_fetch_assoc($sql))
{
 $o = array($poll["option0"], $poll["option1"], $poll["option2"], $poll["option3"], $poll["option4"],
  $poll["option5"], $poll["option6"], $poll["option7"], $poll["option8"], $poll["option9"],
  $poll["option10"], $poll["option11"], $poll["option12"], $poll["option13"], $poll["option14"],
  $poll["option15"], $poll["option16"], $poll["option17"], $poll["option18"], $poll["option19"]);
 
 $added = date("Y-m-d h-i-s",strtotime($poll['added'])) . " GMT (" . (get_elapsed_time(sql_timestamp_to_unix_timestamp($poll["added"]))) . " ago)";
 print("<tr><td align=center><a href=\"polloverview.php?id={$poll['id']}\">{$poll['id']}</a></td><td>{$added}</td><td><a href=\"polloverview.php?id={$poll['id']}\">{$poll['question']}</a></td></tr>\n");
 
}

print("</table><br />\n");

print("<h1>Обзор ответов</h1><br />\n");
print("<table width=100% border=1 cellspacing=0 cellpadding=5><tr><td class=colhead>Опция №</td><td class=colhead>Ответ</td></tr>\n");
foreach($o as $key=>$value) {
 if($value != "")
 print("<tr><td>{$key}</td><td>{$value}</td></tr>\n");
 }
print("</table>\n");
//print_r($o);

}

$subres = mysql_query("SELECT COUNT(*) FROM pollcomments WHERE poll = ".$_GET['id']);
        $subrow = mysql_fetch_array($subres);
        $count = $subrow[0];

        $limited = 10;

if (!$count) {

  print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
  print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
  print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев к опросу</div>");
  print("<div align=\"right\"><a href=#comments class=altlink_white>Добавить комментарий</a></div>");
  print("</td></tr><tr><td align=\"center\">");
  print("Комментариев нет. <a href=#comments>Желаете добавить?</a>");
  print("</td></tr></table><br>");

        }
        else {
                list($pagertop, $pagerbottom, $limit) = pager($limited, $count, "polloverview.php?id=".$GET['id']."&", array(lastpagedefault => 1));

                $subres = sql_query("SELECT pc.id, pc.ip, pc.text, pc.user, pc.added, pc.editedby, pc.editedat, u.avatar, u.warned, ".
                  "u.username, u.title, u.class, u.donor, u.downloaded, u.uploaded, u.gender, u.last_access, e.username AS editedbyname FROM pollcomments AS pc LEFT JOIN users AS u ON pc.user = u.id LEFT JOIN users AS e ON pc.editedby = e.id WHERE poll = " .
                  "".$pollid." ORDER BY pc.id $limit") or sqlerr(__FILE__, __LINE__);
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
                 commenttable($allrows,"pollcomment");
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
  print("<form name=comment method=\"post\" action=\"pollcomment.php?action=add\">");
  print("<center><table border=\"0\"><tr><td class=\"clear\">");
  print("<div align=\"center\">". textbbcode("comment","text","", 1) ."</div>");
  print("</td></tr></table></center>");
  print("</td></tr><tr><td  align=\"center\" colspan=\"2\">");
  print("<input type=\"hidden\" name=\"pid\" value=\"".$pollid."\"/>");
  print("<input type=\"submit\" class=btn value=\"Разместить комментарий\" />");
  print("</td></tr></form></table>");
  
  $sql2 = sql_query("SELECT pollanswers. * , users.username FROM pollanswers LEFT JOIN users ON users.id = pollanswers.userid WHERE pollid = {$pollid} AND selection < 20 ORDER  BY users.id DESC ") or sqlerr(__FILE__, __LINE__);

print("<h1>Обзор голосовавших пользователей</h1>\n");

print("<p><table width=100% border=1 cellspacing=0 cellpadding=5><tr>\n" .
"<td class=colhead align=center>Пользователь</td><td class=colhead>Выбор</td></tr>\n");

if (mysql_num_rows($sql2) == 0) {
 print("<tr><td colspan=2>Извините...Нет голосовавших пользователей!</td></tr></table>");
 stdfoot();
 exit;
 }

while ($useras = mysql_fetch_assoc($sql2))
{
 $username  = ($useras['username'] ? $useras['username'] : "Неизвестно");
 //$useras['selection']--;
 print("<tr><td>{$username}</td><td>{$o[$useras['selection']]}</td></tr>\n");
}
print("</table>\n");


stdfoot();
?>