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
loggedinorreturn();

if (get_user_class() < UC_ADMINISTRATOR)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);

$action = $_GET["action"];

//   Delete News Item    //////////////////////////////////////////////////////

if ($action == 'delete')
{
	$newsid = (int)$_GET["newsid"];
	if (!is_valid_id($newsid))
	stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

	$returnto = makesafe($_GET["returnto"]);

	sql_query("DELETE FROM news WHERE id=$newsid") or sqlerr(__FILE__, __LINE__);

	$CACHE->clearGroupCache("block-news");
	if ($returnto != "")
	header("Location: $returnto");
	else
	$warning = "Новость <b>успешно</b> удалена";
}

//   Add News Item    /////////////////////////////////////////////////////////

if ($action == 'add')
{

	$subject = htmlspecialchars($_POST["subject"]);
	if (!$subject)
	stderr($tracker_lang['error'],"Тема новости не может быть пустой!");

	$body = ((string)$_POST["body"]);
	if (!$body)
	stderr($tracker_lang['error'],"Тело новости не может быть пустым!");

	$added = $_POST["added"];
	if (!$added)
	$added = sqlesc(time());

	sql_query("INSERT INTO news (userid, added, body, subject) VALUES (".
	$CURUSER['id'] . ", $added, " . sqlesc($body) . ", " . sqlesc($subject) . ")") or sqlerr(__FILE__, __LINE__);

	$CACHE->clearGroupCache("block-news");
	$warning = "Новость <b>успешно добавлена</b>";

}

//   Edit News Item    ////////////////////////////////////////////////////////

if ($action == 'edit')
{

	$newsid = (int)$_GET["newsid"];

	if (!is_valid_id($newsid))
	stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

	$res = sql_query("SELECT * FROM news WHERE id=$newsid") or sqlerr(__FILE__, __LINE__);

	if (mysql_num_rows($res) != 1)
	stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

	$arr = mysql_fetch_array($res);

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$body = (string)$_POST['body'];
		$subject = htmlspecialchars($_POST['subject']);

		if ($subject == "")
		stderr($tracker_lang['error'],"Тема новости не может быть пустой!");

		if ($body == "")
		stderr($tracker_lang['error'], "Тело новости не может быть пустым!");

		$body = sqlesc(($body));

		$subject = sqlesc($subject);

		$editedat = sqlesc(time());

		sql_query("UPDATE news SET body=$body, subject=$subject WHERE id=$newsid") or sqlerr(__FILE__, __LINE__);

		$CACHE->clearGroupCache("block-news");

		$returnto = makesafe($_POST['returnto']);

		if ($returnto != "")
		header("Location: $returnto");
		else
		$warning = "Новость <b>успешно</b> отредактирована";
	}
	else
	{
		$returnto = makesafe($_GET['returnto']);
		stdhead("Редактирование новости");
		print("<form method=post name=news action=news.php?action=edit&newsid=$newsid>\n");
		print("<table border=1 cellspacing=0 cellpadding=5>\n");
		print("<tr><td class=colhead>Редактирование новости<input type=hidden name=returnto value=$returnto></td></tr>\n");
		print("<tr><td>Тема: <input type=text name=subject maxlength=70 size=50 value=\"" . makesafe($arr["subject"]) . "\"/></td></tr>");
		print("<tr><td style='padding: 0px'>");
		print textbbcode("body",$arr["body"]);
		//<textarea name=body cols=145 rows=5 style='border: 0px'>" . htmlspecialchars($arr["body"]) .
		print("</textarea></td></tr>\n");
		print("<tr><td align=center><input type=submit value='Отредактировать'></td></tr>\n");
		print("</table>\n");
		print("</form>\n");
		stdfoot();
		die;
	}
}

//   Other Actions and followup    ////////////////////////////////////////////

stdhead("Новости");
if ($warning)
print("<p><font size=-3>($warning)</font></p>");
print("<form method=post name=news action=news.php?action=add>\n");
print("<table border=1 cellspacing=0 cellpadding=5>\n");
print("<tr><td class=colhead>Добавить новость</td></tr>\n");
print("<tr><td>Тема: <input type=text name=subject maxlength=40 size=50 value=\"" . makesafe($arr["subject"]) . "\"/></td></tr>");
print("<tr><td style='padding: 0px'>");
print textbbcode("body");
//<textarea name=body cols=145 rows=5 style='border: 0px'>
print("</textarea></td></tr>\n");
print("<tr><td align=center><input type=submit value='Добавить' class=btn></td></tr>\n");
print("</table></form><br /><br />\n");

stdfoot();
die;
?>