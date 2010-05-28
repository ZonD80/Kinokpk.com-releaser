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
parked();

if ($action == "add")
{
  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    $torrentid = 0 + $_POST["tid"];
	  if (!is_valid_id($torrentid))
			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		$res = sql_query("SELECT name FROM torrents WHERE id = $torrentid") or sqlerr(__FILE__,__LINE__);
		$arr = mysql_fetch_array($res);
		if (!$arr)
		  stderr($tracker_lang['error'], $tracker_lang['no_torrent_with_such_id']);
		$name = $arr[0];
	  $text = trim($_POST["text"]);
	  if (!$text)
			stderr($tracker_lang['error'], $tracker_lang['comment_cant_be_empty']);
//IPB COMMENT TRANSFER
$topicid = mysql_query("SELECT topic_id FROM torrents WHERE id = ".$torrentid);
$topicid = mysql_result($topicid,0);

if ($topicid != 0) {
  $ipbuser = mysql_query("SELECT username FROM users WHERE id=".$CURUSER['id']);
$ipbuser = mysql_result($ipbuser,0);
$forumcomment = format_comment($text);

mysql_close();
// connecting to IPB DB

$fdb = mysql_connect($fmysql_host, $fmysql_user, $fmysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($fmysql_db, $fdb);
//connection opened

$topicdetails = mysql_query("SELECT title,forum_id FROM ".$fprefix."topics WHERE tid=".$topicid);
$topicdetails = mysql_fetch_array($topicdetails);
$topicname = $topicdetails['title'];
$forumid = $topicdetails['forum_id'];

$check = mysql_query("SELECT id FROM ".$fprefix."members WHERE name='".$ipbuser."'");

if(!@mysql_result($check,0)) $ipbid = 66958; else $ipbid=mysql_result($check,0);

$forumcomment = sqlforum($forumcomment);

$post = mysql_query("INSERT INTO ".$fprefix."posts (append_edit, edit_time, author_id, author_name, use_sig, use_emo, ip_address, post_date, icon_id, post, queued, topic_id, post_title, new_topic, edit_name, post_key, post_parent, post_htmlstate) VALUES
  (0, NULL, ".$ipbid.", '".$ipbuser."', 1, 1, '".getip()."', ".time().", 0, '".$forumcomment."', 0, ".$topicid.", NULL, 0, NULL, '".md5(microtime())."', 0, 0)");
 $postid = mysql_insert_id();

 $updtopic = mysql_query ("UPDATE ".$fprefix."topics SET posts=posts+1, last_poster_id= ".$ipbid.", last_poster_name='".$ipbuser."', last_post =".time()." WHERE tid =".$topicid);
 $updateforum = mysql_query("UPDATE ".$fprefix."forums SET posts =posts+1, last_post =".time().", last_poster_id =".$ipbid.", last_poster_name ='".$ipbuser."', last_title='".$topicname."', last_id =".$topicid." WHERE id =".$forumid);
 $updateuser = mysql_query("UPDATE ".$fprefix."members SET posts =posts+1, last_post =".time().", last_activity =".time()." WHERE id=".$ipbid);

 // closing IPB DB connection
mysql_close();
 // connection closed
$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($mysql_db, $db);

  } else $postid = 0;
// IPB COMMENT TRANSFER END
	  sql_query("INSERT INTO comments (user, torrent, added, text, ori_text, ip , post_id) VALUES (" .
	      $CURUSER["id"] . ",$torrentid, '" . get_date_time() . "', " . sqlesc($text) .
	       "," . sqlesc($text) . "," . sqlesc(getip()) . ",".$postid.")");

	  $newid = mysql_insert_id();

    mysql_query("UPDATE users SET bonus=bonus+10 WHERE id =".$CURUSER['id']);
    
	  sql_query("UPDATE torrents SET comments = comments + 1 WHERE id = $torrentid");

	/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ///////////////// 
    /*$res3 = sql_query("SELECT * FROM checkcomm WHERE checkid = $torrentid AND torrent = 1") or sqlerr(__FILE__,__LINE__);
    $subject = sqlesc("Новый комментарий");
    while ($arr3 = mysql_fetch_array($res3)) {
    	$msg = sqlesc("Для торрента [url=details.php?id=$torrentid&viewcomm=$newid#comm$newid]".$name."[/url] добавился новый комментарий.");
    	if ($CURUSER[id] != $arr3[userid])
     		sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) VALUES (0, $arr3[userid], NOW(), $msg, 0, $subject)") or sqlerr(__FILE__,__LINE__);
    }*/

	$subject = sqlesc("Новый комментарий");
	$msg = sqlesc("Для торрента [url=details.php?id=$torrentid&viewcomm=$newid#comm$newid]".$name."[/url] добавился новый комментарий.");
	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, userid, NOW(), $msg, 0, $subject FROM checkcomm WHERE checkid = $torrentid AND torrent = 1 AND userid != $CURUSER[id]") or sqlerr(__FILE__,__LINE__);

    /////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////

	  header("Refresh: 0; url=details.php?id=$torrentid&viewcomm=$newid#comm$newid");
	  die;
	}

  $torrentid = 0 + $_GET["tid"];
  if (!is_valid_id($torrentid))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	$res = sql_query("SELECT name FROM torrents WHERE id = $torrentid") or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if (!$arr)
	  stderr($tracker_lang['error'], $tracker_lang['no_torrent_with_such_id']);

	stdhead("Добление комментария к \"" . $arr["name"] . "\"");

	print("<p><form name=\"comment\" method=\"post\" action=\"comment.php?action=add\">\n");
	print("<input type=\"hidden\" name=\"tid\" value=\"$torrentid\"/>\n");
?>
	<table class=main border=0 cellspacing=0 cellpadding=3>
	<tr>
	<td class="colhead">
<?
	print("".$tracker_lang['add_comment']." к \"" . htmlspecialchars($arr["name"]) . "\"");
?>
	</td>
	</tr>
	<tr>
	<td>
<?
	textbbcode("comment","text","");
?>
	</td></tr></table>
<?
	//print("<textarea name=\"text\" rows=\"10\" cols=\"60\"></textarea></p>\n");
	print("<p><input type=\"submit\" value=\"Добавить\" /></p></form>\n");

	$res = sql_query("SELECT comments.id, text, comments.ip, comments.added, username, title, class, users.id as user, users.avatar, users.donor, users.enabled, users.warned, users.parked FROM comments LEFT JOIN users ON comments.user = users.id WHERE torrent = $torrentid ORDER BY comments.id DESC LIMIT 5");

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
  $commentid = 0 + $_GET["cid"];
  if (!is_valid_id($commentid))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

  $res = sql_query("SELECT c.*, t.name, t.id AS tid, u.username FROM comments AS c LEFT JOIN torrents AS t ON c.torrent = t.id JOIN users AS u ON c.user = u.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
  $arr = mysql_fetch_array($res);
  if (!$arr)
  	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

 	stdhead("Добавления комментария к \"" . $arr["name"] . "\"");

	$text = "[quote=$arr[username]]" . $arr["text"] . "[/quote]\n";

	print("<form method=\"post\" name=\"comment\" action=\"comment.php?action=add\">\n");
	print("<input type=\"hidden\" name=\"tid\" value=\"$arr[tid]\" />\n");
?>

	<table class=main border=0 cellspacing=0 cellpadding=3>
	<tr>
	<td class="colhead">
<?
	print("Добавления комментария к \"" . htmlspecialchars($arr["name"]) . "\"");
?>
	</td>
	</tr>
	<tr>
	<td>
<?
	textbbcode("comment","text",htmlspecialchars($text));
?>
	</td></tr></table>

<?

	print("<p><input type=\"submit\" value=\"Добавить\" /></p></form>\n");

	stdfoot();

}
elseif ($action == "edit")
{
  $commentid = 0 + $_GET["cid"];
  if (!is_valid_id($commentid))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

  $res = sql_query("SELECT c.*, t.name, t.id AS tid FROM comments AS c LEFT JOIN torrents AS t ON c.torrent = t.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
  $arr = mysql_fetch_array($res);
  if (!$arr)
  	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	if ($arr["user"] != $CURUSER["id"] && get_user_class() < UC_MODERATOR)
		stderr($tracker_lang['error'], $tracker_lang['access_denied']);

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
	  $text = $_POST["text"];
    $returnto = $_POST["returnto"];

	  if ($text == "")
	  	stderr($tracker_lang['error'], $tracker_lang['comment_cant_be_empty']);

      // IPB COMMENT TRANSFER
      $posttext = format_comment($text);
      $posttext = sqlforum($posttext);
      // end, continue BELOW
      
	  $text = sqlesc($text);

	  $editedat = sqlesc(get_date_time());

    // IPB COMMENT TRANSFER
    $postid = mysql_query ("SELECT post_id FROM comments WHERE id=".$commentid);
    $postid = mysql_result($postid,0);
    
    if ($postid != 0) {
    mysql_close();
// connecting to IPB DB

$fdb = mysql_connect($fmysql_host, $fmysql_user, $fmysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($fmysql_db, $fdb);
//connection opened
    $post = mysql_query("UPDATE ".$fprefix."posts SET append_edit = 1, edit_time = ".time().", ip_address = '".getip()."', post = '".$posttext."', edit_name = '".$CURUSER['username']."', post_key = '".md5(microtime())."' WHERE pid =".$postid);

     // closing IPB DB connection
mysql_close();
 // connection closed
$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($mysql_db, $db);
    }
// IPB COMMENT TRANSFER END /////////////////////////////////////////////////////////////////////////
	  sql_query("UPDATE comments SET text=$text, editedat=$editedat, editedby=$CURUSER[id] WHERE id=$commentid") or sqlerr(__FILE__, __LINE__);

		if ($returnto)
	  	header("Location: $returnto");
		else
		  header("Location: $DEFAULTBASEURL/");      // change later ----------------------
		die;
	}

 	stdhead("Редактирование комментария к \"" . $arr["name"] . "\"");

	print("<form method=\"post\" name=\"comment\" action=\"comment.php?action=edit&amp;cid=$commentid\">\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"details.php?id={$arr["tid"]}&amp;viewcomm=$commentid#comm$commentid\" />\n");
	print("<input type=\"hidden\" name=\"cid\" value=\"$commentid\" />\n");
?>

	<table class=main border=0 cellspacing=0 cellpadding=3>
	<tr>
	<td class="colhead">
<?
	print("Редактирование комментария к \"" . htmlspecialchars($arr["name"]) . "\"");
?>
	</td>
	</tr>
	<tr>
	<td>
<?
	textbbcode("comment","text",htmlspecialchars($arr["text"]));
?>
	</td></tr></table>

<?

	print("<p><input type=\"submit\" value=\"Отредактировать\" /></p></form>\n");

	stdfoot();
	die;
}
/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ///////////////// 
elseif ($action == "check" || $action == "checkoff")
{
        $tid = 0 + $_GET["tid"];
        if (!is_valid_id($tid))
                stderr($tracker_lang['error'], "Неверный идентификатор $tid.");
        $docheck = mysql_fetch_array(sql_query("SELECT COUNT(*) FROM checkcomm WHERE checkid = " . $tid . " AND userid = " . $CURUSER["id"] . " AND torrent = 1"));
        if ($docheck[0] > 0 && $action=="check")
                stderr($tracker_lang['error'], "<p>Вы уже подписаны на этот торрент.</p><a href=details.php?id=$tid#startcomments>Назад</a>");
        if ($action == "check") {
                sql_query("INSERT INTO checkcomm (checkid, userid, torrent) VALUES ($tid, $CURUSER[id], 1)") or sqlerr(__FILE__,__LINE__);
                stderr($tracker_lang['success'], "<p>Теперь вы следите за комментариями к этому торренту.</p><a href=details.php?id=$tid#startcomments>Назад</a>");
        }
        else {
                sql_query("DELETE FROM checkcomm WHERE checkid = $tid AND userid = $CURUSER[id] AND torrent = 1") or sqlerr(__FILE__,__LINE__);
                stderr($tracker_lang['success'], "<p>Теперь вы не следите за комментариями к этому торренту.</p><a href=details.php?id=$tid#startcomments>Назад</a>");
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

  if (!$sure)
  {
		stderr($tracker_lang['delete']." ".$tracker_lang['comment'], sprintf($tracker_lang['you_want_to_delete_x_click_here'],$tracker_lang['comment'],"?action=delete&cid=$commentid&sure=1"));
  }


	$res = sql_query("SELECT torrent FROM comments WHERE id=$commentid")  or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if ($arr)
		$torrentid = $arr["torrent"];
  // IPB COMMENT TRANSFER
  $postid = mysql_query("SELECT post_id FROM comments WHERE id=".$commentid);
  $postid = mysql_result($postid,0);
  if ($postid != 0) {
      mysql_close();
// connecting to IPB DB

$fdb = mysql_connect($fmysql_host, $fmysql_user, $fmysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($fmysql_db, $fdb);
//connection opened
$postdeldetails = mysql_query("SELECT * FROM ".$fprefix."posts WHERE pid=".$postid);
$postdeldetails = mysql_fetch_array($postdeldetails);

$topicdeltitle = mysql_query("SELECT title FROM ".$fprefix."topics WHERE tid=".$postdeldetails['topic_id']);
$topicdeltitle = mysql_result($topicdeltitle,0);
$topicdelid = strval($postdeldetails['topic_id']);

  $forumid = mysql_query ("SELECT id FROM ".$fprefix."forums WHERE name='Корзина'");
   $forumid = mysql_result ($forumid,0);
   
$topicid = mysql_query("INSERT INTO ".$fprefix."topics (title, description, state, posts, starter_id, start_date, last_poster_id, last_post, icon_id, starter_name, last_poster_name, poll_state, last_vote, views, forum_id, approved, author_mode, pinned, moved_to, total_votes, topic_hasattach, topic_firstpost,	topic_queuedposts, topic_open_time,	topic_close_time,	topic_rating_total,	topic_rating_hits) VALUES
('От: ".$topicdeltitle."', 'От темы с ID: ".$topicdelid."', 'open', 0, ".$postdeldetails['author_id'].", ".time().", ".$postdeldetails['author_id'].", ".time().", 0, '".$postdeldetails['starter_name']."', '".$postdeldetails['starter_name']."', 0, 0, 0, ".$forumid.", 1, 1, 0, NULL, 0, 0, ".$postid.", 0, 0, 0, 0, 0)");
  $topicid = mysql_insert_id();
  
  $post = mysql_query("UPDATE ".$fprefix."posts SET topic_id = ".$topicid.", new_topic = 1 WHERE pid = ".$postid);
  
  $updateforum = mysql_query("UPDATE ".$fprefix."forums SET topics =topics+1, posts =posts+1, last_post =".time().", last_poster_id =".$postdeldetails['author_id'].", last_poster_name ='".$postdeldetails['starter_name']."', last_title='От: ".$topicdeltitle."', last_id =".$topicid." WHERE id =".$forumid);

  
     // closing IPB DB connection
mysql_close();
 // connection closed
$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($mysql_db, $db);

  }
  
	sql_query("DELETE FROM comments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);
	if ($torrentid && mysql_affected_rows() > 0)
		sql_query("UPDATE torrents SET comments = comments - 1 WHERE id = $torrentid");

	list($commentid) = mysql_fetch_row(sql_query("SELECT id FROM comments WHERE torrent = $torrentid ORDER BY added DESC LIMIT 1"));

	$returnto = "details.php?id=$torrentid&amp;viewcomm=$commentid#comm$commentid";

	if ($returnto)
	  header("Location: $returnto");
	else
	  header("Location: $DEFAULTBASEURL/");      // change later ----------------------
	die;
}
elseif ($action == "vieworiginal")
{
	if (get_user_class() < UC_MODERATOR)
		stderr($tracker_lang['error'], $tracker_lang['access_denied']);

  $commentid = 0 + $_GET["cid"];

  if (!is_valid_id($commentid))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

  $res = sql_query("SELECT c.*, t.name, t.id AS tid FROM comments AS c LEFT JOIN torrents AS t ON c.torrent = t.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
  $arr = mysql_fetch_array($res);
  if (!$arr)
  	stderr($tracker_lang['error'], "Неверный идентификатор $commentid.");

  stdhead("Просмотр оригинала");
  print("<h1>Оригинальное содержание комментария №$commentid</h1><p>\n");
	print("<table width=500 border=1 cellspacing=0 cellpadding=5>");
  print("<tr><td class=comment>\n");
	echo htmlspecialchars($arr["ori_text"]);
  print("</td></tr></table>\n");

  $returnto = "details.php?id={$arr["tid"]}&amp;viewcomm=$commentid#comm$commentid";

//	$returnto = "details.php?id=$torrentid&amp;viewcomm=$commentid#$commentid";

	if ($returnto)
 		print("<p><font size=small><a href=$returnto>Назад</a></font></p>\n");

	stdfoot();
	die;
}
else
	stderr($tracker_lang['error'], "Unknown action");

die;
?>