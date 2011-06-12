<?php
/**
 * Friends listing
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();
loggedinorreturn();


$action = (string)$_GET['action'];
$q[] = 'friends';
if ($action)
$fid = (int) $_GET['id'];
else $fid=$CURUSER['id'];

$curusername = make_user_link();


if ($action == 'add') {

	if ($CURUSER['id']==$fid) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('cant_add_myself'));

	$user = sql_query("SELECT id,username,class,donor,warned,enabled FROM users WHERE id=$fid") or sqlerr(__FILE__,__LINE__);
	$user = mysql_fetch_assoc($user);
	if (!$user) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_user'));

	$friend = sql_query("SELECT id FROM friends WHERE (userid=$fid AND friendid={$CURUSER['id']}) OR (friendid=$fid AND userid={$CURUSER['id']})") or sqlerr(__FILE__,__LINE__);
	$friend = mysql_fetch_assoc($friend);
	if ($friend) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('already_in_private_group'));

	$username = make_user_link($user);

	sql_query("INSERT INTO friends (userid,friendid) VALUES ({$CURUSER['id']},$fid)");
	$fiid=mysql_insert_id();
	if (mysql_errno()==1062) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('already_in_private_group'));


	write_sys_msg($fid,sprintf($REL_LANG->say_by_key_to($fid,'friend_notice'),$curusername,$fiid,$fiid),$REL_LANG->say_by_key_to($fid,'friend_notice_subject')." ({$CURUSER['username']})");
	send_notifs('friends','',$fid);
	stderr($REL_LANG->say_by_key('success'),sprintf($REL_LANG->say_by_key('user_notice_sent'),$username),'success');


}
elseif ($action == 'deny'){
	$frarq =sql_query("SELECT userid,friendid,confirmed FROM friends WHERE (friendid={$CURUSER['id']} OR userid={$CURUSER['id']}) AND id=$fid") or sqlerr(__FILE__,__LINE__);
	$frar = mysql_fetch_assoc($frarq);
	if (!$frar) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('cannot_edit_friends'));

	$send_to = (($frar['userid']<>$CURUSER['id'])?$frar['userid']:$frar['friendid']);
	write_sys_msg($send_to,sprintf($REL_LANG->say_by_key_to($send_to,'friend_deny'),$curusername),$REL_LANG->say_by_key_to($send_to,'friendship_cancelled')." ({$CURUSER['username']})");

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
	$query['u'] = "users.username LIKE '%" . sqlwildcardesc($search) . "%' AND users.confirmed=1";
	if ($search)
	$q[] = "search";
	$q[] = $search;
}

if (is_valid_user_class($class)) {
	$query['c'] = "users.class = $class";
	$q[] = 'class';
	$q[] = $class;
}

$state='a';

if (isset($_GET['pending'])) {
	$state = 'p';
	$query['p'] = 'friends.confirmed=0';
	$q[] = 'pending';
	$q[] = 1;
}
elseif (isset($_GET['online'])) {
	$state = 'o';
	$querystr = " LEFT JOIN users ON IF(userid={$CURUSER['id']},friendid=users.id,userid=users.id)";
	$query['o'] = 'users.last_access>'.(time()-300);
	$q[] = 'online';
	$q[] = 1;
} else $query[] = 'friends.confirmed=1';

$query_data = $querystr.' WHERE '.implode(' AND ',$query);

$query['def'] = "(userid={$CURUSER['id']} OR friendid=$fid)";

$querycount = $querystr.' WHERE '.implode(' AND ',$query);
$query = $querycount." GROUP BY friends.id";
$REL_TPL->stdhead($REL_LANG->say_by_key('users'));
$res = sql_query("SELECT (SELECT SUM(1) FROM friends WHERE friends.confirmed=1 AND (userid={$CURUSER['id']} OR friendid={$CURUSER['id']})), (SELECT SUM(1) FROM friends LEFT JOIN users ON IF(userid={$CURUSER['id']},friendid=users.id,userid=users.id) WHERE users.last_access>".(time()-300)." AND (userid={$CURUSER['id']} OR friendid={$CURUSER['id']})), (SELECT SUM(1) FROM friends WHERE friends.confirmed=0 AND (userid={$CURUSER['id']} OR friendid={$CURUSER['id']}))") or sqlerr(__FILE__, __LINE__);
list ($countarr['a'],$countarr['o'],$countarr['p']) = mysql_fetch_array($res);
$countarr = array_map("intval",$countarr);
$count = $countarr[$state];
print("<h1>{$REL_LANG->say_by_key('friends')}</h1>\n");
//var_dump($state);
print("<div id=\"tabs\"><ul>
<li nowrap=\"\" class=\"tab".($state=='a'?'1':'2')."\"><a href=\"".$REL_SEO->make_link('friends')."\"><span>{$REL_LANG->_("Friends")} ({$countarr['a']})</span></a></li>
<li nowrap=\"\" class=\"tab".($state=='o'?'1':'2')."\"><a href=\"".$REL_SEO->make_link('friends','online','')."\"><span>{$REL_LANG->_("Online")} ({$countarr['o']})</span></a></li>
<li nowrap=\"\" class=\"tab".($state=='p'?'1':'2')."\"><a href=\"".$REL_SEO->make_link('friends','pending','')."\"><span>{$REL_LANG->_("Pending")} ({$countarr['p']})</span></a></li>
</ul></div>\n");
print("<div class=\"friends_search\" style=\"margin-top:55px;\">");
print("<form method=\"get\" action=\"".$REL_SEO->make_link('friends')."\">\n");
if ($state=='o') print '<input type="hidden" name="online" value="1">';
if ($state=='p') print '<input type="hidden" name="pending" value="1">';
print($REL_LANG->say_by_key('search')." <input type=\"text\" size=\"30\" name=\"search\" value=\"".$search."\">\n");
print make_classes_select('class',$class);
print("<input class=\"button\" type=\"submit\" value=\"{$REL_LANG->say_by_key('go')}\" style=\"margin-top: -2px;\">\n");
print("</form>\n");
print("</div>\n");

if (!$count) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_friends'),'error'); $REL_TPL->stdfoot(); die(); }




//$res = sql_query("SELECT IF (friends.userid={$CURUSER['id']},friends.friendid,friends.userid) AS friend, IF (friends.userid={$CURUSER['id']},0,1) AS init, friends.id, u.username,u.class,u.country,u.added,u.last_access,u.gender,u.donor, u.warned, u.enabled, u.avatar, u.ratingsum, c.name, c.flagpic FROM friends LEFT JOIN users AS u ON IF (friends.userid={$CURUSER['id']},u.id=friendid,u.id=userid) LEFT JOIN countries AS c ON c.id = u.country$query ORDER BY friends.id DESC") or sqlerr(__FILE__, __LINE__);
$res = $REL_DB->query("(SELECT friends.friendid AS friend, 0 AS init, friends.id, u.username,u.class,u.country,u.added,u.last_access,u.gender,u.donor, u.warned, u.enabled, u.avatar, u.ratingsum, c.name, c.flagpic FROM friends LEFT JOIN users AS u ON u.id=friendid LEFT JOIN countries AS c ON c.id = u.country$query_data AND userid={$CURUSER['id']}) UNION (SELECT friends.userid AS friend, 1 AS init, friends.id, u.username,u.class,u.country,u.added,u.last_access,u.gender,u.donor, u.warned, u.enabled, u.avatar, u.ratingsum, c.name, c.flagpic FROM friends LEFT JOIN users AS u ON u.id=userid LEFT JOIN countries AS c ON c.id = u.country$query_data AND friendid={$CURUSER['id']})") or sqlerr(__FILE__, __LINE__);

print ('<div id="users-table">');
print ("<br />");
//print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
//print("<tr><td class=\"colhead\">Аффка</td> <td class=\"colhead\" align=\"left\">Имя</td><td class=\"colhead\">Зарегестрирован</td><td class=\"colhead\">Последний вход</td><td class=\"colhead\">Рейтинг</td><td class=\"colhead\">Пол</td><td class=\"colhead\" align=\"left\">Уровень</td><td class=\"colhead\">Страна</td><td class=\"colhead\">Подтвержден</td><td class=\"colhead\">Действия</td></tr>\n");
while ($arr = mysql_fetch_assoc($res)) {

	if ($arr['country'] > 0) {
	 $country = "&nbsp;<img style=\"border:none;\" src=\"pic/flag/$arr[flagpic]\" alt=\"$arr[name]\" title=\"$arr[name]\">\n";
	}
	else
	$country = "";

	if ($arr["gender"] == "1") $gender = "<img style=\"border:none;\" src=\"pic/male.gif\" alt=\"Парень\" title=\"Парень\" style=\"margin-left: 4pt\">";
	elseif ($arr["gender"] == "2") $gender = "<img style=\"border:none;\" src=\"pic/female.gif\" alt=\"Девушка\" title=\"Девушка\" style=\"margin-left: 4pt\">";
	else $gender = "<div align=\"center\"><b>?</b></div>";

	print("<div id=\"relgroups\" class=\"relgroups_table\">
			<div id=\"relgroups_image\" class=\"relgroups_image\"><a href=\"{$REL_SEO->make_link('userdetails','id',$arr['friend'],'username',$arr['username'])}\">");
	if($arr["avatar"]){
		print("<img id=\"photo\" style=\"border:none;\" title=".  $arr["username"]." border=\"0\" src=".$arr["avatar"].">");
	}else{
		print("<img id=\"photo\" style=\"border:none;\" title=".  $arr["username"]." border=\"0\" src=\"/pic/default_avatar.gif\">");
	}
	print("</a></div>
			<div class=\"relgroups_name\">
				<dl class=\"clearfix\" style=\"align:left;\">
				<dt>Ник</dt><dd><a href=\"".$REL_SEO->make_link('userdetails','id',$arr['friend'],'username',translit($arr["username"]))."\"><b>".get_user_class_color($arr["class"], $arr["username"]).$gender.get_user_icons($arr).$country."</b></a></dd>
				<dt>Рейтинг</dt><dd>".ratearea($arr['ratingsum'],$arr['friend'],'users', $CURUSER['id'])."</dd>
				<dt>Уровень</dt><dd>" . get_user_class_name($arr["class"]) . "</dd>
				<dt>Когда был</dt><dd>".(time()-$arr['last_access']<300?$REL_LANG->_("Online"):mkprettytime($arr['last_access']))."</dd>
				<dt>Зарегестрирован</dt><dd>".mkprettytime($arr['added'])."</dd>
				</dl>
			 </div>
			 <div id=\"input\"  class=\"relgroups_input\" >
				<ul  class=\"relgroups_input\">
					<li><a href=\"".$REL_SEO->make_link('userdetails','id',$arr['friend'],'username',translit($arr["username"]))."\">Просмотр</a></li>
					");
	if ($state<>'p')print ("<li><a href=\"".$REL_SEO->make_link('friends','action','deny','id',$arr['id'])."\">".$REL_LANG->say_by_key('delete_from_friends')."</a></li><li><a href=\"".$REL_SEO->make_link('present','id',$arr['friend'])."\"><span>Подарок Другу</span><img src=\"pic/presents/present.gif\" title=\"Подарить подарок\" style=\"margin-top: -6px; border:none; width:12px; height:12px;\" /> </a></li><li>");
	elseif ($state=='p' && !$arr['init']) print('<li>'.$REL_LANG->say_by_key('friend_pending').'</li>');
	else print ("<li><a href=\"".$REL_SEO->make_link('friends','action','confirm','id',$arr['id'])."\">".$REL_LANG->say_by_key('confirm')."</a></li>
			<li><a href=\"".$REL_SEO->make_link('friends','action','deny','id',$arr['id'])."\">".$REL_LANG->say_by_key('delete_on_friends')."</a></li>
		  ");

	print("<li><a href=\"".$REL_SEO->make_link('message','action','sendmessage','receiver',$arr['friend'])."\">Личное Сообщение</a></li>");
	print ($arr['init']?"<li>{$REL_LANG->say_by_key('init')}</li>":'');

	print("<ul>
			</div>
		</div>
	</div>	");
	//print ('</td></tr>');
}

print("</div>\n");
print("<div class=\"clear\"></div>");
print('</div></div>');

$REL_TPL->stdfoot();


?>