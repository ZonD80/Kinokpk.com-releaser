<?php
/**
 * User history
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


/**
 * Nice formats data about torrents
 * @param array $res Array to be processed
 * @return string Formatted data
 */
function maketable($res) {
	global $REL_LANG, $REL_CONFIG, $cats, $REL_SEO, $disallow_view;
	if ($disallow_view&&get_privilege('view_private_user_profiles',false)) $ret.="<p>{$REL_LANG->_("You are viewing private profile as administration member")}</p>";
	$ret .= "<table class=main border=1 cellspacing=0 cellpadding=5>" . "<tr><td class=colhead align=left>" . $REL_LANG->say_by_key('type') . "</td><td class=colhead>" . $REL_LANG->say_by_key('name') . "</td>" . ($REL_CONFIG ['use_ttl'] ? "<td class=colhead align=center>" . $REL_LANG->say_by_key('ttl') . "</td>" : "") . "<td class=colhead align=center>" . $REL_LANG->say_by_key('size') . "</td><td class=colhead align=right>" . $REL_LANG->say_by_key('details_seeding') . "</td><td class=colhead align=right>" . $REL_LANG->say_by_key('details_leeching') . "</td></tr>\n";
	while ( $arr = mysql_fetch_assoc ( $res ) ) {
		$rescatids = explode ( ',', $arr ['category'] );
		foreach ( $rescatids as $rescatid )
		$arr ['cat_names'] [] = "<a href=\"".$REL_SEO->make_link('browse','cat',$rescatid)."\">" . $cats [$rescatid] . "</a>";

		$ttl = ($REL_CONFIG ['ttl_days'] * 24) - floor ( (time () - $arr ["added"]) / 3600 );
		if ($ttl == 1)
		$ttl .= "&nbsp;час";
		else
		$ttl .= "&nbsp;часов";
		$size = str_replace ( " ", "<br />", mksize ( $arr ["size"] ) );
		$seeders = number_format ( $arr ["seeders"] );
		$leechers = number_format ( $arr ["leechers"] );
		$ret .= "<tr><td style='padding: 0px'>" . implode ( ',<br />', $arr ['cat_names'] ) . "</td>\n" . "<td><a href=\"".$REL_SEO->make_link('details','id',$arr['torrent'],'name',translit($arr["torrentname"]))."\"><b>" . $arr ["torrentname"] . "</b></a></td>" . ($REL_CONFIG ['use_ttl'] ? "<td align=center>$ttl</td>" : "") . "<td align=center>$size</td><td align=right>$seeders</td><td align=right>$leechers</td></tr>\n";
	}
	$ret .= "</table>\n";
	return $ret;
}

/**
 * Nice prints content with headers, footers, etc, then dies
 * @param string $content Content to be printed
 * @return void
 */
function print_content($content) {
	global $REL_LANG, $id, $user, $type, $REL_TPL, $disallow_view;
	$REL_TPL->stdhead($REL_LANG->say_by_key('history_'.$type));
	if ($disallow_view&&get_privilege('view_private_user_profiles',false)) print"<p>{$REL_LANG->_("You are viewing private profile as administration member")}</p>";
	$REL_TPL->begin_frame($REL_LANG->say_by_key('history_'.$type).sprintf($REL_LANG->say_by_key('to_history'),$id,$user['username']));
	print ($content);
	$REL_TPL->end_frame();
	$REL_TPL->stdfoot();
	die;
}

require "include/bittorrent.php";

INIT();

loggedinorreturn();

$id = (int)$_GET["id"];

if (!$id) $id=$CURUSER['id'];



$page = (int)$_GET["page"];

$type = trim((string)$_GET["type"]);

//-------- Global variables

$perpage = $REL_CONFIG['torrentsperpage'];

$allowed_types = array ('relcomments', 'pollcomments', 'newscomments', 'usercomments', 'reqcomments', 'rgcomments', 'friends','seeding','leeching','downloaded','uploaded','presents','nicknames');

$user = mysql_fetch_assoc(sql_query("SELECT username,class,privacy FROM users WHERE id=$id"));

$am_i_friend = ($id==$CURUSER['id']?true:@mysql_result(sql_query("SELECT 1 FROM friends WHERE (userid={$CURUSER['id']} AND friendid=$id) OR (friendid={$CURUSER['id']} AND userid=$id) AND confirmed=1"),0));
$disallow_view = (($user['privacy']=='highest'||$user['privacy']=='strong')&&!$am_i_friend);
if ($disallow_view&&!get_privilege('view_private_user_profiles',false)) stderr($REL_LANG->_("Error"),$REL_LANG->_('This user uses privacy level, you need to <a href="%s">Become friend of %s</a> to view this page',$REL_SEO->make_link('friends','action','add','id',$id),get_user_class_color($user['class'],$user['username'])));

