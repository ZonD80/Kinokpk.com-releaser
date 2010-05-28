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

if ($action == "add")
{
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $offid = 0 + $_POST["tid"];
                if (!is_valid_id($offid))
                    stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
                $res = sql_query("SELECT name FROM offers WHERE id = $offid") or sqlerr(__FILE__,__LINE__);
                $arr = mysql_fetch_array($res);
                if (!$arr)
                    stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
                $name = $arr[0];
                $text = trim($_POST["msg"]);
                if (!$text)
                    stderr($tracker_lang['error'], $tracker_lang['no_fields_blank']);
                sql_query("INSERT INTO comments (user, offer, added, text, ori_text, ip) VALUES (" .
                $CURUSER["id"] . ",$offid, '" . date("Y-m-d H:i:s", time()) . "', " . sqlesc($text) .
                "," . sqlesc($text) . "," . sqlesc(getip()) . ")");
                $newid = mysql_insert_id();
                sql_query("UPDATE offers SET comments = comments + 1 WHERE id = $offid");
                /////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
                /*$res3 = sql_query("SELECT * FROM checkcomm WHERE checkid = $offid AND offer = 1") or sqlerr(__FILE__,__LINE__);
                $subject = "Новый комментарий";
                while ($arr3 = mysql_fetch_array($res3)) {
                        $msg = sqlesc("К предложению [url=offers.php?id=$offid&viewcomm=$newid#comm$newid]".$name."[/url] был добавлен новый комментарий.");
                        if ($CURUSER[id] != $arr3[userid])
                                sql_query("INSERT INTO messages (sender, receiver, added, msg, location, subject) VALUES (0, $arr3[userid], NOW(), $msg, 1, '$subject')");
                }*/

     			$subject = sqlesc("Новый комментарий");
     			$msg = sqlesc("К предложению [url=offers.php?id=$offid&viewcomm=$newid#comm$newid]".$name."[/url] был добавлен новый комментарий.");
     			sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, userid, NOW(), $msg, 0, $subject FROM checkcomm WHERE checkid = $offid AND offer = 1 AND userid != $CURUSER[id]") or sqlerr(__FILE__,__LINE__);

                /////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
                header("Location: offers.php?id=$offid&viewcomm=$newid#comm$newid");
                exit();
        }
        $offid = 0 + $_GET["tid"];
        if (!is_valid_id($offid))
            stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

        $res = sql_query("SELECT name FROM offers WHERE id = $offid") or sqlerr(__FILE__,__LINE__);
        $arr = mysql_fetch_array($res);
        if (!$arr)
            stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

        stdhead("".$tracker_lang['add_comment']." к \"" . $arr["name"] . "\"");

        $name = (strlen($arr["name"])>40?substr($arr["name"],0,40)."...":$arr["name"]);
        print("<p><form name=\"Form\" method=\"post\" action=\"offcomment.php?action=add\">\n");
        print("<input type=\"hidden\" name=\"tid\" value=\"$offid\"/>\n");
        print("<p align=center><table border=1 cellspacing=\"0\" cellpadding=\"5\">\n");
        echo ("<tr><td class=colhead align=left>".$tracker_lang['add_comment']." к \"" . htmlspecialchars($name) . "\"</td><tr>\n");
        print("<tr><td align=center>\n");
        textbbcode("Form","msg","");
        print("<p align=center><a href=tags.php target=_blank>Все теги</a>\n");
        print("</td></tr>\n");
        print("<tr><td align=center colspan=2><input type=submit value=\"Добавить\" class=btn></td></tr></form></table>\n");
        $res = sql_query("SELECT comments.id, text, comments.added, username, users.id as user, users.avatar, users.uploaded, users.downloaded, users.class, users.enabled, users.parked, users.warned, users.donor FROM comments LEFT JOIN users ON comments.user = users.id WHERE offer = $offid ORDER BY comments.id DESC LIMIT 5");
        $allrows = array();
        while ($row = mysql_fetch_array($res))
            $allrows[] = $row;

        if (count($allrows))
        {
                print("<h2>Последние комментарии в обратном порядке.</h2>\n");
                commenttable($allrows);
        }
        stdfoot();
        die;
}
elseif ($action == "quote") {
        $commentid = 0 + $_GET["cid"];
        if (!is_valid_id($commentid))
                stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
        $res = sql_query("SELECT c.*, o.name, o.id AS oid, u.username FROM comments AS c JOIN offers AS o ON c.offer = o.id JOIN users AS u ON c.user = u.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
        $arr = mysql_fetch_array($res);
        if (!$arr)
                stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
        $text = "[quote=$arr[username]]" . $arr["text"] . "[/quote]\n";
        $offid = $arr["oid"];
        stdhead("Добавить комментарий к \"" . $arr["name"] . "\"");
        $name = (strlen($arr["name"])>40?substr($arr["name"],0,40)."...":$arr["name"]);
        print("<form name=form method=\"post\" action=\"offcomment.php?action=add\">\n");
        print("<input type=\"hidden\" name=\"tid\" value=\"$offid\" />\n");
        print("<table border=1 cellspacing=\"0\" cellpadding=\"5\">\n");
        echo ("<tr><td class=colhead align=left>Редактировать комментарий к \"" . htmlspecialchars($name) . "\"</td><tr>\n");
        print("<tr><td align=center>\n");
        textbbcode("form","msg",htmlspecialchars_uni($text));
        print("<div align=center><a href=tags.php target=_blank>Все теги</a></div>\n");
        print("</td></tr>\n");
        print("<tr><td align=center colspan=2><input type=submit value=\"Добавить\"></td></tr></form></table>\n");
        stdfoot();
}
elseif ($action == "edit") {
        $commentid = 0 + $_GET["cid"];
        if (!is_valid_id($commentid))
                stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
        $res = sql_query("SELECT c.*, o.name FROM comments AS c JOIN offers AS o ON c.offer = o.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
        $arr = mysql_fetch_array($res);
        if (!$arr)
                stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
        if ($arr["user"] != $CURUSER["id"] && get_user_class() < UC_MODERATOR)
                stderr($tracker_lang['error'], $tracker_lang['access_denied']);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $text = $_POST["msg"];
                $returnto = $_POST["returnto"];
                if ($text == "")
                        stderr($tracker_lang['error'], $tracker_lang['no_fields_blank']);
                $text = sqlesc($text);
                $editedat = sqlesc(date("Y-m-d H:i:s", time()));;
                sql_query("UPDATE comments SET text=$text, editedat=$editedat, editedby=$CURUSER[id] WHERE id=$commentid") or sqlerr(__FILE__, __LINE__);
                if ($returnto)
                        header("Location: $returnto");
                else
                        header("Location: $DEFAULTBASEURL/"); // change later ----------------------
                die;
        }

        stdhead("Редактировать комментарий к \"" . $arr["name"] . "\"");
        $name = (strlen($arr["name"])>40?substr($arr["name"],0,40)."...":$arr["name"]);
        print("<form name=Form method=\"post\" action=\"offcomment.php?action=edit&amp;cid=$commentid\">\n");
        print("<input type=\"hidden\" name=\"returnto\" value=\"" . $_SERVER["HTTP_REFERER"] . "\" />\n");
        print("<input type=\"hidden\" name=\"cid\" value=\"$commentid\" />\n");
        print("<table border=1 cellspacing=\"0\" cellpadding=\"5\">\n");
        echo ("<tr><td class=colhead align=left>Редактировать комментарий к \"" . htmlspecialchars($name) . "\"</td><tr>\n");
        print("<tr><td align=center>\n");
        textbbcode("Form","msg",htmlspecialchars(unesc($arr["text"])));
        print("<p align=center><a href=tags.php target=_blank>Все теги</a>\n");
        print("</td></tr>\n");
        print("<tr><td align=center colspan=2><input type=submit value=\"Редактировать!\"></td></tr></form></table>\n");
        stdfoot();
        die;
}
/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
elseif ($action == "check" || $action == "checkoff")
{
        $tid = 0 + $_GET["tid"];
        if (!is_valid_id($tid))
                stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
        $docheck = mysql_fetch_array(sql_query("SELECT COUNT(*) FROM checkcomm WHERE checkid = " . $tid . " AND userid = ". $CURUSER["id"] . " AND offer = 1"));
        if ($docheck[0] > 0 && $action=="check")
                stderr($tracker_lang['error'], "<p>Вы уже подписаны на это предложение.</p><a href=offers.php?id=$tid#startcomments>Назад</a>");
        if ($action == "check") {
                sql_query("INSERT INTO checkcomm (checkid, userid, offer) VALUES ($tid, $CURUSER[id], 1)") or sqlerr(__FILE__,__LINE__);
                stderr($tracker_lang['success'], "<p>Теперь вы следите за комментариями к этому предложению.</p><a href=offers.php?id=$tid#startcomments>Назад</a>");
        }
        else {
                sql_query("DELETE FROM checkcomm WHERE checkid = $tid AND userid = $CURUSER[id] AND offer = 1") or sqlerr(__FILE__,__LINE__);
                stderr($tracker_lang['success'], "<p>Теперь вы не следите за комментариями к этому предложению.</p><a href=offers.php?id=$tid#startcomments>Назад</a>");
        }

}
/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
elseif ($action == "delete")
{
        if (get_user_class() < UC_MODERATOR)
                stderr($tracker_lang['error'], $tracker_lang['access_denied']);
        $commentid = 0 + $_GET["cid"];
        if (!is_valid_id($commentid))
                stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
        $sure = $_GET["sure"];
        if (!$sure) {
                $referer = $_SERVER["HTTP_REFERER"];
                stderr($tracker_lang['delete']." ".$tracker_lang['comment'], sprintf($tracker_lang['you_want_to_delete_x_click_here'],$tracker_lang['comment'],"?action=delete&cid=$commentid&sure=1".($referer ? "&returnto=" . urlencode($referer) : "")));
        }
        $res = sql_query("SELECT offer FROM comments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
        $arr = mysql_fetch_array($res);
        if ($arr)
                $offid = $arr["offer"];
        sql_query("DELETE FROM comments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
        if ($offid && mysql_affected_rows() > 0)
                sql_query("UPDATE offers SET comments = comments - 1 WHERE id = $offid");
        $returnto = $_GET["returnto"];
        if ($returnto)
                header("Location: $returnto");
        else
                header("Location: $DEFAULTBASEURL/"); // change later ----------------------
        die;
}

elseif ($action == "vieworiginal") {
        if (get_user_class() < UC_MODERATOR)
                stderr($tracker_lang['error'], $tracker_lang['access_denied']);
        $commentid = 0 + $_GET["cid"];
        if (!is_valid_id($commentid))
                stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
        $res = sql_query("SELECT c.*, t.name FROM comments AS c JOIN offers AS t ON c.offer = t.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
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