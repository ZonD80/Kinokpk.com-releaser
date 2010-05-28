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

require_once("include/bittorrent.php");

$action = $_GET["action"];
dbconn(false);

loggedinorreturn();

if ($action == "add") {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $reqid = 0 + $_POST["tid"];
                if (!is_valid_id($reqid))
                        stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
                $res = sql_query("SELECT request, userid FROM requests WHERE id = $reqid") or sqlerr(__FILE__,__LINE__);
                $arr = mysql_fetch_array($res);
                if (!$arr)
                        stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
                $name = $arr[0];
                        $text = trim($_POST["text"]);
                if (!$text)
                        stderr($tracker_lang['error'], $tracker_lang['no_fields_blank']);
                sql_query("INSERT INTO comments (user, request, added, text, ori_text, ip) VALUES (" .
                $CURUSER["id"] . ",$reqid, '" . date("Y-m-d H:i:s", time()) . "', " . sqlesc($text) .
                "," . sqlesc($text) . "," . sqlesc(getip()) . ")");
                $newid = mysql_insert_id();
                sql_query("UPDATE requests SET comments = comments + 1 WHERE id = $reqid");
                /////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
                /*$res3 = sql_query("SELECT * FROM checkcomm WHERE checkid = $reqid AND req = 1") or sqlerr(__FILE__,__LINE__);
                $subject = "Новый комментарий";
                while ($arr3 = mysql_fetch_array($res3)) {
                        $msg = sqlesc("К запросу [url=reqdetails.php?id=$reqid&viewcomm=$newid#comm$newid]".$name."[/url] был добавлен новый комментарий.");
                        if ($CURUSER[id] != $arr3[userid])
                                sql_query("INSERT INTO messages (sender, receiver, added, msg, location, subject) VALUES (0, $arr3[userid], NOW(), $msg, 1, '$subject')");
                }*/

     			$subject = sqlesc("Новый комментарий");
     			$msg = sqlesc("К запросу [url=reqdetails.php?id=$reqid&viewcomm=$newid#comm$newid]".$name."[/url] был добавлен новый комментарий.");
     			sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, userid, NOW(), $msg, 0, $subject FROM checkcomm WHERE checkid = $reqid AND req = 1 AND userid != $CURUSER[id]") or sqlerr(__FILE__,__LINE__);

                /////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
                header("Location: requests.php?id=$reqid&viewcomm=$newid#comm$newid");

exit();
}

$reqid = 0 + $_GET["tid"];
if (!is_valid_id($reqid))
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$res = sql_query("SELECT request FROM requests WHERE id = $reqid") or sqlerr(__FILE__,__LINE__);
$arr = mysql_fetch_array($res);
if (!$arr)
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

stdhead("Прокоментировать \"" . $arr["request"] . "\"");

print("<p><form name=\"Form\" method=\"post\" action=\"reqcomment.php?action=add\">\n");
print("<input type=\"hidden\" name=\"tid\" value=\"$reqid\"/>\n");
print("<p align=center><table border=1 cellspacing=1>\n");
print("<tr><td class=colhead colspan=2>Прокоментировать \"" . $arr["request"] . "\"</td></tr>\n");
print("<tr><td align=center>\n");
textbbcode("Form","msg",htmlspecialchars(unesc($arr["texxt"])));
print("<p align=center><a href=tags.php target=_blank>Все теги</a>\n");
print("</td></tr>\n");
print("<tr><td align=center colspan=2><input type=submit value=\"Добавить\" class=btn></td></tr></form></table>\n");

$res = sql_query("SELECT comments.id, text, comments.added, username, users.id as user, users.avatar, users.downloaded, users.uploaded, users.class, users.enabled, users.parked, users.warned, users.donor FROM comments LEFT JOIN users ON comments.user = users.id WHERE request = $reqid ORDER BY comments.id DESC LIMIT 5");

$allrows = array();
while ($row = mysql_fetch_array($res))
$allrows[] = $row;

if (count($allrows)) {
print("<h2>Последние комментарии в обратном порядке.</h2>\n");
commenttable($allrows);
}

stdfoot();

