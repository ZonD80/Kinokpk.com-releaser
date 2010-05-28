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
	global $tracker_lang, $CACHEARRAY, $cats;
	$ret = "<table class=main border=1 cellspacing=0 cellpadding=5>" . "<tr><td class=colhead align=left>" . $tracker_lang ['type'] . "</td><td class=colhead>" . $tracker_lang ['name'] . "</td>" . ($CACHEARRAY ['use_ttl'] ? "<td class=colhead align=center>" . $tracker_lang ['ttl'] . "</td>" : "") . "<td class=colhead align=center>" . $tracker_lang ['size'] . "</td><td class=colhead align=right>" . $tracker_lang ['details_seeding'] . "</td><td class=colhead align=right>" . $tracker_lang ['details_leeching'] . "</td><td class=colhead align=center>" . $tracker_lang ['uploaded'] . "</td>\n" . "<td class=colhead align=center>" . $tracker_lang ['downloaded'] . "</td><td class=colhead align=center>" . $tracker_lang ['ratio'] . "</td></tr>\n";
	while ( $arr = mysql_fetch_assoc ( $res ) ) {
		if ($arr ["downloaded"] > 0) {
			$ratio = number_format ( $arr ["uploaded"] / $arr ["downloaded"], 3 );
			$ratio = "<font color=" . get_ratio_color ( $ratio ) . ">$ratio</font>";
		} else if ($arr ["uploaded"] > 0)
		$ratio = "Inf.";
		else
		$ratio = "---";
		$rescatids = explode ( ',', $arr ['category'] );
		foreach ( $rescatids as $rescatid )
		$arr ['cat_names'] [] = "<a href=\"browse.php?cat={$rescatid}\">" . $cats [$rescatid] . "</a>";

		$ttl = ($CACHEARRAY ['ttl_days'] * 24) - floor ( (time () - $arr ["added"]) / 3600 );
		if ($ttl == 1)
		$ttl .= "&nbsp;час";
		else
		$ttl .= "&nbsp;часов";
		$size = str_replace ( " ", "<br />", mksize ( $arr ["size"] ) );
		$uploaded = str_replace ( " ", "<br />", mksize ( $arr ["uploaded"] ) );
		$downloaded = str_replace ( " ", "<br />", mksize ( $arr ["downloaded"] ) );
		$seeders = number_format ( $arr ["seeders"] );
		$leechers = number_format ( $arr ["leechers"] );
		$ret .= "<tr><td style='padding: 0px'>" . implode ( ',<br />', $arr ['cat_names'] ) . "</td>\n" . "<td><a href=details.php?id=$arr[torrent]><b>" . $arr ["torrentname"] . "</b></a></td>" . ($CACHEARRAY ['use_ttl'] ? "<td align=center>$ttl</td>" : "") . "<td align=center>$size</td><td align=right>$seeders</td><td align=right>$leechers</td><td align=center>$uploaded</td>\n" . "<td align=center>$downloaded</td><td align=center>$ratio</td></tr>\n";
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
	global $tracker_lang, $userid, $USER, $type;
	stdhead($tracker_lang['history_'.$type]);
	begin_frame($tracker_lang['history_'.$type].sprintf($tracker_lang['to_history'],$userid,$USER['username']));
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
getlang('userhistory');

$page = (int)$_GET["page"];

$type = trim((string)$_GET["type"]);

//-------- Global variables

$perpage = $CACHEARRAY['torrentsperpage'];

$allowed_types = array ('comments', 'pollcomments', 'newscomments', 'usercomments', 'reqcomments', 'rgcomments','pagecomments',  'pages', 'friends','seeding','leeching','downloaded','uploaded');

$USER = mysql_fetch_assoc(sql_query("SELECT username,class FROM users WHERE id=$userid"));

if (in_array($type,$allowed_types))
{
	if ($type=='uploaded') {
		$cats = assoc_cats ();
		$r = sql_query ( "SELECT torrents.id, torrents.name, SUM(trackers.seeders) AS seeders, torrents.added, SUM(trackers.leechers) AS leechers, torrents.category FROM torrents LEFT JOIN trackers ON torrents.id=trackers.torrent WHERE owner=$userid GROUP BY torrents.id ORDER BY name" ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $r )) {
			$torrents = "<table class=main border=1 cellspacing=0 cellpadding=5>\n" . "<tr><td class=colhead>" . $tracker_lang ['type'] . "</td><td class=colhead>" . $tracker_lang ['name'] . "</td>" . ($CACHEARRAY ['use_ttl'] ? "<td class=colhead align=center>" . $tracker_lang ['ttl'] . "</td>" : "") . "<td class=colhead>" . $tracker_lang ['tracker_seeders'] . "</td><td class=colhead>" . $tracker_lang ['tracker_leechers'] . "</td></tr>\n";
			while ( $a = mysql_fetch_assoc ( $r ) ) {
				$ttl = ($CACHEARRAY ['ttl_days'] * 24) - floor ( (time () - $a ["added"]) / 3600 );
				if ($ttl == 1)
				$ttl .= "&nbsp;час";
				else
				$ttl .= "&nbsp;часов";
				$rescatids = explode ( ',', $a ['category'] );
				foreach ( $rescatids as $rescatid )
				$a ['cat_names'] [] = "<a href=\"browse.php?cat={$rescatid}\">" . $cats [$rescatid] . "</a>";

				$cat = implode ( ',<br />', $a ['cat_names'] );
				$torrents .= "<tr><td style='padding: 0px'>$cat</td><td><a href=\"details.php?id=" . $a ["id"] . "\"><b>" . $a ["name"] . "</b></a></td>" . ($CACHEARRAY ['use_ttl'] ? "<td align=center>$ttl</td>" : "") . "<td align=right>$a[seeders]</td><td align=right>$a[leechers]</td></tr>\n";
				$hastorrents = true;
			}
			if ($hastorrents) $torrents .= "</table>";
		}
		if (!$torrents) stderr($tracker_lang['error'],$tracker_lang['nothing_found']);
		print_content($torrents);
	}
	elseif ($type=='downloaded') {
		$cats = assoc_cats ();
		$r = sql_query ( "SELECT snatched.torrent AS id, snatched.uploaded, snatched.downloaded, snatched.startedat, snatched.completedat, torrents.name, SUM(trackers.seeders) AS seeders, SUM(trackers.leechers) AS leechers, torrents.category FROM snatched LEFT JOIN torrents ON torrents.id = snatched.torrent LEFT JOIN trackers ON snatched.torrent=trackers.torrent WHERE snatched.finished=1 AND snatched.userid = $userid AND torrents.owner<>$userid GROUP BY id ORDER BY id" ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $r )) {
			$completed = "<table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n" . "<tr><td class=\"colhead\">Тип</td><td class=\"colhead\">Название</td><td class=\"colhead\">Раздающих</td><td class=\"colhead\">Качающих</td><td class=\"colhead\">Раздал</td><td class=\"colhead\">Скачал</td><td class=\"colhead\">Рейтинг</td><td class=\"colhead\">Начал / Закончил</td></tr>\n";
			while ( $a = mysql_fetch_array ( $r ) ) {
				$rescatids = explode ( ',', $a ['category'] );
				foreach ( $rescatids as $rescatid )
				$a ['cat_names'] [] = "<a href=\"browse.php?cat={$rescatid}\">" . $cats [$rescatid] . "</a>";
				if ($a ["downloaded"] > 0) {
					$ratio = number_format ( $a ["uploaded"] / $a ["downloaded"], 3 );
					$ratio = "<font color=\"" . get_ratio_color ( $ratio ) . "\">$ratio</font>";
				} else if ($a ["uploaded"] > 0)
				$ratio = "Inf.";
				else
				$ratio = "---";
				$uploaded = mksize ( $a ["uploaded"] );
				$downloaded = mksize ( $a ["downloaded"] );
				$cat = implode ( ',<br />', $a ['cat_names'] );
				$completed .= "<tr><td style=\"padding: 0px\">$cat</td><td><nobr><a href=\"details.php?id=" . $a ["id"] . "\"><b>" . $a ["name"] . "</b></a></nobr></td>" . "<td align=\"right\">$a[seeders]</td><td align=\"right\">$a[leechers]</td><td align=\"right\">$uploaded</td><td align=\"right\">$downloaded</td><td align=\"center\">$ratio</td><td align=\"center\"><nobr>" . mkprettytime ( $a [startedat] ) . "<br />" . mkprettytime ( $a [completedat] ) . "</nobr></td></tr>\n";
				$hastorrents = true;
			}
			if ($hastorrents) $completed .= "</table>";
		}
		if (!$completed) stderr($tracker_lang['error'],$tracker_lang['nothing_found']);
		print_content($completed);
	}
	elseif ($type=='leeching') {
		$cats = assoc_cats ();
		$res = sql_query ( "SELECT peers.torrent, added, uploaded, downloaded, torrents.name AS torrentname, size, category, SUM(trackers.seeders) AS seeders, SUM(trackers.leechers) AS leechers FROM peers LEFT JOIN torrents ON peers.torrent = torrents.id LEFT JOIN trackers ON peers.torrent=trackers.torrent WHERE userid = $userid AND seeder=0 GROUP BY peers.torrent" ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $res ) > 0)
		$leeching = maketable ( $res );
		if (!$leeching) stderr($tracker_lang['error'],$tracker_lang['nothing_found']);
		print_content($leeching);
	}
	elseif ($type=='seeding') {
		$cats = assoc_cats ();
		$res = sql_query ( "SELECT peers.torrent, added, uploaded, downloaded, torrents.name AS torrentname, size, category, SUM(trackers.seeders) AS seeders, SUM(trackers.leechers) AS leechers FROM peers LEFT JOIN torrents ON peers.torrent = torrents.id LEFT JOIN trackers ON peers.torrent=trackers.torrent WHERE userid = $userid AND seeder=1 GROUP BY peers.torrent" ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $res ) > 0)
		$seeding = maketable ( $res );
		if (!$seeding) stderr($tracker_lang['error'],$tracker_lang['nothing_found']);
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
			if (!$cats) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
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


		list($pagertop, $pagerbottom, $limit) = pager($CACHEARRAY['torrentsperpage'], $count, "userhistory.php?id=$userid&type=pages?".$addparam);

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
				if (($catq['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"userhistory.php?id=$userid&type=pages?cat={$child['id']}\">".makesafe($child['name'])."</a>";
				$resvalue['cat_names'] = get_cur_position_str($tree,$catq['id'],'pagebrowse').(is_array($chsel)?', '.implode(', ',$chsel):'');
				//$resarray[$resvalue['id']] = $resvalue;
			} else $resvalue['cat_names'] = get_cur_position_str($tree,$catq['id'],'pagebrowse');
			$resarray[$resvalue['id']] = $resvalue;
		}

		if (!$resarray) stderr($tracker_lang['error'],"Страниц не найдено. Но вы можете <a href=\"pageupload.php\">создать страницу</a> или <a href=\"javascript: history.go(-1)\">вернуться Назад</a>");

		stdhead($tracker_lang['history_'.$type]);
		begin_frame($tracker_lang['history_'.$type].sprintf($tracker_lang['to_history'],$userid,$USER['username']));

		print('
<table class="embedded" cellspacing="0" cellpadding="5" width="100%">
<tr><td class="colhead" align="center" colspan="10">Список страниц</td></tr>
<tr><td colspan="10">

<table class="embedded" align="center">');

		print('<tr><td align="center" class="embedded" colspan="2"><div class="friends_search" style="width: 290px;">');
		print("<form action=\"userhistory.php\" method=\"get\" style=\"width: 286px;\">".gen_select_area('cat',$tree,$cat,true)."<input type=\"hidden\" name=\"type\" value=\"pages\"><input type=\"hidden\" name=\"id\" value=\"$userid\"><input type=\"submit\" class=\"button\" value=\"{$tracker_lang['search']}\"/></form>\n");
		print('	<form method="get" action="userhistory.php" style="width: 286px;">
<input type="hidden" name="type" value="pages"><input type="hidden" name="id" value="'.$userid.'">
		<input type="text" name="search" style="width: 224px;"/>
		'.$rgselect.'<input class="btn button" type="submit" value="'.$tracker_lang['search'].'" />
		</form>
		</div></table>
		');
		if (isset($cleansearchstr))
		print("<tr><td class=\"index\" colspan=\"12\">".$tracker_lang['search_results_for']." \"" . $cleansearchstr . "\"</td></tr>\n");
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
				print("<tr><td class=\"index\" colspan=\"12\">".$tracker_lang['nothing_found']."</td></tr>\n");
				//print("<p>Попробуйте изменить запрос поиска.</p>\n");
			}
			else {
				print("<tr><td class=\"index\" colspan=\"12\">".$tracker_lang['nothing_found']."</td></tr>\n");
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
	$links = array('comments' => 'details.php?id=', 'pollcomments' => 'polloverview.php?id=', 'newscomments' => 'newsoverview.php?id=', 'usercomments' => 'userdetails.php?id=', 'reqcomments' => 'requests.php?id=', 'rgcomments' => 'relgroups.php?id=', 'pagecomments' => 'pagedetails.php?id=');

	if ($type=='friends') {
		$where = "friends.confirmed=1 AND (userid=$userid OR friendid=$userid)";
		$order = "friends.id DESC";
	}
	else
	{
		$where = "$type.".($type!='pages'?'user':'owner')." = $userid";
		$order = "$type.added DESC";
	}

	$query = "SELECT SUM(1) FROM $type{$leftjoin[$type]} WHERE $where ORDER BY $order";

	$res = sql_query($query) or sqlerr(__FILE__, __LINE__);

	$arr = mysql_fetch_row($res) or stderr($tracker_lang['error'], $tracker_lang['nothing_found']);

	$count = $arr[0];
	if (!$count) stderr($tracker_lang['error'],$tracker_lang['nothing_found']);

	//------ Make page menu

	list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "userhistory.php?type=$type&amp;id=$userid&");

	//------ Get user data

	if ($type<>'friends') {
		stdhead();
		begin_frame($tracker_lang['history_'.$type].sprintf($tracker_lang['to_history'],$userid,$USER['username']));
		$query = "SELECT $type.id, $type.ip, $type.ratingsum, $type.text, $type.user, $type.added, $type.editedby, $type.editedat, users.avatar, users.warned, users.username, users.title, users.class, users.donor, users.enabled, users.ratingsum AS urating, users.gender, users.last_access, e.username AS editedbyname, $name[$type] FROM $type LEFT JOIN users ON $type.user = users.id LEFT JOIN users AS e ON $type.editedby = e.id{$leftjoin[$type]} WHERE $where ORDER BY $order $limit";
		$res = sql_query($query) or sqlerr(__FILE__,__LINE__);
		while ($row = mysql_fetch_assoc($res)) {
			$row['link'] = $links[$type].$row['toid']."#comm{$row['id']}";
			$commentsarray[] = $row;
		}
		print ( "<table id=\"comments-table\" class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >" );
		print ( "<tr><td class=\"colhead\" align=\"center\" >" );
		print ( "<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>" );
		print ( "<div align=\"right\"><a href=\"details.php?id=$id#comments\" class=\"altlink_white\">{$tracker_lang['add_comment']}</a></div>" );
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
	elseif ($type=='friends') {
		stdhead($tracker_lang['history_friends'].' '.$USER['username']);
		begin_frame($tracker_lang['history_friends'].' '.$USER['username'].sprintf($tracker_lang['to_history'],$userid,$USER['username']));

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

			print("<tr><td align=\"left\"><a href=\"userdetails.php?id=$arr[friend]\"><b>".get_user_class_color($arr["class"], $arr["username"])."</b></a>" .get_user_icons($arr).($arr['init']?$tracker_lang['init']:'')."</td>" .
"<td>".mkprettytime($arr['added'])."</td><td>".mkprettytime($arr['last_access'])."</td><td>$ratingsum</td><td>$gender</td>".
"<td align=\"left\">" . get_user_class_name($arr["class"]) . "</td>$country<td>\n");

			if (!$arr['myfriend']) print('<a href="friends.php?action=add&amp;id='.$arr['friend'].'">'.$tracker_lang['add_to_frends'].'</a>'); else print($tracker_lang['no']);
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
	stdhead($tracker_lang['select_history_type']);
	begin_frame($tracker_lang['select_history_type']);
	print("<table width=\"100%\" border=\"1\">");
	$i=0;
	print '<tr>';
	foreach ($allowed_types as $type) {
		$i++;


		print ("<td align=\"center\"><a href=\"userhistory.php?id=".$userid."&amp;type=$type\">{$tracker_lang['history_'.$type]}</a></td>");
		if (($i%4)==0) print '</tr><tr>';
	}
	if ($i%4!=0) print '<td colspan="'.(4-$i%4).'">&nbsp;</td></tr>';
	print ("</table>");
	end_frame();
	stdfoot();
	die();
}
else stderr($tracker_lang['error'],$tracker_lang['invalid_type']);
?>