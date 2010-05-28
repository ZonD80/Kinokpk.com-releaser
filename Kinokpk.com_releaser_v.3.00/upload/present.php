<?php
/**
 * Presents
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
dbconn();
loggedinorreturn();
getlang('present');
getlang('friends');

$fid = (int) $_GET['id'];

$to = (int) $_GET['to'];

$cronrow = sql_query("SELECT * FROM cron WHERE cron_name IN ('rating_enabled','rating_perdownload')");

while ($cronres = mysql_fetch_array($cronrow)) $CRON[$cronres['cron_name']] = $cronres['cron_value'];

if ($CRON['rating_enabled']) $allowed_types = array('torrent','discount','ratingsum'); else $allowed_types = array('ratingsum');

$type = trim((string)$_GET['type']);


$addparam = ($type?'&amp;type='.$type:'').($fid?'&amp;id='.$fid:'').($to?'&amp;to='.$to:'');

if (!$type) {
	stdhead($tracker_lang['presents']);
	begin_frame($tracker_lang['what_present']);

	print('<table border="1" align="center">'.($CRON['rating_enabled']?'<tr><td align="center"><a href="present.php?type=torrent'.$addparam.'">'.$tracker_lang['big_present_torrent'].'</a></td><td align="center"><a href="present.php?type=discount'.$addparam.'">'.$tracker_lang['big_present_discount'].'</a></td>
  ':'').'<td align="center"><a href="present.php?type=ratingsum'.$addparam.'">'.$tracker_lang['big_present_ratingsum'].'</a></td></tr></table>');
	end_frame();
	stdfoot();
	die();
}

if (!in_array($type,$allowed_types)) stderr($tracker_lang['error'],$tracker_lang['no_type']);

if (!is_valid_id($to)) {
	stdhead($tracker_lang['presents']);
	begin_frame($tracker_lang['what_present']);

	print('<div align="center"><form action="present.php" method="GET"><table><tr><td align="center"><input type="text" maxlength="10" length="10" name="to"/>&nbsp;'.$tracker_lang['how_'.$type].($curvalue?', '.$tracker_lang['you_have'].$curvalue:'').'</td></tr><tr><td align="center">'.
	$tracker_lang['how_present_notice_'.$type].
	($type?'<input type="hidden" name="type" value="'.$type.'">':'').
	($fid?"<input type=\"hidden\" name=\"id\" value=\"$fid\">":'').
   '</td></tr><tr><td align="center"><input type="submit" value="'.$tracker_lang['go'].'"></td></tr></table></form></div>');
	end_frame();
	stdfoot();
	die();
}

stdhead($tracker_lang['present_'.$type]);

if (!$fid) {
	begin_frame($tracker_lang['select_friend']);
	$curusername = '<a href="userdetails.php?id='.$CURUSER['id'].'">'.get_user_class_color($CURUSER['class'],$CURUSER['username']).'</a>';



	$page = (int) $_GET["page"];

	$search = (string) $_GET['search'];
	$search = htmlspecialchars(trim($search));

	$class = (int) $_GET['class'];
	if ($class == '-' || !is_valid_user_class($class))
	$class = '';

	if ($search != '' || $class) {
		$querystr = " LEFT JOIN users ON friendid=users.id";
		$query_table = $query['u'] = "users.username LIKE '%" . sqlwildcardesc($search) . "%' AND users.confirmed=1";
		if ($search)
		$q = "search=" . $search;
	}

	if (is_valid_user_class($class)) {
		$query['c'] = "users.class = $class";
		$q .= ($q ? "&amp;" : "") . "class=$class";
	}

	$query['def'] = "userid={$CURUSER['id']} OR friendid={$CURUSER['id']}";

	$query = $querystr.' WHERE '.implode(' AND ',$query)." GROUP BY friends.id";



	print("<h1>{$tracker_lang['friends']}</h1>\n");

	print("<form method=\"get\" action=\"present.php\">\n");
	print($tracker_lang['search']." <input type=\"text\" size=\"30\" name=\"search\" value=\"".$search."\">\n");
	print("<select name=\"class\">\n");
	print("<option value=\"-\">(Все уровни)</option>\n");
	for ($i = 0;;++$i) {
		if ($c = get_user_class_name($i))
		print("<option value=\"$i\"" . (is_valid_user_class($class) && $class == $i ? " selected" : "") . ">$c</option>\n");
		else
		break;
	}
	print("</select>\n");
	if ($type) print("<input type=\"hidden\" name=\"type\" value=\"$type\">");
	if ($to) print("<input type=\"hidden\" name=\"to\" value=\"$to\">");
	print("<input type=\"submit\" value=\"{$tracker_lang['go']}\">\n");
	print("</form>\n");

	$res = sql_query("SELECT SUM(1) FROM friends$query") or sqlerr(__FILE__, __LINE__);
	$count = @mysql_result($res,0);
	if (!$count) { end_frame(); stdmsg($tracker_lang['error'],$tracker_lang['no_friends'],'error'); stdfoot(); die(); }
	$perpage = 50;
	list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER['PHP_SELF'] . "?".$q);



	$res = sql_query("SELECT IF (friends.userid={$CURUSER['id']},friends.friendid,friends.userid) AS friend, IF (friends.userid={$CURUSER['id']},0,1) AS init, friends.confirmed AS fconf, friends.id, u.username,u.class,u.country,u.ratingsum,u.added,u.last_access,u.gender,u.donor, u.warned, u.confirmed, u.enabled, c.name, c.flagpic FROM friends LEFT JOIN users AS u ON IF (friends.userid={$CURUSER['id']},u.id=friendid,u.id=userid) LEFT JOIN countries AS c ON c.id = u.country$query ORDER BY friends.id,friends.confirmed DESC $limit") or sqlerr(__FILE__, __LINE__);

	print ('<div id="users-table">');
	print ("<p>$pagertop</p>");
	print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
	print("<tr><td class=\"colhead\" align=\"left\">Имя</td><td class=\"colhead\">Зарегестрирован</td><td class=\"colhead\">Последний вход</td><td class=\"colhead\">Рейтинг</td><td class=\"colhead\">Пол</td><td class=\"colhead\" align=\"left\">Уровень</td><td class=\"colhead\">Страна</td><td class=\"colhead\">Подтвержден</td><td class=\"colhead\">Действия</td></tr>\n");
	while ($arr = mysql_fetch_assoc($res)) {
		if ($arr['country'] > 0) {
			$country = "<td style=\"padding: 0px\" align=\"center\"><img src=\"pic/flag/$arr[flagpic]\" alt=\"$arr[name]\" title=\"$arr[name]\"></td>";
		}
		else
		$country = "<td align=\"center\">---</td>";
		$ratingsum = ratearea($arr['ratingsum'],$arr['friend'],'users',$CURUSER['id']);


		if ($arr["gender"] == "1") $gender = "<img src=\"pic/male.gif\" alt=\"Парень\" title=\"Парень\" style=\"margin-left: 4pt\">";
		elseif ($arr["gender"] == "2") $gender = "<img src=\"pic/female.gif\" alt=\"Девушка\" title=\"Девушка\" style=\"margin-left: 4pt\">";
		else $gender = "<div align=\"center\"><b>?</b></div>";

		print("<tr".(!$arr['fconf']?' style="background-color: #ffc;"':'')."><td align=\"left\"><a href=\"userdetails.php?id=$arr[friend]\"><b>".get_user_class_color($arr["class"], $arr["username"])."</b></a>" .get_user_icons($arr).($arr['init']?$tracker_lang['init']:'')."</td>" .
"<td>".mkprettytime($arr['added'])."</td><td>".mkprettytime($arr['last_access'])."</td><td>$ratingsum</td><td>$gender</td>".
"<td align=\"left\">" . get_user_class_name($arr["class"]) . "</td>$country<td>\n");

		if ($arr['fconf']) print('<a href="friends.php?action=deny&amp;id='.$arr['id'].'">'.$tracker_lang['delete_from_friends'].'</a>');
		elseif (!$arr['fconf'] && !$arr['init']) print($tracker_lang['friend_pending']);
		else
		print ('<a href="friends.php?action=confirm&amp;id='.$arr['id'].'">'.$tracker_lang['confirm'].'</a>');


		print ('</td><td>'.($arr['fconf']?"<a href=\"present.php?id=$arr[friend]".$addparam."\">{$tracker_lang['select_present']}</a>":$tracker_lang['user_unconfirmed']));
		print ('</td></tr>');
	}
	print("</table>\n");
	print ("<p>$pagerbottom</p>");
	print('</div>');

	end_frame();

	stdfoot();

	die();
}

$friendres = sql_query("SELECT IF (friends.userid={$CURUSER['id']},friends.friendid,friends.userid) AS friend, users.class, users.username FROM friends LEFT JOIN users ON IF (friends.userid={$CURUSER['id']},users.id=friendid,users.id=userid) WHERE (userid=$fid AND friendid={$CURUSER['id']}) OR (friendid=$fid AND userid={$CURUSER['id']}) AND friends.confirmed=1") or sqlerr(__FILE__,__LINE__);
$friend = mysql_fetch_assoc($friendres);
if (!$friend) { stdmsg($tracker_lang['error'],$tracker_lang['no_friend'], 'error'); stdfoot(); die(); }
$friendname = '<a href="userdetails.php?id='.$friend['friend'].'">'.get_user_class_color($friend['class'],$friend['username']).'</a>';
$curusername = '<a href="userdetails.php?id='.$CURUSER['id'].'">'.get_user_class_color($CURUSER['class'],$CURUSER['username']).'</a>';

if ($type=='torrent') {


	$freerow = sql_query("SELECT name,freefor,owner,orig_owner FROM torrents WHERE id=$to AND NOT FIND_IN_SET($fid,freefor)") or sqlerr(__FILE__,__LINE__);
	if (!mysql_num_rows($freerow)) {end_frame(); stdmsg($tracker_lang['error'],$tracker_lang['no_present_torrent'],'error'); stdfoot(); die(); }

	$freeres = mysql_fetch_assoc($freerow);
	if (($fid==$freeres['owner']) || ($fid==$freeres['orig_owner'])) {end_frame(); stdmsg($tracker_lang['error'],$tracker_lang['no_present_torrent'],'error'); stdfoot(); die(); }

	if (!$freeres['freefor'])
	sql_query("UPDATE torrents SET freefor=$fid WHERE id=$to") or sqlerr(__FILE__,__LINE__);
	else
	sql_query("UPDATE torrents SET freefor='{$freeres['freefor']},$fid' WHERE id=$to") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE users SET ratingsum=ratingsum-{$CRON['rating_perdownload']} WHERE id={$CURUSER['id']}") or sqlerr(__FILE__,__LINE__);

}

else {
	if ($to>=$CURUSER[$type]) {  end_frame(); stdmsg($tracker_lang['error'],$tracker_lang['no_money_'.$type],'error'); stdfoot(); die(); }

	sql_query("UPDATE users SET $type=$type+$to WHERE id=$fid") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE users SET $type=$type-$to WHERE id={$CURUSER['id']}") or sqlerr(__FILE__,__LINE__);
}

write_sys_msg($fid,sprintf($tracker_lang['presented'],$curusername,sprintf($tracker_lang['presented_'.$type],(($type=='torrent')?"<a href=\"details.php?id=$to\">{$freeres['name']}</a>":$to)),$CURUSER['id']),$tracker_lang['presents']);

stdmsg($tracker_lang['success'],sprintf($tracker_lang['you_success_presented'],$friendname));
stdfoot();

?>