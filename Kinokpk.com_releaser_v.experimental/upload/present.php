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
INIT();
loggedinorreturn();



$fid = (int) $_GET['id'];

$to = (int) $_GET['to'];

$action = trim((string)$_GET['a']);


if ($REL_CRON['rating_enabled']) $allowed_types = array('torrent','discount','ratingsum'); else $allowed_types = array('ratingsum','torrent');

$type = trim((string)$_GET['type']);

$q[] ='present';

if ($type) {
  $q[] = 'type';
  $q[] = $type;
}
if ($fid) {
  $q[] = 'id';
  $q[] = $fid;
}
if ($to) {
  $q[] = 'to';
  $q[] = $to;
}

if ($action) {
	$q[] = 'a';
	$q[] = $action;
}

if ($action=='viewpresent') {
	$present = $REL_DB->query_row("SELECT * FROM presents WHERE id=$fid");
	if (!$present) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Invalid id'));
	else {
		$presenter = get_user($present['presenter']);
		$receiver = get_user($present['userid']);
		$REL_TPL->assign('presenter',$presenter);
		$REL_TPL->assign('receiver',$receiver);
		$REL_TPL->assign('present',$present);
		$links['present_more'] = $REL_SEO->make_link('present','id',$receiver['id'],'type',$present['type']);
		$links['present_similar'] = $REL_SEO->make_link('present','type',$present['type']);
		$REL_TPL->assign('links',$links);
	}
	$REL_TPL->stdhead($REL_LANG->_('Viewing present of %s',$presenter['username']));
	$REL_TPL->output($action);
	$REL_TPL->stdfoot();
	die();
} elseif ($action) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Unknown action'));

