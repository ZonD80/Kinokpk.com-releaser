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
    if(!is_valid_id($_POST["nid"])) stderr($tracker_lang["error"],$tracker_lang["invalid_id"]);
    
    $nid = 0 + $_POST["nid"];
	  $text = trim($_POST["text"]);
	  if (!$text)
			stderr($tracker_lang['error'], $tracker_lang['comment_cant_be_empty']);

      // ANTISPAM AND ANTIFLOOD SYSTEM
     $last_pmres = sql_query("SELECT NOW()-added AS seconds, text AS msg, id, news AS torrent FROM newscomments WHERE user=".$CURUSER['id']." ORDER BY added DESC LIMIT 4");
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
              $msgview.= "\n[url=newsoverview.php?id={$torids[$key]}&viewcomm=$msgid#comm$msgid]Комментарий ID={$msgid}[/url] от пользователя ".$CURUSER['username'];
            }
            $modcomment = sql_query("SELECT modcomment FROM users WHERE id=".$CURUSER['id']);
            $modcomment = mysql_result($modcomment,0);
            if (strpos($modcomment,"Maybe spammer in news") === false) {
                $arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

        while (list($admin) = mysql_fetch_array($arow)) {
        sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
        $admin, '" . get_date_time() . "', 'Пользователь [url=userdetails.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] может быть спамером, т.к. его 5 последних посланных комментариев к новостям полностью совпадают.$msgview', 'Сообщение о спаме!', 1)") or sqlerr(__FILE__, __LINE__);
      }
      $modcomment .= "\n".get_date_time()." - Maybe spammer in news";
      sql_query("UPDATE users SET modcomment = ".sqlesc($modcomment)." WHERE id =".$CURUSER['id']);

      } else {
        sql_query("UPDATE users SET enabled='no', dis_reason='Spam in news' WHERE id=".$CURUSER['id']);

         $arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");

        while (list($admin) = mysql_fetch_array($arow)) {
        sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
        $admin, '" . get_date_time() . "', 'Пользователь [url=userdetails.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url] забанен системой за спам в комментариях к новостям, его IP адрес (".$CURUSER['ip'].")', 'Сообщение о спаме [бан]!', 1)") or sqlerr(__FILE__, __LINE__);
       stderr("Поздравляем!","Вы успешно забанены системой за спам в комментариях к новостям! Если вы не согласны с решением системы, <a href=\"contact.php\">подайте жалобу админам</a>.");
      }
      }
            stderr($tracker_lang['error'],"На нашем сайте стоит защита от спама, ваши 5 последних комментариев к новостям совпадают. В отсылке комментария отказано. <b><u>ВНИМАНИЕ! ЕСЛИ ВЫ ЕЩЕ РАЗ ПОПЫТАЕТЕСЬ ОТПРАВИТЬ ИДЕНТИЧНОЕ СООБЩЕНИЕ, ВЫ БУДЕТЕ АВТОМАТИЧЕСКИ ЗАБЛОКИРОВАНЫ СИСТЕМОЙ!!!</u></b> <a href=\"javascript: history.go(-1)\">Назад</a>");

        }
       }

// ANITSPAM SYSTEM END

	  sql_query("INSERT INTO newscomments (user, news, added, text, ori_text, ip) VALUES (" .
	      $CURUSER["id"] . ",$nid, '" . get_date_time() . "', " . sqlesc($text) .
	       "," . sqlesc($text) . "," . sqlesc(getip()) . ")") or die(mysql_error());
	       
	       $newid = mysql_insert_id();

   if (!defined("CACHE_REQUIRED")){
 	require_once(ROOT_PATH . 'classes/cache/cache.class.php');
	require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
  }
  		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());

  $cache->clearGroupCache("block-news");
               
	  header("Refresh: 0; url=newsoverview.php?id=$nid&viewcomm=$newid#comm$newid");
	  die;
	}

  if (!is_valid_id($_GET["nid"]))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
  $nid = 0 + $_GET["nid"];

	stdhead("Добление комментария к новости");

	print("<p><form name=\"comment\" method=\"post\" action=\"newscomment.php?action=add\">\n");
	print("<input type=\"hidden\" name=\"nid\" value=\"$nid\"/>\n");
?>
	<table class="main" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<td class="colhead">
