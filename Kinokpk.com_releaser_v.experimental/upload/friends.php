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


$action = (string)$_GET['action'];
if ($action)
$fid = (int) $_GET['id'];
else $fid=$CURUSER['id'];

$curusername = '<a href="'.$REL_SEO->make_link('userdetails','id',$CURUSER['id'],'username',translit($CURUSER['username'])).'">'.get_user_class_color($CURUSER['class'],$CURUSER['username']).'</a>';


if ($action == 'add') {

	if ($CURUSER['id']==$fid) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('cant_add_myself'));

	$user = sql_query("SELECT id,username,class FROM users WHERE id=$fid") or sqlerr(__FILE__,__LINE__);
	$user = mysql_fetch_assoc($user);
	if (!$user) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_user'));

	$friend = sql_query("SELECT id FROM friends WHERE (userid=$fid AND friendid={$CURUSER['id']}) OR (friendid=$fid AND userid={$CURUSER['id']})") or sqlerr(__FILE__,__LINE__);
	$friend = mysql_fetch_assoc($friend);
	if ($friend) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('already_in_private_group'));

	$username = '<a href="'.$REL_SEO->make_link('userdetails','id',$fid,'username',translit($user['username'])).'">'.get_user_class_color($user['class'],$user['username']).'</a>';

	sql_query("INSERT INTO friends (userid,friendid) VALUES ({$CURUSER['id']},$fid)");
	$fiid=mysql_insert_id();
	if (mysql_errno()==1062) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('already_in_private_group'));


	write_sys_msg($fid,sprintf($REL_LANG->say_by_key('friend_notice'),$curusername,$fiid,$fiid),$REL_LANG->say_by_key('friend_notice_subject')." ({$CURUSER['username']})");

	stderr($REL_LANG->say_by_key('success'),sprintf($REL_LANG->say_by_key('user_notice_sent'),$username),'success');


}
elseif ($action == 'deny'){
	$frarq =sql_query("SELECT userid,friendid,confirmed FROM friends WHERE (friendid={$CURUSER['id']} OR userid={$CURUSER['id']}) AND id=$fid") or sqlerr(__FILE__,__LINE__);
	$frar = mysql_fetch_assoc($frarq);
	if (!$frar) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('cannot_edit_friends'));

	write_sys_msg((($frar['userid']<>$CURUSER['id'])?$frar['userid']:$frar['friendid']),sprintf($REL_LANG->say_by_key('friend_deny'),$curusername),$REL_LANG->say_by_key('friendship_cancelled')." ({$CURUSER['username']})");

	sql_query("DELETE FROM friends WHERE id=$fid");
	stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('friend_deleted'),'success');

}
elseif ($action == 'confirm'){
	$frarq =sql_query("SELECT friends.userid, friends.id, users.class, users.username FROM friends LEFT JOIN users ON users.id=friends.userid WHERE friendid={$CURUSER['id']} AND friends.id=$fid") or sqlerr(__FILE__,__LINE__);
	$frar = mysql_fetch_assoc($frarq);
	if (!$frar) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('cannot_edit_friends'));
	$username = '<a href="'.$REL_SEO->make_link('userdetails','id',$frar['userid'],'username',translit($frar['username'])).'">'.get_user_class_color($frar['class'],$frar['username']).'</a>';
	sql_query("UPDATE friends SET confirmed=1 WHERE id=$fid");

	stderr($REL_LANG->say_by_key('success'),sprintf($REL_LANG->say_by_key('friend_confirmed'),$username),'success');

}

$page = (int) $_GET["page"];

$search = (string) $_GET['search'];
$search = htmlspecialchars(trim($search));

$class = (int) $_GET['class'];
if ($class == '-' || !is_valid_user_class($class))
$class = '';

if ($search != '' || $class) {
	$querystr = " LEFT JOIN users ON friendid=users.id OR userid=users.id";
	$query_table = $query['u'] = "users.username LIKE '%" . sqlwildcardesc($search) . "%' AND users.confirmed=1";
	if ($search)
	$q = "search=" . $search;
}

