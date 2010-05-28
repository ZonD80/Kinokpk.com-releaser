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


//loggedinorreturn();
parked();

$page = (int) $_GET["page"];

$cat = (int) $_GET['cat'];

$tree = make_tree();

if (isset($_GET['dead'])) $dead = 1; else $dead = 0;
if (isset($_GET['nofile'])) $nofile = 1; else $nofile = 0;
if (isset($_GET['unchecked'])) $unchecked = 1; else $unchecked = 0;


if (isset($_GET['ajax']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
	$ajax=1;
	$searchstr = unesc(base64_decode((string)$_GET["search"]));
}
else {$ajax=0; $searchstr = unesc((string)$_GET["search"]); }

$cleansearchstr = htmlspecialchars($searchstr);
if (empty($cleansearchstr))
unset($cleansearchstr);

if (($cat!=0) && is_valid_id($cat)) {


	$cats = get_full_childs_ids($tree,$cat);
	if (!$cats) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);
	else {
		foreach ($cats as $catid) $catq[] = " FIND_IN_SET($catid,torrents.category) ";

		if ($catq) $catq = implode('OR',$catq);

		$wherea['cat'] = $catq;
		$addparam .= "cat=$cat&";
	}
};

if ($dead) {
	$wherea['dead'] = "torrents.visible = 0";
	$addparam = "dead&";
	$dead = "''";
}
if ($nofile) {
	$wherea['nofile'] = "torrents.filename = 'nofile'";
	$addparam = "nofile&";
	$nofile = "''";

}
if ($unchecked) {
	$wherea['unchecked'] = "torrents.moderatedby = 0";
	$addparam = "unchecked&";
	$unchecked = "''";
}
if (!is_array($wherea)) $wherea[] = "torrents.visible=1 AND torrents.banned=0 AND torrents.moderatedby<>0";

if (isset($cleansearchstr))
{
	$wherea['search'] = "torrents.name LIKE '%" . sqlwildcardesc($searchstr) . "%'";
	$addparam .= "search=" . urlencode($searchstr) . "&amp;";

}


if (is_array($wherea)) $where = implode(" AND ", $wherea);



if ($ajax) header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);

$res = sql_query("SELECT COUNT(*) FROM torrents WHERE $where") or sqlerr(__FILE__,__LINE__);
$row = mysql_fetch_array($res);
$count = $row[0];


list($pagertop, $pagerbottom, $limit) = browsepager($CACHEARRAY['torrentsperpage'], $count, "browse.php?".$addparam , "#releases-table");

$query = "SELECT torrents.id, torrents.moderated, torrents.moderatedby, torrents.category, torrents.images, torrents.free, torrents.name, torrents.times_completed, torrents.size, torrents.added, torrents.numfiles, torrents.filename, torrents.sticky, torrents.owner," .
        "users.username, users.class FROM torrents LEFT JOIN users ON torrents.owner = users.id WHERE $where ORDER BY torrents.sticky DESC, torrents.added DESC $limit";
$res = sql_query($query) or sqlerr(__FILE__,__LINE__);

while ($resvalue = mysql_fetch_array($res)) {
	$chsel = array();
	$cats = explode(',',$resvalue['category']);
	$catq= array_shift($cats);
	$catq = get_cur_branch($tree,$catq);
	$childs = get_childs($tree,$catq['parent_id']);
	if ($childs) {
		foreach($childs as $child)
		//			if (($catq['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"browse.php?cat={$child['id']}\"><img src=\"pic/cats/{$child['image']}\" title=\"{$child['name']}\" alt=\"{$child['name']}\"/></a>";
		//		$resvalue['cat_names'] = get_cur_position_str($tree,$catq['id'],'browse',true).(is_array($chsel)?', '.implode(', ',$chsel):'');
		if (($catq['id'] != $child['id']) && in_array($child['id'],$cats)) $chsel[]="<a href=\"browse.php?cat={$child['id']}\">".makesafe($child['name'])."</a>";
		$resvalue['cat_names'] = get_cur_position_str($tree,$catq['id']).(is_array($chsel)?', '.implode(', ',$chsel):'');

		//$resarray[$resvalue['id']] = $resvalue;
	} else $resvalue['cat_names'] = get_cur_position_str($tree,$catq['id'],'browse',true);
	$resarray[$resvalue['id']] = $resvalue;


}

