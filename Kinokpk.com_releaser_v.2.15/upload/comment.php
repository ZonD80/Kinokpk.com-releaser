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
    	  if (!is_valid_id($_POST["tid"]))
			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
			
    $torrentid = 0 + $_POST["tid"];

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
     $last_pmres = sql_query("SELECT NOW()-added AS seconds, text AS msg, id, torrent FROM comments WHERE user=".$CURUSER['id']." ORDER BY added DESC LIMIT 4");
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
              $msgview.= "\n[url=details.php?id={$torids[$key]}&viewcomm=$msgid#comm$msgid]Комментарий ID={$msgid}[/url] от пользователя ".$CURUSER['username'];
            }
            $modcomment = sql_query("SELECT modcomment FROM users WHERE id=".$CURUSER['id']);
            $modcomment = mysql_result($modcomment,0);
            if (strpos($modcomment,"Maybe spammer in comments") === false) {
                $arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

        while (list($admin) = mysql_fetch_array($arow)) {
        sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
        $admin, '" . get_date_time() . "', 'Пользователь [url=userdetails.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] может быть спамером, т.к. его 5 последних посланных комментариев полностью совпадают.$msgview', 'Сообщение о спаме!', 1)") or sqlerr(__FILE__, __LINE__);
      }
      $modcomment .= "\n".get_date_time()." - Maybe spammer in comments";
      sql_query("UPDATE users SET modcomment = ".sqlesc($modcomment)." WHERE id =".$CURUSER['id']);

      } else {
        sql_query("UPDATE users SET enabled='no', dis_reason='Spam in comments' WHERE id=".$CURUSER['id']);

         $arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

        while (list($admin) = mysql_fetch_array($arow)) {
        sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
        $admin, '" . get_date_time() . "', 'Пользователь [url=userdetails.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] забанен системой за спам, его IP адрес (".$CURUSER['ip'].")', 'Сообщение о спаме [бан]!', 1)") or sqlerr(__FILE__, __LINE__);
       stderr("Поздравляем!","Вы успешно забанены системой за спам в комментариях к релизам! Если вы не согласны с решением системы, <a href=\"contact.php\">подайте жалобу админам</a>.");
      }
      }
            stderr($tracker_lang['error'],"На нашем сайте стоит защита от спама, ваши 5 последних комментариев совпадают. В отсылке комментария отказано. <b><u>ВНИМАНИЕ! ЕСЛИ ВЫ ЕЩЕ РАЗ ПОПЫТАЕТЕСЬ ОТПРАВИТЬ ИДЕНТИЧНОЕ СООБЩЕНИЕ, ВЫ БУДЕТЕ АВТОМАТИЧЕСКИ ЗАБЛОКИРОВАНЫ СИСТЕМОЙ!!!</u></b> <a href=\"javascript: history.go(-1)\">Назад</a>");

        }
       }

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
	  sql_query("INSERT INTO comments (user, torrent, added, text, ori_text, ip , post_id) VALUES (" .
	      $CURUSER["id"] . ",$torrentid, '" . get_date_time() . "', " . sqlesc($text) .
	       "," . sqlesc($text) . "," . sqlesc(getip()) . ",".$postid.")") or die(mysql_error());

	  $newid = mysql_insert_id();
   if (!defined("CACHE_REQUIRED")){
 	require_once(ROOT_PATH . 'classes/cache/cache.class.php');
	require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
  }
  		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());

  $cache->clearGroupCache("block-indextorrents");
    
    sql_query("UPDATE users SET bonus=bonus+10 WHERE id =".$CURUSER['id']);
    
	  sql_query("UPDATE torrents SET comments = comments + 1 WHERE id = $torrentid");

	/////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ///////////////// 

	$subject = sqlesc("Новый комментарий");
	$msg = sqlesc("Для торрента [url=details.php?id=$torrentid&viewcomm=$newid#comm$newid]".$name."[/url] добавился новый комментарий.");
	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, userid, NOW(), $msg, 0, $subject FROM checkcomm WHERE checkid = $torrentid AND torrent = 1 AND userid != $CURUSER[id]") or sqlerr(__FILE__,__LINE__);

    /////////////////СЛЕЖЕНИЕ ЗА КОММЕНТАМИ/////////////////

	  header("Refresh: 0; url=details.php?id=$torrentid&viewcomm=$newid#comm$newid");
	  die;
	}

  if (!is_valid_id($_GET["tid"]))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
    $torrentid = 0 + $_GET["tid"];
    
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

  if (!is_valid_id($_GET["cid"]))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

    $commentid = 0 + $_GET["cid"];
    
  $res = sql_query("SELECT c.*, t.name, t.id AS tid, u.username FROM comments AS c LEFT JOIN torrents AS t ON c.torrent = t.id JOIN users AS u ON c.user = u.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
  $arr = mysql_fetch_array($res);
  if (!$arr)
  	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

if ($CACHEARRAY['use_integration']) $postid = $arr['post_id'];

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
  if (!is_valid_id($_GET["cid"]))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		  $commentid = 0 + $_GET["cid"];

  $res = sql_query("SELECT c.*, t.name, t.id AS tid FROM comments AS c LEFT JOIN torrents AS t ON c.torrent = t.id WHERE c.id=$commentid") or sqlerr(__FILE__,__LINE__);
  $arr = mysql_fetch_array($res);
  if (!$arr)
  	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
  	
   $postid = $arr['post_id'];

	if ($arr["user"] != $CURUSER["id"] && get_user_class() < UC_MODERATOR)
		stderr($tracker_lang['error'], $tracker_lang['access_denied']);

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
	  $text = $_POST["text"];

	  if ($text == "")
	  	stderr($tracker_lang['error'], $tracker_lang['comment_cant_be_empty']);
if ($CACHEARRAY['use_integration']) {
      // IPB COMMENT TRANSFER
      $posttext = format_comment($text);
      // end, continue BELOW
}
	  $text = sqlesc($text);

	  $editedat = sqlesc(get_date_time());
	  
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
	  	header("Location: ".$_POST["returnto"]);
		else
		  header("Location: {$CACHEARRAY['defaultbaseurl']}/");      // change later ----------------------
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
        if (!is_valid_id($_GET["tid"]))
                stderr($tracker_lang['error'], "Неверный идентификатор $tid.");
                        $tid = 0 + $_GET["tid"];
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

  if (!is_valid_id($_GET["cid"]))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		
		  $commentid = 0 + $_GET["cid"];


	$res = sql_query("SELECT torrent,post_id FROM comments WHERE id=$commentid")  or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if ($arr)
		$torrentid = $arr["torrent"];
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
   if (!defined("CACHE_REQUIRED")){
 	require_once(ROOT_PATH . 'classes/cache/cache.class.php');
	require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
  }
  		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());

  $cache->clearGroupCache("block-indextorrents");
    
	list($commentid) = mysql_fetch_row(sql_query("SELECT id FROM comments WHERE torrent = $torrentid ORDER BY added DESC LIMIT 1"));

	$returnto = "details.php?id=$torrentid&amp;viewcomm=$commentid#comm$commentid";

	if ($returnto)
	  header("Location: $returnto");
	else
	  header("Location: {$CACHEARRAY['defaultbaseurl']}/");      // change later ----------------------
	die;
}
elseif ($action == "vieworiginal")
{
	if (get_user_class() < UC_MODERATOR)
		stderr($tracker_lang['error'], $tracker_lang['access_denied']);

  if (!is_valid_id($_GET["cid"]))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		  $commentid = 0 + $_GET["cid"];

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