if (is_valid_user_class($class)) {
	$query['c'] = "users.class = $class";
	$q .= ($q ? "&amp;" : "") . "class=$class";
}

if (isset($_GET['pending'])) {
	$pend=true;
	$query['p'] = 'friends.confirmed=0';
	$q .= ($q ? "&amp;" : "") . "pending";
} else $query['p'] = 'friends.confirmed=1';

$query['def'] = "(userid={$CURUSER['id']} OR friendid=$fid)";

$querycount = $querystr.' WHERE '.implode(' AND ',$query);
$query = $querycount." GROUP BY friends.id";
stdhead($REL_LANG->say_by_key('users'));

print("<h1>{$REL_LANG->say_by_key('friends')}</h1>\n");
print("<div id=\"tabs\"><ul>
<li nowrap=\"\" class=\"tab".($pend?'2':'1')."\"><a href=\"".$REL_SEO->make_link('friends')."\"><span>Друзья</span></a></li>
<li nowrap=\"\" class=\"tab".($pend?'1':'2')."\"><a href=\"".$REL_SEO->make_link('friends','pending','')."\"><span>Ожидает подтверждения</span></a></li>
</ul></div>\n");
print("<div class=\"friends_search\" style=\"margin-top:55px;\">");
print("<form method=\"get\" action=\"".$REL_SEO->make_link('friends')."\">\n");
print($REL_LANG->say_by_key('search')." <input type=\"text\" size=\"30\" name=\"search\" value=\"".$search."\">\n");
print("<select name=\"class\">\n");
print("<option value=\"-\">(Все уровни)</option>\n");
for ($i = 0;;++$i) {
	if ($c = get_user_class_name($i))
	print("<option value=\"$i\"" . (is_valid_user_class($class) && $class == $i ? " selected" : "") . ">$c</option>\n");
	else
	break;
}
print("</select>\n");
print("<input class=\"button\" type=\"submit\" value=\"{$REL_LANG->say_by_key('go')}\" style=\"margin-top: -2px;\">\n");
print("</form>\n");
print("</div>\n");

$res = sql_query("SELECT SUM(1) FROM friends$querycount") or sqlerr(__FILE__, __LINE__);
$count = @mysql_result($res,0);
if (!$count) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_friends'),'error'); stdfoot(); die(); }
$perpage = 50;
list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER['PHP_SELF'] . "?".$q);



