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
dbconn();
loggedinorreturn();
getlang('friends');

$action = (string)$_GET['action'];
$fid = (int) $_GET['id'];

if (!$fid) $fid=$CURUSER['id'];

$curusername = '<a href="userdetails.php?id='.$CURUSER['id'].'">'.get_user_class_color($CURUSER['class'],$CURUSER['username']).'</a>';


if ($action == 'add') {

	if ($CURUSER['id']==$fid) stderr($tracker_lang['error'],$tracker_lang['cant_add_myself']);

	$user = sql_query("SELECT id,username,class FROM users WHERE id=$fid") or sqlerr(__FILE__,__LINE__);
	$user = mysql_fetch_assoc($user);
	if (!$user) stderr($tracker_lang['error'],$tracker_lang['no_user']);

	$friend = sql_query("SELECT id FROM friends WHERE (userid=$fid AND friendid={$CURUSER['id']}) OR (friendid=$fid AND userid={$CURUSER['id']})") or sqlerr(__FILE__,__LINE__);
	$friend = mysql_fetch_assoc($friend);
	if ($friend) stderr($tracker_lang['error'],$tracker_lang['already_in_private_group']);

	$username = '<a href="userdetails.php?id='.$fid.'">'.get_user_class_color($user['class'],$user['username']).'</a>';

	sql_query("INSERT INTO friends (userid,friendid) VALUES ({$CURUSER['id']},$fid)");
	$fiid=mysql_insert_id();
	if (mysql_errno()==1062) stderr($tracker_lang['error'],$tracker_lang['already_in_private_group']);


	write_sys_msg($fid,sprintf($tracker_lang['friend_notice'],$curusername,$fiid,$fiid),$tracker_lang['friend_notice_subject']." ({$CURUSER['username']})");

	stderr($tracker_lang['success'],sprintf($tracker_lang['user_notice_sent'],$username),'success');


}
elseif ($action == 'deny'){
	$frarq =sql_query("SELECT userid,friendid,id,confirmed FROM friends WHERE friendid={$CURUSER['id']} OR userid={$CURUSER['id']} AND id=$fid") or sqlerr(__FILE__,__LINE__);
	$frar = mysql_fetch_assoc($frarq);
	if (!$frar) stderr($tracker_lang['error'],$tracker_lang['cannot_edit_friends']);

	write_sys_msg((($frar['userid']<>$CURUSER['id'])?$frar['userid']:$frar['friendid']),sprintf($tracker_lang['friend_deny'],$curusername),$tracker_lang['friendship_cancelled']." ({$CURUSER['username']})");

	sql_query("DELETE FROM friends WHERE id=$fid");
	stderr($tracker_lang['success'],$tracker_lang['friend_deleted'],'success');

}
elseif ($action == 'confirm'){
	$frarq =sql_query("SELECT friends.userid, friends.id, users.class, users.username FROM friends LEFT JOIN users ON users.id=friends.userid WHERE friendid={$CURUSER['id']} AND friends.id=$fid") or sqlerr(__FILE__,__LINE__);
	$frar = mysql_fetch_assoc($frarq);
	if (!$frar) stderr($tracker_lang['error'],$tracker_lang['cannot_edit_friends']);
	$username = '<a href="userdetails.php?id='.$frar['userid'].'">'.get_user_class_color($frar['class'],$frar['username']).'</a>';
	sql_query("UPDATE friends SET confirmed=1 WHERE id=$fid");

	stderr($tracker_lang['success'],sprintf($tracker_lang['friend_confirmed'],$username),'success');

}

if (isset($_GET['ajax']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
	$ajax = 1;
	header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);

} else $ajax=0;

$page = (int) $_GET["page"];

$search = (string) $_GET['search'];
if ($ajax) $search = base64_decode($search);
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

$query['def'] = "userid={$CURUSER['id']} OR friendid=$fid";

$query = $querystr.' WHERE '.implode(' AND ',$query)." GROUP BY friends.id";

