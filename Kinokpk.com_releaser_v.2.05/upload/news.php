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
	stderr($tracker_lang['error'], "Permission denied.");

$action = $_GET["action"];

//   Delete News Item    //////////////////////////////////////////////////////

if ($action == 'delete')
{
	$newsid = (int)$_GET["newsid"];
  if (!is_valid_id($newsid))
  	stderr($tracker_lang['error'],"Invalid news item ID - Code 1.");

  $returnto = htmlentities($_GET["returnto"]);

  $sure = $_GET["sure"];
  if (!$sure)
    stderr("Удалить новость","Вы действителньо хотите удалить эту новость? Нажмите\n" .
    	"<a href=?action=delete&newsid=$newsid&returnto=$returnto&sure=1>сюда</a> если вы уверены.");

  sql_query("DELETE FROM news WHERE id=$newsid") or sqlerr(__FILE__, __LINE__);
  sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='news_lastupdate'");
	if ($returnto != "")
		header("Location: $returnto");
	else
		$warning = "Новость <b>успешно</b> удалена";
}

//   Add News Item    /////////////////////////////////////////////////////////

if ($action == 'add')
{

	$subject = $_POST["subject"];
	if (!$subject)
		stderr($tracker_lang['error'],"Тема новости не может быть пустой!");

	$body = $_POST["body"];
	if (!$body)
		stderr($tracker_lang['error'],"Тело новости не может быть пустым!");

	$added = $_POST["added"];
	if (!$added)
		$added = sqlesc(get_date_time());

  sql_query("INSERT INTO news (userid, added, body, subject) VALUES (".
  	$CURUSER['id'] . ", $added, " . sqlesc($body) . ", " . sqlesc($subject) . ")") or sqlerr(__FILE__, __LINE__);

      sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='news_lastupdate'");
	if (mysql_affected_rows() == 1)
		$warning = "Новость <b>успешно добавлена</b>";
	else
		stderr($tracker_lang['error'],"Только-что произошло что-то непонятное.");
}

//   Edit News Item    ////////////////////////////////////////////////////////

if ($action == 'edit')
{

	$newsid = (int)$_GET["newsid"];

  if (!is_valid_id($newsid))
  	stderr($tracker_lang['error'],"Invalid news item ID - Code 2.");

  $res = sql_query("SELECT * FROM news WHERE id=$newsid") or sqlerr(__FILE__, __LINE__);

	if (mysql_num_rows($res) != 1)
	  stderr($tracker_lang['error'], "No news item with ID.");

	$arr = mysql_fetch_array($res);

  if ($_SERVER['REQUEST_METHOD'] == 'POST')
  {
  	$body = $_POST['body'];
  	$subject = $_POST['subject'];


	$subject = $_POST["subject"];
	if ($subject == "")
		stderr($tracker_lang['error'],"Тема новости не может быть пустой!");

    if ($body == "")
    	stderr($tracker_lang['error'], "Тело новости не может быть пустым!");

    $body = sqlesc($body);

    $subject = sqlesc($subject);

    $editedat = sqlesc(get_date_time());

    sql_query("UPDATE news SET body=$body, subject=$subject WHERE id=$newsid") or sqlerr(__FILE__, __LINE__);
    
      sql_query("UPDATE cache_stats SET cache_value=".time()." WHERE cache_name='news_lastupdate'");

    $returnto = htmlentities($_POST['returnto']);

		if ($returnto != "")
			header("Location: $returnto");
		else
			$warning = "Новость <b>успешно</b> отредактирована";
  }
  else
  {
 	 	$returnto = htmlentities($_GET['returnto']);
	  stdhead("Редактирование новости");
	  print("<form method=post name=news action=?action=edit&newsid=$newsid>\n");
	  print("<table border=1 cellspacing=0 cellpadding=5>\n");
	  print("<tr><td class=colhead>Редактирование новости<input type=hidden name=returnto value=$returnto></td></tr>\n");
	  print("<tr><td>Тема: <input type=text name=subject maxlength=70 size=50 value=\"" . htmlspecialchars($arr["subject"]) . "\"/></td></tr>");
	  print("<tr><td style='padding: 0px'>");
	  textbbcode("news","body",htmlspecialchars($arr["body"]));
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
print("<form method=post name=news action=?action=add>\n");
print("<table border=1 cellspacing=0 cellpadding=5>\n");
print("<tr><td class=colhead>Добавить новость</td></tr>\n");
print("<tr><td>Тема: <input type=text name=subject maxlength=40 size=50 value=\"" . htmlspecialchars($arr["subject"]) . "\"/></td></tr>");
print("<tr><td style='padding: 0px'>");
textbbcode("news","body","");
//<textarea name=body cols=145 rows=5 style='border: 0px'>
print("</textarea></td></tr>\n");
print("<tr><td align=center><input type=submit value='Добавить' class=btn></td></tr>\n");
print("</table></form><br /><br />\n");

stdfoot();
die;
?>