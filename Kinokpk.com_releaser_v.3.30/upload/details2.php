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


if (! is_valid_id ( $_GET ['id'] ))
stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
$id = ( int ) $_GET ["id"];

$res = sql_query ("SELECT torrents.category, torrents.free, torrents.ratingsum, torrents.descr, SUM(trackers.seeders) AS seeders, SUM(trackers.leechers) AS leechers, torrents.banned, torrents.info_hash, torrents.tiger_hash, torrents.filename, torrents.last_action AS lastseed, torrents.name, torrents.owner, torrents.visible, torrents.size, torrents.added, torrents.views, torrents.hits, torrents.times_completed, torrents.id, torrents.ismulti, torrents.numfiles, torrents.images, torrents.online, torrents.moderatedby, torrents.freefor, (SELECT class FROM users WHERE id=torrents.moderatedby) AS modclass, (SELECT username FROM users WHERE id=torrents.moderatedby) AS modname, users.username, users.ratingsum AS userrating, users.class, torrents.relgroup AS rgid, relgroups.name AS rgname, relgroups.image AS rgimage,".($CURUSER?" IF((torrents.relgroup=0) OR (relgroups.private=0) OR FIND_IN_SET({$CURUSER['id']},relgroups.owners) OR FIND_IN_SET({$CURUSER['id']},relgroups.members),1,(SELECT 1 FROM rg_subscribes WHERE rgid=torrents.relgroup AND userid={$CURUSER['id']}))":' IF((torrents.relgroup=0) OR (relgroups.private=0),1,0)')." AS relgroup_allowed FROM torrents LEFT JOIN users ON torrents.owner = users.id LEFT JOIN trackers ON torrents.id=trackers.torrent LEFT JOIN relgroups ON torrents.relgroup=relgroups.id WHERE torrents.id = $id GROUP BY torrents.id" ) or sqlerr ( __FILE__, __LINE__ );
$row = mysql_fetch_array ( $res );
/*
print('<pre>');
print_r($row);
print('</pre>');
*/
$owned = $moderator = 0;
if (get_user_class () >= UC_MODERATOR)
$owned = $moderator = 1;
elseif ($CURUSER ["id"] == $row ["owner"])
$owned = 1;

