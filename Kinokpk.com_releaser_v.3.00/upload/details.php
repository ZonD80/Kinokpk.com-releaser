<?php
/**
 * Release details
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once ("include/bittorrent.php");

dbconn ();

getlang ( 'details' );

if (! is_valid_id ( $_GET ['id'] ))
stderr ( $tracker_lang ['error'], $tracker_lang ['invalid_id'] );
$id = ( int ) $_GET ["id"];

$res = sql_query ("SELECT torrents.category, torrents.free, torrents.ratingsum, torrents.descr, SUM(trackers.seeders) AS seeders, SUM(trackers.leechers) AS leechers, torrents.banned, torrents.info_hash, torrents.topic_id, torrents.filename, torrents.last_action AS lastseed, torrents.name, torrents.owner, torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.ismulti, torrents.numfiles, torrents.images, torrents.online, torrents.moderatedby, torrents.freefor, (SELECT class FROM users WHERE id=torrents.moderatedby) AS modclass, (SELECT username FROM users WHERE id=torrents.moderatedby) AS modname, users.username, users.ratingsum AS userrating, users.class, torrents.relgroup AS rgid, relgroups.name AS rgname, relgroups.image AS rgimage,".($CURUSER?" IF((torrents.relgroup=0) OR (relgroups.private=0) OR FIND_IN_SET({$CURUSER['id']},relgroups.owners) OR FIND_IN_SET({$CURUSER['id']},relgroups.members),1,(SELECT 1 FROM rg_subscribes WHERE rgid=torrents.relgroup AND userid={$CURUSER['id']}))":' IF((torrents.relgroup=0) OR (relgroups.private=0),1,0)')." AS relgroup_allowed FROM torrents LEFT JOIN users ON torrents.owner = users.id LEFT JOIN trackers ON torrents.id=trackers.torrent LEFT JOIN relgroups ON torrents.relgroup=relgroups.id WHERE torrents.id = $id GROUP BY torrents.id" ) or sqlerr ( __FILE__, __LINE__ );
$row = mysql_fetch_array ( $res );
$owned = $moderator = 0;
if (get_user_class () >= UC_MODERATOR)
$owned = $moderator = 1;
elseif ($CURUSER ["id"] == $row ["owner"])
$owned = 1;

if (! $row || ($row ["banned"] && ! $moderator))
stderr ( $tracker_lang ['error'], $tracker_lang ['no_torrent_with_such_id'] );
else {
	if ($row['rgid']) $rgcontent = "<a href=\"relgroups.php?id={$row['rgid']}\">".($row['rgimage']?"<img style=\"border:none;\" title=\"Релиз группы {$row['rgname']}\" src=\"{$row['rgimage']}\"/>":'Релиз группы '.$row['rgname'])."</a>&nbsp;";

	if ((get_user_class()<UC_MODERATOR) && !$row['relgroup_allowed'] && $row['rgid']) stderr($tracker_lang['error'],sprintf($tracker_lang['private_release_access_denied'],$rgcontent));

	stdhead ( $tracker_lang ['torrent_details'] . " \"" . $row ["name"] . "\"" );

	if ($CURUSER ["id"] == $row ["owner"] || get_user_class () >= UC_MODERATOR || ($row ["filename"] == "nofile" && (get_user_class () == UC_UPLOADER)))
	$owned = 1;
	else
	$owned = 0;

	$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	/*	  print("<table cellspacing=\"0\" cellpadding=\"0\" class=\"tabs\"><tbody><tr>
	 <td class=\"tab0\"> </td><td nowrap=\"nowrap\" class=\"tab1\"><a href=\"details.php?id=$id\">Описание</a></td>
	 <td class=\"tab\"> </td><td nowrap=\"nowrap\" class=\"tab2\"><a href=\"torrent_info.php?id=$id\">{$tracker_lang ['torrent_info']}</a></td>
	 <td class=\"tab3\"> </td></tr></tbody></table>\n");*/
	print("<div id=\"tabs\"><ul>
	<li class=\"tab1\"><a href=\"details.php?id=$id\"><span>Описание</span></a></li>
	<li nowrap=\"\" class=\"tab2\"><a href=\"torrent_info.php?id=$id\"><span>{$tracker_lang ['torrent_info']}</span></a></li>
	</ul></div>\n");
	print ( "<table style=\"width:100%; border:1px; float:left;\" cellspacing=\"0\" cellpadding=\"5\">\n" );
	/*		print ( "<tr><td class=\"collhead\" colspan=\"2\"><div style=\"float: left; width: auto;\">&nbsp;" . $tracker_lang ['torrent_details'] . "</div><div align=\"right\"><a href=\"details.php?id=$id#comments\"><b>{$tracker_lang['add_comment']}</b></a> | <a href=\"bookmark.php?torrent=$row[id]\"><b>Добавить в избранное</b></a> | <a href=\"exportrelease.php?id=$id\"><small>Экспортировать на сайт</small></a></div></td></tr>" );*/
	$url = "edit.php?id=" . $row ["id"];
	if (isset ( $_GET ["returnto"] )) {
		$addthis = "&amp;returnto=" . urlencode ( $_GET ["returnto"] );
		$url .= $addthis;
		$keepget .= $addthis;
	}
	$editlink = "a href=\"$url\" class=\"sublink\"";
	//present
	if ($row ['freefor']) {
		$row ['freefor'] = explode ( ',', $row ['freefor'] );
		$thisispresent = (in_array ( $CURUSER ['id'], $row ['freefor'] ) ? '<img src="pic/presents/present.gif" title="' . $tracker_lang ['present_for_you'] . '" alt="' . $tracker_lang ['present_for_you'] . '"/>&nbsp;' : '');
	} else
	unset ( $thisispresent );
	//present end
	if ($owned) {
		$s = "<br />";
		if ($row ["filename"] == "nofile" && (get_user_class () == UC_UPLOADER)) {
			$s .= " $spacer<$editlink>[Редактрировать для добавления торрента]</a>";
		} else {
			$s .= " $spacer<$editlink>[" . $tracker_lang ['edit'] . "]</a>";
		}
	}
	tr ( $tracker_lang ['name'] . '<br /><b>' . $tracker_lang ['download'] . '</b>' . $s, "<h1>$rgcontent$thisispresent" . (($row ['free']) ? "<img src=\"pic/freedownload.gif\" title=\"" . $tracker_lang ['golden'] . "\" alt=\"" . $tracker_lang ['golden'] . "\"/>&nbsp;" : '') . "<a class=\"index\" href=\"download.php?id=$id\" onclick=\"javascript:$.facebox({ajax:'download.php?id=$id'}); return false;\">" . $row ["name"] . "</a></h1>", 1, 1, "10%" );

	// make main category and childs


	$tree = make_tree ();

	$cats = explode ( ',', $row ['category'] );
	$cat = array_shift ( $cats );
	$cat = get_cur_branch ( $tree, $cat );
	$childs = get_childs ( $tree, $cat ['parent_id'] );
	if ($childs) {
		foreach ( $childs as $child )
		if (($cat ['id'] != $child ['id']) && in_array ( $child ['id'], $cats ))
		$chsel [] = "<a href=\"browse.php?cat={$child['id']}\">" . makesafe ( $child ['name'] ) . "</a>";
	}
	tr ( $tracker_lang ['type'], get_cur_position_str ( $tree, $cat ['id'] ) . (is_array ( $chsel ) ? ', ' . implode ( ', ', $chsel ) : ''), 1 );

	//print ('<tr><td colspan="2" align="center"><a href="present.php?type=torrent&amp;to='.$id.'">'.$tracker_lang['present_to_friend'].'</a></td></tr>');


	//tr ( $tracker_lang ['info_hash'], $row ["info_hash"] );
	print("<tr><td style=\"text-align:right;\" class=\"heading\">".$tracker_lang ['info_hash']."</td><td><div style=\"float:left;\"><a title=\"Google it!\" href=\"http://www.google.com/search?q=".$row ["info_hash"]."\">{$row["info_hash"]}</a></div><div><b>,&nbsp;".$tracker_lang ['number_release']."</b>&nbsp;&nbsp;<font style=\"color:red\">".$id."</font></div></td></tr>");
	//tr ($tracker_lang ['number_release'],$id );
	tr ( $tracker_lang ['check'], '<div id="checkfield">' . ($row ['moderatedby'] ? $tracker_lang ['checked_by'] . '</font><a href="userdetails.php?id=' . $row ['moderatedby'] . '">' . get_user_class_color ( $row ['modclass'], $row ['modname'] ) . '</a> ' . ((get_user_class () >= UC_MODERATOR) ? '<a onclick="return ajaxcheck();" href="takeedit.php?checkonly&amp;id=' . $id . '">' . $tracker_lang ['uncheck'] . '</a>' : '') : $tracker_lang ['not_yet_checked'] . ((get_user_class () >= UC_MODERATOR) ? ' <a onclick="return ajaxcheck();" href="takeedit.php?checkonly&amp;id=' . $id . '">' . $tracker_lang ['check'] . '</a>' : '')) . '</div>', 1 );
	$spbegin = "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\">{$tracker_lang['screens']} ({$tracker_lang['view']})</div><div class=\"sp-body\"><textarea>";
	$spend = "</textarea></div></div>";

	if ($row ['images']) {
		$images = explode ( ',', $row ['images'] );
			
		$k = 0;
		foreach ( $images as $img ) {
			$k ++;

			$img = "<a href=\"$img\" onclick=\"javascript: $.facebox({image:'$img'}); return false;\"><img style=\"border: 2px dashed #c1d0d8;\" alt='Изображение для " . $row ["name"] . " (кликните для просмотра полного изображения)' width=\"240\" src=\"$img\" /></a><br />";
			//$img.="<a href='pic/loading.gif' rel='facebox'><img src='pic/loading.gif'/></a>";
			if ($k <= 1)
			$imgcontent .= $img;
			else
			$imgspoiler .= $img;

		}
	}

	print ( '<tr><td colspan="2"><table width="100%"><tr><td style="vertical-align: top;">' . ($imgcontent ? $imgcontent : '<img src="pic/noimage.gif"/>') . (! empty ( $imgspoiler ) ? sprintf($spbegin,"{$tracker_lang['screens']} ({$tracker_lang['view']})") . $imgspoiler . $spend : '') . '</td><td style="vertical-align: top; text-align:left; width:100%">'.($row['online']?$row['online'].'<hr />':'') . format_comment ( $row ['descr'] ) . '</td></tr></table></td></tr>' );

	if ($CACHEARRAY ['use_integration'] && $row['topic_id']) {
		tr ( "Релиз на форуме {$CACHEARRAY['forumname']}", "<a href=\"{$CACHEARRAY['forumurl']}/index.php?showtopic=" . $row ['topic_id'] . "\">{$CACHEARRAY['forumurl']}/index.php?showtopic=" . $row ['topic_id'] . "</a>", 1 );
		$topicid = $row ['topic_id'];
	}

	if (! $CURUSER) {
		print ( "</table>\n" );
		stdfoot ();
		die ();
	}
	//    if (!empty($row['online']))
	// tr("Смотреть онлайн<br />","<form method=\"post\" action=\"online/onlinevideo.php\"><input type=\"hidden\" name=\"onlinevideo\" value=\"".$row['online']."\"><input type=\"submit\" value=\"Смотреть онлайн на $CACHEARRAY['defaultbaseurl']\" /></form> <b>ТОЛЬКО ДЛЯ КПК НА WINDOWS MOBILE 5.0 ВЫШЕ! ФОРМАТ WMV</b>",1,1);
	if (! $row ["visible"])
	tr ( $tracker_lang ['visible'], "<b>" . $tracker_lang ['no'] . "</b> (" . $tracker_lang ['dead'] . ")", 1 );
	if ($moderator)
	tr ( $tracker_lang ['banned'], (! $row ["banned"] ? $tracker_lang ['no'] : $tracker_lang ['yes']) );

	if ($row ['filename'] != 'nofile')
	tr ( $tracker_lang ['seeder'], $tracker_lang ['seeder_last_seen'] . " " . get_elapsed_time ( $row ["lastseed"] ) . " " . $tracker_lang ['ago'] );
	if ($row["ismulti"]) {
		if (!$_GET["filelist"])
		tr($tracker_lang['files']."<br /><a href=\"details.php?id=$id&amp;filelist=1$keepget#filelist\" class=\"sublink\">[".$tracker_lang['open_list']."]</a>", $row["numfiles"] . " ".$tracker_lang['files_l'], 1);
		else {
			tr($tracker_lang['files'], $row["numfiles"] . " ".$tracker_lang['files_l'], 1);

			$s = "<table class=main border=\"1\" cellspacing=0 cellpadding=\"5\">\n";

			$subres = sql_query("SELECT * FROM files WHERE torrent = $id ORDER BY id");
			$s.="<tr><td class=colhead>".$tracker_lang['path']."</td><td class=colhead align=right>".$tracker_lang['size']."</td></tr>\n";
			while ($subrow = mysql_fetch_array($subres)) {
				$s .= "<tr><td>" . $subrow["filename"] .
                            			"</td><td align=\"right\">" . mksize($subrow["size"]) . "</td></tr>\n";
			}

			$s .= "</table>\n";
			tr("<a name=\"filelist\">".$tracker_lang['file_list']."</a><br /><a href=\"details.php?id=$id$keepget\" class=\"sublink\">[".$tracker_lang['close_list']."]</a>", $s, 1);
		}
	}

	tr ( $tracker_lang ['size'], mksize ( $row ["size"] ) . " (" . number_format ( $row ["size"] ) . " " . $tracker_lang ['bytes'] . ")" );

	tr ( $tracker_lang ['added'], mkprettytime ( $row ["added"] ) );
	tr ( $tracker_lang ['views'], $row ["views"] );

	if ($row ['filename'] != 'nofile') {
		tr ( $tracker_lang ['hits'], $row ["hits"] );
		tr ( $tracker_lang ['snatched'], $row ["times_completed"] . " " . $tracker_lang ['times'] );
	}
	$keepget = "";
	$uprow = (isset ( $row ["username"] ) ? ("<a href='userdetails.php?id=" . $row ["owner"] . "'>" . get_user_class_color ( $row ['class'], $row ["username"] ) . "</a>") : "<i>Аноним</i>");
	/*
	 if ($owned)
	 $uprow .= " $spacer<$editlink><b>[".$tracker_lang['edit']."]</b></a>";
	 */

	tr ( "Выложил", $uprow . $spacer . ratearea ( $row ['userrating'], $row ['owner'], 'users' ,$CURUSER['id']), 1 );
	tr ( $tracker_lang ['vote'], ratearea ( $row ['ratingsum'], $id, 'torrents',(($row['owner']==$CURUSER['id'])?$id:0) ) . reportarea ( $id, 'torrents' ), 1 );


	if ($row ['filename'] != 'nofile')
	tr ( $tracker_lang ['downloading'] . "<br /><a href=\"torrent_info.php?id=$id&amp;dllist=1$keepget#seeders\" class=\"sublink\">[" . $tracker_lang ['open_list'] . "]</a>", $row ["seeders"] . " " . $tracker_lang ['seeders_l'] . ", " . $row ["leechers"] . " " . $tracker_lang ['leechers_l'] . " = " . ($row ["seeders"] + $row ["leechers"]) . " " . $tracker_lang ['peers_l'].'<br/><small>Если торрент мультитрекерный, то возможно мы не успели получить данные о количестве пиров. <a href="torrent_info.php?id='.$id.'">Посмотреть данные о торренте</a></small>', 1 );

	if ($row ["times_completed"] > 0) {
		$res = sql_query ( "SELECT users.id, users.username, users.title, users.uploaded, users.downloaded, users.donor, users.enabled, users.warned, users.last_access, users.class, snatched.startedat, peers.last_action, snatched.completedat, peers.seeder, snatched.userid, snatched.uploaded AS sn_up, snatched.downloaded AS sn_dn FROM snatched LEFT JOIN peers ON (snatched.torrent=peers.torrent AND snatched.userid=peers.userid) INNER JOIN users ON snatched.userid = users.id WHERE snatched.finished=1 AND snatched.torrent =$id AND snatched.userid<>{$row['owner']} ORDER BY users.class DESC $limit" ) or sqlerr ( __FILE__, __LINE__ );
		$snatched_full = "<table width=100% class=main border=1 cellspacing=0 cellpadding=5>\n";
		$snatched_full .= "<tr><td class=colhead>Юзер</td><td class=colhead>Раздал</td><td class=colhead>Скачал</td><td class=colhead>Рейтинг</td><td class=colhead align=center>Начал / Закончил</td><td class=colhead align=center>Действие</td><td class=colhead align=center>Сидирует</td><td class=colhead align=center>ЛС</td></tr>";
			
		while ( $arr = mysql_fetch_assoc ( $res ) ) {
			//start Global
			if ($arr ["downloaded"] > 0) {
				$ratio = number_format ( $arr ["uploaded"] / $arr ["downloaded"], 2 );
				//  $ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
			} else if ($arr ["uploaded"] > 0)
			$ratio = "Inf.";
			else
			$ratio = "---";
			$uploaded = mksize ( $arr ["uploaded"] );
			$downloaded = mksize ( $arr ["downloaded"] );
			//start torrent
			if ($arr ["sn_dn"] > 0) {
				$ratio2 = number_format ( $arr ["sn_up"] / $arr ["sn_dn"], 2 );
				$ratio2 = "<font color=" . get_ratio_color ( $ratio2 ) . ">$ratio2</font>";
			} else if ($arr ["sn_up"] > 0)
			$ratio2 = "Inf.";
			else
			$ratio2 = "---";
			$uploaded2 = mksize ( $arr ["sn_up"] );
			$downloaded2 = mksize ( $arr ["sn_dn"] );
			//end
			//$highlight = $CURUSER["id"] == $arr["id"] ? " bgcolor=#00A527" : "";;
			$snatched_small [] = "<a href=userdetails.php?id=$arr[userid]>" . get_user_class_color ( $arr ["class"], $arr ["username"] ) . " (<font color=" . get_ratio_color ( $ratio ) . ">$ratio</font>)</a>";
			$snatched_full .= "<tr$highlight><td><a href=userdetails.php?id=$arr[userid]>" . get_user_class_color ( $arr ["class"], $arr ["username"] ) . "</a>" . get_user_icons ( $arr ) . "</td><td><nobr>$uploaded&nbsp;Общего<br />$uploaded2&nbsp;Торрент</nobr></td><td><nobr>$downloaded&nbsp;Общего<br />$downloaded2&nbsp;Торрент</nobr></td><td><nobr>$ratio&nbsp;Общего<br />$ratio2&nbsp;Торрент</nobr></td><td align=center><nobr>" . mkprettytime($arr ["startedat"]) . "<br />" . mkprettytime($arr ["completedat"]) . "</nobr></td><td align=center><nobr>" . get_elapsed_time($arr ["last_action"]) . "</nobr></td><td align=center>" . ($arr ["seeder"] ? "<b><font color=green>Да</font>" : "<font color=red>Нет</font></b>") . "</td><td align=center><a href=message.php?action=sendmessage&amp;receiver=$arr[userid]><img src=pic/button_pm.gif border=\"0\"></a></td></tr>\n";
		}
		$snatched_full .= "</table>\n";
			
		if ($row ["seeders"] == 0 || ($row ["leechers"] / $row ["seeders"] >= 2))
		$reseed_button = "<form action=\"takereseed.php\"><input type=\"hidden\" name=\"torrent\" value=\"$id\" /><input type=\"submit\" value=\"Позвать скачавших\" /></form>";
		if (! $_GET ["snatched"] == 1)
		tr ( "Скачавшие<br /><a href=\"details.php?id=$id&amp;snatched=1#snatched\" class=\"sublink\">[Посмотреть список]</a>", "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\"><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td class=bottom width=50%><i>Открыть</i></td></tr></table></div><div class=\"sp-body\">" . @implode ( ", ", $snatched_small ) . $reseed_button . '</div></div>', 1 );
		else
		tr ( "Скачавшие<br /><a href=\"details.php?id=$id\" class=\"sublink\" name=\"snatched\">[Cпрятать список]</a>", $snatched_full, 1 );
	}
	print ( '<tr><td colspan="2" align="center"><a href="present.php?type=torrent&amp;to=' . $id . '">' . $tracker_lang ['present_to_friend'] . '</a></td></tr>' );


	?>
<script language="javascript" type="text/javascript">
//<![CDATA[
var no_ajax = true;
var switched = 0;

function ajaxcheck() {

      (function($){
      if ($) no_ajax = false;
   $("#checkfield").empty();
   $("#checkfield").append('<div align="center"><img src="pic/loading.gif" border="0"/></div>');
    $.get("takeedit.php", { ajax: 1, checkonly: "", id: <?=$id;?> }, function(data){
   $("#checkfield").empty();
   $("#checkfield").append(data);
});
})(jQuery);

return no_ajax;

}

//]]>
</script>
	<?

	print ( "</table>\n" );

	print ( "<div align=\"center\"><a href=\"#\" onclick=\"location.href='pass_on.php?to=pre&amp;from=" . $id . "'; return false;\">
<< Предыдущий релиз</a>&nbsp;
<a href=\"#\" onclick=\"location.href='pass_on.php?to=pre&amp;from=" . $id . "&amp;cat=" . $row ['category'] . "'; return false;\">[из этой категории]</a>
&nbsp; | &nbsp;
<a href=\"#\" onclick=\"location.href='pass_on.php?to=next&amp;from=" . $id . "&amp;cat=" . $row ['category'] . "'; return false;\">[из этой категории]</a>&nbsp;
<a href=\"#\" onclick=\"location.href='pass_on.php?to=next&amp;from=" . $id . "'; return false;\">
Следующий релиз >></a><br />
<a href=\"browse.php\">Все релизы</a>
&nbsp; | &nbsp;
<a href=\"browse.php?cat=" . $row ['category'] . "\">Все релизы этой категории</a></div>" );

}
$subres = sql_query ( "SELECT SUM(1) FROM comments WHERE torrent = $id" );
$subrow = mysql_fetch_array ( $subres );
$count = $subrow [0];

