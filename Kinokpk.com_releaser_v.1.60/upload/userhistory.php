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

gzip();

dbconn(false);

loggedinorreturn();

$userid = (int)$_GET["id"];

if (!is_valid_id($userid)) stderr($tracker_lang['error'], "Invalid ID");

if (get_user_class()< UC_POWER_USER || ($CURUSER["id"] != $userid && get_user_class() < UC_MODERATOR))
	stderr($tracker_lang['error'], "Нет доступа");

$page = $_GET["page"];

$action = $_GET["action"];
$type = $_GET["type"];

//-------- Global variables

$perpage = 25;

if ($action == "viewcomments")
if ($type == "torrents")
{
	$select_is = "COUNT(*)";

	// LEFT due to orphan comments
	$from_is = "comments AS c LEFT JOIN torrents as t
	            ON c.torrent = t.id";

	$where_is = "c.user = $userid";
	$order_is = "c.id DESC";

	$query = "SELECT $select_is FROM $from_is WHERE $where_is ORDER BY $order_is";

	$res = sql_query($query) or sqlerr(__FILE__, __LINE__);

	$arr = mysql_fetch_row($res) or stderr($tracker_lang['error'], "Комментарии не найдены");

	$commentcount = $arr[0];

	//------ Make page menu

	list($pagertop, $pagerbottom, $limit) = pager($perpage, $commentcount, $_SERVER["PHP_SELF"] . "?action=viewcomments&id=$userid&");

	//------ Get user data

	$res = sql_query("SELECT username, donor, warned, enabled FROM users WHERE id=$userid") or sqlerr(__FILE__, __LINE__);

	if (mysql_num_rows($res) == 1)
	{
		$arr = mysql_fetch_assoc($res);

	  $subject = "<a href=userdetails.php?id=$userid><b>$arr[username]</b></a>" . get_user_icons($arr, true);
	}
	else
	  $subject = "unknown[$userid]";

	//------ Get comments

	$select_is = "t.name, c.torrent AS t_id, c.id, c.added, c.text";

	$query = "SELECT $select_is FROM $from_is WHERE $where_is ORDER BY $order_is $limit";

	$res = sql_query($query) or sqlerr(__FILE__, __LINE__);

	if (mysql_num_rows($res) == 0) stderr($tracker_lang['error'], "Комментарии не найдены");

	stdhead("История комментариев");

	print("<h1>История комментариев для $subject</h1>\n");

	if ($commentcount > $perpage) echo $pagertop;

	//------ Print table

	begin_main_frame();

	begin_frame();

	while ($arr = mysql_fetch_assoc($res))
	{

		$commentid = $arr["id"];

	  $torrent = $arr["name"];

    // make sure the line doesn't wrap
	  if (strlen($torrent) > 55) $torrent = substr($torrent,0,52) . "...";

	  $torrentid = $arr["t_id"];

	  //find the page; this code should probably be in details.php instead

	  $subres = sql_query("SELECT COUNT(*) FROM comments WHERE torrent = $torrentid AND id < $commentid")
	  	or sqlerr(__FILE__, __LINE__);
	  $subrow = mysql_fetch_row($subres);
    $count = $subrow[0];
    $comm_page = floor($count/20);
    $page_url = $comm_page?"&page=$comm_page":"";

	  $added = $arr["added"] . " GMT (" . (get_elapsed_time(sql_timestamp_to_unix_timestamp($arr["added"]))) . " назад)";

	  print("<p class=sub><table border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>".
	  "$added&nbsp;---&nbsp;<b>Торрент:&nbsp;</b>".
	  ($torrent?("<a href=details.php?id=$torrentid&tocomm=1>$torrent</a>"):" [Удален] ").
	  "&nbsp;---&nbsp;<b>Комментарий:&nbsp;</b>#<a href=details.php?id=$torrentid&tocomm=1$page_url>$commentid</a>
	  </td></tr></table></p>\n");

	  begin_table(true);

	  $body = format_comment($arr["text"]);

	  print("<tr valign=top><td class=comment>$body</td></tr>\n");

	  end_table();
	}

	end_frame();

	end_main_frame();

	if ($commentcount > $perpage) echo $pagerbottom;

	stdfoot();

	die;
}
elseif ($type == "polls")
{
	$select_is = "COUNT(*)";

	// LEFT due to orphan comments
	$from_is = "pollcomments AS pc LEFT JOIN polls as p
	            ON pc.poll = p.id";

	$where_is = "pc.user = $userid";
	$order_is = "pc.id DESC";

	$query = "SELECT $select_is FROM $from_is WHERE $where_is ORDER BY $order_is";

	$res = sql_query($query) or sqlerr(__FILE__, __LINE__);

	$arr = mysql_fetch_row($res) or stderr($tracker_lang['error'], "Комментарии к опросам не найдены");

	$commentcount = $arr[0];

	//------ Make page menu

	list($pagertop, $pagerbottom, $limit) = pager($perpage, $commentcount, $_SERVER["PHP_SELF"] . "?action=viewcomments&type=polls&id=$userid&");

	//------ Get user data

	$res = sql_query("SELECT username, donor, warned, enabled FROM users WHERE id=$userid") or sqlerr(__FILE__, __LINE__);

	if (mysql_num_rows($res) == 1)
	{
		$arr = mysql_fetch_assoc($res);

	  $subject = "<a href=userdetails.php?id=$userid><b>$arr[username]</b></a>" . get_user_icons($arr, true);
	}
	else
	  $subject = "unknown[$userid]";

	//------ Get comments

	$select_is = "p.question, pc.poll AS p_id, pc.id, pc.added, pc.text";

	$query = "SELECT $select_is FROM $from_is WHERE $where_is ORDER BY $order_is $limit";

	$res = sql_query($query) or sqlerr(__FILE__, __LINE__);

	if (mysql_num_rows($res) == 0) stderr($tracker_lang['error'], "Комментарии к опросам не найдены");

	stdhead("История комментариев к опросам");

	print("<h1>История комментариев для $subject</h1>\n");

	if ($commentcount > $perpage) echo $pagertop;

	//------ Print table

	begin_main_frame();

	begin_frame();

	while ($arr = mysql_fetch_assoc($res))
	{

		$commentid = $arr["id"];

	  $poll = $arr["question"];

    // make sure the line doesn't wrap
	  if (strlen($poll) > 55) $poll = substr($poll,0,52) . "...";

	  $pid = $arr["p_id"];

	  //find the page; this code should probably be in details.php instead

	  $subres = sql_query("SELECT COUNT(*) FROM pollcomments WHERE poll = $pid AND id < $commentid")
	  	or sqlerr(__FILE__, __LINE__);
	  $subrow = mysql_fetch_row($subres);
    $count = $subrow[0];
    $comm_page = floor($count/20);
    $page_url = $comm_page?"&page=$comm_page":"";

	  $added = $arr["added"] . " GMT (" . (get_elapsed_time(sql_timestamp_to_unix_timestamp($arr["added"]))) . " назад)";

	  print("<p class=sub><table border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>".
	  "$added&nbsp;---&nbsp;<b>Вопрос:&nbsp;</b>".
	  ($poll?("<a href=polloverview.php?id=$pid&tocomm=1>$poll</a>"):" [Удален] ").
	  "&nbsp;---&nbsp;<b>Комментарий:&nbsp;</b>#<a href=polloverview.php?id=$pid&tocomm=1$page_url>$commentid</a>
	  </td></tr></table></p>\n");

	  begin_table(true);

	  $body = format_comment($arr["text"]);

	  print("<tr valign=top><td class=comment>$body</td></tr>\n");

	  end_table();
	}

	end_frame();

	end_main_frame();

	if ($commentcount > $perpage) echo $pagerbottom;

	stdfoot();

	die;
}
elseif ($type == "news")
{
	$select_is = "COUNT(*)";

	// LEFT due to orphan comments
	$from_is = "newscomments AS nc LEFT JOIN news as n
	            ON nc.news = n.id";

	$where_is = "nc.user = $userid";
	$order_is = "nc.id DESC";

	$query = "SELECT $select_is FROM $from_is WHERE $where_is ORDER BY $order_is";

	$res = sql_query($query) or sqlerr(__FILE__, __LINE__);

	$arr = mysql_fetch_row($res) or stderr($tracker_lang['error'], "Комментарии к новостям не найдены");

	$commentcount = $arr[0];

	//------ Make page menu

	list($pagertop, $pagerbottom, $limit) = pager($perpage, $commentcount, $_SERVER["PHP_SELF"] . "?action=viewcomments&type=news&id=$userid&");

	//------ Get user data

	$res = sql_query("SELECT username, donor, warned, enabled FROM users WHERE id=$userid") or sqlerr(__FILE__, __LINE__);

	if (mysql_num_rows($res) == 1)
	{
		$arr = mysql_fetch_assoc($res);

	  $subject = "<a href=userdetails.php?id=$userid><b>$arr[username]</b></a>" . get_user_icons($arr, true);
	}
	else
	  $subject = "unknown[$userid]";

	//------ Get comments

	$select_is = "n.subject, nc.news AS n_id, nc.id, nc.added, nc.text";

	$query = "SELECT $select_is FROM $from_is WHERE $where_is ORDER BY $order_is $limit";

	$res = sql_query($query) or sqlerr(__FILE__, __LINE__);

	if (mysql_num_rows($res) == 0) stderr($tracker_lang['error'], "Комментарии для новостей не найдены");

	stdhead("История комментариев к новостям");

	print("<h1>История комментариев для $subject</h1>\n");

	if ($commentcount > $perpage) echo $pagertop;

	//------ Print table

	begin_main_frame();

	begin_frame();

	while ($arr = mysql_fetch_assoc($res))
	{

		$commentid = $arr["id"];

	  $news = $arr["subject"];

    // make sure the line doesn't wrap
	  if (strlen($news) > 55) $news = substr($news,0,52) . "...";

	  $newsid = $arr["n_id"];

	  //find the page; this code should probably be in details.php instead

	  $subres = sql_query("SELECT COUNT(*) FROM newscomments WHERE news = $newsid AND id < $commentid")
	  	or sqlerr(__FILE__, __LINE__);
	  $subrow = mysql_fetch_row($subres);
    $count = $subrow[0];
    $comm_page = floor($count/20);
    $page_url = $comm_page?"&page=$comm_page":"";

	  $added = $arr["added"] . " GMT (" . (get_elapsed_time(sql_timestamp_to_unix_timestamp($arr["added"]))) . " назад)";

	  print("<p class=sub><table border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>".
	  "$added&nbsp;---&nbsp;<b>Новость:&nbsp;</b>".
	  ($news?("<a href=details.php?id=$newsid&tocomm=1>$news</a>"):" [Удален] ").
	  "&nbsp;---&nbsp;<b>Комментарий:&nbsp;</b>#<a href=details.php?id=$newsid&tocomm=1$page_url>$commentid</a>
	  </td></tr></table></p>\n");

	  begin_table(true);

	  $body = format_comment($arr["text"]);

	  print("<tr valign=top><td class=comment>$body</td></tr>\n");

	  end_table();
	}

	end_frame();

	end_main_frame();

	if ($commentcount > $perpage) echo $pagerbottom;

	stdfoot();

	die;
}
elseif (!isset($_GET['type'])) {
  stdhead("Выбрать тип комментариев");
  print("<div align=\"center\">Выберите тип комментариев:</div><table width=\"100%\" border=\"1\"><tr><td align=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?action=viewcomments&type=torrents&id=".$userid."\">К релизам</a></td><td align=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?action=viewcomments&type=polls&id=".$userid."\">К опросам</a></td><td align=\"center\"><a href=\"".$_SERVER["PHP_SELF"]."?action=viewcomments&type=news&id=".$userid."\">К новостям</a></td></tr></table>");
  stdfoot();
  die;
}

//-------- Handle unknown action

if ($action != "")
	stderr($tracker_lang['error'], "Неизвестное действие.");

//-------- Any other case

stderr($tracker_lang['error'], "Неверный или отсутствующий запрос.");

?>