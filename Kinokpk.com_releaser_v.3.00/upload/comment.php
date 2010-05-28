<?php
/**
 * Comments processor
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

if ($action == "add")
{
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if (!is_valid_id($_POST["tid"]))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
			
		$torrentid = (int) $_POST["tid"];

		$res = sql_query("SELECT name,topic_id FROM torrents WHERE id = $torrentid") or sqlerr(__FILE__,__LINE__);
		$arr = mysql_fetch_array($res);
		if (!$arr)
		stderr($tracker_lang['error'], $tracker_lang['no_torrent_with_such_id']);
		$name = $arr[0];

		if ($CACHEARRAY['use_integration']) $topicid=$arr[1];

		$text = trim($_POST["text"]);
		if (!$text)
		stderr($tracker_lang['error'], $tracker_lang['comment_cant_be_empty']);
		// ANTISPAM AND ANTIFLOOD SYSTEM
		$last_pmres = sql_query("SELECT ".time()."-added AS seconds, text AS msg, id, torrent FROM comments WHERE user=".$CURUSER['id']." ORDER BY added DESC LIMIT 4");
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
					$msgview.= "\n<a href=details.php?id={$torids[$key]}#comm$msgid>Комментарий ID={$msgid}</a> от пользователя ".$CURUSER['username'];
				}
				$modcomment = sql_query("SELECT modcomment FROM users WHERE id=".$CURUSER['id']);
				$modcomment = mysql_result($modcomment,0);
				if (strpos($modcomment,"Maybe spammer in comments") === false) {
					$arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

					while (list($admin) = mysql_fetch_array($arow)) {
						sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
						$admin, '" . time() . "', 'Пользователь <a href=userdetails.php?id=".$CURUSER['id'].">".$CURUSER['username']."</a> может быть спамером, т.к. его 5 последних посланных комментариев полностью совпадают.$msgview', 'Сообщение о спаме!', 1)") or sqlerr(__FILE__, __LINE__);
					}
					$modcomment .= "\n".time()." - Maybe spammer in comments";
					sql_query("UPDATE users SET modcomment = ".sqlesc($modcomment)." WHERE id =".$CURUSER['id']);

				} else {
					sql_query("UPDATE users SET enabled=0, dis_reason='Spam in comments' WHERE id=".$CURUSER['id']);

					$arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

					while (list($admin) = mysql_fetch_array($arow)) {
						sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
						$admin, '" . time() . "', 'Пользователь <a href=userdetails.php?id=".$CURUSER['id'].">".$CURUSER['username']."</a> забанен системой за спам, его IP адрес (".$CURUSER['ip'].")', 'Сообщение о спаме [бан]!', 1)") or sqlerr(__FILE__, __LINE__);
						stderr("Поздравляем!","Вы успешно забанены системой за спам в комментариях к релизам! Если вы не согласны с решением системы, <a href=\"contact.php\">подайте жалобу админам</a>.");
					}
				}
				stderr($tracker_lang['error'],"На нашем сайте стоит защита от спама, ваши 5 последних комментариев совпадают. В отсылке комментария отказано. <b><u>ВНИМАНИЕ! ЕСЛИ ВЫ ЕЩЕ РАЗ ПОПЫТАЕТЕСЬ ОТПРАВИТЬ ИДЕНТИЧНОЕ СООБЩЕНИЕ, ВЫ БУДЕТЕ АВТОМАТИЧЕСКИ ЗАБЛОКИРОВАНЫ СИСТЕМОЙ!!!</u></b> <a href=\"javascript: history.go(-1)\">Назад</a>");

			}
		}

		$text = ($text);
		// ANITSPAM SYSTEM END
		if ($CACHEARRAY['use_integration']) {
			//IPB COMMENT TRANSFER

			if ($topicid != 0) {
				$ipbuser = $CURUSER['username'];
				$forumcomment = format_comment($text);

				forumconn();

				$topicdetails = sql_query("SELECT title,forum_id FROM ".$fprefix."topics WHERE tid=".$topicid);
				$topicdetails = mysql_fetch_array($topicdetails);
				$topicname = $topicdetails['title'];
				$forumid = $topicdetails['forum_id'];

				$check = sql_query("SELECT id FROM ".$fprefix."members WHERE name='".$ipbuser."'");

				if(!@mysql_result($check,0)) $ipbid = 66958; else $ipbid=mysql_result($check,0);


				$post = sql_query("INSERT INTO ".$fprefix."posts (append_edit, edit_time, author_id, author_name, use_sig, use_emo, ip_address, post_date, icon_id, post, queued, topic_id, post_title, new_topic, edit_name, post_key, post_parent, post_htmlstate) VALUES
				(0, NULL, ".sqlesc($ipbid).", ".sqlesc($ipbuser).", 1, 1, ".sqlesc(getip()).", ".time().", 0, ".sqlesc($forumcomment).", 0, ".sqlesc($topicid).", NULL, 0, NULL, '".md5(microtime())."', 0, 0)");
				$postid = mysql_insert_id();

				$updtopic = sql_query ("UPDATE ".$fprefix."topics SET posts=posts+1, last_poster_id= ".sqlesc($ipbid).", last_poster_name=".sqlesc($ipbuser).", last_post =".time()." WHERE tid =".sqlesc($topicid));
				$updateforum = sql_query("UPDATE ".$fprefix."forums SET posts =posts+1, last_post =".time().", last_poster_id =".sqlesc($ipbid).", last_poster_name =".sqlesc($ipbuser).", last_title=".sqlesc($topicname).", last_id =".sqlesc($topicid)." WHERE id =".sqlesc($forumid));
				$updateuser = sql_query("UPDATE ".$fprefix."members SET posts =posts+1, last_post =".time().", last_activity =".time()." WHERE id=".$ipbid);

				// closing IPB DB connection
				relconn();
				// connection closed


			} else $postid = 0;
			// IPB COMMENT TRANSFER END
		} else $postid = 0;
		sql_query("INSERT INTO comments (user, torrent, added, text, ip , post_id) VALUES (" .
		$CURUSER["id"] . ",$torrentid, '" . time() . "', " . sqlesc($text) .
	       "," . sqlesc(getip()) . ",".$postid.")") or die(mysql_error());

		$newid = mysql_insert_id();

		$clearcache = array('block-indextorrents','block-comments');
		foreach ($clearcache as $cachevalue) $CACHE->clearGroupCache($cachevalue);

		sql_query("UPDATE torrents SET comments = comments + 1 WHERE id = $torrentid");

		/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////

		send_comment_notifs($torrentid,"<a href=\"details.php?id=$torrentid#comm$newid\">".$name."</a>",'comments');

		/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////

		safe_redirect(" details.php?id=$torrentid#comm$newid");
		die;
	}
}
elseif ($action == "quote")
{

	if (!is_valid_id($_GET["cid"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	$commentid = (int) $_GET["cid"];

	$res = sql_query("SELECT c.*, t.name, t.id AS tid, u.username FROM comments AS c LEFT JOIN torrents AS t ON c.torrent = t.id JOIN users AS u ON c.user = u.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	if ($CACHEARRAY['use_integration']) $postid = $arr['post_id'];

	stdhead("Добавления комментария к \"" . $arr["name"] . "\"");

	$text = "<blockquote><p>" . format_comment($arr["text"]) . "</p></blockquote><cite>$arr[username]</cite><hr /><br /><br />\n";

	print("<form method=\"post\" name=\"comment\" action=\"comment.php?action=add\">\n");
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

	$res = sql_query("SELECT c.*, t.name, t.id AS tid FROM comments AS c LEFT JOIN torrents AS t ON c.torrent = t.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	$postid = $arr['post_id'];

	if ($arr["user"] != $CURUSER["id"] && get_user_class() < UC_MODERATOR)
	stderr($tracker_lang['error'], $tracker_lang['access_denied']);

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$text = ((string)$_POST["text"]);

		if ($text == "")
		stderr($tracker_lang['error'], $tracker_lang['comment_cant_be_empty']);
		if ($CACHEARRAY['use_integration']) {
			// IPB COMMENT TRANSFER
			$posttext = format_comment($text);
			// end, continue BELOW
		}
		$text = sqlesc(($text));

		$editedat = sqlesc(time());
			
		if ($CACHEARRAY['use_integration']) {
			// IPB COMMENT TRANSFER

			if ($postid != 0) {
				// connecting to IPB DB

				forumconn();

				//connection opened
				$post = sql_query("UPDATE ".$fprefix."posts SET append_edit = 1, edit_time = ".time().", ip_address = ".sqlesc(getip()).", post = ".sqlesc($posttext).", edit_name = ".sqlesc($CURUSER['username']).", post_key = '".md5(microtime())."' WHERE pid =".sqlesc($postid)) or die(mysql_error());

				// closing IPB DB connection
				relconn();
				// connection closed

			}
			// IPB COMMENT TRANSFER END /////////////////////////////////////////////////////////////////////////
		}
		sql_query("UPDATE comments SET text=$text, editedat=$editedat, editedby=$CURUSER[id] WHERE id=$commentid") or sqlerr(__FILE__, __LINE__);

		if ($_POST["returnto"])
		safe_redirect(" ".$_POST["returnto"]);
		else
		safe_redirect(" {$CACHEARRAY['defaultbaseurl']}/");      // change later ----------------------
		die;
	}

	stdhead("Редактирование комментария к \"" . $arr["name"] . "\"");

	print("<form method=\"post\" name=\"comment\" action=\"comment.php?action=edit&amp;cid=$commentid\">\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"details.php?id={$arr['tid']}#comm$commentid\" />\n");
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


	$res = sql_query("SELECT torrent,post_id FROM comments WHERE id=$commentid")  or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if ($arr)
	$torrentid = $arr["torrent"];
	else
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
	if ($CACHEARRAY['use_integration']) {
		// IPB COMMENT TRANSFER
		$postid = $arr['post_id'];
		if ($postid != 0) {
			// connecting to IPB DB
			forumconn();
			//connection opened
			$postdeldetails = sql_query("SELECT * FROM ".$fprefix."posts WHERE pid=".sqlesc($postid));
			$postdeldetails = mysql_fetch_array($postdeldetails);

			$topicdeltitle = sql_query("SELECT title FROM ".$fprefix."topics WHERE tid=".sqlesc($postdeldetails['topic_id']));
			$topicdeltitle = mysql_result($topicdeltitle,0);
			$topicdelid = strval($postdeldetails['topic_id']);

			$forumid = $CACHEARRAY['forum_bin_id'];

			$topicid = sql_query("INSERT INTO ".$fprefix."topics (title, description, state, posts, starter_id, start_date, last_poster_id, last_post, icon_id, starter_name, last_poster_name, poll_state, last_vote, views, forum_id, approved, author_mode, pinned, moved_to, total_votes, topic_hasattach, topic_firstpost,	topic_queuedposts, topic_open_time,	topic_close_time,	topic_rating_total,	topic_rating_hits) VALUES
(".sqlesc($topicdeltitle).", ".sqlesc($topicdelid).", 'open', 0, ".sqlesc($postdeldetails['author_id']).", ".time().", ".sqlesc($postdeldetails['author_id']).", ".time().", 0, ".sqlesc($postdeldetails['starter_name']).", ".sqlesc($postdeldetails['starter_name']).", 0, 0, 0, ".sqlesc($forumid).", 1, 1, 0, NULL, 0, 0, ".sqlesc($postid).", 0, 0, 0, 0, 0)");
			$topicid = mysql_insert_id();

			$post = sql_query("UPDATE ".$fprefix."posts SET topic_id = ".sqlesc($topicid).", new_topic = 1 WHERE pid = ".sqlesc($postid));

			$updateforum = sql_query("UPDATE ".$fprefix."forums SET topics =topics+1, posts =posts+1, last_post =".time().", last_poster_id =".sqlesc($postdeldetails['author_id']).", last_poster_name =".sqlesc($postdeldetails['starter_name']).", last_title=".sqlesc($topicdeltitle).", last_id =".sqlesc($topicid)." WHERE id =".sqlesc($forumid));


			// closing IPB DB connection
			relconn();
			// connection closed


		}
		// IPB comment transfer end
	}

	sql_query("DELETE FROM comments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
	if ($torrentid && mysql_affected_rows() > 0)
	sql_query("UPDATE torrents SET comments = comments - 1 WHERE id = $torrentid");

	$clearcache = array('block-indextorrents','block-comments');
	foreach ($clearcache as $cachevalue) $CACHE->clearGroupCache($cachevalue);

	list($commentid) = mysql_fetch_row(sql_query("SELECT id FROM comments WHERE torrent = $torrentid ORDER BY added DESC LIMIT 1"));

	$returnto = "details.php?id=$torrentid#comm$commentid";

	if ($returnto)
	safe_redirect(" $returnto");
	else
	safe_redirect(" {$CACHEARRAY['defaultbaseurl']}/");      // change later ----------------------
	die;
}
else
stderr($tracker_lang['error'], "Unknown action");

die;
?>