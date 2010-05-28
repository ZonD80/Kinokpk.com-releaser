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

require_once("include/bittorrent.php");

$action = $_GET["action"];

dbconn();

loggedinorreturn();
parked();

if ($action == "add")
{
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (!is_valid_id($_POST["tid"]))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
			
		$pageid = (int) $_POST["tid"];

		$res = sql_query("SELECT name FROM pages WHERE id = $pageid") or sqlerr(__FILE__,__LINE__);
		$arr = mysql_fetch_array($res);
		if (!$arr)
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		$name = $arr[0];

		if ($CACHEARRAY['use_integration']) $topicid=$arr[1];

		$text = trim($_POST["text"]);
		if (!$text)
		stderr($tracker_lang['error'], $tracker_lang['comment_cant_be_empty']);
		// ANTISPAM AND ANTIFLOOD SYSTEM
		$last_pmres = sql_query("SELECT ".time()."-added AS seconds, text AS msg, id, page FROM pagecomments WHERE user=".$CURUSER['id']." ORDER BY added DESC LIMIT 4");
		while ($last_pmresrow = mysql_fetch_array($last_pmres)){
			$last_pmrow[] = $last_pmresrow;
			$msgids[] = $last_pmresrow['id'];
			$torids[] = $last_pmresrow['page'];
		}
		//   print_r($last_pmrow);
		if ($last_pmrow[0]){
			if (($CACHEARRAY['as_timeout'] > round($last_pmrow[0]['seconds'])) && $CACHEARRAY['as_timeout']) {
				$seconds =  $CACHEARRAY['as_timeout'] - round($last_pmrow[0]['seconds']);
				stderr($tracker_lang['error'],"На нашем сайте стоит защита от флуда, пожалуйста, повторите попытку через $seconds секунд. <a href=\"javascript: history.go(-1)\">Назад</a>");
			}

			if ($CACHEARRAY['as_check_messages'] && ($last_pmrow[0]['msg'] == $text) && ($last_pmrow[1]['msg'] == $text) && ($last_pmrow[2]['msg'] == $text) && ($last_pmrow[3]['msg'] == $text)) {
				$msgview='';
				foreach ($msgids as $key => $msgid){
					$msgview.= "\n<a href=pagedetails.php?id={$torids[$key]}#comm$msgid>Комментарий ID={$msgid}</a> от пользователя ".$CURUSER['username'];
				}
				$modcomment = sql_query("SELECT modcomment FROM users WHERE id=".$CURUSER['id']);
				$modcomment = mysql_result($modcomment,0);
				if (strpos($modcomment,"Maybe spammer in pagecomments") === false) {
					$arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

					while (list($admin) = mysql_fetch_array($arow)) {
						sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
						$admin, '" . time() . "', 'Пользователь <a href=userpagedetails.php?id=".$CURUSER['id'].">".$CURUSER['username']."</a> может быть спамером, т.к. его 5 последних посланных комментариев к страницам полностью совпадают.$msgview', 'Сообщение о спаме!', 1)") or sqlerr(__FILE__, __LINE__);
					}
					$modcomment .= "\n".time()." - Maybe spammer in pagecomments";
					sql_query("UPDATE users SET modcomment = ".sqlesc($modcomment)." WHERE id =".$CURUSER['id']);

				} else {
					sql_query("UPDATE users SET enabled=0, dis_reason='Spam in pagecomments' WHERE id=".$CURUSER['id']);

					$arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

					while (list($admin) = mysql_fetch_array($arow)) {
						sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
						$admin, '" . time() . "', 'Пользователь <a href=userpagedetails.php?id=".$CURUSER['id'].">".$CURUSER['username']."</a> забанен системой за спам, его IP адрес (".$CURUSER['ip'].")', 'Сообщение о спаме [бан]!', 1)") or sqlerr(__FILE__, __LINE__);
						stderr("Поздравляем!","Вы успешно забанены системой за спам в комментариях к страницам! Если вы не согласны с решением системы, <a href=\"contact.php\">подайте жалобу админам</a>.");
					}
				}
				stderr($tracker_lang['error'],"На нашем сайте стоит защита от спама, ваши 5 последних комментариев к страницам совпадают. В отсылке комментария отказано. <b><u>ВНИМАНИЕ! ЕСЛИ ВЫ ЕЩЕ РАЗ ПОПЫТАЕТЕСЬ ОТПРАВИТЬ ИДЕНТИЧНОЕ СООБЩЕНИЕ, ВЫ БУДЕТЕ АВТОМАТИЧЕСКИ ЗАБЛОКИРОВАНЫ СИСТЕМОЙ!!!</u></b> <a href=\"javascript: history.go(-1)\">Назад</a>");

			}
		}

		$text = ($text);
		// ANITSPAM SYSTEM END

		sql_query("INSERT INTO pagecomments (user, page, added, text, ip) VALUES (" .
		$CURUSER["id"] . ",$pageid, '" . time() . "', " . sqlesc($text) .
	       "," . sqlesc(getip()) . ")") or die(mysql_error());

		$newid = mysql_insert_id();

		sql_query("UPDATE users SET bonus=bonus+10 WHERE id =".$CURUSER['id']);

		sql_query("UPDATE pages SET comments = comments + 1 WHERE id = $pageid");

		/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////

		// send_comment_notifs($pageid,"<a href=pagedetails.php?id=$pageid#comm$newid>".$name."</a>",'pagecomments');

		/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////

		header("Location: pagedetails.php?id=$pageid#comm$newid");
		die;
	}

	if (!is_valid_id($_GET["tid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	$pageid = (int) $_GET["tid"];

	$res = sql_query("SELECT name FROM pages WHERE id = $pageid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($tracker_lang['error'], $tracker_lang['no_page_with_such_id']);

	stdhead("Добление комментария к \"" . $arr["name"] . "\"");

	print("<p><form name=\"comment\" method=\"post\" action=\"pagecomment.php?action=add\">\n");
	print("<input type=\"hidden\" name=\"tid\" value=\"$pageid\"/>\n");
	?>
<table class=main border=0 cellspacing=0 cellpadding=3>
	<tr>
		<td class="colhead"><?
		print("".$tracker_lang['add_comment']." к \"" . htmlspecialchars($arr["name"]) . "\"");
		?></td>
	</tr>
	<tr>
		<td><?
		print textbbcode("text","");
		?></td>
	</tr>
</table>
		<?
		//print("<textarea name=\"text\" rows=\"10\" cols=\"60\"></textarea></p>\n");
		print("<p><input type=\"submit\" value=\"Добавить\" /></p></form>\n");

		$res = sql_query("SELECT pagecomments.id, text, pagecomments.ip, pagecomments.ratingsum, pagecomments.added, username, title, class, users.id as user, users.avatar, users.donor, users.enabled, users.warned, users.parked FROM pagecomments LEFT JOIN users ON pagecomments.user = users.id WHERE page = $pageid ORDER BY pagecomments.id DESC LIMIT 5");

		$allrows = array();
		while ($row = mysql_fetch_array($res))
		$allrows[] = $row;

		if (count($allrows)) {
	  print("<h2>Последние комментарии, в обратном порядке</h2>\n");
	  commenttable($allrows);
		}

		stdfoot();
		die;
}
elseif ($action == "quote")
{

	if (!is_valid_id($_GET["cid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	$commentid = (int) $_GET["cid"];

	$res = sql_query("SELECT c.*, t.name, t.id AS tid, u.username FROM pagecomments AS c LEFT JOIN pages AS t ON c.page = t.id JOIN users AS u ON c.user = u.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);


	stdhead("Добавления комментария к \"" . htmlspecialchars($arr["name"]) . "\"");

	$text = "{$arr[username]}:<br /><blockquote><cite title=\"$arr[username]\">" . $arr["text"] . "</cite></blockquote><hr />\n";

	print("<form method=\"post\" name=\"comment\" action=\"pagecomment.php?action=add\">\n");
	print("<input type=\"hidden\" name=\"tid\" value=\"$arr[tid]\" />\n");
	?>

<table class=main border=0 cellspacing=0 cellpadding=3>
	<tr>
		<td class="colhead"><?
		print("Добавления комментария к \"" . htmlspecialchars($arr["name"]) . "\"");
		?></td>
	</tr>
	<tr>
		<td><?
		print textbbcode("text",$text);
		?></td>
	</tr>
</table>

		<?

		print("<p><input type=\"submit\" value=\"Добавить\" /></p></form>\n");

		stdfoot();

}
elseif ($action == "edit")
{
	if (!is_valid_id($_GET["cid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	$commentid = (int) $_GET["cid"];

	$res = sql_query("SELECT c.*, t.name, t.id AS tid FROM pagecomments AS c LEFT JOIN pages AS t ON c.page = t.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	if ($arr["user"] != $CURUSER["id"] && get_user_class() < UC_MODERATOR)
	stderr($tracker_lang['error'], $tracker_lang['access_denied']);

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$text = ((string)$_POST["text"]);

		if ($text == "")
		stderr($tracker_lang['error'], $tracker_lang['comment_cant_be_empty']);

		$text = sqlesc(($text));

		$editedat = time();
			
		sql_query("UPDATE pagecomments SET text=$text, editedat=$editedat, editedby=$CURUSER[id] WHERE id=$commentid") or sqlerr(__FILE__, __LINE__);

		if ($_POST["returnto"])
		header("Location: ".strip_tags($_POST["returnto"]));
		else
		header("Location: {$CACHEARRAY['defaultbaseurl']}/");      // change later ----------------------
		die;
	}

	stdhead("Редактирование комментария к \"" . $arr["name"] . "\"");

	print("<form method=\"post\" name=\"comment\" action=\"pagecomment.php?action=edit&amp;cid=$commentid\">\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"pagedetails.php?id={$arr["tid"]}#comm$commentid\" />\n");
	print("<input type=\"hidden\" name=\"cid\" value=\"$commentid\" />\n");
	?>

<table class=main border=0 cellspacing=0 cellpadding=3>
	<tr>
		<td class="colhead"><?
		print("Редактирование комментария к \"" . htmlspecialchars($arr["name"]) . "\"");
		?></td>
	</tr>
	<tr>
		<td><?
		print textbbcode("text",$arr["text"]);
		?></td>
	</tr>
</table>

		<?

		print("<p><input type=\"submit\" value=\"Отредактировать\" /></p></form>\n");

		stdfoot();
		die;
}
elseif ($action == "delete")
{
	if (get_user_class() < UC_MODERATOR)
	stderr($tracker_lang['error'], $tracker_lang['access_denied']);

	if (!is_valid_id($_GET["cid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	$commentid = (int) $_GET["cid"];


	$res = sql_query("SELECT page FROM pagecomments WHERE id=$commentid")  or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if ($arr)
	$pageid = $arr["page"];
	else
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	sql_query("DELETE FROM pagecomments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
	if ($pageid && mysql_affected_rows() > 0)
	sql_query("UPDATE pages SET comments = comments - 1 WHERE id = $pageid");


	list($commentid) = mysql_fetch_row(sql_query("SELECT id FROM pagecomments WHERE page = $pageid ORDER BY added DESC LIMIT 1"));

	$returnto = "pagedetails.php?id=$pageid#comm$commentid";

	if ($returnto)
	header("Location: $returnto");
	else
	header("Location: {$CACHEARRAY['defaultbaseurl']}/");      // change later ----------------------
	die;
}
else
stderr($tracker_lang['error'], "Unknown action");

die;
?>