$limited = 10;

if (! $count) {

	print ( "<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">" );
	print ( "<tr><td class=\"colhead\" align=\"left\" colspan=\"2\">" );
	print ( "<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев {$CACHEARRAY['defaultbaseurl']}</div>" );
	print ( "<div align=\"right\"><a href=\"details.php?id=$id#comments\" class=\"altlink_white\">Добавить комментарий</a></div>" );
	print ( "</td></tr><tr><td align=\"center\">" );
	print ( "Комментариев нет. <a href=\"details.php?id=$id#comments\">Желаете добавить?</a>" );
	print ( "</td></tr></table><br />" );

} else {
	list ( $pagertop, $pagerbottom, $limit ) = pager ( $limited, $count, "details.php?id=$id&amp;",array ('lastpagedefault' => 1 ) );

	$subres = sql_query ( "SELECT c.id, c.post_id, c.ip, c.ratingsum, c.text, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, " . "u.username, u.title, u.class, u.donor, u.enabled, u.ratingsum AS urating, u.gender, sessions.time AS last_access, e.username AS editedbyname FROM comments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN sessions ON c.user=sessions.uid LEFT JOIN users AS e ON c.editedby = e.id WHERE torrent = " . "$id GROUP BY c.id ORDER BY c.id $limit" ) or sqlerr ( __FILE__, __LINE__ );
	$allrows = array ();
	if ($CACHEARRAY ['use_integration']) {
		// ipb comment transfer
		$postids = array ();
		// end, cont below
	}
	while ( $subrow = mysql_fetch_array ( $subres ) ) {
		$subrow['subject'] = $row['name'];
		$subrow['link'] = "details.php?id=$id#comm{$subrow['id']}";
		$allrows [] = $subrow;
	}

	if ($CACHEARRAY ['use_integration']) {
		// ipb comment transfer
		foreach ( $allrows as $rows )
		$postids [] = $rows ['post_id'];
		// end,cont below
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
	commenttable ( $allrows );
	print ( "</td></tr>" );
	print ( "<tr><td>" );
	print ( $pagerbottom );
	print ( "</td></tr>" );
	print ( "</table>" );
}


print ( "<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">" );
print ( "<tr><td class=colhead align=\"left\" colspan=\"2\">  <div id=\"comments\"></div><b>:: {$tracker_lang['add_comment']} к релизу | " . is_i_notified ( $id, 'comments' ) . "</b></td></tr>" );
print ( "<tr><td width=\"100%\" align=\"center\" >" );
//print("Ваше имя: ");
//print("".$CURUSER['username']."<p>");
print ( "<form name=\"comment\" method=\"post\" action=\"comment.php?action=add\">" );
print ( "<table width=\"100%\"><tr><td align=\"center\">" . textbbcode ( "text") . "</td></tr>" );

print ( "<tr><td  align=\"center\">" );
print ( "<input type=\"hidden\" name=\"tid\" value=\"$id\"/>" );
print ( "<input type=\"submit\" value=\"Разместить комментарий\" />" );
print ( "</td></tr></table></form>" );

if ($CACHEARRAY ['use_integration']) {
	if ($topicid != 0) {

		// connecting to IPB DB
		forumconn ();
		//connection opened


		if (count ( $postids ) >= 1)
		$condition = "AND pid NOT IN (" . implode ( ",", $postids ) . ")";
		else
		$condition = "";

		$postsarray = sql_query ( "SELECT author_name, post, post_date FROM " . $fprefix . "posts WHERE topic_id=" . $topicid . " AND new_topic<>1 " . $condition . " ORDER BY post_date DESC LIMIT 5" );
		$forumid = sql_query ( "SELECT forum_id FROM " . $fprefix . "topics WHERE tid=" . $topicid );
		$forumid = @mysql_result ( $forumid, 0 );

		if (! $forumid)
		sql_query ( "UPDATE torrents SET topic_id=0 WHERE id=$id" );
		else {

			while ( $posts = mysql_fetch_array ( $postsarray ) ) {
				$count = 1;
				if ($count == 1) {
					print ( "<table class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >" );
					print ( "<tr><td class=\"colhead\" align=\"center\" >" );
					print ( "<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев форума {$CACHEARRAY['forumname']}</div>" );
					print ( "</td></tr>" );
					print ( "<tr><td>" );
				}
				print ( "<b><i>" . $posts ['author_name'] . "</i></b> от " . mkprettytime ( $posts ['post_date'] ) . ":<br /><br />" );
				print ( str_replace ( "style_emoticons/<#EMO_DIR#>", $CACHEARRAY ['forumurl'] . "/style_emoticons/" . $CACHEARRAY ['emo_dir'], $posts ['post'] ) . "<hr />" );
				if ($count == 1) {
					print ( "</tr></td>" );
					print ( "</table>" );
				}
				$count ++;
			}

			relconn ();
		}
	}
}

print '</table>';
sql_query ( "UPDATE torrents SET views = views + 1 WHERE id = $id" );
set_visited('torrents',$id);
stdfoot ();

?>
