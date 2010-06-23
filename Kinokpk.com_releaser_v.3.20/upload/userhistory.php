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
	global $REL_LANG, $REL_CONFIG, $cats, $REL_SEO;
	$ret = "<table class=main border=1 cellspacing=0 cellpadding=5>" . "<tr><td class=colhead align=left>" . $REL_LANG->say_by_key('type') . "</td><td class=colhead>" . $REL_LANG->say_by_key('name') . "</td>" . ($REL_CONFIG ['use_ttl'] ? "<td class=colhead align=center>" . $REL_LANG->say_by_key('ttl') . "</td>" : "") . "<td class=colhead align=center>" . $REL_LANG->say_by_key('size') . "</td><td class=colhead align=right>" . $REL_LANG->say_by_key('details_seeding') . "</td><td class=colhead align=right>" . $REL_LANG->say_by_key('details_leeching') . "</td></tr>\n";
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
 * Nice prints content with headers, footers, etc, thed dies
 * @param string $content Content to be printed
 * @return void
 */
function print_content($content) {
	global $REL_LANG, $userid, $USER, $type;
	stdhead($REL_LANG->say_by_key('history_'.$type));
	begin_frame($REL_LANG->say_by_key('history_'.$type).sprintf($REL_LANG->say_by_key('to_history'),$userid,$USER['username']));
	print ($content);
	end_frame();
	stdfoot();
	die;
}

require "include/bittorrent.php";

dbconn();

loggedinorreturn();

$userid = (int)$_GET["id"];

if (!$userid) $userid=$CURUSER['id'];
$REL_LANG->load('userhistory');
$REL_LANG->load('userdetails');

$page = (int)$_GET["page"];

$type = trim((string)$_GET["type"]);

//-------- Global variables

$perpage = $REL_CONFIG['torrentsperpage'];

$allowed_types = array ('comments', 'pollcomments', 'newscomments', 'usercomments', 'reqcomments', 'rgcomments','pagecomments',  'pages', 'friends','seeding','leeching','downloaded','uploaded','presents');

$USER = mysql_fetch_assoc(sql_query("SELECT username,class FROM users WHERE id=$userid"));

