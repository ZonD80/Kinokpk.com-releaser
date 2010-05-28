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

if ($_SERVER['REQUEST_METHOD'] == 'POST')
        $action = $_POST["action"];
else
        $action = $_GET["action"];

//получаем постом удаление предложения
if ($_POST["deloffer"])
{
        if (get_user_class() < UC_MODERATOR)
                stderr($tracker_lang['error'],"У вас нет прав для удаления предложения");
        if (isset($_POST["deloffer"]))
        {
                sql_query("DELETE FROM offers WHERE id = $_POST[deloffer]") or sqlerr(__FILE__, __LINE__);
                sql_query("DELETE FROM offervotes WHERE offerid = $_POST[deloffer]") or sqlerr(__FILE__, __LINE__);
                sql_query("DELETE FROM comments WHERE offer = $_POST[deloffer]") or sqlerr(__FILE__, __LINE__);
                sql_query("DELETE FROM checkcomm WHERE checkid =$_POST[deloffer] AND offer = 1") or sqlerr(__FILE__, __LINE__);
                stderr($tracker_lang['success'],"Удалено!<p>Вернуться к <a href=viewoffers.php><b>списку</b></a></p>");
        }
        else
                stderr($tracker_lang['error'],"Не удалось удалить<p>Вернуться к <a href=viewoffers.php><b>списку</b></a></p>");
}

//редактирование предложения
if ($action == 'edit') {
        if ($_SERVER['REQUEST_METHOD']=='POST') {
                $id = $_POST["id"];
                $name = htmlspecialchars($_POST["offertitle"]);
                $descr = $_POST["msg"];
                $cat = $_POST["category"];
                $name = sqlesc($name);
                $descr = sqlesc($descr);
                $cat = sqlesc($cat);
                sql_query("UPDATE offers SET category=$cat, name=$name, descr=$descr WHERE id=$id");
                header("Location: offers.php?id=$id");
        }
        
if (!is_numeric($_GET['id'])) die ("Access denied: Wrong ID");
$id = 0 + $_GET["id"];
        $res = sql_query("SELECT * FROM offers WHERE id = $id");
        $row = mysql_fetch_array($res);
        if ($CURUSER["id"] != $row["userid"]){
                if (get_user_class() < UC_MODERATOR)
                        stderr("Ошибка!", "У вас нет прав для редактирования этого предложения");
        }
        stdhead("Редактировать предложение \"" . $row["name"] . "\"");
        if (!$row)
                die();
        print("<form method=post name=form action=offers.php>\n");
        print("<table border=1 width=560 cellspacing=0 cellpadding=5>\n");
        print("<tr><td class=colhead align=left colspan=2>Редактировать предложение \"" . $row["name"] . "\"</td><tr>\n");
        print("<tr><td align=left><b>Название: </b><br /><input type=text size=80 name=offertitle value=\"" . $row["name"] . "\"></td>");
        $s = "<select name=\"category\">\n";
        $cats = genrelist();
        foreach ($cats as $subrow) {
                $s .= "<option value=\"" . $subrow["id"] . "\"";
                if ($subrow["id"] == $row["category"])
                        $s .= " selected=\"selected\"";
                $s .= ">" . htmlspecialchars($subrow["name"]) . "</option>\n";
        }
        $s .= "</select>\n";
        print("<td align=right><b>Категория: </b><br />$s</tr></td><br />\n<tr><td align=center colspan=2><p><b>Описание:</b><br />");
        textbbcode("form","msg",unesc(htmlspecialchars($row["descr"])));
        print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
        print("<tr><td align=center colspan=2><input type=submit value=\"Редактировать!\">\n");
        print ("<input type=hidden name=action value=edit>");
        print("</form>\n");
        //delete
        print("<form method=post action=offers.php><input type=hidden name=deloffer value=$id><input type=submit value=Удалить></form>");
        print("</table>\n");
        stdfoot();
        die;
}

//просмотр голосовавших за предложение
if ($action == 'vote')
{
  if (!is_numeric($_GET['voteid'])) die ("Access denied: Wrong offer ID");
        $offerid = 0 + $_GET["voteid"];
        $userid = $CURUSER["id"];
        $res = sql_query("SELECT * FROM offervotes WHERE offerid=$offerid and userid=$userid") or sqlerr(__FILE__, __LINE__);
        $arr = mysql_fetch_assoc($res);
        $voted = $arr;
        stdhead("Голосование");
        if ($voted)
        {
                stdmsg($tracker_lang['error'], "<p>Вы уже голосовали за это предложение, можно голосовать только один раз за одно предложение</p><p>Вернуться к <a href=viewoffers.php><b>списку</b></a></p>");
                stdfoot();
                die;
        }
        else
        {
                sql_query("UPDATE offers SET `votes` = `votes` + 1 WHERE id=$offerid") or sqlerr(__FILE__, __LINE__);
                @sql_query("INSERT INTO offervotes (offerid, userid) VALUES($offerid, $userid)") or sqlerr(__FILE__, __LINE__);
                stdmsg("Ваш голос принят", "<p>Ваш голос был принят</p><p>Вернуться к <a href=viewoffers.php><b>списку</b></a></p>");
                stdfoot();
                die;
        }
}


