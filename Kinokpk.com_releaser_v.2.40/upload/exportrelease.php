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

require_once("include/bittorrent.php");

dbconn(false);

gzip();

loggedinorreturn();

getlang('exportrelease');

if (!is_valid_id($_GET['id'])) stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$id = (int) $_GET['id'];

$res = sql_query("SELECT torrents.banned, torrents.topic_id, torrents.name, torrents.descr_type, torrents.size, torrents.id, torrents.tags, torrents.images, categories.name AS cat_name FROM torrents LEFT JOIN categories ON torrents.category = categories.id WHERE torrents.id = $id")
or sqlerr(__FILE__, __LINE__);
$row = mysql_fetch_array($res);

if (!$row || ($row["banned"] == "yes"))
stderr($tracker_lang['error'], $tracker_lang['no_torrent_with_such_id']);

$detid = sql_query("SELECT descr_torrents.value, descr_details.name, descr_details.hide, descr_details.input, descr_details.spoiler FROM descr_torrents LEFT JOIN descr_details ON descr_details.id = descr_torrents.typeid WHERE descr_torrents.torrent = ".$id." ORDER BY descr_details.sort ASC");

if ($row['images']) $image = array_shift(explode(",",$row['images']));

$formvalue = "[center][url={$CACHEARRAY['defaultbaseurl']}/details.php?id={$row['id']}][img]{$CACHEARRAY['defaultbaseurl']}/torrents/images/{$image}[/img][/url][/center]\n\n[b]Полное название:[/b] ".$row['name']."\n[b]Тип:[/b] ".$row['cat_name']."\n[b]Теги (жанры):[/b] ".$row['tags']."\n";

while ($did = mysql_fetch_array($detid))  {

	if (($did['value'] != '') && ($did['hide'] != 'yes')) $formvalue.="[b]".strip_tags($did['name']).":[/b] ".$did['value']."\n";

}

$formvalue .= "[b]Размер:[/b] ".mksize($row['size'])."\n";

if ($CACHEARRAY['use_integration']) $formvalue .= "[b]Релиз на форуме {$CACHEARRAY['forumname']}:[/b] [url]{$CACHEARRAY['forumurl']}/index.php?showtopic=".$row['topic_id']."[/url]\n\n";


$formvalue .= "[center][b]Оригинал релиза на [url={$CACHEARRAY['defaultbaseurl']}/details.php?id={$row['id']}]{$CACHEARRAY['sitename']}[/url][/b][/center]";

$formvalue = str_replace("[siteurl]",$CACHEARRAY['defaultbaseurl'],$formvalue);
$formvalue = str_replace("подробнее тут",'',$formvalue);

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

print('<div align="center">'.$tracker_lang['exportrelease_warning'].'<br/><textarea name="exportform" rows="30" cols="40" id="exportform" wrap="soft" readonly>'.$formvalue.'</textarea><br/><a href="javascript://" onClick="javascript:SelectAll();">'.$tracker_lang['select_all'].'</a></div>');

end_frame();
end_main_frame();

stdfoot();

?>