if (!$ajax) {
	stdhead($tracker_lang['users']);
	print '<script language="javascript" type="text/javascript">
//<![CDATA[
var no_ajax = true;
var switched = 0;
function pageswitcher(page) {
     (function($){
    if ($) no_ajax = false;
    $("#users-table").empty();
    $("#users-table").append(\'<div align="center"><img src="pic/loading.gif" border="0" alt="loading"/></div>\');
    $.get("friends.php", { ajax: 1, page: page, search: "'.base64_encode($search).'", class: "'.$class.'" }, function(data){
    $("#users-table").empty();
    $("#users-table").append(data);
});
})(jQuery);

if (!switched){
window.location.href = window.location.href+"#users-table";
switched++;
}
else window.location.href = window.location.href;

return no_ajax;
}
//]]>
</script>
';
}

if (!$ajax) {
	print("<h1>{$tracker_lang['friends']}</h1>\n");

	print("<form method=\"get\" action=\"friends.php\">\n");
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
	print("<input type=\"submit\" value=\"{$tracker_lang['go']}\">\n");
	print("</form>\n");

}
$res = sql_query("SELECT COUNT(friends.id) FROM friends$query") or sqlerr(__FILE__, __LINE__);
$count = @mysql_result($res,0);
if (!$count) { stdmsg($tracker_lang['error'],$tracker_lang['nothing_found'],'error'); stdfoot(); die(); }
$perpage = 50;
list($pagertop, $pagerbottom, $limit) = browsepager($perpage, $count, $_SERVER['PHP_SELF'] . "?".$q , "#users-table" );



$res = sql_query("SELECT IF (friends.userid={$CURUSER['id']},friends.friendid,friends.userid) AS friend, IF (friends.userid={$CURUSER['id']},0,1) AS init, friends.confirmed AS fconf, friends.id, u.username,u.class,u.country,u.uploaded,u.downloaded,u.added,u.last_access,u.gender,u.donor, u.warned, u.enabled, c.name, c.flagpic FROM friends LEFT JOIN users AS u ON IF (friends.userid={$CURUSER['id']},u.id=friendid,u.id=userid) LEFT JOIN countries AS c ON c.id = u.country$query ORDER BY friends.id,friends.confirmed DESC $limit") or sqlerr(__FILE__, __LINE__);

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
	if ($arr["downloaded"] > 0) {
		$ratio = number_format($arr["uploaded"] / $arr["downloaded"], 2);
		if (($arr["uploaded"] / $arr["downloaded"]) > 100)
		$ratio = "10(int)";
		$ratio = "<font color=\"" . get_ratio_color($ratio) . "\">$ratio</font>";
	}
	else
	if ($arr["uploaded"] > 0)
	$ratio = "Inf.";
	else
	$ratio = "------";

	if ($arr["gender"] == "1") $gender = "<img src=\"pic/male.gif\" alt=\"Парень\" title=\"Парень\" style=\"margin-left: 4pt\">";
	elseif ($arr["gender"] == "2") $gender = "<img src=\"pic/female.gif\" alt=\"Девушка\" title=\"Девушка\" style=\"margin-left: 4pt\">";
	else $gender = "<div align=\"center\"><b>?</b></div>";

	print("<tr".(!$arr['fconf']?' style="background-color: #ffc;"':'')."><td align=\"left\"><a href=\"userdetails.php?id=$arr[friend]\"><b>".get_user_class_color($arr["class"], $arr["username"])."</b></a>" .get_user_icons($arr).($arr['init']?$tracker_lang['init']:'')."</td>" .
"<td>".mkprettytime($arr['added'])."</td><td>".mkprettytime($arr['last_access'])."</td><td>$ratio</td><td>$gender</td>".
"<td align=\"left\">" . get_user_class_name($arr["class"]) . "</td>$country<td>\n");

	if ($arr['fconf']) print('<a href="friends.php?action=deny&amp;id='.$arr['id'].'">'.$tracker_lang['delete_from_friends'].'</a>');
	elseif (!$arr['fconf'] && !$arr['init']) print($tracker_lang['friend_pending']);
	else
	print ('<a href="friends.php?action=confirm&amp;id='.$arr['id'].'">'.$tracker_lang['confirm'].'</a>');


	print ('</td><td><a href="message.php?action=sendmessage&amp;receiver='.$arr['friend'].'">ЛС</a>'.(($arr['class']>=UC_MODERATOR)?' / <a href="email-gateway.php?id='.$arr['friend'].'">email</a>':''));
	print ('</td></tr>');
}
print("</table>\n");
print ("<p>$pagerbottom</p>");
print('</div>');

if (!$ajax) stdfoot();


?>