<?
	print("".$tracker_lang['add_comment']." к новости");
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

	$res = sql_query("SELECT newscomments.id, text, newscomments.ip, newscomments.added, username, title, class, users.id as user, users.avatar, users.donor, users.enabled, users.warned, users.parked FROM newscomments LEFT JOIN users ON newscomments.user = users.id WHERE news = $nid ORDER BY comments.id DESC");

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
  $res = sql_query("SELECT nc.*, n.id AS nid, u.username FROM newscomments AS nc LEFT JOIN news AS n ON nc.news = n.id JOIN users AS u ON nc.user = u.id WHERE nc.id=$commentid") or sqlerr(__FILE__,__LINE__);
  $arr = mysql_fetch_array($res);
  if (!$arr)
  	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

 	stdhead("Добавления комментария к новости");

	$text = "[quote=$arr[username]]" . $arr["text"] . "[/quote]\n";

	print("<form method=\"post\" name=\"comment\" action=\"newscomment.php?action=add\">\n");
	print("<input type=\"hidden\" name=\"nid\" value=\"$arr[nid]\" />\n");
?>

	<table class="main" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<td class="colhead">
<?
	print("Добавления комментария к опросу");
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
  $res = sql_query("SELECT nc.*, n.id AS nid FROM newscomments AS nc LEFT JOIN news AS n ON nc.news = n.id WHERE nc.id=$commentid") or sqlerr(__FILE__,__LINE__);
  $arr = mysql_fetch_array($res);
  if (!$arr)
  	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	if ($arr["user"] != $CURUSER["id"] && get_user_class() < UC_MODERATOR)
		stderr($tracker_lang['error'], $tracker_lang['access_denied']);

	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
	  $text = $_POST["text"];
    $returnto = urlencode($_POST["returnto"]);

	  if ($text == "")
	  	stderr($tracker_lang['error'], $tracker_lang['comment_cant_be_empty']);
	  	
	  $text = sqlesc($text);

	  $editedat = sqlesc(get_date_time());

	  sql_query("UPDATE newscomments SET text=$text, editedat=$editedat, editedby=$CURUSER[id] WHERE id=$commentid") or sqlerr(__FILE__, __LINE__);

   if (!defined("CACHE_REQUIRED")){
 	require_once(ROOT_PATH . 'classes/cache/cache.class.php');
	require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
  }
  		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());

  $cache->clearGroupCache("block-news");
          
		if ($returnto)
	  	header("Location: $returnto");
		else
		  header("Location: {$CACHEARRAY['defaultbaseurl']}/");      // change later ----------------------
		die;
	}

 	stdhead("Редактирование комментария к новости");

	print("<form method=\"post\" name=\"comment\" action=\"newscomment.php?action=edit&amp;cid=$commentid\">\n");
	print("<input type=\"hidden\" name=\"returnto\" value=\"newsoverview.php?id={$arr["nid"]}&amp;viewcomm=$commentid#comm$commentid\" />\n");
	print("<input type=\"hidden\" name=\"cid\" value=\"$commentid\" />\n");
?>

	<table class="main" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<td class="colhead">
<?
	print("Редактирование комментария к новости");
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

elseif ($action == "delete")
{
	if (get_user_class() < UC_MODERATOR)
		stderr($tracker_lang['error'], $tracker_lang['access_denied']);

  if (!is_valid_id($_GET["cid"]))
		stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
		  $commentid = 0 + $_GET["cid"];


	$res = sql_query("SELECT news FROM newscomments WHERE id=$commentid")  or sqlerr(__FILE__,__LINE__);
	$arr = mysql_fetch_array($res);
	if ($arr)
		$nid = $arr["news"];

	sql_query("DELETE FROM newscomments WHERE id=$commentid") or sqlerr(__FILE__,__LINE__);

   if (!defined("CACHE_REQUIRED")){
 	require_once(ROOT_PATH . 'classes/cache/cache.class.php');
	require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
  }
  		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());

  $cache->clearGroupCache("block-news");
        
	list($commentid) = mysql_fetch_row(sql_query("SELECT id FROM newscomments WHERE news = $nid ORDER BY added DESC LIMIT 1"));

	$returnto = "newsoverview.php?id=$nid&amp;viewcomm=$commentid#comm$commentid";

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
  $res = sql_query("SELECT nc.*, n.id AS nid FROM newscomments AS nc LEFT JOIN news AS n ON nc.news = n.id WHERE nc.id=$commentid") or sqlerr(__FILE__,__LINE__);
  $arr = mysql_fetch_array($res);
  if (!$arr)
  	stderr($tracker_lang['error'], "Неверный идентификатор $commentid.");

  stdhead("Просмотр оригинала");
  print("<h1>Оригинальное содержание комментария №$commentid</h1><p>\n");
	print("<table width=500 border=1 cellspacing=0 cellpadding=5>");
  print("<tr><td class=comment>\n");
	echo htmlspecialchars($arr["ori_text"]);
  print("</td></tr></table>\n");

  $returnto = "newsoverview.php?id={$arr["nid"]}&amp;viewcomm=$commentid#comm$commentid";

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