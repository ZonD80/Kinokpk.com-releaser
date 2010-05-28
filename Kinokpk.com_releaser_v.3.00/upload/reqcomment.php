<?php
/**
 * Requests comment parser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

$action = $_GET["action"];
dbconn();

loggedinorreturn();

if ($action == "add") {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$reqid = (int) $_POST["tid"];
		if (!is_valid_id($reqid))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		$res = sql_query("SELECT request, userid FROM requests WHERE id = $reqid") or sqlerr(__FILE__,__LINE__);
		$arr = mysql_fetch_array($res);
		if (!$arr)
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		$name = $arr[0];
		$text = trim(((string)$_POST["text"]));
		if (!$text)
		stderr($tracker_lang['error'], $tracker_lang['no_fields_blank']);

		// ANTISPAM AND ANTIFLOOD SYSTEM
		$last_pmres = sql_query("SELECT ".time()."-added AS seconds, text AS msg, id, request AS torrent FROM reqcomments WHERE user=".$CURUSER['id']." ORDER BY added DESC LIMIT 4");
		while ($last_pmresrow = mysql_fetch_array($last_pmres)){
			$last_pmrow[] = $last_pmresrow;
			$msgids[] = $last_pmresrow['id'];
			$torids[] = $last_pmresrow['torrent'];
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
					$msgview.= "\n<a href=requests.php?id={$torids[$key]}#comm$msgid>Комментарий ID={$msgid}</a> от пользователя ".$CURUSER['username'];
				}
				$modcomment = sql_query("SELECT modcomment FROM users WHERE id=".$CURUSER['id']);
				$modcomment = mysql_result($modcomment,0);
				if (strpos($modcomment,"Maybe spammer in requests comments") === false) {
					$arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

					while (list($admin) = mysql_fetch_array($arow)) {
						sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
						$admin, '" . time() . "', 'Пользователь <a href=userdetails.php?id=".$CURUSER['id'].">".$CURUSER['username']."</a> может быть спамером, т.к. его 5 последних посланных комментариев к запросам полностью совпадают.$msgview', 'Сообщение о спаме!', 1)") or sqlerr(__FILE__, __LINE__);
					}
					$modcomment .= "\n".time()." - Maybe spammer in requests comments";
					sql_query("UPDATE users SET modcomment = ".sqlesc($modcomment)." WHERE id =".$CURUSER['id']);

				} else {
					sql_query("UPDATE users SET enabled=0, dis_reason='Spam in requests comments' WHERE id=".$CURUSER['id']);

					$arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

					while (list($admin) = mysql_fetch_array($arow)) {
						sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
						$admin, '" . time() . "', 'Пользователь <a href=userdetails.php?id=".$CURUSER['id'].">".$CURUSER['username']."</a> забанен системой за спам в комментариях к запросам, его IP адрес (".$CURUSER['ip'].")', 'Сообщение о спаме [бан]!', 1)") or sqlerr(__FILE__, __LINE__);
						stderr("Поздравляем!","Вы успешно забанены системой за спам в комментариях к запросам! Если вы не согласны с решением системы, <a href=\"contact.php\">подайте жалобу админам</a>.");
					}
				}
				stderr($tracker_lang['error'],"На нашем сайте стоит защита от спама, ваши 5 последних комментариев к запросам совпадают. В отсылке комментария отказано. <b><u>ВНИМАНИЕ! ЕСЛИ ВЫ ЕЩЕ РАЗ ПОПЫТАЕТЕСЬ ОТПРАВИТЬ ИДЕНТИЧНОЕ СООБЩЕНИЕ, ВЫ БУДЕТЕ АВТОМАТИЧЕСКИ ЗАБЛОКИРОВАНЫ СИСТЕМОЙ!!!</u></b> <a href=\"javascript: history.go(-1)\">Назад</a>");

			}
		}

		// ANITSPAM SYSTEM END

		sql_query("INSERT INTO reqcomments (user, request, added, text, ip) VALUES (" .
		$CURUSER["id"] . ",$reqid, '" . time() . "', " . sqlesc($text) .
                "," . sqlesc(getip()) . ")");
		$newid = mysql_insert_id();
		sql_query("UPDATE requests SET comments = comments + 1 WHERE id = $reqid");

		$CACHE->clearGroupCache("block-req");

		send_comment_notifs($reqid,"<a href=requests.php?id=$reqid#comm$newid>".$name."</a>",'reqcomments');
		/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
		safe_redirect(" requests.php?id=$reqid#comm$newid");

		exit();
	}
}
elseif ($action == "quote")
{
	if (!is_valid_id($_GET["cid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	$commentid = (int) $_GET["cid"];
	$res = sql_query("SELECT c.*, r.request, r.id AS rid, u.username FROM reqcomments AS c JOIN requests AS r ON c.request = r.id JOIN users AS u ON c.user = u.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	$text = "<blockquote><p>" . format_comment($arr["text"]) . "</p><cite>$arr[username]</cite></blockquote><hr /><br /><br />\n";
	$reqid = $arr["rid"];

	stdhead("Добавление комментаря к \"" . $arr["request"] . "\"");

	print("<form name=form method=\"post\" action=\"reqcomment.php?action=add\">\n");
	print("<input type=\"hidden\" name=\"tid\" value=\"$reqid\"/>\n");
	print("<p align=center><table border=1 cellspacing=1>\n");
	print("<tr><td class=colhead colspan=2>Добавить комментарий к \"" . htmlspecialchars($arr["request"]) . "\"</td></tr>\n");
	print("<tr><td align=center>\n");
	print textbbcode("text",$text);
	print("</td></tr>\n");
	print("<tr><td align=center colspan=2><input type=submit value=\"Добавить\"></td></tr></form></table>\n");

	stdfoot();

}
elseif ($action == "edit")
{
	if (!is_valid_id($_GET["cid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	$commentid = (int) $_GET["cid"];
	$res = sql_query("SELECT c.*, r.request, r.id AS rid FROM reqcomments AS c JOIN requests AS r ON c.request = r.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);

	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	if ($arr["user"] != $CURUSER["id"] && get_user_class() < UC_MODERATOR)
	stderr($tracker_lang['error'], $tracker_lang['access_denied']);

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$text = ((string)$_POST["msg"]);
		$returnto = makesafe($_POST["returnto"]);

		if ($text == "")
		stderr($tracker_lang['error'], $tracker_lang['no_fields_blank']);

		$text = sqlesc($text);

		$editedat = sqlesc(time());

		sql_query("UPDATE reqcomments SET text=$text, editedat=$editedat, editedby=$CURUSER[id]  WHERE id=$commentid") or sqlerr(__FILE__, __LINE__);


		$CACHE->clearGroupCache("block-req");

		if ($returnto)
		safe_redirect(" $returnto");
		else
		safe_redirect(" {$CACHEARRAY['defaultbaseurl']}/"); // change later ----------------------

		die;
	}

	stdhead("Редактировать комментарий к \"" . $arr["request"] . "\"");

	print("<form name=form method=\"post\" action=\"reqcomment.php?action=edit&amp;cid=$commentid\">\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"requests.php?id={$arr["rid"]}#comm$commentid\" />\n");
	print("<input type=\"hidden\" name=\"cid\" value=\"$commentid\" />\n");
	print("<p align=center><table border=1 cellspacing=1>\n");
	print("<tr><td class=colhead colspan=2>Редактировать комментарий к \"" . htmlspecialchars($arr["request"]) . "\"</td></tr>\n");
	print("<tr><td align=center>\n");
	print textbbcode("msg",$arr["text"]);
	print("</td></tr>\n");
	print("<tr><td align=center colspan=2><input type=submit value=\"".$tracker_lang['edit']."\"></td></tr></form></table></p>\n");

	stdfoot();

	die;
}
/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
elseif ($action == "check" || $action == "checkoff")
{
	if (!is_valid_id($_GET["tid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	$tid = (int) $_GET["tid"];

	$docheck = mysql_fetch_array(sql_query("SELECT SUM(1) FROM notifs WHERE checkid = " . $tid . " AND userid = ". $CURUSER["id"] . " AND type='requests'"));
	if ($docheck[0] > 0 && $action=="check")
	stderr($tracker_lang['error'], "<p>Вы уже подписаны на этот запрос.</p><a href=requests.php?id=$tid#startcomments>Назад</a>");
	if ($action == "check") {
		sql_query("INSERT INTO notifs (checkid, userid, type) VALUES ($tid, $CURUSER[id], 'requests')") or sqlerr(__FILE__,__LINE__);
		stderr($tracker_lang['success'], "<p>Теперь вы следите за комментариями к этому запросу.</p><a href=requests.php?id=$tid#startcomments>Назад</a>");
	}
	else {
		sql_query("DELETE FROM notifs WHERE checkid = $tid AND userid = $CURUSER[id] AND type = 'requests'") or sqlerr(__FILE__,__LINE__);
		stderr($tracker_lang['success'], "<p>Теперь вы не следите за комментариями к этому запросу.</p><a href=requests.php?id=$tid#startcomments>Назад</a>");
	}

}
/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////
elseif ($action == "delete")
{
	if (get_user_class() < UC_MODERATOR)
	stderr($tracker_lang['error'], $tracker_lang['access_denied']);


	if (!is_valid_id($_GET["cid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	$commentid = (int) $_GET["cid"];


	$res = sql_query("SELECT request FROM reqcomments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if ($arr)
	$reqid = $arr["request"];
	else
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	sql_query("DELETE FROM reqcomments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE requests SET comments = comments - 1 WHERE id = $reqid");


	$CACHE->clearGroupCache("block-req");

	$returnto = urlencode($_GET["returnto"]);

	if ($returnto)
	safe_redirect(" $returnto");
	else
	safe_redirect(" {$CACHEARRAY['defaultbaseurl']}/"); // change later ----------------------


	die;
}

else
stderr($tracker_lang['error'], "Unknown action $action");

die;
?>