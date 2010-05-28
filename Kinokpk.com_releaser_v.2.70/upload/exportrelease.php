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

require_once("include/bittorrent.php");

dbconn();

gzip();

loggedinorreturn();

getlang('exportrelease');

if (!is_valid_id($_GET['id'])) stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$id = (int) $_GET['id'];

$res = sql_query("SELECT torrents.banned, torrents.topic_id, torrents.name, torrents.descr, torrents.size, torrents.id, torrents.images, torrents.category FROM torrents WHERE torrents.id = $id")
or sqlerr(__FILE__, __LINE__);
$row = mysql_fetch_array($res);

if (!$row || $row["banned"])
stderr($tracker_lang['error'], $tracker_lang['no_torrent_with_such_id']);


if ($row['images']) $image = array_shift(explode(",",$row['images']));

$tree = make_tree();

$cats = explode(',',$row['category']);
$cat= array_shift($cats);
$cat = get_cur_branch($tree,$cat);
$childs = get_childs($tree,$cat['parent_id']);
if ($childs) {
	foreach($childs as $child)
	if (($cat['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"browse.php?cat={$child['id']}\">".makesafe($child['name'])."</a>";
}
$catstr = get_cur_position_str($tree,$cat['id']).(is_array($chsel)?', '.implode(', ',$chsel):'');

$formvalue = "<div align=\"center\"><a href=\"{$CACHEARRAY['defaultbaseurl']}/details.php?id={$row['id']}\"><img width=\"200px\" src=\"{$image}\"></a></div><br /><br /><b>Полное название:</b> ".$row['name']."<br /><b>Тип:</b> ".$catstr."<br />";

$formvalue .= format_comment($row['descr']);

$formvalue .= "<b>Размер:</b> ".mksize($row['size'])."<br />";

if ($CACHEARRAY['use_integration']) $formvalue .= "<b>Релиз на форуме {$CACHEARRAY['forumname']}:</b> <a href=\"{$CACHEARRAY['forumurl']}/index.php?showtopic=".$row['topic_id']."\">{$CACHEARRAY['forumurl']}/index.php?showtopic=".$row['topic_id']."</a><br /><br />";


$formvalue .= "<div align=\"center\">Оригинал релиза на <a href=\"{$CACHEARRAY['defaultbaseurl']}/details.php?id={$row['id']}\">{$CACHEARRAY['sitename']}</a></b></div>";

stdhead($tracker_lang['exportrelease_mname']);

print('<script type="text/javascript">
function SelectAll(){
	var target = document.getElementById(\'exportform\');
	target.focus();
	target.select();		
}
</script>');

begin_main_frame();
begin_frame($tracker_lang['exportrelease_notice']);

print('<div align="center">'.$tracker_lang['exportrelease_warning'].'<br /><textarea name="exportform" rows="30" cols="40" id="exportform" wrap="soft" readonly>'.$formvalue.'</textarea><br /><a href="javascript://" onClick="javascript:SelectAll();">'.$tracker_lang['select_all'].'</a></div>');

end_frame();
end_main_frame();

stdfoot();

?>