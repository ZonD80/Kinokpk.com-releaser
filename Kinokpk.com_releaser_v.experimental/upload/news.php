<?php
/**
 * News viewver & admincp
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";

INIT();
loggedinorreturn();

get_privilege('news_operation');

$action = (string)$_GET["action"];

//   Delete News Item    //////////////////////////////////////////////////////

if ($action == 'delete')
{
	$newsid = (int)$_GET["newsid"];
	if (!is_valid_id($newsid))
	stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

	$returnto = makesafe($_GET["returnto"]);

	sql_query("DELETE FROM news WHERE id=$newsid") or sqlerr(__FILE__, __LINE__);
	sql_query("DELETE FROM comments WHERE toid=$newsid AND type='news'") or sqlerr(__FILE__, __LINE__);
	sql_query("DELETE FROM notifs WHERE type='newscomments' AND checkid=$newsid") or sqlerr(__FILE__, __LINE__);

	$REL_CACHE->clearGroupCache("block-news");
	if ($returnto != "")
	safe_redirect($returnto);
	else
	$warning = "Новость <b>успешно</b> удалена";
}

elseif ($action == 'add')
{

	$subject = htmlspecialchars((string)$_POST["subject"]);
	if (!$subject)
	stderr($REL_LANG->say_by_key('error'),"Тема новости не может быть пустой!");

	$body = ((string)$_POST["body"]);
	if (!$body)
	stderr($REL_LANG->say_by_key('error'),"Тело новости не может быть пустым!");

	$added = time();

	sql_query("INSERT INTO news (userid, added, body, subject) VALUES (".
	$CURUSER['id'] . ", $added, " . sqlesc($body) . ", " . sqlesc($subject) . ")") or sqlerr(__FILE__, __LINE__);

	$REL_CACHE->clearGroupCache("block-news");
	$warning = "Новость <b>успешно добавлена</b>";

}

elseif ($action == 'edit')
{

	$newsid = (int)$_GET["newsid"];

	if (!is_valid_id($newsid))
	stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$body = (string)$_POST['body'];
		$subject = htmlspecialchars((string)$_POST['subject']);

		if (!$subject)
		stderr($REL_LANG->say_by_key('error'),"Тема новости не может быть пустой!");

		if (!$body)
		stderr($REL_LANG->say_by_key('error'), "Тело новости не может быть пустым!");

		$body = sqlesc(($body));

		$subject = sqlesc($subject);

		$editedat = sqlesc(time());

		sql_query("UPDATE news SET body=$body, subject=$subject WHERE id=$newsid") or sqlerr(__FILE__, __LINE__);

		$REL_CACHE->clearGroupCache("block-news");

		$returnto = makesafe($_POST['returnto']);

		if ($returnto != "")
		safe_redirect($returnto);
		else
		$warning = "Новость <b>успешно</b> отредактирована";
	}
	else
	{
		$res = sql_query("SELECT * FROM news WHERE id=$newsid") or sqlerr(__FILE__, __LINE__);

		if (mysql_num_rows($res) != 1)
		stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

		$arr = mysql_fetch_array($res);
		$returnto = makesafe($_GET['returnto']);
		$REL_TPL->stdhead("Редактирование новости");
		print("<form method=post name=news action=\"".$REL_SEO->make_link('news','action','edit','newsid',$newsid)."\">\n");
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
		$REL_TPL->stdfoot();
		die;
	}
}

//   Other Actions and followup    ////////////////////////////////////////////

$REL_TPL->stdhead("Новости");
if ($warning)
print("<p><font size=-3>($warning)</font></p>");
print("<form method=post name=news action=\"".$REL_SEO->make_link('news','action','add')."\">\n");
print("<table border=1 cellspacing=0 cellpadding=5>\n");
print("<tr><td class=colhead>Добавить новость</td></tr>\n");
print("<tr><td>Тема: <input type=text name=subject maxlength=40 size=50 value=\"" . makesafe($arr["subject"]) . "\"/></td></tr>");
print("<tr><td style='padding: 0px'>");
print textbbcode("body",$arr["body"]);
//<textarea name=body cols=145 rows=5 style='border: 0px'>
print("</textarea></td></tr>\n");
print("<tr><td align=center><input type=submit value='Добавить' class=btn></td></tr>\n");
print("</table></form><br /><br />\n");

$REL_TPL->stdfoot();
?>