if (in_array($type,$allowed_types))
{
	if ($type=='uploaded') {
		$cats = assoc_cats ();
		$r = sql_query ( "SELECT torrents.id, torrents.name, SUM(trackers.seeders) AS seeders, torrents.added, SUM(trackers.leechers) AS leechers, torrents.category FROM torrents LEFT JOIN trackers ON torrents.id=trackers.torrent WHERE owner=$userid GROUP BY torrents.id ORDER BY name" ) or sqlerr ( __FILE__, __LINE__ );
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
		$r = sql_query ( "SELECT snatched.torrent AS id, snatched.startedat, snatched.completedat, torrents.name, SUM(trackers.seeders) AS seeders, SUM(trackers.leechers) AS leechers, torrents.category FROM snatched LEFT JOIN torrents ON torrents.id = snatched.torrent LEFT JOIN trackers ON snatched.torrent=trackers.torrent WHERE snatched.finished=1 AND snatched.userid = $userid AND torrents.owner<>$userid GROUP BY id ORDER BY id" ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $r )) {
			$completed = "<table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n" . "<tr><td class=\"colhead\">Тип</td><td class=\"colhead\">Название</td><td class=\"colhead\">Раздающих</td><td class=\"colhead\">Качающих</td><td class=\"colhead\">Начал / Закончил</td></tr>\n";
			while ( $a = mysql_fetch_array ( $r ) ) {
				$rescatids = explode ( ',', $a ['category'] );
				foreach ( $rescatids as $rescatid )
				$a ['cat_names'] [] = "<a href=\"".$REL_SEO->make_link('browse','cat',$rescatid)."\">" . $cats [$rescatid] . "</a>";
				$cat = implode ( ',<br />', $a ['cat_names'] );
				$completed .= "<tr><td style=\"padding: 0px\">$cat</td><td><nobr><a href=\"".$REL_SEO->make_link('details','id',$a["id"],'name',translit($a["name"]))."\"><b>" . $a ["name"] . "</b></a></nobr></td>" . "<td align=\"right\">$a[seeders]</td><td align=\"right\">$a[leechers]</td><td align=\"center\"><nobr>" . mkprettytime ( $a [startedat] ) . "<br />" . mkprettytime ( $a [completedat] ) . "</nobr></td></tr>\n";
				$hastorrents = true;
			}
			if ($hastorrents) $completed .= "</table>";
		}
		if (!$completed) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('nothing_found'));
		print_content($completed);
	}
	elseif ($type=='leeching') {
		$cats = assoc_cats ();
		$res = sql_query ( "SELECT peers.torrent, added, torrents.name AS torrentname, size, category, SUM(trackers.seeders) AS seeders, SUM(trackers.leechers) AS leechers FROM peers LEFT JOIN torrents ON peers.torrent = torrents.id LEFT JOIN trackers ON peers.torrent=trackers.torrent WHERE userid = $userid AND seeder=0 GROUP BY peers.torrent" ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $res ) > 0)
		$leeching = maketable ( $res );
		if (!$leeching) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('nothing_found'));
		print_content($leeching);
	}
	elseif ($type=='seeding') {
		$cats = assoc_cats ();
		$res = sql_query ( "SELECT peers.torrent, added, torrents.name AS torrentname, size, category, SUM(trackers.seeders) AS seeders, SUM(trackers.leechers) AS leechers FROM peers LEFT JOIN torrents ON peers.torrent = torrents.id LEFT JOIN trackers ON peers.torrent=trackers.torrent WHERE userid = $userid AND seeder=1 GROUP BY peers.torrent" ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $res ) > 0)
		$seeding = maketable ( $res );
		if (!$seeding) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('nothing_found'));
		print_content($seeding);
	}
	elseif ($type=='pages') {

		$cat = (int) $_GET['cat'];

		$tree = make_pages_tree(get_user_class());

		$searchstr = (string) $_GET['search'];
		$cleansearchstr = htmlspecialchars($searchstr);
		if (empty($cleansearchstr))
		unset($cleansearchstr);

		if (($cat!=0) && is_valid_id($cat)) {

			$cats = get_full_childs_ids($tree,$cat,'pagecategories');
			if (!$cats) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
			else {
				foreach ($cats as $catid) $catq[] = " FIND_IN_SET($catid,pages.category) ";

				if ($catq) $catq = implode('OR',$catq);

				$wherea['cat'] = $catq;
				$addparam .= "cat=$cat&";
			}
		}


		if ((get_user_class()>=UC_MODERATOR)) { $modview=true; }

		$wherea[] = "pages.class<=".get_user_class();

		if (isset($cleansearchstr))
		{
			$wherea['search'] = "pages.name LIKE '%" . sqlwildcardesc($searchstr) . "%' OR pages.tags LIKE '%" . sqlwildcardesc($searchstr) . "%'";
			$addparam .= "search=" . urlencode($searchstr) . "&amp;";

		}

		$wherea['owner'] = "owner=$userid";
		$wherea['class'] = "pages.class<=".get_user_class();

		if (is_array($wherea)) $where = implode(" AND ", $wherea);

		// CACHE SYSTEM REMOVED UNTIL 2.75

		$res = sql_query("SELECT SUM(1) FROM pages".($where?" WHERE $where":'')) or sqlerr(__FILE__,__LINE__);
		$row = mysql_fetch_array($res);
		$count = $row[0];


		list($pagertop, $pagerbottom, $limit) = pager($REL_CONFIG['torrentsperpage'], $count, $REL_SEO->make_link('userhistory','id',$userid,'type','pages').$addparam);

		$query = "SELECT pages.*, users.username, users.class FROM pages LEFT JOIN users ON pages.owner = users.id".($where?" WHERE $where":'')." ORDER BY pages.sticky DESC, pages.added DESC $limit";
		$res = sql_query($query) or sqlerr(__FILE__,__LINE__);

		while ($resvalue = mysql_fetch_array($res)) {
			$chsel = array();
			$cats = explode(',',$resvalue['category']);
			$catq= array_shift($cats);
			$catq = get_cur_branch($tree,$catq);
			//var_dump($cats);
			$childs = get_childs($tree,$catq['parent_id']);
			if ($childs) {
				foreach($childs as $child)
				if (($catq['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"".$REL_SEO->make_link('userhistory','id',$userid,'type','pages','cat',$child['id'],'name',translit(makesafe($child['name'])))."\">".makesafe($child['name'])."</a>";
				$resvalue['cat_names'] = get_cur_position_str($tree,$catq['id'],'pagebrowse').(is_array($chsel)?', '.implode(', ',$chsel):'');
				//$resarray[$resvalue['id']] = $resvalue;
			} else $resvalue['cat_names'] = get_cur_position_str($tree,$catq['id'],'pagebrowse');
			$resarray[$resvalue['id']] = $resvalue;
		}

		if (!$resarray) stderr($REL_LANG->say_by_key('error'),"Страниц не найдено. Но вы можете <a href=\"".$REL_SEO->make_link('pageupload')."\">создать страницу</a> или <a href=\"javascript: history.go(-1)\">вернуться Назад</a>");

		stdhead($REL_LANG->say_by_key('history_'.$type));
		begin_frame($REL_LANG->say_by_key('history_'.$type).sprintf($REL_LANG->say_by_key('to_history'),$userid,$USER['username']));

		print('
<table class="embedded" cellspacing="0" cellpadding="5" width="100%">
<tr><td class="colhead" align="center" colspan="10">Список страниц</td></tr>
<tr><td colspan="10">

<table class="embedded" align="center">');

		print('<tr><td align="center" class="embedded" colspan="2"><div class="friends_search" style="width: 290px;">');
		print("<form action=\"".$REL_SEO->make_link('userhistory')."\" method=\"get\" style=\"width: 286px;\">".gen_select_area('cat',$tree,$cat,true)."<input type=\"hidden\" name=\"type\" value=\"pages\"><input type=\"hidden\" name=\"id\" value=\"$userid\"><input type=\"submit\" class=\"button\" value=\"{$REL_LANG->say_by_key('search')}\"/></form>\n");
		print('	<form method="get" action="'.$REL_SEO->make_link('userhistory').'" style="width: 286px;">
<input type="hidden" name="type" value="pages"><input type="hidden" name="id" value="'.$userid.'">
		<input type="text" name="search" style="width: 224px;"/>
		'.$rgselect.'<input class="btn button" type="submit" value="'.$REL_LANG->say_by_key('search').'" />
		</form>
		</div></table>
		');
		if (isset($cleansearchstr))
		print("<tr><td class=\"index\" colspan=\"12\">".$REL_LANG->say_by_key('search_results_for')." \"" . $cleansearchstr . "\"</td></tr>\n");
		print("</td></tr></table>");


		print("<div id=\"releases-table\">");
		if ($count) {

			print("<table class=\"embedded\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\">");
			print("<tr><td class=\"index\" colspan=\"12\">");
			print($pagertop);
			print("</td></tr>");
			pagetable($resarray);
			print("<tr><td class=\"index\" colspan=\"12\">");
			print($pagerbottom);
			print("</td></tr>");
		}
		else {
			if (isset($cleansearchstr)) {
				print("<tr><td class=\"index\" colspan=\"12\">".$REL_LANG->say_by_key('nothing_found')."</td></tr>\n");
				//print("<p>Попробуйте изменить запрос поиска.</p>\n");
			}
			else {
				print("<tr><td class=\"index\" colspan=\"12\">".$REL_LANG->say_by_key('nothing_found')."</td></tr>\n");
				//print("<p>Извините, данная категория пустая.</p>\n");
			}
		}

		print("</table></div>");
		end_frame();
		stdfoot();
		die();
	}

	$leftjoin = array(
	'comments' => ' LEFT JOIN torrents ON comments.torrent=torrents.id',
	'reqcomments'=> ' LEFT JOIN requests ON reqcomments.request=requests.id',
	'pollcomments'=> ' LEFT JOIN polls ON pollcomments.poll=polls.id',
	'newscomments' => ' LEFT JOIN news ON newscomments.news=news.id',
	'rgcomments' => ' LEFT JOIN relgroups ON rgcomments.relgroup=relgroups.id',
	'pagecomments' => ' LEFT JOIN pages ON pagecomments.page=pages.id');
	$comment = array('comments' => 'torrent', 'pollcomments' => 'poll', 'newscomments' => 'news', 'usercomments' => 'userid', 'reqcomments' => 'request', 'rgcomments' => 'relgroup', 'pagecomments' => 'page');
	$name = array('comments' => 'torrents.id AS toid, torrents.name AS subject', 'pollcomments' => 'polls.id AS toid, polls.question AS subject', 'newscomments' => 'news.id AS toid, news.subject', 'usercomments' => 'users.id AS toid, (SELECT username FROM users WHERE users.id = usercomments.userid) AS subject', 'reqcomments' => 'requests.id AS toid, requests.request AS subject', 'rgcomments' => 'relgroups.id AS toid, relgroups.name AS subject', 'pagecomments' => 'pages.id AS toid, pages.name AS subject');
	$links = array('comments' => $REL_SEO->make_link('details','id',''), 'pollcomments' => $REL_SEO->make_link('polloverview','id',''), 'newscomments' => $REL_SEO->make_link('newsoverview','id',''), 'usercomments' => $REL_SEO->make_link('userdetails','id',''), 'reqcomments' => $REL_SEO->make_link('relgroups','id',''), 'pagecomments' => $REL_SEO->make_link('pagedetails','id',''));

	if ($type=='friends') {
		$where = "friends.confirmed=1 AND (userid=$userid OR friendid=$userid)";
		$order = "friends.id DESC";
	}
	else
	{
		if ($type<>'pages'&&$type<>'presents') $add_='user';
		elseif ($type=='pages') $add_='owner';
		elseif ($type=='presents') $add_='userid';
		$where = "$type.$add_ = $userid";
		if ($type<>'presents')
		$order = "$type.added DESC";
		else $order = "$type.id DESC";
	}

	$query = "SELECT SUM(1) FROM $type{$leftjoin[$type]} WHERE $where ORDER BY $order";

	$res = sql_query($query) or sqlerr(__FILE__, __LINE__);

	$arr = mysql_fetch_row($res) or stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('nothing_found'));

	$count = $arr[0];
	if (!$count) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('nothing_found'));

	//------ Make page menu

	list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $REL_SEO->make_link('userhistory','type',$type,'id',$userid)."&");

	//------ Get user data

	if ($type<>'friends'&&$type<>'presents') {
		stdhead();
		begin_frame($REL_LANG->say_by_key('history_'.$type).sprintf($REL_LANG->say_by_key('to_history'),$userid,$USER['username']));
		$query = "SELECT $type.id, $type.ip, $type.ratingsum, $type.text, $type.user, $type.added, $type.editedby, $type.editedat, users.avatar, users.warned, users.username, users.title, users.class, users.donor, users.enabled, users.ratingsum AS urating, users.gender, users.last_access, e.username AS editedbyname, $name[$type] FROM $type LEFT JOIN users ON $type.user = users.id LEFT JOIN users AS e ON $type.editedby = e.id{$leftjoin[$type]} WHERE $where ORDER BY $order $limit";
		$res = sql_query($query) or sqlerr(__FILE__,__LINE__);
		while ($row = mysql_fetch_assoc($res)) {
			$row['link'] = $links[$type].$row['toid']."#comm{$row['id']}";
			$commentsarray[] = $row;
		}
		print ( "<table id=\"comments-table\" class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >" );
		print ( "<tr><td class=\"colhead\" align=\"center\" >" );
		print ( "<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>" );
		print ( "<div align=\"right\"><a href=\"".$REL_SEO->make_link('details','id',$id)."#comments\" class=\"altlink_white\">{$REL_LANG->say_by_key('add_comment')}</a></div>" );
		print ( "</td></tr>" );

		print ( "<tr><td>" );
		print ( $pagertop );
		print ( "</td></tr>" );
		print ( "<tr><td>" );
		commenttable($commentsarray);
		print ( "</td></tr>" );
		print ( "<tr><td>" );
		print ( $pagerbottom );
		print ( "</td></tr>" );
		print ( "</table>" );
		end_frame();
		stdfoot();

	}
	elseif ($type=='presents') {
		$presentres = sql_query("SELECT presents.*,users.username,users.class FROM presents LEFT JOIN users ON users.id=presenter WHERE userid=$userid ORDER BY id DESC $limit");
		while ($prrow = mysql_fetch_assoc($presentres)) {
			$presents[] = $prrow;
		}
		stdhead($REL_LANG->_("History of user presents"));
		begin_frame($REL_LANG->_("History of user presents").sprintf($REL_LANG->say_by_key('to_history'),$userid,$USER['username']));
		$switch_pr = array('torrent'=>'Release','ratingsum'=>"Amount of rating",'discount'=>"Amount of discount");

		print ('<table width="100%"><tr>');
		print "<td colspan=\"3\">$pagertop</td></tr><tr>";
		$i=0;
		foreach ($presents as $present) {
			$i++;
			$prtext = ($present['msg']);
			print '<td align="center">'.$REL_LANG->_('Present type').': '.$REL_LANG->_($switch_pr[$present['type']]).'<br/><a href="'.$REL_SEO->make_link('present','a','viewpresent','id',$present['id']).'"><img style="border:none;" src="pic/presents/'.$present['type'].'_big.png" titie="'.$REL_LANG->_('Present').'"/></a><br/>'.$REL_LANG->_("With wish of").' "'.$prtext.($present['presenter']==$CURUSER['id']?"<br/>({$REL_LANG->_("Yours")})":'", '.$REL_LANG->_("From")." <a href=\"{$REL_SEO->make_link('userdetails','id',$present['presenter'],'username',$present['username'])}\">".get_user_class_color($present['class'],$present['username'])."</a>").'</td>';
			if (($i%3)==0) print '</tr><tr>';
		}
		if ($i%3!=0) print '<td colspan="'.(3-$i%3).'">&nbsp;</td></tr>';
		print "<tr><td colspan=\"3\">$pagerbottom</td></tr>";
		print ('</table>');
		end_frame();
		stdfoot();
		die();
	}
	elseif ($type=='friends') {
		stdhead($REL_LANG->say_by_key('history_friends').' '.$USER['username']);
		begin_frame($REL_LANG->say_by_key('history_friends').' '.$USER['username'].sprintf($REL_LANG->say_by_key('to_history'),$userid,$USER['username']));

		$res = sql_query("SELECT IF (friends.userid={$userid},friends.friendid,friends.userid) AS friend, (SELECT 1 FROM friends WHERE (userid=friend AND friendid={$CURUSER['id']}) OR (friendid=friend AND userid={$CURUSER['id']})) AS myfriend, friends.id, u.username,u.class,u.country,u.ratingsum,u.added,u.last_access,u.gender,u.donor, u.warned, u.confirmed, u.enabled, c.name, c.flagpic FROM friends LEFT JOIN users AS u ON IF (friends.userid={$userid},u.id=friendid,u.id=userid) LEFT JOIN countries AS c ON c.id = u.country WHERE $where ORDER BY friends.id DESC $limit") or sqlerr(__FILE__, __LINE__);

		print ('<div id="users-table">');
		print ("<p>$pagertop</p>");
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
		print ("<p>$pagerbottom</p>");
		print('</div>');
		end_frame();
		stdfoot();
		die();
	}
}
elseif (!isset($_GET['type'])) {
	stdhead($REL_LANG->say_by_key('select_history_type'));
	begin_frame($REL_LANG->say_by_key('select_history_type'));
	print("<table width=\"100%\" border=\"1\">");
	$i=0;
	print '<tr>';
	foreach ($allowed_types as $type) {
		$i++;


		print ("<td align=\"center\"><a href=\"".$REL_SEO->make_link('userhistory','id',$userid,'type',$type)."\">{$REL_LANG->say_by_key('history_'.$type)}</a></td>");
		if (($i%4)==0) print '</tr><tr>';
	}
	if ($i%4!=0) print '<td colspan="'.(4-$i%4).'">&nbsp;</td></tr>';
	print ("</table>");
	end_frame();
	stdfoot();
	die();
}
else stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_type'));
?>