if (! $row || ($row ["banned"] && ! $moderator))
stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('no_torrent_with_such_id') );
else {
	if ($row['rgid']) $rgcontent = "<span class='rg'><a href=\"".$REL_SEO->make_link('relgroups','id',$row['rgid'],'name',translit($row['rgname']))."\">".($row['rgimage']?"<img style=\"border:none;\" title=\"Релиз группы {$row['rgname']}\" src=\"{$row['rgimage']}\"/>":'Релиз группы '.$row['rgname'])."</a></span>";

	if ((get_user_class()<UC_MODERATOR) && !$row['relgroup_allowed'] && $row['rgid']) stderr($REL_LANG->say_by_key('error'),sprintf($REL_LANG->say_by_key('private_release_access_denied'),$rgcontent));

	$REL_TPL->stdhead( $row ["name"]." - {$REL_LANG->say_by_key('torrent_details')}" );
?>



<style type="text/css">

.reklama{background:none repeat scroll 0 0 #F0F0F0;height:16px;margin-top:44px;padding:5px 3px;margin-top: -1px;}
#tabs_torrent{border-bottom:1px solid #BCD2E6;float:left;font-size:93%;line-height:normal;width:100%;}
#tabs_torrent ul{list-style:none outside none;margin:0;padding:10px 10px 0 50px;}
#tabs_torrent li{float:left;margin:0 2px 0 0;padding:0;}
#tabs_torrent li.tab1_torrent a{border-color:#D8DFEA #D8DFEA -moz-use-text-color;border-style:solid solid none;border-width:1px;float:left;margin:0;padding:0 0 0 4px;text-decoration:none;}
#tabs_torrent li.tab1_torrent a span{font-size:13px;font-weight:bold;background:none repeat scroll 0 0 #FFFFFF;color:#627EB7;display:block;float:left;padding:5px 10px 4px 6px;}
#tabs_torrent li.tab2_torrent a{background:none repeat scroll 0 0 #D8DFEA;float:left;margin-top:1px;padding:0 0 0 4px;text-decoration:none;}
#tabs_torrent li.tab2_torrent a span{color:#627EB7;display:block;float:left;font-size:13px;font-weight:bold;padding:4px 10px 5px 6px;}
div.torrent_release_name{height:40px}
div.torrent_release_name span.rg{float: left; margin: 5px;}
div.torrent_release_name h1{height:40px;line-height:2.5;padding-left:150px;text-align:left;width:80%;}
div.torrent_release_name h1 img{height:32px;margin-left:5px;vertical-align:top;width:32px;}
div.torrent_release_header div.torrent_release_merit{background:none repeat scroll 0 0 #FAFAFA;border:1px solid #E0E0E0;height:54px;margin-bottom:2px;}
div.torrent_release_header div.torrent_release_merit p{float:left;margin:0 10px;width:54px;}
div.torrent_release_header div.torrent_release_merit p img{height:48px;padding:3px;width:48px;}
div.torrent_release_body_image div.sp-wrap{border:1px solid #D8DFEA;margin-bottom:2px;margin-top:2px;width:240px;}
div.torrent_release_body_descr div.sp-wrap{border:1px solid #D8DFEA;margin-bottom:2px;margin-top:2px;}
div.torrent_release_body_image div.sp-body{background:none;}
div.torrent_release_body div.trailer div.sp-body{border:none;padding:0px;}
div.torrent_release_body div.trailer {border:1px solid #D8DFEA;margin-bottom:2px;margin-top:2px;width:240px;}
div.torrent_release_body_image {width: 250px; float: left; padding-right: 5px;}
div.torrent_release_body_descr {float: left;width:70%;}
div#facebox_trailer	{left: 334.5px; position: fixed; display: block; top: 50px;}
div#torrent_release div.torrent_release_body_description{border:1px solid #CCCCCC;}
div.torrent_release_body_description .colhead{width: 100%;padding:3px 0; margin-left: 0px; margin-bottom: 6px;}
div.torrent_release_body_description .colhead_body{padding:0 5px;}

</style>

<?
	if ($CURUSER ["id"] == $row ["owner"] || get_user_class () >= UC_MODERATOR || ($row ["filename"] == "nofile" && (get_user_class () == UC_UPLOADER)))
	$owned = 1;
	else
	$owned = 0;

	$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		//название 
	print('<div class="torrent_release_name">
	'.$rgcontent.'<h1>'.$thisispresent.'&nbsp;'. $row['name'].'
	' .(($row ['free']) ? "
	<img src=\"pic/freestar.png\" title=\"" . $REL_LANG->say_by_key('golden') . "\" alt=\"" . $REL_LANG->say_by_key('golden') . "\"/>&nbsp;" : '') . '
	</h1></div>');
	if ($CURUSER) {
	print("<div id=\"tabs_torrent\">
			<ul>
				<li class=\"tab1_torrent\"><a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))."\"><span>Описание</span></a></li>
				<li nowrap class=\"tab2_torrent\"><a href=\"".$REL_SEO->make_link('torrent_info','id',$id,'name',translit($row['name']))."\"><span>{$REL_LANG->say_by_key('torrent_info')}</span></a></li>
				<li nowrap class=\"tab2_torrent\"><a href=\"".$REL_SEO->make_link('exportrelease','id',$id,'name',translit($row['name']))."\"><span>{$REL_LANG->say_by_key('exportrelease_mname')}</span></a></li>
			</ul></div>\n");
	}
	print('<div class="clear"></div>');
	/*реклама нужна без блоков сверху*/
	print('<div class="reklama ">');
	print('<script type="text/javascript"><!--
google_ad_client = "pub-9450325557720942";
/* 468x15, создано 26.10.10 */
google_ad_slot = "8397359313";
google_ad_width = 468;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>');
print('</div>');
/*end*/
/*начало релиза*/
print('<div id="torrent_release">');
	$url = $REL_SEO->make_link('edit','id',$row ["id"],'name',translit($row['name']));
	if (isset ( $_GET ["returnto"] )) {
		$addthis = "&amp;returnto=" . urlencode ( $_GET ["returnto"] );
		$url .= $addthis;
		$keepget .= $addthis;
	}
	$editlink = "a href=\"$url\" class=\"sublink\"";
	//present
	if ($row ['freefor']) {
		$row ['freefor'] = explode ( ',', $row ['freefor'] );
		$thisispresent = (in_array ( $CURUSER ['id'], $row ['freefor'] ) ? '<img src="pic/presents/present.gif" title="' . $REL_LANG->say_by_key('present_for_you') . '" alt="' . $REL_LANG->say_by_key('present_for_you') . '"/>&nbsp;' : '');
	} else
	unset ( $thisispresent );
	//present end
	if ($owned) {
		$s = "<br />";
		if ($row ["filename"] == "nofile" && (get_user_class () == UC_UPLOADER)) {
			$s .= "<$editlink>[Редактрировать для добавления торрента]</a>";
		} else {
			$s .= "<$editlink>[" . $REL_LANG->say_by_key('edit') . "]</a>";
		}
	}

	print('<div class="torrent_release_header">');
		
		//дальше заслуги
		if ($CURUSER) {
		print('<div class="torrent_release_merit">');
			print("<p><span class='rm_download'><a class=\"index\" href=\"".$REL_SEO->make_link("download","id",$id,"name",translit($row['name'])).	"\" onclick=\"javascript:$.facebox({ajax:'".$REL_SEO->make_link("download","id",$id,"name",translit($row['name']))."'}); return false;\"><img src='pic/download_r.png' title='" . $REL_LANG->say_by_key('download')."&nbsp;".$row['name'] ."' alt=''/></a></span></p>");	
			print('<p><span class="rm_bookmark"><a class="index" href="'.$REL_SEO->make_link("bookmark","id",$id,"name",translit($row['name'])).'" "><img src="pic/heart.png" title="' . $REL_LANG->say_by_key('bookmark_this').'&nbsp;'.$row['name'] . '" alt=""/></a></span></p>
				   <p><span class="rm_bookmark"><a href="'.$REL_SEO->make_link('present','type','torrent','to',$id).'"><img src="pic/present.png" title="' . $REL_LANG->say_by_key('present').'" alt=""/></a></span></p>
				   <p><span class="rm_bookmark"><a href='.$REL_SEO->make_link('details2','id',$id,'name',translit($row['name'])).'#comments><img src="pic/add_comment.png" title="' . $REL_LANG->say_by_key('add_comment').'&nbsp;'.$row['name'] . '" alt=""/></a></span> </p>
				   <p><span class="rm_bookmark"><a href=" http://www.torrentsbook.com/report.php?id='.$row ["id"].'&type=torrents"><img src="pic/warning.png" title="' . $REL_LANG->say_by_key('report_it').'&nbsp;'.$row['name'] . '" alt=""/></a></span></p>
				  ');
		if ($owned) {
			 print(' <p><span class="rm_bookmark"><'.$editlink.'><img src="pic/edit_r.png" title="' . $REL_LANG->say_by_key('edit').'&nbsp;'.$row['name'] . '" alt=""/></a></span></p>');
		}
			 print('<p><span class="rm_bookmark"><a href="donate.php"><img src="pic/beer.png" title="Помощь сайту" alt=""/></a></span></p>');	
		print('</div>');
		}
	print('</div>');

	$tree = make_tree ();


			$cats = explode ( ',', $row ['category'] );
			$cat = array_shift ( $cats );
			$cat = get_cur_branch ( $tree, $cat );
			$childs = get_childs ( $tree, $cat ['parent_id'] );
			if ($childs) {
				foreach ( $childs as $child )
				if (($cat ['id'] != $child ['id']) && in_array ( $child ['id'], $cats ))
				$chsel [] = "<a href=\"".$REL_SEO->make_link('browse','cat',$child['id'],'name',translit($child['name']))."\">" . makesafe ( $child ['name'] ) . "</a>";
			}
			$OUT .= "<strong>{$REL_LANG->say_by_key('type')}:</strong> ". get_cur_position_str ( $tree, $cat ['id'] ) . (is_array ( $chsel ) ? ', ' . implode ( ', ', $chsel ) : '')."<br/>";

			$OUT .= "<strong>{$REL_LANG->say_by_key('info_hash')}:</strong> BTIH:<a title=\"Google it!\" href=\"http://www.google.com/search?q=".$row ["info_hash"]."\">{$row["info_hash"]}</a>".($row['tiger_hash']?", TTH:<a title=\"Google it!\" href=\"http://www.google.com/search?q=".$row ["tiger_hash"]."\">{$row["tiger_hash"]}</a>":'')."<b>,&nbsp;".$REL_LANG->say_by_key('number_release')."</b>&nbsp;&nbsp;<font style=\"color:red\">".$id."</font><br/>";
			if ($CURUSER)
			$OUT .= "<strong>{$REL_LANG->say_by_key('check')}:</strong>".'<div id="checkfield">' . ($row ['moderatedby'] ? $REL_LANG->say_by_key('checked_by') . ' <a href="'.$REL_SEO->make_link('userdetails','id',$row ['moderatedby'],'username',translit($row['modname'])).'">' . get_user_class_color ( $row ['modclass'], $row ['modname'] ) . '</a> ' . ((get_user_class () >= UC_MODERATOR) ? '<a onclick="return ajaxcheck();" href="'.$REL_SEO->make_link('takeedit','checkonly','','id', $id).'">' . $REL_LANG->say_by_key('uncheck') . '</a>' : '') : $REL_LANG->say_by_key('not_yet_checked') . ((get_user_class () >= UC_MODERATOR) ? ' <a onclick="return ajaxcheck();" href="'.$REL_SEO->make_link('takeedit','checkonly','','id',$id).'">' . $REL_LANG->say_by_key('check') . '</a>' : '')) . '</div><br/>';
			$spbegin = "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\">{$REL_LANG->say_by_key('screens')} ({$REL_LANG->say_by_key('view')})</div><div class=\"sp-body\"><textarea>";
			$spend = "</textarea></div></div>";
			$pbegin = "<div class='trailer'><div class=\"sp-head folded clickable\">{$REL_LANG->say_by_key('trailer')} ({$REL_LANG->say_by_key('view')})</div><div id='trailer_body' class=\"sp-body\"><div id=\"facebox_trailer\">
						<div class=\"popup\"><table><tbody><tr><td class=\"tl\"></td><td class=\"b\"></td><td class=\"tr\"></td></tr><tr><td class=\"b\"></td><td class=\"body\"><div class=\"content\" style=\"display: block;\">";
			$pend = "</div><div class=\"footer\" style=\"display: block;\"><a class=\"close\" href=\"#\" ><img class=\"close_image\" title=\"close\" src=\"pic/facebox/closelabel.gif\"></a></div></td><td class=\"b\"></td></tr> <tr>  <td class=\"bl\"></td><td class=\"b\"></td><td class=\"br\"></td></tr></tbody></table></div></div></div></div>";

			if ($CURUSER) {
				if (! $row ["visible"])
				$OUT .= "<strong>{$REL_LANG->say_by_key('visible')}:</strong> <b>" . $REL_LANG->say_by_key('no') . "</b> (" . $REL_LANG->say_by_key('dead') . ")"."<br/>";
				if ($row ['filename'] != 'nofile')
				$OUT .= "<strong>{$REL_LANG->say_by_key('seeder')}:</strong> {$REL_LANG->say_by_key('seeder_last_seen')} " . get_elapsed_time ( $row ["lastseed"] ) . " " . $REL_LANG->say_by_key('ago')."<br/>";

				$OUT .= "<strong>{$REL_LANG->say_by_key('size')}:</strong> ". mksize ( $row ["size"] ) . " (" . number_format ( $row ["size"] ) . " " . $REL_LANG->say_by_key('bytes') . ")"."<br/>";

				$OUT .= "<strong>{$REL_LANG->say_by_key('added')}:</strong> ". mkprettytime ( $row ["added"] )."<br/>";
				$OUT .= "<strong>{$REL_LANG->say_by_key('views')}:</strong> ". $row ["views"]."<br/>";

				if ($row ['filename'] != 'nofile') {
					$OUT .= "<strong>{$REL_LANG->say_by_key('hits')}:</strong> {$row ["hits"]}"."<br/>";
					$OUT .= "<strong>{$REL_LANG->say_by_key('snatched')}:</strong> {$row ["times_completed"]} " . $REL_LANG->say_by_key('times')."<br/>";
				}
				$keepget = "";
				$uprow = (isset ( $row ["username"] ) ? ("<a href='userdetails.php?id=" . $row ["owner"] . "'>" . get_user_class_color ( $row ['class'], $row ["username"] ) . "</a>") : "<i>Аноним</i>");


				$OUT .= "<strong>Выложил:</strong>  $uprow $spacer ". ratearea ( $row ['userrating'], $row ['owner'], 'users' ,$CURUSER['id'])."<br/>";
				$OUT .= "<strong>{$REL_LANG->say_by_key('vote')} за релиз:</strong> $spacer".ratearea ( $row ['ratingsum'], $id, 'torrents',(($row['owner']==$CURUSER['id'])?$id:0) )."<br/>";
			}

			if ($row ['images']) {
				$images = explode ( ',', $row ['images'] );
					
				$k = 0;
				foreach ( $images as $img ) {
					$k ++;

					if ($k <= 1)
					$width = '240';
					else
					$width = '223';

					$img = "<a href=\"$img\" onclick=\"javascript: $.facebox({image:'$img'}); return false;\"><img style=\"border: 2px dashed #c1d0d8;\" alt='Изображение для " . $row ["name"] . " (кликните для просмотра полного изображения)' width=\"".$width."\" src=\"$img\" /></a><br />";
					//$img.="<a href='pic/loading.gif' rel='facebox'><img src='pic/loading.gif'/></a>";
					if ($k <= 1)
					$imgcontent .= $img;
					else
					$imgspoiler .= $img;

				}
			}
	print('<div class="torrent_release_body">');
		print('<div class="torrent_release_body_image">
		' . ($imgcontent ? $imgcontent : '<img src="pic/noimage.gif"/>') . (! empty ( $imgspoiler ) ? sprintf($spbegin,"{$REL_LANG->say_by_key('screens')} ({$REL_LANG->say_by_key('view')})") . $imgspoiler . $spend : '') . '
		'.($row['online']? $pbegin .''. $row['online'] .''. $pend.'':'').'
		</div>');
		print('<div class="torrent_release_body_descr">'. format_comment ( $row ['descr'] ) . '</div>');
			
		print('<div class="clear"></div>');
	print('</div>');
		print('<div class="clear"></div>');
/*конец релиза для не зареганных*/

	if (! $CURUSER) {
		print('</div>');
		$REL_TPL->stdfoot();
		die ();
	}
		print('<div class="torrent_release_body_description">');
			print('<div class="colhead">Описание релиза</div>
						<div class="colhead_body">'.$OUT.'
							<table cellspacing="0" cellpadding="5" style="width: 100%;">
								<tbody>');
			
if ($moderator)
	tr ( $REL_LANG->say_by_key('banned'), (! $row ["banned"] ? $REL_LANG->say_by_key('no') : $REL_LANG->say_by_key('yes')) );

if ($row["ismulti"]) {
	if (!$_GET["filelist"])
		tr($REL_LANG->say_by_key('files')."<br /><a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']),'filelist',1)."$keepget#filelist\" class=\"sublink\">[".$REL_LANG->say_by_key('open_list')."]</a>", $row["numfiles"] . " ".$REL_LANG->say_by_key('files_l'), 1);
	else {
		tr($REL_LANG->say_by_key('files'), $row["numfiles"] . " ".$REL_LANG->say_by_key('files_l'), 1);

			$s = "<table class=main border=\"1\" cellspacing=0 cellpadding=\"5\">\n";

			$subres = sql_query("SELECT * FROM files WHERE torrent = $id ORDER BY id");
			$s.="<tr><td class=colhead>".$REL_LANG->say_by_key('path')."</td><td class=colhead align=right>".$REL_LANG->say_by_key('size')."</td></tr>\n";
			while ($subrow = mysql_fetch_array($subres)) {
				$s .= "<tr><td>" . iconv('utf8','windows-1251',$subrow["filename"]) .
                            			"</td><td align=\"right\">" . mksize($subrow["size"]) . "</td></tr>\n";
			}

			$s .= "</table>\n";
			tr("<a name=\"filelist\">".$REL_LANG->say_by_key('file_list')."</a><br /><a href=\"details.php?id=$id$keepget\" class=\"sublink\">[".$REL_LANG->say_by_key('close_list')."]</a>", $s, 1);
		}
}
if ($row ['filename'] != 'nofile')
	tr ( $REL_LANG->say_by_key('downloading') . "<br /><a href=\"".$REL_SEO->make_link('torrent_info','id',$id,'name',translit($row['name']),'dllist',1)."$keepget#seeders\" class=\"sublink\">[" . $REL_LANG->say_by_key('open_list') . "]</a>", $row ["seeders"] . " " . $REL_LANG->say_by_key('seeders_l') . ", " . $row ["leechers"] . " " . $REL_LANG->say_by_key('leechers_l') . " = " . ($row ["seeders"] + $row ["leechers"]) . " " . $REL_LANG->say_by_key('peers_l').'<br/><small>Если торрент мультитрекерный, то возможно мы не успели получить данные о количестве пиров. <a href="'.$REL_SEO->make_link('torrent_info','id',$id,'name',translit($row['name'])).'">Посмотреть данные о торренте</a></small>', 1 );

if ($row ["times_completed"] > 0) {
		$res = sql_query ( "SELECT users.id, users.username, users.title, users.donor, users.enabled, users.warned, users.ratingsum, users.class, snatched.startedat, peers.last_action, snatched.completedat, peers.seeder, snatched.userid FROM snatched LEFT JOIN peers ON (snatched.torrent=peers.torrent AND snatched.userid=peers.userid) INNER JOIN users ON snatched.userid = users.id WHERE snatched.finished=1 AND snatched.torrent =$id AND snatched.userid<>{$row['owner']} ORDER BY users.class DESC $limit" ) or sqlerr ( __FILE__, __LINE__ );
		$snatched_full = "<table width=100% class=main border=1 cellspacing=0 cellpadding=5>\n";
		$snatched_full .= "<tr><td class=colhead>Юзер</td><td class=colhead>Рейтинг</td><td class=colhead align=center>Начал / Закончил</td><td class=colhead align=center>Действие</td><td class=colhead align=center>Сидирует</td><td class=colhead align=center>ЛС</td></tr>";
			
		while ( $arr = mysql_fetch_assoc ( $res ) ) {

			$snatched_small [] = "<a href=\"".$REL_SEO->make_link('userdetails','id',$arr['userid'],'username',translit($arr["username"]))."\">" . get_user_class_color ( $arr ["class"], $arr ["username"] ) . "</a>";
			$snatched_full .= "<tr$highlight><td><a href=\"".$REL_SEO->make_link('userdetails','id',$arr['userid'],'username',translit($arr["username"]))."\">" . get_user_class_color ( $arr ["class"], $arr ["username"] ) . "</a>" . get_user_icons ( $arr ) . "</td><td nowrap>".ratearea($arr['ratingsum'],$arr['id'],'users',$CURUSER['id'])."</td><td align=center><nobr>" . mkprettytime($arr ["startedat"]) . "<br />" . mkprettytime($arr ["completedat"]) . "</nobr></td><td align=center><nobr>" . get_elapsed_time($arr ["last_action"]) . "</nobr></td><td align=center>" . ($arr ["seeder"] ? "<b><font color=green>Да</font>" : "<font color=red>Нет</font></b>") . "</td><td align=center><a href=\"".$REL_SEO->make_link('message','action','sendmessage','receiver',$arr['userid'])."\"><img src=pic/button_pm.gif border=\"0\"></a></td></tr>\n";
		}
		$snatched_full .= "</table>\n";
			
	if ($row ["seeders"] == 0 || ($row ["leechers"] / $row ["seeders"] >= 2))
		$reseed_button = "<form action=\"".$REL_SEO->make_link('takereseed')."\"><input type=\"hidden\" name=\"torrent\" value=\"$id\" /><input type=\"submit\" value=\"Позвать скачавших\" /></form>";
	if (! $_GET ["snatched"] == 1)
		tr ( "Скачавшие<br /><a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']),'snatched',1)."#snatched\" class=\"sublink\">[Посмотреть список]</a>", "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\"><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td class=bottom width=50%><i>Открыть</i></td></tr></table></div><div class=\"sp-body\">" . @implode ( ", ", $snatched_small ) . $reseed_button . '</div></div>', 1 );
	else
		tr ( "Скачавшие<br /><a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))."\" class=\"sublink\" name=\"snatched\">[Cпрятать список]</a>", $snatched_full, 1 );
}
	

	print ( "</table>\n" );


			print('</div>');
		print('</div>');


/*конец релиза*/
print('</div>');
	

	// make main category and childs
/*
бесит
<div style=\"text-align:right;\"><!-- AddThis Button BEGIN -->
<a class=\"addthis_button\" href=\"http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4b9d38957e4015b5\"><img src=\"http://s7.addthis.com/static/btn/v2/lg-share-".substr((string)$_COOKIE['lang'],0,2).".gif\" width=\"125\" height=\"16\" alt=\"{$REL_LANG->say_by_key('bookmark_this')}\" style=\"border:0\"/></a><script type=\"text/javascript\" src=\"http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4beeed1d66d87035\"></script>
<!-- AddThis Button END -->
*/

	





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
    $.get("<?=$REL_SEO->make_link('takeedit')?>", { ajax: 1, checkonly: "", id: <?=$id;?> }, function(data){
   $("#checkfield").empty();
   $("#checkfield").append(data);
});
})(jQuery);

return no_ajax;

}

//]]>
</script>
	<?



	print ( "<div class='clear'></div><div align=\"center\"><a href=\"#\" onclick=\"location.href='".$REL_SEO->make_link('pass_on','to','pre','from',$id)."'; return false;\">
<< Предыдущий релиз</a>&nbsp;
<a href=\"#\" onclick=\"location.href='".$REL_SEO->make_link('pass_on','to','pre','from',$id,'cat',$row ['category'])."'; return false;\">[из этой категории]</a>
&nbsp; | &nbsp;
<a href=\"#\" onclick=\"location.href='".$REL_SEO->make_link('pass_on','to','next','from',$id,'cat',$row ['category'])."'; return false;\">[из этой категории]</a>&nbsp;
<a href=\"#\" onclick=\"location.href='".$REL_SEO->make_link('pass_on','to','next','from',$id)."'; return false;\">
Следующий релиз >></a><br />
<a href=\"".$REL_SEO->make_link('browse')."\">Все релизы</a>
&nbsp; | &nbsp;
<a href=\"".$REL_SEO->make_link('browse','cat',$row['category'])."\">Все релизы этой категории</a></div>" );

}
$subres = sql_query ( "SELECT SUM(1) FROM comments WHERE toid = $id AND type='rel'" );
$subrow = mysql_fetch_array ( $subres );
$count = $subrow [0];

$limited = 10;

if (! $count) {

	print ('<div id="newcomment_placeholder">'. "<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">" );
	print ( "<tr><td class=\"colhead\" align=\"left\" colspan=\"2\">" );
	print ( "<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев {$REL_CONFIG['defaultbaseurl']}</div>" );
	print ( "<div align=\"right\"><a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))."#comments\" class=\"altlink_white\">Добавить комментарий</a></div>" );
	print ( "</td></tr><tr><td align=\"center\">" );
	print ( "Комментариев нет. <a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))."#comments\">Желаете добавить?</a>" );
	print ( "</td></tr></table><br /></div>");

} else {
	list ( $pagertop, $pagerbottom, $limit ) = pager ( $limited, $count, array('details','id',$id,'name',translit($row['name'])),array ('lastpagedefault' => 1 ) );

	$subres = sql_query ( "SELECT c.id, c.type, c.ip, c.ratingsum, c.text, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, " . "u.username, u.title, u.class, u.donor, u.info, u.enabled, u.ratingsum AS urating, u.gender, sessions.time AS last_access, e.username AS editedbyname FROM comments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN sessions ON c.user=sessions.uid LEFT JOIN users AS e ON c.editedby = e.id WHERE c.toid = " . "$id AND c.type='rel' GROUP BY c.id ORDER BY c.id $limit" ) or sqlerr ( __FILE__, __LINE__ );
	$allrows = prepare_for_commenttable($subres,$row['name'],$REL_SEO->make_link('details','id',$id,'name',translit($row['name'])));


	print ( "<table id=\"comments-table\" class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >" );
	print ( "<tr><td class=\"colhead\" align=\"center\" >" );
	print ( "<div style=\"float: left; width: auto;\" align=\"left\"> :: Список комментариев</div>" );
	print ( "<div align=\"right\"><a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))."#comments\" class=\"altlink_white\">{$REL_LANG->_('Add comment to %s','релизу')}</a></div>" );
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


$REL_TPL->assignByRef('to_id',$id);
$REL_TPL->assignByRef('is_i_notified',is_i_notified ( $id, 'relcomments' ));
$REL_TPL->assign('textbbcode',textbbcode('text'));
$REL_TPL->assignByRef('FORM_TYPE_LANG',$REL_LANG->_('Release'));
$FORM_TYPE = 'rel';
$REL_TPL->assignByRef('FORM_TYPE',$FORM_TYPE);
$REL_TPL->display('commenttable_form.tpl');

sql_query ( "UPDATE torrents SET views = views + 1 WHERE id = $id" );
set_visited('torrents',$id);
$REL_TPL->stdfoot();

?>
