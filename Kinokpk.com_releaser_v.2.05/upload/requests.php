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

dbconn();
loggedinorreturn();

if ($_SERVER["REQUEST_METHOD"] == 'POST')
		$action = $_POST["action"];
else
		$action = $_GET["action"];

if ($action == 'new') {
		if ($_SERVER['REQUEST_METHOD']=='POST') {
				$requesttitle = htmlspecialchars($_POST["requesttitle"]);
				if (!$requesttitle)
						stderr($tracker_lang['error'],"Вы не ввели название");
				$request = $requesttitle;
				$descr = unesc($_POST["descr"]);
				if (!$descr)
						stderr($tracker_lang['error'],"Вы должны ввести описание!");
				$cat = (0 + $_POST["category"]);
				if (!is_valid_id($cat))
						stderr($tracker_lang['error'],"Вы должны выбрать категорию для запроса!");
				$request = sqlesc($request);
				$descr = sqlesc($descr);
				$cat = sqlesc($cat);
				sql_query("INSERT INTO requests (hits,userid, cat, request, descr, added) VALUES(1,$CURUSER[id], $cat, $request, $descr, '" . get_date_time() . "')") or sqlerr(__FILE__,__LINE__);
        $id = mysql_insert_id();
        sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='requests_lastupdate'");
                    sql_query("UPDATE users SET bonus=bonus+5 WHERE id =".$CURUSER['id']);
                    
				@sql_query("INSERT INTO addedrequests VALUES(0, $id, $CURUSER[id])") or sqlerr(__FILE__, __LINE__);
				header("Location: requests.php?id=$id");
				die;
		}
		stdhead("Сделать запрос");
		
		print("<h1>Сделать запрос</h1><p>Чтобы посмотреть свои запросы, нажмите <a href=viewrequests.php?requestorid=$CURUSER[id]>здесь</a></p>\n<br />\n");
		?>
		<table border=1 width=550 cellspacing=0 cellpadding=5>
		<tr><td class=colhead align=left>Поиск торрентов (чтобы проверить, нет ли уже такого торрента, который вы ищите)</td></tr>
		<tr><td align=left><form method="get" action=browse.php>
		<input type="text" name="search" size="40" value="<?= htmlspecialchars($searchstr) ?>" />&nbsp;в&nbsp<select name="cat">
		<option value="0">(все типы)</option>
		<?
		$cats = genrelist();
		$catdropdown = "";
		foreach ($cats as $cat) {
				$catdropdown .= "<option value=\"" . $cat["id"] . "\"";
				if ($cat["id"] == $_GET["cat"])
						$catdropdown .= " selected=\"selected\"";
				$catdropdown .= ">" . htmlspecialchars($cat["name"]) . "</option>\n";
		}
		$deadchkbox = "<input type=\"checkbox\" name=\"incldead\" value=\"1\"";
		$deadchkbox .= " /> включая мертвые торренты\n";
		print("$catdropdown");
		print("</select>");
		print("$deadchkbox");
		print("<input type=\"submit\" value=\"Искать!\">");
		print("</form>");
		print("</td></tr></table>");
		print("<form method=post action=requests.php name=request>\n");
		print("<table border=1 cellspacing=0 cellpadding=5>\n");
		print("<tr><td class=colhead align=left colspan=2>Заполните необходимые поля</a></td><tr>\n");
		print("<tr><td align=left><b>Название: </b><br /><input type=text size=80 name=requesttitle></td>");
		print("<td align=center><b>Категория: </b><br /><select name=\"category\">");
		print("<option value=\"0\">(Выбрать)</option>");
		print($catdropdown);
		print("</select></td></tr>");
		print("<br />\n");
		print("<tr><td align=center colspan=2><b>Описание: </b><br />\n");
		textbbcode("request","descr");
		print ("<input type=hidden name=action value=new>");
		print("<tr><td align=center colspan=2><input type=submit value=\"Готово!\">\n");
		print("</form>\n");
		print("</table>\n");
		stdfoot();
		die;
}
if ($action == 'edit') {
		if ($_SERVER['REQUEST_METHOD']=='POST') {
				$id = $_POST["id"];
				$name = htmlspecialchars($_POST["requesttitle"]);
				$descr = $_POST["msg"];
				$cat = $_POST["category"];
				$name = sqlesc($name);
				$descr = sqlesc($descr);
				$cat = sqlesc($cat);

				sql_query("UPDATE requests SET cat=$cat, request=$name, descr=$descr WHERE id=$id") or sqlerr(__FILE__, __LINE__);
        sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='requests_lastupdate'");
				header("Location: requests.php?id=$id");
		}
if (!is_valid_id($_GET["id"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		$id = 0 + $_GET["id"];

		$res = sql_query("SELECT * FROM requests WHERE id = $id");
		$row = mysql_fetch_array($res);
		if ($CURUSER["id"] != $row["userid"])
		{
				if (get_user_class() < UC_MODERATOR)
						stderr("Ошибка!", "Вы не владелец данного запроса.");
		}
		stdhead("Редактирование запроса \"" . $row["request"] . "\"");
		if (!$row)
				die();
		$where = "WHERE userid = " . $CURUSER["id"] . "";
		$res2 = sql_query("SELECT * FROM requests $where") or sqlerr(__FILE__, __LINE__);
		$num2 = mysql_num_rows($res2);
		print("<form method=post name=form action=requests.php></a>\n");
		print("<table border=1 width=560 cellspacing=0 cellpadding=5>\n");
		print("<tr><td class=colhead align=left>Редактирование запроса \"" . $row["request"] . "\"</td><tr>\n");
		print("<tr><td align=left>Название: <input type=text size=40 name=requesttitle value=\"" . htmlspecialchars($row["request"]) . "\">");
		$s = "<select name=\"category\">\n";

		$cats = genrelist();
		foreach ($cats as $subrow)
		{
				$s .= "<option value=\"" . $subrow["id"] . "\"";
				if ($subrow["id"] == $row["cat"])
						$s .= " selected=\"selected\"";
				$s .= ">" . htmlspecialchars($subrow["name"]) . "</option>\n";
		}
		$s .= "</select>\n";
		print("&nbsp;Категория: $s<p /><b>Описание</b>:<br />\n");
		textbbcode("form","msg",htmlspecialchars($row["descr"]));
		print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
		print("<input type=\"hidden\" name=\"action\" value=\"edit\">\n");
		print("<tr><td align=center><input type=submit value=\"Редактировать!\">\n");
		print("</form>\n");
		print("</table>\n");

		stdfoot();

		die;
}

if ($action=='reset')
{
if (!is_valid_id($_GET["requestid"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		$requestid = $_GET["requestid"];
		$res = sql_query("SELECT userid, filledby FROM requests WHERE id =$requestid") or sqlerr(__FILE__, __LINE__);
		$arr = mysql_fetch_assoc($res);
		if (($CURUSER[id] == $arr[userid]) || (get_user_class() >= UC_MODERATOR) || ($CURUSER[id] == $arr[filledby]))
		{
				@sql_query("UPDATE requests SET filled='', filledby=0 WHERE id=$requestid") or sqlerr(__FILE__, __LINE__);
        sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='requests_lastupdate'");
        stderr($tracker_lang['success'],"Запрос номер $requestid был успешно сброшен.");
		}
		else
				stderr($tracker_lang['error'],"Извините, но вы не можете сбросить данные этого запроса");

}

if ($action=='filled')
{
		$filledurl = $_POST["filledurl"];
		$requestid = $_POST["requestid"];
		if (substr($filledurl, 0, strlen($DEFAULTBASEURL)) != $DEFAULTBASEURL || !$requestid)
		{
				stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		}

		$res = sql_query("SELECT users.username, requests.userid, requests.request FROM requests INNER JOIN users ON requests.userid = users.id WHERE requests.id = " . sqlesc($requestid)) or sqlerr(__FILE__, __LINE__);
		$arr = mysql_fetch_assoc($res);

		$msg = "Ваш запрос, [url=requests.php?id=" . $requestid . "][b]" . $arr[request] . "[/b][/url] был выполнен пользователем [url=userdetails.php?id=" . $CURUSER["id"] . "][b]" . $CURUSER["username"] . "[/b][/url]. Вы можете скачать его [url=" . $filledurl. "][b]тут[/b][/url]. Пожалуйста не забудьте сказать спасибо. Если это не то, что вы просили или по каким-то причинам вас не устраивает исполнение, то нажмите [URL=requests.php?action=reset&requestid=" . $requestid . "]здесь[/url].";
		$subject = "Ваш запрос выполнен";
		sql_query ("UPDATE requests SET filled = " . sqlesc($filledurl) . ", filledby = $CURUSER[id] WHERE id = " . sqlesc($requestid)) or sqlerr(__FILE__, __LINE__);
    sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='requests_lastupdate'");
    sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, location, subject) VALUES(0, 0, $arr[userid], '" . get_date_time() . "', " . sqlesc($msg) . ", 1, " . sqlesc($subject) . ")") or sqlerr(__FILE__, __LINE__);
		stderr($tracker_lang['success'],"Запрос номер $requestid был успешно выполнен с <a href=$filledurl>$filledurl</a>. Пользователь <a href=userdetails.php?id=$arr[userid]><b>$arr[username]</b></a> автоматически получит об этом сообщение. Если вы сделали ошибку при указании адреса выполненного запроса, то пожалуйста отмените свое выполнение нажав <a href=requests.php?action=reset&requestid=$requestid>здесь</a>.");
}

if ($action == 'vote')
{
		$requestid = 0 + $_GET["voteid"];
		$userid = $CURUSER["id"];
		$res = sql_query("SELECT * FROM addedrequests WHERE requestid=$requestid AND userid = $userid") or sqlerr(__FILE__, __LINE__);
		$arr = mysql_fetch_assoc($res);
		$voted = $arr;
		if ($voted) {
				stderr($tracker_lang['error'], "<p>Вы уже голосовали за этот запрос, можно голосовать только один раз за один запрос</p><p>Вернуться к <a href=viewrequests.php><b>запросам</b></a></p>");
		} else {
				sql_query("UPDATE requests SET hits = hits + 1 WHERE id=$requestid") or sqlerr(__FILE__, __LINE__);
				@sql_query("INSERT INTO addedrequests VALUES(0, $requestid, $userid)") or sqlerr(__FILE__, __LINE__);
        sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='requests_lastupdate'");
        stderr("Ваш голос принят", "<p>Ваш голос был принят</p><p>Вернуться к <a href=viewrequests.php><b>списку</b></a></p>");
		}
}

$id = 0 + $_GET["id"];

$res = sql_query("SELECT * FROM requests WHERE id = $id") or sqlerr(__FILE__, __LINE__);
$num = mysql_fetch_array($res);

if (mysql_num_rows($res) == 0)
		stderr ($tracker_lang['error'], $tracker_lang['invalid_id']);

$s = $num["request"];

stdhead("Детали запроса \"$s\"");

print("<table width=\"600\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
print("<tr><td class=\"colhead\" colspan=\"2\">Детали запроса \"$s\"</td></tr>");
print("<tr><td align=left>Запрос</td><td width=90% align=left>$num[request]</td></tr>");
print("<tr><td align=left>Инфо</td><td width=90% align=left>" . format_comment($num["descr"]) . "</td></tr>");
print("<tr><td align=left>Добавлен</td><td width=90% align=left>$num[added]</td></tr>");

$cres = sql_query("SELECT username, id FROM users WHERE id=$num[userid]");
if (mysql_num_rows($cres) == 1)
{
		$carr = mysql_fetch_assoc($cres);
		$username = "$carr[username]";
		$user_req_id = $carr["id"];
}
print("<tr><td align=left>Запросил</td><td width=90% align=left><a href=userdetails.php?id=$user_req_id>$username</a>&nbsp;".'<a href="simpaty.php?action=add&amp;good&amp;targetid='.$user_req_id.'&amp;type=request'.$id.'&amp;returnto=' . urlencode($_SERVER["REQUEST_URI"]) . '" title="'.$tracker_lang['respect'].'"><img src="pic/thum_good.gif" border="0" alt="'.$tracker_lang['respect'].'" title="'.$tracker_lang['respect'].'"></a>&nbsp;&nbsp;<a href="simpaty.php?action=add&amp;bad&amp;targetid='.$user_req_id.'&amp;type=request'.$id.'&amp;returnto=' . urlencode($_SERVER["REQUEST_URI"]) . '" title="'.$tracker_lang['antirespect'].'"><img src="pic/thum_bad.gif" border="0" alt="'.$tracker_lang['antirespect'].'" title="'.$tracker_lang['antirespect'].'"></a>'."</td></tr>");
print("<tr><td align=left>Голосовать за этот запрос</td><td width=50% align=left><a href=requests.php?action=vote&voteid=$id><b>Голосовать</b></a></td></tr></tr>");

if ($num["filled"] == '')
{
       print("<form method=post action=requests.php>");
       print("<tr><td align=left>Выполнить запрос</td><td>Введите <b>полный</b> адрес торрента, например как $DEFAULTBASEURL/details.php?id=11 (просто скопируйте/вставьте его из другого окна/вкладки)");
       print("<input type=text size=80 name=filledurl>\n");
       print("<input type=hidden value=$id name=requestid>");
       print("<input type=hidden name=action value=filled>");
       print("<input type=submit value=\"Выполнить запрос\">\n</form></td></tr>");
}
if (get_user_class() >= UC_MODERATOR || $CURUSER["id"] == $num["userid"])
print("<tr><td align=left>Опции</td><td width=50% align=left><a OnClich=\"return confirm('Вы уверены?')\" href=viewrequests.php?delreq[]=$id>".$tracker_lang['delete']."</a> <b>|</b> <a href=requests.php?action=reset&requestid=$id>Сбросить выполнение</a>  <b>|</b>  <a href=requests.php?action=edit&id=$id>".$tracker_lang['edit']."</a></center></td></tr>");
//print("<tr><td class=embedded colspan=2><p><a name=\"startcomments\"></a></p></td></tr>\n");

		/*$checkcomm = mysql_num_rows(sql_query("SELECT * FROM checkcomm WHERE userid = $CURUSER[id] AND checkid = $id AND req = 1"));
		$check = (!$checkcomm ? "<a class=index href=reqcomment.php?action=check&amp;tid=$id>".$tracker_lang['monitor_comments']."</a>" : "<a class=index href=reqcomment.php?action=checkoff&amp;tid=$id>".$tracker_lang['monitor_comments_disable']."</a>");
		$commentbar = "<p align=center><a class=index href=reqcomment.php?action=add&amp;tid=$id>".$tracker_lang['add_comment']."</a>&nbsp;&nbsp;&nbsp;&nbsp;$check</p>\n";*/

$subres = sql_query("SELECT COUNT(*) FROM comments WHERE request = $id");
$subrow = mysql_fetch_array($subres);
$count = $subrow[0];
print("</table>");

print("<p><a name=\"startcomments\"></a></p>\n");

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
  print("<tr><td  align=\"center\" >");
  //print("<b>Ваше имя:</b> ");
  //print("".$CURUSER['username']."<p>");
  print("<form name=\"comment\" method=\"post\" action=\"reqcomment.php?action=add\">");
  print("<div>");
  textbbcode("comment","text","");
  print("</div>");
  print("</td></tr><tr><td align=\"center\" colspan=\"2\">");
  print("<input type=\"hidden\" name=\"tid\" value=\"$id\"/>");
  print("<input type=\"submit\" class=btn value=\"Разместить комментарий\" />");
  print("</td></tr></form></table>");
} else {
		list($pagertop, $pagerbottom, $limit) = pager(20, $count, "requests.php?id=$id&", array(lastpagedefault => 1));
		$subres = sql_query("SELECT c.id, c.ip, c.text, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, ".
		"u.username, u.title, u.class, u.donor, u.downloaded, u.uploaded, u.enabled, u.parked, u.last_access, e.username AS editedbyname FROM comments c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN users AS e ON c.editedby = e.id WHERE c.request = " .
		"$id ORDER BY c.id $limit") or sqlerr(__FILE__, __LINE__);
		$allrows = array();
		while ($subrow = mysql_fetch_array($subres))
		$allrows[] = $subrow;
		print("<table class=main cellSpacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
		print("<tr><td class=\"colhead\" align=\"center\" >");
		print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
		print("<div align=\"right\"><a href=#comments class=altlink_white>Добавить комментарий</a></div>");
		print("</td></tr>");
//		print($commentbar);
		print("<tr><td>");
		print($pagertop);
		print("</td></tr>");
		print("<tr><td>");
		commenttable($allrows, "reqcomment");
		print("</td></tr>");
		print("<tr><td>");
		print($pagerbottom);
		print("</td></tr>");
		print("</table>");

		print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
		print("<tr><td class=colhead align=\"left\" colspan=\"2\">  <a name=comments>&nbsp;</a><b>:: Добавить комментарий к запросу</b></td></tr>");
		print("<tr><td width=\"100%\" align=\"center\" >");
		//print("Ваше имя: ");
		//print("".$CURUSER['username']."<p>");
		print("<form name=comment method=\"post\" action=\"reqcomment.php?action=add\">");
		print("<center><table border=\"0\"><tr><td class=\"clear\">");
		print("<div align=\"center\">". textbbcode("comment","text","") ."</div>");
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