$res = sql_query("SELECT IF (friends.userid={$CURUSER['id']},friends.friendid,friends.userid) AS friend, IF (friends.userid={$CURUSER['id']},0,1) AS init, friends.id, u.username,u.class,u.country,u.added,u.last_access,u.gender,u.donor, u.warned, u.enabled, u.avatar, u.ratingsum, c.name, c.flagpic FROM friends LEFT JOIN users AS u ON IF (friends.userid={$CURUSER['id']},u.id=friendid,u.id=userid) LEFT JOIN countries AS c ON c.id = u.country$query ORDER BY friends.id DESC $limit") or sqlerr(__FILE__, __LINE__);
print ('<div id="users-table">');
print ("<p>$pagertop</p><br />");
print ("<br />");
//print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
//print("<tr><td class=\"colhead\">Аффка</td> <td class=\"colhead\" align=\"left\">Имя</td><td class=\"colhead\">Зарегестрирован</td><td class=\"colhead\">Последний вход</td><td class=\"colhead\">Рейтинг</td><td class=\"colhead\">Пол</td><td class=\"colhead\" align=\"left\">Уровень</td><td class=\"colhead\">Страна</td><td class=\"colhead\">Подтвержден</td><td class=\"colhead\">Действия</td></tr>\n");
while ($arr = mysql_fetch_assoc($res)) {

	if ($arr['country'] > 0) {
	 $country = "&nbsp;<img src=\"pic/flag/$arr[flagpic]\" alt=\"$arr[name]\" title=\"$arr[name]\">\n";
	 }
	 else
	 $country = "";

	if ($arr["gender"] == "1") $gender = "<img src=\"pic/male.gif\" alt=\"Парень\" title=\"Парень\" style=\"margin-left: 4pt\">";
	elseif ($arr["gender"] == "2") $gender = "<img src=\"pic/female.gif\" alt=\"Девушка\" title=\"Девушка\" style=\"margin-left: 4pt\">";
	else $gender = "<div align=\"center\"><b>?</b></div>";

	print("<div id=\"relgroups\" class=\"relgroups_table\">
			<div id=\"relgroups_image\" class=\"relgroups_image\">");
	if($arr["avatar"]){
		print("<img id=\"photo\" title=".  $arr["username"]." border=\"0\" src=".$arr["avatar"].">");
	}else{
		print("<img id=\"photo\" title=".  $arr["username"]." border=\"0\" src=\"/pic/default_avatar.gif\">");
	}
	print("</div>
			<div class=\"relgroups_name\">
				<dl class=\"clearfix\" style=\"align:left;\">
				<dt>Ник</dt><dd><a href=\"".$REL_SEO->make_link('userdetails','id',$arr['friend'],'username',translit($arr["username"]))."\"><b>".get_user_class_color($arr["class"], $arr["username"]).$gender.get_user_icons($arr).$country."</b></a></dd>
				<dt>Рейтинг</dt><dd>".ratearea($arr['ratingsum'],$arr['friend'],'users', $CURUSER['id'])."</dd>
				<dt>Уровень</dt><dd>" . get_user_class_name($arr["class"]) . "</dd>
				<dt>Когда был</dt><dd>".mkprettytime($arr['last_access'])."</dd>
				<dt>Зарегестрирован</dt><dd>".mkprettytime($arr['added'])."</dd>
				</dl>
			 </div>
			 <div id=\"input\"  class=\"relgroups_input\" >
				<ul  class=\"relgroups_input\">
					<li><a href=\"".$REL_SEO->make_link('userdetails','id',$arr['friend'],'username',translit($arr["username"]))."\">Просмотр</a></li>
					");
	if (!$pend)print ("<li><a href=\"".$REL_SEO->make_link('friends','action','deny','id',$arr['id'])."\">".$REL_LANG->say_by_key('delete_from_friends')."</a></li><li><a href=\"".$REL_SEO->make_link('present','id',$arr['friend'])."\"><span>Подарок Другу</span><img src=\"pic/presents/present.gif\" title=\"Подарить подарок\" style=\"margin-top: -6px; border:none; width:12px; height:12px;\" /> </a></li><li>");
	elseif ($pend && !$arr['init']) print('<li>'.$REL_LANG->say_by_key('friend_pending').'</li>');
	else print ("<li><a href=\"".$REL_SEO->make_link('friends','action','confirm','id',$arr['id'])."\">".$REL_LANG->say_by_key('confirm')."</a></li>
			<li><a href=\"".$REL_SEO->make_link('friends','action','deny','id',$arr['id'])."\">".$REL_LANG->say_by_key('delete_on_friends')."</a></li>
		  ");

	print("<li><a href=\"".$REL_SEO->make_link('message','action','sendmessage','receiver',$arr['friend'])."\">Личное Сообщение</a></li>");
	print ($arr['init']?"<li>{$REL_LANG->say_by_key('init')}</li>":'');
	// <li>".((get_user_class() >= UC_ADMINISTRATOR)?"<a href=\"email-gateway.php?id=".$arr['friend']."\">email</a>":"</li>")."
	print("<ul>
			</div>
		</div>
	</div>	");
	//print ('</td></tr>');
}

print("</div>\n");
print("<div class=\"clear\"></div>");
print ("<p>$pagerbottom</p>\n");
print('</div></div>');

stdfoot();


?>