if (!$resarray) stderr($tracker_lang['error'],"Ничего не найдено. <a href=\"javascript: history.go(-1)\">Назад</a>");

$ids = array_keys($resarray);
$ids = implode(",",$ids);

$rqueryarray = array();

$rquery = sql_query("SELECT id, comments, seeders+remote_seeders AS seeders, leechers+remote_leechers AS leechers FROM torrents WHERE id IN ($ids)");
while ($rres = mysql_fetch_array($rquery)) $rqueryarray[$rres['id']] = $rres;
foreach ($resarray as $key => $value) {
	$resarray[$key]['seeders'] = $rqueryarray[$key]['seeders'];
	$resarray[$key]['leechers'] = $rqueryarray[$key]['leechers'];
	$resarray[$key]['comments'] = $rqueryarray[$key]['comments'];
}




if (!$ajax) {       stdhead($tracker_lang['browse']);

?>

<table class="embedded" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<td class="colhead" align="center" colspan="10">Список релизов [<a
			href="browse.php?unchecked">Непроверенные релизы</a>]</td>
	</tr>
	<tr>
		<td colspan="10">

		<table class="embedded" align="center">
			<tr>
				<td class="bottom">
				<table class="bottom">

				<?

				print("<tr><td colspan=\"2\"><form action=\"browse.php\" method=\"get\">".gen_select_area('cat',$tree,$cat , true)."<input type=\"submit\" value=\"{$tracker_lang['go']}\"/></form></td></tr>\n");

				?>
				</table>
				</td>
			</tr>
			<tr>
				<td align="center" class="embedded" colspan="2">
				<form method="get" action="browse.php"><?=$tracker_lang['search'];?>:
				<input type="text" name="search" size="40" /> <input class="btn"
					type="submit" value="<?=$tracker_lang['search'];?>!" /></form>
				<!-- Google Search -->
				<form action="http://www.google.com/cse"><input name="cx"
					value="008925083164290612781:gpt7xhlrdou" type="hidden" /> <input
					name="ie" value="windows-1251" type="hidden" /> <?=$tracker_lang['search'];?>:<input
					name="q" size="31" type="text" /> <input name="sa"
					value="Поиск Google!" type="submit" /></form>
				<!-- Google Search --></td>
			</tr>
		</table>
		<?

		if (isset($cleansearchstr))
		print("<tr><td class=\"index\" colspan=\"12\">".$tracker_lang['search_results_for']." \"" . $cleansearchstr . "\"</td></tr>\n");
		print("</td></tr></table>");

}

if (!$ajax) print '<script language="javascript" type="text/javascript">
//<![CDATA[
var no_ajax = true;
var switched=0;
function pageswitcher(page) {

   (function($){
     if ($) no_ajax = false;
   $("#releases-table").empty();
   $("#releases-table").append(\'<div align="center"><img src="pic/loading.gif" border="0"/></div>\');
   $.get("browse.php", { ajax: 1,'.($dead?"dead: '', ":"").($nofile?"nofile: '', ":"").($unchecked?"unchecked: '', ":"").($cleansearchstr?"search: '".base64_encode($cleansearchstr)."', ":"").($join?"join: 1, ":"").($catajax?"cat: $catajax, ":"").' page: page }, function(data){
   $("#releases-table").empty();
   $("#releases-table").append(data);

});
})(jQuery);

if (!switched){
window.location.href = window.location.href+"#releases-table";
switched++;
}
else window.location.href = window.location.href;


return no_ajax;
}
//]]>
</script>';

print("<div id=\"releases-table\">");
if ($count) {

	print("<table class=\"embedded\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\">");
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