//создание нового предложения
if ($action == 'new') {
        if ($_SERVER['REQUEST_METHOD']=='POST') {
                        if (get_user_class() < UC_USER)
                                die;
                        $name = htmlspecialchars($_POST["name"]);
                        $descrmain = unesc($_POST["msg"]);
                        $descr = "$descrmain";
                        $catid = (0 + $_POST["type"]);
                        if (!is_valid_id($catid))
                                stderr($tracker_lang['error'],"Вы не выбрали категорию!");
                        if (!$name)
                                stderr($tracker_lang['error'],"Вы не ввели название!");
                        if (!$descr)
                                stderr($tracker_lang['error'],"Вы не ввели описание!");
                        // Replace punctuation characters with spaces
                        $ret = sql_query("INSERT INTO offers (userid, name, descr, category, added) VALUES (" .
                        implode(",", array_map("sqlesc", array($CURUSER["id"], $name, $descr, 0 + $_POST["type"]))) .
                        ", '" . get_date_time() . "')");
                        if (!$ret) {
                                if (mysql_errno() == 1062)
                                        stderr($tracker_lang['error'],"!!!");
                                stderr($tracker_lang['error'],"mysql puked: ".mysql_error());
                        }
                        $id = mysql_insert_id();
                        header("Location: offers.php?id=$id");
        }

        stdhead("Предложение");
        if (get_user_class() < UC_USER)
        {
                stdmsg($tracker_lang['error'], "Вы не можете создавать предложения.", 'error');
                stdfoot();
                exit;
        }
        echo ("<form action=\"offers.php\" name=\"form\" method=\"post\">");
        echo ("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" width=560>");
        echo ("<tr><td class=colhead align=left colspan=2>Информация</td><tr>\n");
        echo ("<tr><td align=left><b>Название: </b><br /><input type=\"text\" name=\"name\" size=\"80\" /></td>");
        $s = "<select name=\"type\">\n<option value=\"0\">(Выбрать)</option>\n";
        $cats = genrelist();
        foreach ($cats as $row)
               $s .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";
        $s .= "</select>\n";
        echo ("<td align=right><b>Категория: </b><br />$s</tr></td>\n<tr><td align=center colspan=2><p><b>Описание:</b><br />");
        textbbcode("form","msg",htmlspecialchars(unesc($arr["texxt"])));
        echo ("</td></tr>");
        echo ("<input type=hidden name=action value=new>");
        echo ("<tr><td align=\"center\" colspan=\"2\"><input type=\"submit\" class=btn value=\"Предложить\"></td></tr>");
        echo ("</table>");
        echo ("</form>");
        stdfoot();
        die;
}


//тело, просмотр предложения
if (!is_numeric($_GET['id'])) die ("Access denied: Wrong ID");
$id = 0 + $_GET["id"];

$res = sql_query("SELECT * FROM `offers` WHERE `id` = $id") or sqlerr(__FILE__, __LINE__);
$num = mysql_fetch_array($res);
if (mysql_num_rows($res) == 0)
        stderr ($tracker_lang['error'],"Неверный ID предложения");

$s = $num["votes"];

stdhead("Детали предложения \"" . $num["name"] . "\"");

print("<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
print("<tr><td class=\"colhead\" colspan=\"2\">Детали предложения \"$num[name]\"</td></tr>");
print("<tr><td align=left>Название</td><td width=90% align=left >$num[name]</td></tr>");
if ($num["descr"])
?><tr><td align=left>Описание</td><td width=90% align=left colspan=2><?=format_comment($num["descr"])?></td></tr><?

print("<tr><td align=left>Добавлено</td><td width=90% align=left >$num[added]</td></tr>");

$cres = sql_query("SELECT username FROM users WHERE id=" . $num["userid"]);
if (mysql_num_rows($cres) == 1)
{
        $carr = mysql_fetch_assoc($cres);
        $username = $carr["username"];
}

$url = "offers.php?action=edit&id=$id";
if (isset($_GET["returnto"]))
{
        $addthis = "&amp;returnto=" . urlencode($_GET["returnto"]);
        $url .= $addthis;
        $keepget .= $addthis;
}
$editlink = "a href=\"$url\" class=\"sublink\"";
print("<tr><td align=left>Предлагает</td><td width=90% align=left> <a href=\"userdetails.php?id=".$num["userid"]."\">$username</a>&nbsp;<a href=\"simpaty.php?action=add&amp;good&amp;targetid=".$num["userid"]."&amp;type=offer$id&amp;returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "\" title=\"".$tracker_lang['respect']."\"><img src=\"pic/thum_good.gif\" border=\"0\" alt=\"".$tracker_lang['respect']."\" title=\"".$tracker_lang['respect']."\"></a>&nbsp;&nbsp;<a href=\"simpaty.php?action=add&amp;bad&amp;targetid=".$num["userid"]."&amp;type=offer$id&amp;returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "\" title=\"".$tracker_lang['antirespect']."\"><img src=\"pic/thum_bad.gif\" border=\"0\" alt=\"".$tracker_lang['antirespect']."\" title=\"".$tracker_lang['antirespect']."\"></a>");
if (get_user_class() >= UC_MODERATOR || $CURUSER["id"] == $num["userid"])
        print(" &nbsp;&nbsp;&nbsp;<$editlink><b>[Редактировать]</b></a>");
print("</td></tr>");
if ($CURUSER["id"] != $num["userid"])
{
        print("<tr><td align=left>Голосовать</td><td width=50% align=left ><b><a href=offers.php?action=vote&voteid=$id>Голосовать</a></b></td></tr>");
}

//print("<tr><td class=embedded colspan=2><p><a name=\"startcomments\"></a></p></td></tr>\n");

/*$checkcomm = mysql_num_rows(sql_query("SELECT * FROM checkcomm WHERE userid = $CURUSER[id] AND checkid = $id AND offer = 1"));
$check = (!$checkcomm ? "<a class=index href=offcomment.php?action=check&amp;tid=$id>".$tracker_lang['monitor_comments']."</a>" : "<a class=index href=offcomment.php?action=checkoff&amp;tid=$id>".$tracker_lang['monitor_comments_disable']."</a>");
$commentbar = "<p align=center><a class=index href=offcomment.php?action=add&amp;tid=$id>".$tracker_lang['add_comment']."</a>&nbsp;&nbsp;&nbsp;&nbsp;$check</p>\n";*/

$subres = sql_query("SELECT COUNT(*) FROM comments WHERE offer = $id");
$subrow = mysql_fetch_array($subres);
$count = $subrow[0];

print("</table>");

print("<p><a name=\"startcomments\"></a></p>");

if (!$count) {
  		print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
  		print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
  		print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
  		print("<div align=\"right\"><a href=#comments>Добавить комментарий</a></div>");
  		print("</td></tr><tr><td align=\"center\">");
  		print("Комментариев нет. <a href=#comments>Желаете добавить?</a>");
  		print("</td></tr></table><br>");
  		print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
  		print("<tr><td class=colhead align=\"left\" colspan=\"2\">  <a name=comments>&nbsp;</a><b>:: Без комментариев</b></td></tr>");
  		print("<tr><td  align=\"center\">");
  		//print("<b>Ваше имя:</b> ");
  		//print("".$CURUSER['username']."<p>");
  		print("<form name=comment method=\"post\" action=\"offcomment.php?action=add\">");
  		print("<div align=\"center\">".textbbcode("comment","msg","")."</div>");
  		print("</td></tr><tr><td align=\"center\" colspan=\"2\">");
  		print("<input type=\"hidden\" name=\"tid\" value=\"$id\"/>");
  		print("<input type=\"submit\" class=btn value=\"Разместить комментарий\" />");
		print("</td></tr></form></table>");
} else {
        list($pagertop, $pagerbottom, $limit) = pager(20, $count, "offers.php?id=$id&", array(lastpagedefault => 1));
        $subres = sql_query("SELECT c.id, c.ip, c.text, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, ".
        "u.username, u.title, u.class, u.donor, u.parked, u.downloaded, u.uploaded, u.enabled, u.last_access, e.username AS editedbyname FROM comments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN users AS e ON e.id = c.editedby WHERE offer = " .
        "$id ORDER BY c.id $limit") or sqlerr(__FILE__, __LINE__);
        $allrows = array();
        while ($subrow = mysql_fetch_array($subres))
        $allrows[] = $subrow;

		print("<table class=main cellSpacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
		print("<tr><td class=\"colhead\" align=\"center\" >");
		print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
		print("<div align=\"right\"><a href=#comments class=altlink_white>Добавить комментарий</a></div>");
		print("</td></tr>");

//      print($commentbar);
		print("<tr><td>");
		print($pagertop);
		print("</td></tr>");
		print("<tr><td>");
			commenttable($allrows, "offcomment");
		print("</td></tr>");
		print("<tr><td>");
		print($pagerbottom);
		print("</td></tr>");
		print("</table>");

		print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
		print("<tr><td class=colhead align=\"left\" colspan=\"2\"><a name=comments>&nbsp;</a><b>:: Добавить комментарий к предложению</b></td></tr>");
		print("<tr><td width=\"100%\" align=\"center\">");
		//print("Ваше имя: ");
		//print("".$CURUSER['username']."<p>");
		print("<form name=comment method=\"post\" action=\"offcomment.php?action=add\">");
		print("<center><table border=\"0\"><tr><td class=\"clear\">");
		print("<div align=\"center\">". textbbcode("comment","msg","") ."</div>");
		print("</td></tr></table></center>");
		print("</td></tr><tr><td  align=\"center\" colspan=\"2\">");
		print("<input type=\"hidden\" name=\"tid\" value=\"$id\"/>");
		print("<input type=\"submit\" class=btn value=\"Разместить комментарий\" />");
		print("</td></tr></form></table>");

}

//print($commentbar);
stdfoot();
die;
?>