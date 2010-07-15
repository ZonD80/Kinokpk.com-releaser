<?php
/**
 * Browse
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

dbconn();


//loggedinorreturn();

$page = (int) $_GET["page"];

$cat = (int) $_GET['cat'];
$relgroup = (int) $_GET['relgroup'];

$tree = make_tree();

if (isset($_GET['dead'])) $dead = 1; else $dead = 0;
if (isset($_GET['nofile'])) $nofile = 1; else $nofile = 0;
if (isset($_GET['unchecked'])) $unchecked = 1; else $unchecked = 0;

$searchstr = (string) $_GET['search'];
$cleansearchstr = htmlspecialchars($searchstr);
if (empty($cleansearchstr))
unset($cleansearchstr);

if (($cat!=0) && is_valid_id($cat)) {

	$cats = get_full_childs_ids($tree,$cat);
	if (!$cats) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));
	else {
		foreach ($cats as $catid) $catq[] = " FIND_IN_SET($catid,torrents.category) ";

		if ($catq) $catq = implode('OR',$catq);

		$wherea['cat'] = $catq;
		$addparam .= "cat=$cat&";
	}
}

if ($dead) {
	$wherea['dead'] = "torrents.visible = 0";
	$addparam .= "dead&";
	$dead = "''";
}
if ($nofile) {
	$wherea['nofile'] = "torrents.filename = 'nofile'";
	$addparam .= "nofile&";
	$nofile = "''";

}
if ($unchecked) {
	$wherea['unchecked'] = "torrents.moderatedby = 0";
	$addparam .= "unchecked&";
	$unchecked = "''";
}

if ($relgroup) {
	$wherea['relgroup'] = "torrents.relgroup =  $relgroup";
	$addparam .= "relgroup=$relgroup&";
}

if ((get_user_class()>=UC_MODERATOR)) { $modview=true; }

if ($unchecked && !$modview) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('unchecked_only_moders'));


if (!is_array($wherea) || !$modview) $wherea[] = "torrents.visible=1 AND torrents.banned=0 AND torrents.moderatedby<>0";

if (isset($cleansearchstr))
{
	$wherea['search'] = "torrents.name LIKE '%" . sqlwildcardesc($searchstr) . "%'";
	$addparam .= "search=" . urlencode($searchstr) . "&amp;";

}


if (is_array($wherea)) $where = implode(" AND ", $wherea);

// CACHE SYSTEM REMOVED UNTIL 2.75

$res = sql_query("SELECT SUM(1) FROM torrents".($where?" WHERE $where":'')) or sqlerr(__FILE__,__LINE__);
$row = mysql_fetch_array($res);
$count = $row[0];


list($pagertop, $pagerbottom, $limit) = pager($REL_CONFIG['torrentsperpage'], $count, $REL_SEO->make_link('browse').'?'.$addparam);

$query = "SELECT torrents.id, torrents.last_action,".($modview?" torrents.moderated, torrents.moderatedby, (SELECT username FROM users WHERE id=torrents.moderatedby) AS modname, (SELECT class FROM users WHERE id=torrents.moderatedby) AS modclass, torrents.visible, torrents.banned,":'')." torrents.category, torrents.images, torrents.free, torrents.name, torrents.times_completed, torrents.size, torrents.added, torrents.numfiles, torrents.filename, torrents.sticky, torrents.owner, torrents.relgroup AS rgid, relgroups.name AS rgname, relgroups.image AS rgimage,".($CURUSER?" IF((torrents.relgroup=0) OR (relgroups.private=0) OR FIND_IN_SET({$CURUSER['id']},relgroups.owners) OR FIND_IN_SET({$CURUSER['id']},relgroups.members),1,(SELECT 1 FROM rg_subscribes WHERE rgid=torrents.relgroup AND userid={$CURUSER['id']}))":' IF((torrents.relgroup=0) OR (relgroups.private=0),1,0)')." AS relgroup_allowed, " .
        "users.username, users.class FROM torrents LEFT JOIN relgroups ON torrents.relgroup=relgroups.id LEFT JOIN users ON torrents.owner = users.id".($where?" WHERE $where":'')." ORDER BY torrents.sticky DESC, torrents.added DESC $limit";
$res = sql_query($query) or sqlerr(__FILE__,__LINE__);

$seocats = $REL_SEO->assoc_cats();
while ($resvalue = mysql_fetch_array($res)) {
	$chsel = array();
	$cats = explode(',',$resvalue['category']);
	$catq= array_shift($cats);
	$resvalue['cat_seo'] = $seocats[$catq];
	$catq = get_cur_branch($tree,$catq);
	$childs = get_childs($tree,$catq['parent_id']);
	if ($childs) {
		foreach($childs as $child)
		if (($catq['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"".$REL_SEO->make_link("browse","cat",$child['id'])."\">".makesafe($child['name'])."</a>";
		$resvalue['cat_names'] = get_cur_position_str($tree,$catq['id']).($chsel && is_array($chsel)?', '.implode(', ',$chsel):'');
		//$resarray[$resvalue['id']] = $resvalue;
	} else $resvalue['cat_names'] = get_cur_position_str($tree,$catq['id']);
	$resarray[$resvalue['id']] = $resvalue;
}

if (!$resarray) stderr($REL_LANG->say_by_key('error'),"Ничего не найдено. <a href=\"javascript: history.go(-1)\">Назад</a>");

$ids = array_keys($resarray);
$ids = implode(",",$ids);

$rqueryarray = array();

$rquery = sql_query("SELECT torrents.id, category, comments, freefor, SUM(trackers.seeders) AS seeders, SUM(trackers.leechers) AS leechers FROM torrents LEFT JOIN trackers ON torrents.id=trackers.torrent WHERE torrents.id IN ($ids) GROUP BY torrents.id");

$REL_CONFIG['pron_cats'] = explode(',',$REL_CONFIG['pron_cats']); //pron

while ($rres = mysql_fetch_array($rquery)) $rqueryarray[$rres['id']] = $rres;
foreach ($resarray as $key => $value) {
	///////// pron
	$pron=false;
	if ($REL_CONFIG['pron_cats'] && !$CURUSER['pron'] && $rqueryarray[$key]['category']) {
		$rqueryarray[$key]['category'] = explode(',',$rqueryarray[$key]['category']);
		foreach ($rqueryarray[$key]['category'] as $category)
		if (in_array($category,$REL_CONFIG['pron_cats'])) $pron=true;
	}
	if ($pron) { $resarray[$key]['images'] = $REL_CONFIG['defaultbaseurl'].'/pic/nopron.gif';  $resarray[$key]['name'] = $REL_LANG->say_by_key('xxx_release'); }
	///////// pron
	$resarray[$key]['seeders'] = $rqueryarray[$key]['seeders'];
	$resarray[$key]['leechers'] = $rqueryarray[$key]['leechers'];
	$resarray[$key]['comments'] = $rqueryarray[$key]['comments'];
	$resarray[$key]['freefor'] = ($rqueryarray[$key]['freefor']?explode(',',$rqueryarray[$key]['freefor']):NULL);
}




stdhead($REL_LANG->say_by_key('browse'));


begin_frame('Список релизов '.($modview?'[<a href="'.$REL_SEO->make_link('browse','unchecked','').'">Показать непроверенные релизы отдельно</a>]':''));




$rgarrayres = sql_query("SELECT id,name FROM relgroups ORDER BY added DESC");
while($rgarrayrow = mysql_fetch_assoc($rgarrayres)) {
	$rgarray[$rgarrayrow['id']] = $rgarrayrow['name'];
}

if ($rgarray) {
	$rgselect = '&nbsp;'.$REL_LANG->say_by_key('relgroup').': <select style="width: 120px;" class="linkselect" name="relgroup"><option value="0">('.$REL_LANG->say_by_key('choose').')</option>';
	foreach ($rgarray as $rgid=>$rgname) $rgselect.='<option   value="'.$rgid.'"'.(($relgroup==$rgid)?" selected=\"1\"":'').'>'.$rgname."</option>\n";
	$rgselect.='</select>';
}



print("<div class=\"friends_search\">
<form class='formbr' action=\"".$REL_SEO->make_link('browse')."\" method=\"get\">".'
<input type="text" name="search" size="30" style="margin-right: 10px;"/>
'.gen_select_area('cat',$tree,$cat, true).'<br />
<div class="brel">
'.$rgselect.'
</div>
<input class="button" type="submit" size="40" value="'.$REL_LANG->say_by_key('search').'!" />
</form>
<div class="clear"></div>
<!-- Google Search -->
<form action="http://www.google.com/cse">
    <input name="cx" value="008925083164290612781:gpt7xhlrdou" type="hidden" />
    <input name="ie" value="utf-8" type="hidden" />
    <input name="q" size="43" type="text" />
    <input name="sa" class="button" value="Поиск Google!" type="submit" />
</form>
<!-- Google Search -->
</div>
');
end_frame();

if (isset($cleansearchstr)){
begin_frame($REL_LANG->say_by_key('search_results_for')." \"" . $cleansearchstr . "\"\n");
}else{
begin_frame('Релизы');
}

print("<div id=\"releases-table\">");


/* print("<table cellspacing=\"0\" cellpadding=\"0\" class=\"tabs\"><tbody><tr>
 <td class=\"tab0\"> </td><td nowrap=\"\" class=\"tab1\"><a href=\"friends.php\">Общее</a></td>
 <td class=\"tab\"> </td><td nowrap=\"\" class=\"tab2\"><a href=\"user_friends_requests.php\">Наше</a></td>
 <td class=\"tab\"> </td><td nowrap=\"\" class=\"tab2\"><a href=\"user_friends_requests.php\">Мультитрекер</a></td>
 <td class=\"tab3\"> </td></tr></tbody></table>\n");*/



if ($count) {

	print("<table align=\"center\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\">");
	print("<tr><td class=\"index\" colspan=\"12\">");
	print($pagertop);
	print("</td></tr>");
	$returnto = urlencode(basename($_SERVER["REQUEST_URI"]));
	torrenttable($resarray, "index", $returnto);
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

end_frame();
stdfoot();

?>
<script language="JavaScript">
<!--
$(document).ready(function () {
	 $('.blocks_r').hide();
});
//-->
</script>