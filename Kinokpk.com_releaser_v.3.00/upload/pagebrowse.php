<?php
/**
 * Pages browser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

dbconn();
//getlang('browse');
loggedinorreturn();

$page = (int) $_GET["page"];

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


if (is_array($wherea)) $where = implode(" AND ", $wherea);

// CACHE SYSTEM REMOVED UNTIL 2.75

$res = sql_query("SELECT SUM(1) FROM pages".($where?" WHERE $where":'')) or sqlerr(__FILE__,__LINE__);
$row = mysql_fetch_array($res);
$count = $row[0];


list($pagertop, $pagerbottom, $limit) = pager($CACHEARRAY['torrentsperpage'], $count, "pagebrowse.php?".$addparam);

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
		if (($catq['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"pagebrowse.php?cat={$child['id']}\">".makesafe($child['name'])."</a>";
		$resvalue['cat_names'] = get_cur_position_str($tree,$catq['id'],'pagebrowse').($chsel && is_array($chsel)?', '.implode(', ',$chsel):'');
		//$resarray[$resvalue['id']] = $resvalue;
	} else $resvalue['cat_names'] = get_cur_position_str($tree,$catq['id'],'pagebrowse');
	$resarray[$resvalue['id']] = $resvalue;
}

if (!$resarray) stderr($tracker_lang['error'],"Страниц не найдено. Но вы можете <a href=\"pageupload.php\">создать страницу</a> или <a href=\"javascript: history.go(-1)\">вернуться Назад</a>");



stdhead('Страницы');

print('
<table class="embedded" cellspacing="0" cellpadding="5" width="100%">
<tr><td class="colhead" align="center" colspan="10">Список страниц</td></tr>
<tr><td colspan="10">

<table class="embedded" align="center">');

print('<tr><td align="center" class="embedded" colspan="2"><div class="friends_search" style="width: 290px;">');
print("<form action=\"pagebrowse.php\" method=\"get\" style=\"width: 286px;\">".gen_select_area('cat',$tree,$cat,true)."<input type=\"submit\" class=\"button\" value=\"{$tracker_lang['search']}\"/></form>\n");
print('	<form method="get" action="pagebrowse.php" style="width: 286px;">
		<input type="text" name="search" style="width: 224px;"/>
		'.$rgselect.'<input class="btn button" type="submit" value="'.$tracker_lang['search'].'" />
		</form>
		</div></table>
		');
print("");
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
stdfoot();
?>