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

$spbegin = "<div style=\"position: static;\" class=\"sp-wrap\"><div class=\"sp-head folded clickable\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"><tr><td class=\"bottom\" width=\"50%\"><i>Посмотреть голосовавших</i></td></tr></table></div><div class=\"sp-body\">";
$spend = "</div></div>";

$count = get_row_count("polls");

$addparam = $_SERVER['QUERY_STRING'];

if ($addparam != "")
$addparam = $addparam . "&" . $pagerlink;
else
$addparam = $pagerlink;
list($pagertop, $pagerbottom, $limit) = pager(5, $count, "pollsarchive.php?" . $addparam);

$pollsrow = sql_query("SELECT id FROM polls ORDER BY id DESC $limit");

stdhead("Архив опросов");

print('<table width="100%" border="1"><tr><td>'.$pagertop.'</td></tr>');
while (list($id) = mysql_fetch_array($pollsrow)) {

	$poll = sql_query("SELECT polls.*, polls_structure.value, polls_structure.id AS sid,polls_votes.vid,polls_votes.user,users.username,users.class,(SELECT SUM(1) FROM pollcomments WHERE poll = $id) AS numcomm FROM polls LEFT JOIN polls_structure ON polls.id = polls_structure.pollid LEFT JOIN polls_votes ON polls_votes.sid=polls_structure.id LEFT JOIN users ON users.id=polls_votes.user WHERE polls.id = $id ORDER BY sid ASC");
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

	print('<tr><td><table width="100%" border="1"><tr><td>Опрос № '.$id.'</td><td>Открыт: '.mkprettytime($pstart).(!is_null($pexp)?(($pexp > time())?", заканчивается: ".mkprettytime($pexp):", <font color=\"red\">закончен</font>: ".mkprettytime($pexp)):'').'</td></tr><tr><td align="center" class="colhead" colspan="2">'.$pquestion.'</b>'.((get_user_class() >= UC_ADMINISTRATOR)?" [<a href=\"pollsadmin.php?action=edit&id=$id\">Редактировать</a>][<a onClick=\"return confirm('Вы уверены?')\" href=\"pollsadmin.php?action=delete&id=$id\">Удалить</a>]":"").'</td></tr>');

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
			//     print $votedrow."<hr />";
			//   print_r ($vote);
			$vid=$vote['vid'];
			$userid=$vote['userid'];
			$user['username']=$vote['username'];
			$user['class']=$vote['userclass'];

			//      print($vote['vid'].$vote['username'].$vote['userclass'].$vote['userid'].",");
			if ($vote['userid'] == $CURUSER['id']) $voted = $votedrow;
			if (!is_null($vid)) $votecount[$votedrow]++;

			if ((($public) || (get_user_class() >= UC_MODERATOR)) && !is_null($vid))
			$usercode[$votedrow] .= "<a href=\"userdetails.php?id=$userid\">".get_user_class_color($user['class'],$user['username'])."</a>".((get_user_class() >= UC_MODERATOR)?" [<a onClick=\"return confirm('Удалить этот голос?')\" href=\"polloverview.php?deletevote&vid=".$vid."\">D</a>] ":" ");

			if (($votecount[$votedrow]) >= $maxvotes) $maxvotes = $votecount[$votedrow];

		}
	}     $tvotes = array_sum($votecount);

	@$percentpervote = 50/$maxvotes;
	if (!$percentpervote) $percentpervote=0;

	foreach ($sidcount as $sidkey => $vsid){
		@$percent = round($votecount[$vsid]*100/($tvotes));
		if (!$percent) $percent = 0;
		print("<tr><td width=\"250px\">");
		if ($vsid == $voted)
		print("<b>".$sidvals[$sidkey]." - ваш голос</b>");
		else print($sidvals[$sidkey]);
		print("</td><td><img src=\"./themes/$ss_uri/images/bar_left.gif\"><img src=\"./themes/$ss_uri/images/bar.gif\" height=\"12\" width=\"".round($percentpervote*$votecount[$vsid])."%\"><img src=\"./themes/$ss_uri/images/bar_right.gif\">$percent%, голосов: ".$votecount[$vsid]."<br />".((!$usercode[$vsid])?"Опрос приватный или никто не голосовал":$spbegin.$usercode[$vsid].$spend)."</td></tr>");
	}
	print('<tr><td>Опрос находится в архиве, голосования запрещены</td>');
	print("<td align=\"center\"><h1>Всего голосов: $tvotes, Комментариев: $comments</h1><br />[<a href=\"polloverview.php?id=$id\"><b>Подробнее/Список комментариев</b></a>]</td></tr>");

	print ('</table></td></tr>');
}
print('<tr><td>'.$pagerbottom.'</td></tr></table>');
stdfoot();

?>