if (!$type) {
	$REL_TPL->stdhead($REL_LANG->say_by_key('presents'));
	$REL_TPL->begin_frame($REL_LANG->say_by_key('what_present'));
//var_Dump(array_merge($q,array('type','ratingsum')));
	print('<table border="1" align="center">'.($REL_CRON['rating_enabled']?'<tr><td align="center"><a href="'.$REL_SEO->make_link(array_merge($q,array('type','torrent'))).'">'.$REL_LANG->say_by_key('big_present_torrent').'</a></td><td align="center"><a href="'.$REL_SEO->make_link(array_merge($q,array('type','discount'))).'">'.$REL_LANG->say_by_key('big_present_discount').'</a></td>
  ':'').'<td align="center"><a href="'.$REL_SEO->make_link(array_merge($q,array('type','ratingsum'))).'">'.$REL_LANG->say_by_key('big_present_ratingsum').'</a></td></tr></table>');
	$REL_TPL->end_frame();
	$REL_TPL->stdfoot();
	die();
}

if (!in_array($type,$allowed_types)) stderr($REL_LANG->_("Error"),$REL_LANG->_("Invalid present type or currently you can not to present this, e.g. rating system is inactive."));

if (!is_valid_id($to)) {
	$REL_TPL->stdhead($REL_LANG->say_by_key('presents'));
	$REL_TPL->begin_frame($REL_LANG->say_by_key('what_present'));

	print('<div align="center"><form action="'.$REL_SEO->make_link('present').'" method="GET"><table><tr><td align="center"><input type="text" maxlength="10" length="10" name="to"/>&nbsp;'.$REL_LANG->say_by_key('how_'.$type).($curvalue?', '.$REL_LANG->say_by_key('you_have').$curvalue:'').'</td></tr><tr><td align="center">'.
	$REL_LANG->say_by_key('how_present_notice_'.$type).
	($type?'<input type="hidden" name="type" value="'.$type.'">':'').
	($fid?"<input type=\"hidden\" name=\"id\" value=\"$fid\">":'').
   '</td></tr><tr><td align="center"><input type="submit" value="'.$REL_LANG->say_by_key('go').'"></td></tr></table></form></div>');
	$REL_TPL->end_frame();
	$REL_TPL->stdfoot();
	die();
}

$REL_TPL->stdhead($REL_LANG->say_by_key('present_'.$type));

if (!$fid) {
	$REL_TPL->begin_frame($REL_LANG->say_by_key('select_friend'));
	$curusername = make_user_link();



	$page = (int) $_GET["page"];

	$search = (string) $_GET['search'];
	$search = htmlspecialchars(trim($search));

	$class = (int) $_GET['class'];
	if ($class == '-' || !is_valid_user_class($class))
	$class = '';
	
	$query = array();
	if ($search != '' || $class) {
		$querystr = " LEFT JOIN users ON friendid=users.id";
		$query_table = $query['u'] = "users.username LIKE '%" . sqlwildcardesc($search) . "%' AND users.confirmed=1";
		if ($search)
		$q[] = "search";
		$q[] = $search;
	}

	if (is_valid_user_class($class)) {
		$query['c'] = "users.class = $class";
		$q[] = "class";
		$q[] = $class;
	}
	
	$query_data = $querystr.' WHERE '.implode(' AND ',$query);

$query['def'] = "(userid={$CURUSER['id']} OR friendid=$fid)";

$querycount = $querystr.' WHERE '.implode(' AND ',$query);
$query = $querycount." GROUP BY friends.id";

	print("<h1>{$REL_LANG->say_by_key('friends')}</h1>\n");

	print("<form method=\"get\" action=\"".$REL_SEO->make_link('present')."\">\n");
	print($REL_LANG->say_by_key('search')." <input type=\"text\" size=\"30\" name=\"search\" value=\"".$search."\">\n");
	print make_classes_select('class',$class);
	if ($type) print("<input type=\"hidden\" name=\"type\" value=\"$type\">");
	if ($to) print("<input type=\"hidden\" name=\"to\" value=\"$to\">");
	print("<input type=\"submit\" value=\"{$REL_LANG->say_by_key('go')}\">\n");
	print("</form>\n");

	$res = sql_query("SELECT SUM(1) FROM friends$query") or sqlerr(__FILE__, __LINE__);
	$count = @mysql_result($res,0);
	if (!$count) { $REL_TPL->end_frame(); stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_friends'),'error'); $REL_TPL->stdfoot(); die(); }
	//$limit



	$res = sql_query("(SELECT friends.friendid AS friend, 0 AS init, friends.confirmed AS fconf, friends.id, u.username,u.class,u.country,u.ratingsum,u.added,u.last_access,u.gender,u.donor, u.warned, u.confirmed, u.enabled, c.name, c.flagpic FROM friends LEFT JOIN users AS u ON u.id=friendid LEFT JOIN countries AS c ON c.id = u.country$query_data friends.userid={$CURUSER['id']}) UNION (SELECT friends.userid AS friend, 1 AS init, friends.confirmed AS fconf, friends.id, u.username,u.class,u.country,u.ratingsum,u.added,u.last_access,u.gender,u.donor, u.warned, u.confirmed, u.enabled, c.name, c.flagpic FROM friends LEFT JOIN users AS u ON u.id=userid LEFT JOIN countries AS c ON c.id = u.country$query_data friends.friendid={$CURUSER['id']})") or sqlerr(__FILE__, __LINE__);

	print ('<div id="users-table">');
	print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\">\n");
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

		print("<tr".(!$arr['fconf']?' style="background-color: #ffc;"':'')."><td align=\"left\"><a href=\"".$REL_SEO->make_link('userdetails','id',$arr['friend'],'username',translit($arr["username"]))."\"><b>".get_user_class_color($arr["class"], $arr["username"])."</b></a>" .get_user_icons($arr).($arr['init']?$REL_LANG->say_by_key('init'):'')."</td>" .
"<td>".mkprettytime($arr['added'])."</td><td>".mkprettytime($arr['last_access'])."</td><td>$ratingsum</td><td>$gender</td>".
"<td align=\"left\">" . get_user_class_name($arr["class"]) . "</td>$country<td>\n");

		if ($arr['fconf']) print('<a href="'.$REL_SEO->make_link('friends','action','deny','id',$arr['id']).'">'.$REL_LANG->say_by_key('delete_from_friends').'</a>');
		elseif (!$arr['fconf'] && !$arr['init']) print($REL_LANG->say_by_key('friend_pending'));
		else
		print ('<a href="'.$REL_SEO->make_link('friends','action','confirm','id',$arr['id']).'">'.$REL_LANG->say_by_key('confirm').'</a>');


		print ('</td><td>'.($arr['fconf']?"<a href=\"{$REL_SEO->make_link(array_merge($q,array('id',$arr['friend'])))}\">{$REL_LANG->say_by_key('select_present')}</a>":$REL_LANG->say_by_key('user_unconfirmed')));
		print ('</td></tr>');
	}
	print("</table>\n");
	print('</div>');

	$REL_TPL->end_frame();

	$REL_TPL->stdfoot();

	die();
}

$reason = cleanhtml((string)$_POST['reason']);

if (!$reason) {
	$REL_TPL->begin_frame($REL_LANG->_("Enter a short message to attach to your present"));
	print "<form method=\"post\" action=\"{$REL_SEO->make_link('present','id',$fid,'to',$to,'type',$type)}\">";
	?>
<table width="100%">
	<tr>
		<td align="center"><input type="text" name="reason" size="120"
			maxlength="255" /></td>
	</tr>
	<tr>
		<td align="center"><input type="submit"
			value="<?=$REL_LANG->_("Present now!");?>" /></td>
	</tr>
</table>
	<?php
	print "</form>";
	$REL_TPL->end_frame();
	$REL_TPL->stdfoot();
	die();
}
$friendres = sql_query("SELECT IF (friends.userid={$CURUSER['id']},friends.friendid,friends.userid) AS friend, users.class, users.username FROM friends LEFT JOIN users ON IF (friends.userid={$CURUSER['id']},users.id=friendid,users.id=userid) WHERE (userid=$fid AND friendid={$CURUSER['id']}) OR (friendid=$fid AND userid={$CURUSER['id']}) AND friends.confirmed=1") or sqlerr(__FILE__,__LINE__);
$friend = mysql_fetch_assoc($friendres);
if (!$friend) { stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_friend'), 'error'); $REL_TPL->stdfoot(); die(); }
$friendname = '<a href="'.$REL_SEO->make_link('userdetails','id',$friend['friend'],'username',translit($friend['username'])).'">'.get_user_class_color($friend['class'],$friend['username']).'</a>';
$curusername = make_user_link();

if ($type=='torrent') {


	$freerow = sql_query("SELECT name,freefor,owner,orig_owner FROM torrents WHERE id=$to AND NOT FIND_IN_SET($fid,freefor)") or sqlerr(__FILE__,__LINE__);
	if (!mysql_num_rows($freerow)) {$REL_TPL->end_frame(); stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_present_torrent'),'error'); $REL_TPL->stdfoot(); die(); }

	$freeres = mysql_fetch_assoc($freerow);
	if (($fid==$freeres['owner']) || ($fid==$freeres['orig_owner'])) {$REL_TPL->end_frame(); stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_present_torrent'),'error'); $REL_TPL->stdfoot(); die(); }

	if (!$freeres['freefor'])
	sql_query("UPDATE torrents SET freefor=$fid WHERE id=$to") or sqlerr(__FILE__,__LINE__);
	else
	sql_query("UPDATE torrents SET freefor='{$freeres['freefor']},$fid' WHERE id=$to") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE users SET ratingsum=ratingsum-{$REL_CRON['rating_perdownload']} WHERE id={$CURUSER['id']}") or sqlerr(__FILE__,__LINE__);

}

else {
	if ($to>=$CURUSER[$type]) {  $REL_TPL->end_frame(); stdmsg($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_money_'.$type),'error'); $REL_TPL->stdfoot(); die(); }

	sql_query("UPDATE users SET $type=$type+$to WHERE id=$fid") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE users SET $type=$type-$to WHERE id={$CURUSER['id']}") or sqlerr(__FILE__,__LINE__);
}

sql_query("INSERT INTO presents (userid,presenter,type,msg) VALUES ($fid,{$CURUSER['id']},".sqlesc($type).",".sqlesc($reason).")");
write_sys_msg($fid,sprintf($REL_LANG->say_by_key_to($fid,'presented'),$curusername,sprintf($REL_LANG->say_by_key_to($fid,'presented_'.$type),(($type=='torrent')?"<a href=\"".$REL_SEO->make_link('details','id',$to)."\">{$freeres['name']}</a>":$to)),$CURUSER['id']),$REL_LANG->say_by_key_to($fid,'presents'));

stdmsg($REL_LANG->say_by_key('success'),sprintf($REL_LANG->say_by_key('you_success_presented'),$friendname));
$REL_TPL->stdfoot();

?>