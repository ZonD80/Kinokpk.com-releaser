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
dbconn(false);

if (isset($_GET['deletevote']) && is_valid_id($_GET['vid']) && (get_user_class() >= UC_MODERATOR)) {
	$vid = (int) $_GET['vid'];

	sql_query("DELETE FROM polls_votes WHERE vid=$vid");
	if (!defined("CACHE_REQUIRED")){
		require_once(ROOT_PATH . 'classes/cache/cache.class.php');
		require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
		define("CACHE_REQUIRED",1);
	}
	$cache=new Cache();
	$cache->addDriver('file', new FileCacheDriver());

	$cache->clearGroupCache("block-polls");
	stderr($tracker_lang['success'],"Голос удален");
}
loggedinorreturn();

if (isset($_GET['vote'])  && ($_SERVER['REQUEST_METHOD'] == 'POST')){
	if ((isset($_POST["vote"]) && !is_valid_id($_POST["vote"])) || !is_valid_id((int) $_GET['id'])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	$voteid = (int) $_POST['vote'];
	$pid = (int) $_GET['id'];

	$pexprow = sql_query("SELECT exp FROM polls WHERE id=$pid");
	list($pexp) = mysql_fetch_array($pexprow);
	if (!is_null($pexp) && ($pexp < time())) stderr($tracker_lang['error'],"Срок действия опроса или вопроса истек, вы не можете голосовать");



	$votedrow = sql_query("SELECT sid FROM polls_votes WHERE user=".$CURUSER['id']." AND pid=$pid");
	list($voted) = mysql_fetch_array($votedrow);
	if ($voted) stderr($tracker_lang['error'],"Вы уже голосовали в этом опросе");
	 
	sql_query("INSERT INTO polls_votes (sid,user,pid) VALUES ($voteid,".$CURUSER['id'].",$pid)");
	if (!defined("CACHE_REQUIRED")){
		require_once(ROOT_PATH . 'classes/cache/cache.class.php');
		require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
		define("CACHE_REQUIRED",1);
	}
	$cache=new Cache();
	$cache->addDriver('file', new FileCacheDriver());

	$cache->clearGroupCache("block-polls");
	header("Location: polloverview.php?id=$pid");

}

if (!is_valid_id((int) $_GET['id'])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);


$id = (int) $_GET['id'];
$poll = sql_query("SELECT polls.*, polls_structure.value, polls_structure.id AS sid,polls_votes.vid,polls_votes.user,users.username,users.class,(SELECT COUNT(*) FROM pollcomments WHERE poll = $id) AS numcomm FROM polls LEFT JOIN polls_structure ON polls.id = polls_structure.pollid LEFT JOIN polls_votes ON polls_votes.sid=polls_structure.id LEFT JOIN users ON users.id=polls_votes.user WHERE polls.id = $id ORDER BY sid ASC");
$pquestion = array();
$pstart = array();
$pexp = array();
$public = array();
$sidvalues = array();
$votes = array();
$sids = array();
$votesres = array();
$sidcount = array();
$sidvals = array();
$votecount = array();
$usercode = array();
$comments = array();

while ($pollarray = mysql_fetch_array($poll)) {
	$pquestion[] = $pollarray['question'];
	$pstart[] = $pollarray['start'];
	$pexp[] = $pollarray['exp'];
	$public[] = $pollarray['public'];
	$comments[] = $pollarray['numcomm'];
	$sidvalues[$pollarray['sid']] = $pollarray['value'];
	$votes[] = array($pollarray['sid'] => array('vid'=>$pollarray['vid'],'userid'=>$pollarray['user'],'username'=>$pollarray['username'],'userclass'=>$pollarray['class']));
	$sids[] = $pollarray['sid'];
}
$pstart = @array_unique($pstart);
$pstart = $pstart[0];
if (!$pstart) stderr($tracker_lang['error'], "Такого опроса не существует");
$pexp = @array_unique($pexp);
$pexp = $pexp[0];
$pquestion = @array_unique($pquestion);
$pquestion = $pquestion[0];
$public = @array_unique($public);
$public = $public[0];
$comments = @array_unique($comments);
$comments = $comments[0];

$sids = @array_unique($sids);
sort($sids);
reset($sids);



stdhead("Обзор опроса");
print '<table width="100%" border="1"><tr><td>Опрос № '.$id.'</td><td>Открыт: '.get_date_time($pstart).((!is_null($pexp) && ($pexp > time()))?", заканчивается: ".get_date_time($pexp):", <font color=\"red\">закончен</font>: ".get_date_time($pexp)).'</td></tr><tr><td align="center" class="colhead" colspan="2"><b>'.$pquestion.'</b>'.((get_user_class() >= UC_ADMINISTRATOR)?" [<a href=\"pollsadmin.php?action=add\">Создать новый</a>] [<a href=\"pollsadmin.php?action=edit&id=$id\">Редактировать</a>] [<a onClick=\"return confirm('Вы уверены?')\" href=\"pollsadmin.php?action=delete&id=$id\">Удалить</a>]":"").'</td></tr>';

foreach ($sids as $sid)
$votesres[$sid] = array();

$voted=0;

foreach($votes as $votetemp)
foreach ($votetemp as $sid => $value)
array_push($votesres[$sid],$value);




foreach ($votesres as $votedrow => $votes) {

	$sidcount[] = $votedrow;
	$sidvals[] = $sidvalues[$votedrow];
	$votecount[$votedrow] = 0;
	$usercode[$votedrow] = '';

	foreach($votes as $vote) {
		//     print $votedrow."<hr>";
		//   print_r ($vote);
		$vid=$vote['vid'];
		$userid=$vote['userid'];
		$user['username']=$vote['username'];
		$user['class']=$vote['userclass'];

		//      print($vote['vid'].$vote['username'].$vote['userclass'].$vote['userid'].",");
		if ($vote['userid'] == $CURUSER['id']) $voted = $votedrow;
		if (!is_null($vid)) $votecount[$votedrow]++;

		if ((($public == 'yes') || (get_user_class() >= UC_MODERATOR)) && !is_null($vid))
		$usercode[$votedrow] .= "<a href=\"userdetails.php?id=$userid\">".get_user_class_color($user['class'],$user['username'])."</a>".((get_user_class() >= UC_MODERATOR)?" [<a onClick=\"return confirm('Удалить этот голос?')\" href=\"polloverview.php?deletevote&vid=".$vid."\">D</a>] ":" ");

		if (($votecount[$votedrow]) >= $maxvotes) $maxvotes = $votecount[$votedrow];

	}
}        $tvotes = array_sum($votecount);

@$percentpervote = 50/$maxvotes;
if (!$percentpervote) $percentpervote=0;

foreach ($sidcount as $sidkey => $vsid){
	@$percent = round($votecount[$vsid]*100/($tvotes));
	if (!$percent) $percent = 0;
	print("<tr><td width=\"250px\">");
	if ($vsid == $voted)
	print("<b>".$sidvals[$sidkey]." - ваш голос</b>");
	elseif (((!is_null($pexp) && ($pexp > time())) || is_null($pexp)) && !$voted) print("<form name=\"voteform\" method=\"post\" action=\"polloverview.php?vote&id=$id\"><input type=\"radio\" name=\"vote\" value=\"$vsid\"><input type=\"hidden\" name=\"type\" value=\"$ptype\">".$sidvals[$sidkey]);
	else print($sidvals[$sidkey]);
	print("</td><td><img src=\"./themes/$ss_uri/images/bar_left.gif\"><img src=\"./themes/$ss_uri/images/bar.gif\" height=\"12\" width=\"".round($percentpervote*$votecount[$vsid])."%\"><img src=\"./themes/$ss_uri/images/bar_right.gif\">$percent%, голосов: ".$votecount[$vsid].((!$usercode[$vsid])?"<br/>Никто не голосовал":"<br/>".$usercode[$vsid])."</td></tr>");
}
if (((!is_null($pexp) && ($pexp > time())) || is_null($pexp)) && !$voted) print("<tr><td><input type=\"submit\" value=\"Голосовать за этот вариант!\"></form><br/><div align=\"center\">[<a href=\"pollsarchive.php\">Архив опросов</a>]</div></td>");
elseif (!is_null($pexp) && ($pexp < time())) print('<tr><td>Опрос закрыт<br/><div align="center">[<a href="pollsarchive.php">Архив опросов</a>]</div></td>');
elseif ($voted) print('<tr><td>Вы уже голосовали в этом опросе<br/><div align="center">[<a href="pollsarchive.php">Архив опросов</a>]</div></td>');
print("<td><h1>Всего голосов: $tvotes, Комментариев: $comments</h1></td></tr>");

print ('</table>');

// POLLCOMMENTS START

$subres = mysql_query("SELECT COUNT(*) FROM pollcomments WHERE poll = ".(int) $_GET['id']);
$subrow = mysql_fetch_array($subres);
$count = $subrow[0];

$limited = 10;

if (!$count) {

	print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
	print("<tr><td class=colhead align=\"left\" colspan=\"2\">");
	print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев к опросу</div>");
	print("<div align=\"right\"><a href=#comments class=altlink_white>Добавить комментарий</a></div>");
	print("</td></tr><tr><td align=\"center\">");
	print("Комментариев нет. <a href=#comments>Желаете добавить?</a>");
	print("</td></tr></table><br>");

}
else {
	list($pagertop, $pagerbottom, $limit) = pager($limited, $count, "polloverview.php?id=".(int) $_GET['id']."&", array(lastpagedefault => 1));

	$subres = sql_query("SELECT pc.id, pc.ip, pc.text, pc.user, pc.added, pc.editedby, pc.editedat, u.avatar, u.warned, ".
                  "u.username, u.title, u.class, u.donor, u.downloaded, u.uploaded, u.gender, u.last_access, e.username AS editedbyname FROM pollcomments AS pc LEFT JOIN users AS u ON pc.user = u.id LEFT JOIN users AS e ON pc.editedby = e.id WHERE poll = " .
                  "".$id." ORDER BY pc.id $limit") or sqlerr(__FILE__, __LINE__);
	$allrows = array();

	while ($subrow = mysql_fetch_array($subres))
	$allrows[] = $subrow;




	print("<table class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >");
	print("<tr><td class=\"colhead\" align=\"center\" >");
	print("<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>");
	print("<div align=\"right\"><a href=#comments class=altlink_white>Добавить комментарий</a></div>");
	print("</td></tr>");

	print("<tr><td>");
	print($pagertop);
	print("</td></tr>");
	print("<tr><td>");
	commenttable($allrows,"pollcomment");
	print("</td></tr>");
	print("<tr><td>");
	print($pagerbottom);
	print("</td></tr>");
	print("</table>");
}



print("<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">");
print("<tr><td class=colhead align=\"left\" colspan=\"2\">  <a name=comments>&nbsp;</a><b>:: Добавить комментарий к опросу</b></td></tr>");
print("<tr><td width=\"100%\" align=\"center\" >");
//print("Ваше имя: ");
//print("".$CURUSER['username']."<p>");
print("<form name=comment method=\"post\" action=\"pollcomment.php?action=add\">");
print("<center><table border=\"0\"><tr><td class=\"clear\">");
print("<div align=\"center\">". textbbcode("comment","text","", 1) ."</div>");
print("</td></tr></table></center>");
print("</td></tr><tr><td  align=\"center\" colspan=\"2\">");
print("<input type=\"hidden\" name=\"pid\" value=\"".$id."\"/>");
print("<input type=\"submit\" class=btn value=\"Разместить комментарий\" />");
print("</td></tr></form></table>");

stdfoot();

?>
