<?php
/**
 * Release exporter
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require_once("include/bittorrent.php");

INIT();


loggedinorreturn();



if (!is_valid_id($_GET['id'])) stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

$id = (int) $_GET['id'];

$res = sql_query("SELECT torrents.banned, torrents.name, torrents.descr, torrents.size, torrents.id, torrents.images, torrents.category FROM torrents WHERE torrents.id = $id")
or sqlerr(__FILE__, __LINE__);
$row = mysql_fetch_array($res);

if (!$row || $row["banned"])
stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('no_torrent_with_such_id'));


if ($row['images']) $image = array_shift(explode(",",$row['images']));

$tree = make_tree();

$cats = explode(',',$row['category']);
$cat= array_shift($cats);
$cat = get_cur_branch($tree,$cat);
$childs = get_childs($tree,$cat['parent_id']);
if ($childs) {
	foreach($childs as $child)
	if (($cat['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"".$REL_SEO->make_link('browse','cat',$child['id'])."\">".makesafe($child['name'])."</a>";
}
$catstr = get_cur_position_str($tree,$cat['id']).(is_array($chsel)?', '.implode(', ',$chsel):'');

$formvalue = "<div align=\"center\"><a href=\"".$REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name']))."\"><img width=\"200px\" src=\"{$image}\"></a></div><br /><br /><b>Полное название:</b> ".$row['name']."<br /><b>Тип:</b> ".$catstr."<br />";

$formvalue .= format_comment($row['descr'],true);

$formvalue .= "<b>Размер:</b> ".mksize($row['size'])."<br />";

$formvalue .= "<div align=\"center\">Оригинал релиза на <a href=\"".$REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name']))."\">{$REL_CONFIG['sitename']}</a></b></div>";

$REL_TPL->stdhead($REL_LANG->say_by_key('exportrelease_mname'));

print('<script type="text/javascript">
function SelectAll(){
	var target = document.getElementById(\'exportform\');
	target.focus();
	target.select();		
}
</script>');

$REL_TPL->begin_main_frame();
print("<div id=\"tabs\"><ul>
	<li class=\"tab2\"><a href=\"".$REL_SEO->make_link('details','id',$id,'name',translit($row['name']))."\"><span>Описание</span></a></li>
	<li nowrap=\"\" class=\"tab2\"><a href=\"".$REL_SEO->make_link('torrent_info','id',$id,'name',translit($row['name']))."\"><span>{$REL_LANG->say_by_key('torrent_info')}</span></a></li>
	<li nowrap=\"\" class=\"tab1\"><a href=\"".$REL_SEO->make_link('exportrelease','id',$id,'name',translit($row['name']))."\"><span>{$REL_LANG->say_by_key('exportrelease_mname')}</span></a></li>
	</ul></div>\n <br />");
$REL_TPL->begin_frame($REL_LANG->say_by_key('exportrelease_notice'));

print('<div align="center"><textarea name="exportform" rows="20" cols="100" id="exportform" wrap="soft" readonly>'.$formvalue.'</textarea><br />'.$REL_LANG->say_by_key('exportrelease_warning').'<br /><a href="javascript://" onClick="javascript:SelectAll();">'.$REL_LANG->say_by_key('select_all').'</a></div>');

$REL_TPL->end_frame();
$REL_TPL->end_main_frame();
$REL_TPL->stdfoot();

?>