die;
}
elseif ($action == "quote")
{
$commentid = 0 + $_GET["cid"];
if (!is_valid_id($commentid))
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
$res = sql_query("SELECT c.*, r.request, r.id AS rid, u.username FROM comments AS c JOIN requests AS r ON c.request = r.id JOIN users AS u ON c.user = u.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
$arr = mysql_fetch_array($res);
if (!$arr)
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$text = "[quote=$arr[username]]" . $arr["text"] . "[/quote]\n";
$reqid = $arr["rid"];

stdhead("Добавление комментаря к \"" . $arr["request"] . "\"");

print("<form name=form method=\"post\" action=\"reqcomment.php?action=add\">\n");
print("<input type=\"hidden\" name=\"tid\" value=\"$reqid\"/>\n");
print("<p align=center><table border=1 cellspacing=1>\n");
print("<tr><td class=colhead colspan=2>Добавить комментарий к \"" . htmlspecialchars($arr["request"]) . "\"</td></tr>\n");
print("<tr><td align=center>\n");
textbbcode("form","text",htmlspecialchars(unesc($text)));
print("<div align=center><a href=tags.php target=_blank>Все теги</a></div>\n");
print("</td></tr>\n");
print("<tr><td align=center colspan=2><input type=submit value=\"Добавить\"></td></tr></form></table>\n");

stdfoot();

}
elseif ($action == "edit")
{
$commentid = 0 + $_GET["cid"];
if (!is_valid_id($commentid))
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
$res = sql_query("SELECT c.*, r.request, r.id AS rid FROM comments AS c JOIN requests AS r ON c.request = r.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);

$arr = mysql_fetch_array($res);
if (!$arr)
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

if ($arr["user"] != $CURUSER["id"] && get_user_class() < UC_MODERATOR)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
$text = $_POST["msg"];
$returnto = $_POST["returnto"];

if ($text == "")
stderr($tracker_lang['error'], $tracker_lang['no_fields_blank']);

$text = sqlesc($text);

$editedat = sqlesc(get_date_time());

sql_query("UPDATE comments SET text=$text, editedat=$editedat, editedby=$CURUSER[id]  WHERE id=$commentid") or sqlerr(__FILE__, __LINE__);
if ($returnto)
header("Location: $returnto");
else
header("Location: $DEFAULTBASEURL/"); // change later ----------------------

die;
}

stdhead("Редактировать комментарий к \"" . $arr["request"] . "\"");

print("<form name=form method=\"post\" action=\"reqcomment.php?action=edit&amp;cid=$commentid\">\n");
print("<input type=\"hidden\" name=\"returnto\" value=\"requests.php?id={$arr["rid"]}&amp;viewcomm=$commentid#comm$commentid\" />\n");
print("<input type=\"hidden\" name=\"cid\" value=\"$commentid\" />\n");
print("<p align=center><table border=1 cellspacing=1>\n");
print("<tr><td class=colhead colspan=2>Редактировать комментарий к \"" . htmlspecialchars($arr["request"]) . "\"</td></tr>\n");
print("<tr><td align=center>\n");
textbbcode("form","msg",htmlspecialchars(unesc($arr["text"])));
print("<p align=center><a href=tags.php target=_blank>Все теги</a>\n");
print("</td></tr>\n");
print("<tr><td align=center colspan=2><input type=submit value=\"".$tracker_lang['edit']."\"></td></tr></form></table>\n");

stdfoot();

die;
}
/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
elseif ($action == "check" || $action == "checkoff")
{
        $tid = 0 + $_GET["tid"];
        if (!is_valid_id($tid))
                stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
        $docheck = mysql_fetch_array(sql_query("SELECT COUNT(*) FROM checkcomm WHERE checkid = " . $tid . " AND userid = ". $CURUSER["id"] . " AND req = 1"));
        if ($docheck[0] > 0 && $action=="check")
                stderr($tracker_lang['error'], "<p>Вы уже подписаны на этот запрос.</p><a href=requests.php?id=$tid#startcomments>Назад</a>");
        if ($action == "check") {
                sql_query("INSERT INTO checkcomm (checkid, userid, req) VALUES ($tid, $CURUSER[id], 1)") or sqlerr(__FILE__,__LINE__);
                stderr($tracker_lang['success'], "<p>Теперь вы следите за комментариями к этому запросу.</p><a href=requests.php?id=$tid#startcomments>Назад</a>");
        }
        else {
                sql_query("DELETE FROM checkcomm WHERE checkid = $tid AND userid = $CURUSER[id] AND req = 1") or sqlerr(__FILE__,__LINE__);
                stderr($tracker_lang['success'], "<p>Теперь вы не следите за комментариями к этому запросу.</p><a href=requests.php?id=$tid#startcomments>Назад</a>");
        }

}
/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
elseif ($action == "delete")
{
if (get_user_class() < UC_MODERATOR)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);

$commentid = 0 + $_GET["cid"];

if (!is_valid_id($commentid))
stderr($tracker_lang['error'], "Invalid ID $commentid.");

$sure = $_GET["sure"];

if (!$sure)
{
$referer = $_SERVER["HTTP_REFERER"];
stderr($tracker_lang['delete']." ".$tracker_lang['comment'], sprintf($tracker_lang['you_want_to_delete_x_click_here'],$tracker_lang['comment'],"?action=delete&cid=$commentid&sure=1".($referer ? "&returnto=" . urlencode($referer) : "")));
}


$res = sql_query("SELECT request FROM comments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
$arr = mysql_fetch_array($res);
if ($arr)
$reqid = $arr["request"];

sql_query("DELETE FROM comments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
if ($reqid && mysql_affected_rows() > 0)
sql_query("UPDATE requests SET comments = comments - 1 WHERE id = $reqid");

$returnto = $_GET["returnto"];

if ($returnto)
header("Location: $returnto");
else
header("Location: $DEFAULTBASEURL/"); // change later ----------------------


die;
}
elseif ($action == "vieworiginal")
{
if (get_user_class() < UC_MODERATOR)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);

$commentid = 0 + $_GET["cid"];

if (!is_valid_id($commentid))
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$res = sql_query("SELECT c.*, r.request FROM comments AS c JOIN requests AS r ON c.request = r.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
$arr = mysql_fetch_array($res);
if (!$arr)
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

stdhead("Оригинал");
print("<table width=500 border=1 cellspacing=0 cellpadding=5>");
print("<tr><td class=colhead>Оригинальное содержания комментария #$commentid</td></tr>");
print("<tr><td class=comment>\n");
echo htmlspecialchars($arr["ori_text"]);
print("</td></tr></table>\n");

$returnto = $_SERVER["HTTP_REFERER"];

if ($returnto)
print("<p><font size=small>(<a href=$returnto>".$tracker_lang['back']."</a>)</font></p>\n");

stdfoot();

die;
}
else
stderr($tracker_lang['error'], "Unknown action $action");

die;
?>