if (in_array($type,$allowed_types))
{
	if ($type=='uploaded') {
		$cats = assoc_cats ();
		$r = sql_query ( "SELECT torrents.id, torrents.name, torrents.seeders, torrents.added, torrents.leechers, torrents.category FROM torrents WHERE owner=$id ORDER BY id DESC" ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $r )) {
			$torrents = "<table class=main border=1 cellspacing=0 cellpadding=5>\n" . "<tr><td class=colhead>" . $REL_LANG->say_by_key('type') . "</td><td class=colhead>" . $REL_LANG->say_by_key('name') . "</td>" . ($REL_CONFIG ['use_ttl'] ? "<td class=colhead align=center>" . $REL_LANG->say_by_key('ttl') . "</td>" : "") . "<td class=colhead>" . $REL_LANG->say_by_key('tracker_seeders') . "</td><td class=colhead>" . $REL_LANG->say_by_key('tracker_leechers') . "</td></tr>\n";
			while ( $a = mysql_fetch_assoc ( $r ) ) {
				$ttl = ($REL_CONFIG ['ttl_days'] * 24) - floor ( (time () - $a ["added"]) / 3600 );
				if ($ttl == 1)
				$ttl .= "&nbsp;час";
				else
				$ttl .= "&nbsp;часов";
				$rescatids = explode ( ',', $a ['category'] );
				foreach ( $rescatids as $rescatid )
				$a ['cat_names'] [] = "<a href=\"".$REL_SEO->make_link('browse','cat',$rescatid,'name',translit($cats[$rescatid]))."\">" . $cats [$rescatid] . "</a>";

				$cat = implode ( ',<br />', $a ['cat_names'] );
				$torrents .= "<tr><td style='padding: 0px'>$cat</td><td><a href=\"".$REL_SEO->make_link('details','id',$a["id"],'name',translit($a["name"]))."\"><b>" . $a ["name"] . "</b></a></td>" . ($REL_CONFIG ['use_ttl'] ? "<td align=center>$ttl</td>" : "") . "<td align=right>$a[seeders]</td><td align=right>$a[leechers]</td></tr>\n";
				$hastorrents = true;
			}
			if ($hastorrents) $torrents .= "</table>";
		}
		if (!$torrents) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('nothing_found'));
		print_content($torrents);
	}
	elseif ($type=='downloaded') {
		$cats = assoc_cats ();
		$r = sql_query ( "SELECT snatched.torrent AS id, snatched.completedat, torrents.name, torrents.seeders, torrents.leechers, torrents.category FROM snatched LEFT JOIN torrents ON torrents.id = snatched.torrent WHERE snatched.userid = $id AND torrents.owner<>$id GROUP BY id ORDER BY id" ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $r )) {
			$completed = "<table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n" . "<tr><td class=\"colhead\">Тип</td><td class=\"colhead\">Название</td><td class=\"colhead\">Раздающих</td><td class=\"colhead\">Качающих</td><td class=\"colhead\">{$REL_LANG->_('Date')}</td></tr>\n";
			if ($id==$CURUSER['id']) $completed.= ('<tr><td align="center" colspan="5">'.$REL_LANG->_('You can download all previous releases in one ZIP-archive without rating decrease<br/><a href="%s">View downloaded releases</a> or <a href="%s">Download ZIP-archive with torrents</a>',$REL_SEO->make_link('userhistory','id',$id,'type','downloaded'),$REL_SEO->make_link('download','a','my')).'</td></tr>');
			while ( $a = mysql_fetch_array ( $r ) ) {
				$rescatids = explode ( ',', $a ['category'] );
				foreach ( $rescatids as $rescatid )
				$a ['cat_names'] [] = "<a href=\"".$REL_SEO->make_link('browse','cat',$rescatid)."\">" . $cats [$rescatid] . "</a>";
				$cat = implode ( ',<br />', $a ['cat_names'] );
				$completed .= "<tr><td style=\"padding: 0px\">$cat</td><td><nobr><a href=\"".$REL_SEO->make_link('details','id',$a["id"],'name',translit($a["name"]))."\"><b>" . $a ["name"] . "</b></a></nobr></td>" . "<td align=\"right\">$a[seeders]</td><td align=\"right\">$a[leechers]</td><td align=\"center\">" . mkprettytime ( $a [completedat] ) . "</td></tr>\n";
				$hastorrents = true;
			}
			if ($hastorrents) $completed .= "</table>";
		}
		if (!$completed) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('nothing_found'));
		print_content($completed);
	}
	elseif ($type=='leeching') {
		$cats = assoc_cats ();
		$res = sql_query ( "SELECT peers.torrent, added, torrents.name AS torrentname, size, category, torrents.seeders, torrents.leechers FROM peers LEFT JOIN torrents ON peers.torrent = torrents.id WHERE userid = $id AND seeder=0 GROUP BY peers.torrent" ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $res ) > 0)
		$leeching = maketable ( $res );
		if (!$leeching) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('nothing_found'));
		print_content($leeching);
	}
	elseif ($type=='seeding') {
		$cats = assoc_cats ();
		$res = sql_query ( "SELECT peers.torrent, added, torrents.name AS torrentname, size, category, torrents.seeders, torrents.leechers FROM peers LEFT JOIN torrents ON peers.torrent = torrents.id WHERE userid = $id AND seeder=1 GROUP BY peers.torrent" ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $res ) > 0)
		$seeding = maketable ( $res );
		if (!$seeding) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('nothing_found'));
		print_content($seeding);
	}

	$leftjoin = array(
	'relcomments' => ' LEFT JOIN torrents ON comments.toid=torrents.id',
	'reqcomments'=> ' LEFT JOIN requests ON comments.toid=requests.id',
	'pollcomments'=> ' LEFT JOIN polls ON comments.toid=polls.id',
	'newscomments' => ' LEFT JOIN news ON comments.toid=news.id',
	'rgcomments' => ' LEFT JOIN relgroups ON comments.toid=relgroups.id');
	$name = array('relcomments' => 'torrents.id AS toid, torrents.name AS subject', 'pollcomments' => 'polls.id AS toid, polls.question AS subject', 'newscomments' => 'news.id AS toid, news.subject', 'usercomments' => 'users.id AS toid, (SELECT username FROM users WHERE users.id = comments.toid AND comments.type=\'user\') AS subject', 'reqcomments' => 'requests.id AS toid, requests.request AS subject', 'rgcomments' => 'relgroups.id AS toid, relgroups.name AS subject');
	$links = array('relcomments' => $REL_SEO->make_link('details','id',''), 'pollcomments' => $REL_SEO->make_link('polloverview','id',''), 'newscomments' => $REL_SEO->make_link('newsoverview','id',''), 'usercomments' => $REL_SEO->make_link('userdetails','id',''), 'reqcomments' => $REL_SEO->make_link('relgroups','id',''));

	if ($type=='friends') {
		$where = "friends.confirmed=1 AND (userid=$id OR friendid=$id)";
		$order = "friends.id DESC";
	}
	elseif ($type=='nicknames') {
		$where = "userid = $id";
		$order = "date DESC";
	}
	else
	{
		if (preg_match('/comments/',$type)) $where = "comments.user= $id AND comments.type='".str_replace('comments','',$type)."'";
		elseif ($type=='presents') $where = "$type.userid = $id";
		if ($type<>'presents')
		$order = "comments.added DESC";
		else $order = "$type.id DESC";
	}
	if ($type=='presents'||$type=='friends') $from_select = $type;
	elseif ($type=='nicknames') $from_select = 'nickhistory';
	else $from_select = 'comments';
	$query = "SELECT SUM(1) FROM ".$from_select."{$leftjoin[$type]} WHERE $where ORDER BY $order";

	$res = sql_query($query) or sqlerr(__FILE__, __LINE__);

	$arr = mysql_fetch_row($res) or stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('nothing_found'));

	$count = $arr[0];
	if (!$count) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('nothing_found'));

	$limit = "LIMIT 50";

	if ($type<>'friends'&&$type<>'presents'&&$type<>'nicknames') {
		if (!pagercheck()) {
			$REL_TPL->stdhead($REL_LANG->say_by_key('history_'.$type)." {$REL_LANG->_("From")} {$user['username']}");
			if ($disallow_view&&get_privilege('view_private_user_profiles',false)) print"<p>{$REL_LANG->_("You are viewing private profile as administration member")}</p>";
		}
		$REL_TPL->begin_frame($REL_LANG->say_by_key('history_'.$type).sprintf($REL_LANG->say_by_key('to_history'),$id,$user['username']));


		$limit = ajaxpager(25, $count, array('userhistory','id',$id,'name',$user['username'],'type',$type), 'comments-table > tbody:last');
		$query = "SELECT comments.id, comments.toid, comments.type, comments.ip, comments.ratingsum, comments.text, comments.user, comments.added, comments.editedby, comments.editedat, users.avatar, users.warned, users.username, users.title, users.class, users.donor, users.enabled, users.ratingsum AS urating, users.gender, users.last_access, e.username AS editedbyname, $name[$type] FROM comments LEFT JOIN users ON comments.user = users.id LEFT JOIN users AS e ON comments.editedby = e.id{$leftjoin[$type]} WHERE $where ORDER BY $order $limit";
		$res = sql_query($query) or sqlerr(__FILE__,__LINE__);
		$commentsarray = prepare_for_commenttable($res);
		if (!pagercheck()) {
			print ( "<div id=\"pager_scrollbox\"><table id=\"comments-table\" class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >" );
			print ( "<tr><td class=\"colhead\" align=\"center\" >" );
			print ( "<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>" );
			//print ( "<div align=\"right\"><a href=\"".$REL_SEO->make_link('details','id',$id)."#comments\" class=\"altlink_white\">{$REL_LANG->say_by_key('add_comment')}</a></div>" );
			print ( "</td></tr>" );
			print ( "<tr><td>" );
			commenttable($commentsarray);
			print ( "</td></tr>" );
			print ( "</table></div>" );
		} else {
			print ( "<tr><td>" );
			commenttable($commentsarray);
			print ( "</td></tr>" );
			die();
		}
		$REL_TPL->end_frame();
		$REL_TPL->stdfoot();

	}
	elseif ($type=='presents') {
		$presentres = sql_query("SELECT presents.*,users.username,users.class FROM presents LEFT JOIN users ON users.id=presenter WHERE userid=$id ORDER BY id DESC $limit");
		while ($prrow = mysql_fetch_assoc($presentres)) {
			$presents[] = $prrow;
		}
		$REL_TPL->stdhead($REL_LANG->_("History of user presents"));
		if ($disallow_view&&get_privilege('view_private_user_profiles',false)) print"<p>{$REL_LANG->_("You are viewing private profile as administration member")}</p>";

		$REL_TPL->begin_frame($REL_LANG->_("History of user presents").sprintf($REL_LANG->say_by_key('to_history'),$id,$user['username']));
		$switch_pr = array('torrent'=>'Release','ratingsum'=>"Amount of rating",'discount'=>"Amount of discount");

		print ('<table width="100%"><tr>');

		$i=0;
		foreach ($presents as $present) {
			$i++;
			$prtext = ($present['msg']);
			print '<td align="center">'.$REL_LANG->_('Present type').': '.$REL_LANG->_($switch_pr[$present['type']]).'<br/><a href="'.$REL_SEO->make_link('present','a','viewpresent','id',$present['id']).'"><img style="border:none;" src="pic/presents/'.$present['type'].'_big.png" titie="'.$REL_LANG->_('Present').'"/></a><br/>'.$REL_LANG->_("With wish of").' "'.$prtext.($present['presenter']==$CURUSER['id']?"<br/>({$REL_LANG->_("Yours")})":'", '.$REL_LANG->_("From")." <a href=\"{$REL_SEO->make_link('userdetails','id',$present['presenter'],'username',$present['username'])}\">".get_user_class_color($present['class'],$present['username'])."</a>").'</td>';
			if (($i%3)==0) print '</tr><tr>';
		}
		if ($i%3!=0) print '<td colspan="'.(3-$i%3).'">&nbsp;</td></tr>';
		print ('</table>');
		$REL_TPL->end_frame();
		$REL_TPL->stdfoot();
		die();
	}
	elseif ($type=='friends') {
		$REL_TPL->stdhead($REL_LANG->say_by_key('history_friends').' '.$user['username']);
		if ($disallow_view&&get_privilege('view_private_user_profiles',false)) print"<p>{$REL_LANG->_("You are viewing private profile as administration member")}</p>";

		$REL_TPL->begin_frame($REL_LANG->say_by_key('history_friends').' '.$user['username'].sprintf($REL_LANG->say_by_key('to_history'),$id,$user['username']));

		$res = sql_query("SELECT IF (friends.userid={$id},friends.friendid,friends.userid) AS friend, (SELECT 1 FROM friends WHERE (userid=friend AND friendid={$CURUSER['id']}) OR (friendid=friend AND userid={$CURUSER['id']})) AS myfriend, friends.id, u.username,u.class,u.country,u.ratingsum,u.added,u.last_access,u.gender,u.donor, u.warned, u.confirmed, u.enabled, c.name, c.flagpic FROM friends LEFT JOIN users AS u ON IF (friends.userid={$id},u.id=friendid,u.id=userid) LEFT JOIN countries AS c ON c.id = u.country WHERE $where ORDER BY friends.id DESC $limit") or sqlerr(__FILE__, __LINE__);

		print ('<div id="users-table">');
		print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
		print("<tr><td class=\"colhead\" align=\"left\">Имя</td><td class=\"colhead\">Зарегестрирован</td><td class=\"colhead\">Последний вход</td><td class=\"colhead\">Рейтинг</td><td class=\"colhead\">Пол</td><td class=\"colhead\" align=\"left\">Уровень</td><td class=\"colhead\">Страна</td><td class=\"colhead\">Добавить в друзья</td></tr>\n");
		while ($arr = mysql_fetch_assoc($res)) {
			if ($arr['country'] > 0) {
				$country = "<td style=\"padding: 0px\" align=\"center\"><img src=\"pic/flag/$arr[flagpic]\" alt=\"$arr[name]\" title=\"$arr[name]\"></td>";
			}
			else
			$country = "<td align=\"center\">---</td>";
			$ratingsum = ratearea($arr['ratingsum'],$arr['friend'],'users', $CURUSER['id']);


			if ($arr["gender"] == "1") $gender = "<img src=\"pic/male.gif\" alt=\"Парень\" title=\"Парень\" style=\"margin-left: 4pt\">";
			elseif ($arr["gender"] == "2") $gender = "<img src=\"pic/female.gif\" alt=\"Девушка\" title=\"Девушка\" style=\"margin-left: 4pt\">";
			else $gender = "<div align=\"center\"><b>?</b></div>";

			print("<tr><td align=\"left\"><a href=\"".$REL_SEO->make_link('userdetails','id',$arr['friend'],'username',translit($arr["username"]))."\"><b>".get_user_class_color($arr["class"], $arr["username"])."</b></a>" .get_user_icons($arr).($arr['init']?$REL_LANG->say_by_key('init'):'')."</td>" .
"<td>".mkprettytime($arr['added'])."</td><td>".mkprettytime($arr['last_access'])."</td><td>$ratingsum</td><td>$gender</td>".
"<td align=\"left\">" . get_user_class_name($arr["class"]) . "</td>$country<td>\n");

			if (!$arr['myfriend']) print('<a href="'.$REL_SEO->make_link('friends','action','add','id',$arr['friend']).'">'.$REL_LANG->say_by_key('add_to_frends').'</a>'); else print($REL_LANG->say_by_key('no'));
			print ('</td></tr>');
		}
		print("</table>\n");
		print('</div>');
		$REL_TPL->end_frame();
		$REL_TPL->stdfoot();
		die();
	}

	elseif ($type=='nicknames') {
		$nicknames = $REL_DB->query_return("SELECT id,nick,date FROM nickhistory WHERE userid=$id");
		if (!$nicknames) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('This user does not have any nickname changes yet. <a href="%s">Go back to user history</a>',$REL_SEO->make_link('userhistory','id',$id,'name',$user['username'])));

		$REL_TPL->stdhead($REL_LANG->_('History of nickname changes for %s',$user['username']));
		$REL_TPL->begin_frame($REL_LANG->_('History of nickname changes for %s',$user['username']));
		$REL_TPL->assignByRef('nick', $nicknames);
		$REL_TPL->assign('IS_MODERATOR',get_privilege('is_moderator',false));
		$REL_TPL->output('nicknames');
		$REL_TPL->end_frame();
		$REL_TPL->stdfoot();
	}
}
elseif (!isset($_GET['type'])) {
	$REL_TPL->stdhead($REL_LANG->say_by_key('select_history_type'));
	$REL_TPL->begin_frame($REL_LANG->say_by_key('select_history_type'));
	print("<table width=\"100%\" border=\"1\">");
	$i=0;
	print '<tr>';
	foreach ($allowed_types as $type) {
		$i++;


		print ("<td align=\"center\"><a href=\"".$REL_SEO->make_link('userhistory','id',$id,'type',$type)."\">{$REL_LANG->say_by_key('history_'.$type)}</a></td>");
		if (($i%4)==0) print '</tr><tr>';
	}
	if ($i%4!=0) print '<td colspan="'.(4-$i%4).'">&nbsp;</td></tr>';
	print ("</table>");
	$REL_TPL->end_frame();
	$REL_TPL->stdfoot();
	die();
}
else stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_type'));
?>