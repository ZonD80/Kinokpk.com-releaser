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


//loggedinorreturn();
parked();

$page = (int) $_GET["page"];

$cat = (int) $_GET['cat'];

if (isset($_GET['dead'])) $dead = 1; else $dead = 0;
if (isset($_GET['nofile'])) $nofile = 1; else $nofile = 0;


if (isset($_GET['ajax']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {

	$tagstr = unesc(base64_decode($_GET["tag"])); $ajax=1;
}
else {$ajax=0; $tagstr = unesc($_GET["tag"]); }

$cleantagstr = htmlspecialchars($tagstr);
if (empty($cleantagstr))
unset($cleantagstr);

$searchstr = unesc($_GET["search"]);
$cleansearchstr = htmlspecialchars($searchstr);
if (empty($cleansearchstr))
unset($cleansearchstr);

if (($cat!=0) && is_valid_id($cat)) {
	$wherea['cat'] = "torrents.category = $cat";
	$addparam = "cat=$cat&";
	$catajax = $cat;
} else $catajax = "''";

if ($dead) {
	$wherea['dead'] = "torrents.visible = 'no'";
	$addparam = "dead&";
	$dead = "''";
	$nocache = 1;
}
elseif ($nofile) {
	$wherea['nofile'] = "torrents.filename = 'nofile'";
	$addparam = "nofile&";
	$nofile = "''";
	$nocache = 1;

}
elseif (isset($cleansearchstr))
{
	$wherea['search'] = "torrents.name LIKE '%" . sqlwildcardesc($searchstr) . "%'";
	$addparam .= "search=" . urlencode($searchstr) . "&amp;";
	$nocache = 1;
}
else {$wherea['dead'] = "torrents.visible = 'yes'"; $nocache = 0; }

if (isset($cleantagstr))
{
	$wherea['tags'] = "torrents.tags LIKE '%" . sqlwildcardesc($tagstr) . "%'";
	$addparam .= "tag=" . urlencode($tagstr) . "&";
}


if (is_array($wherea)) $where = implode(" AND ", $wherea);

// adding cache types
if (!$nocache) {
	if (!empty($wherea['tags']) && empty($wherea['cat'])) { $cachetype = 'tags'; $cachename = "tag-".md5($cleantagstr)."-"; }
	elseif (empty($wherea['tags']) && !empty($wherea['cat'])) { $cachetype = 'cat'; $cachename = "cat-$cat-"; }
	else { $cachetype = 'normal'; $cachename = ''; }
}
// end



if ($ajax) header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);

$res = sql_query("SELECT COUNT(*) FROM torrents WHERE $where") or sqlerr(__FILE__,__LINE__);
$row = mysql_fetch_array($res);
$count = $row[0];


list($pagertop, $pagerbottom, $limit) = browsepager($CACHEARRAY['torrentsperpage'], $count, "browse.php?".$addparam , "#releases-table");

if (!$nocache) {

	if (!defined("CACHE_REQUIRED")){
		require_once(ROOT_PATH . 'classes/cache/cache.class.php');
		require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
		define("CACHE_REQUIRED",1);
	}
	$cache=new Cache();
	$cache->addDriver('file', new FileCacheDriver());

	$resarray = array();

	$resarray = $cache->get('browse-'.$cachetype, $cachename.'query'.(($page==0)?"":$page));

} else $resarray=false;

if ($resarray===false) {
	$query = "SELECT torrents.id, torrents.moderated, torrents.moderatedby, torrents.category, torrents.tags, torrents.images, torrents.free, torrents.name, torrents.times_completed, torrents.size, torrents.added, torrents.numfiles, torrents.filename, torrents.sticky, torrents.owner," .
        " categories.name AS cat_name, categories.image AS cat_pic, users.username, users.class FROM torrents LEFT JOIN categories ON category = categories.id LEFT JOIN users ON torrents.owner = users.id WHERE $where ORDER BY torrents.sticky ASC, torrents.added DESC $limit";
	$res = sql_query($query) or sqlerr(__FILE__,__LINE__);

	while ($resvalue = mysql_fetch_array($res)) {
		$resarray[$resvalue['id']] = $resvalue;
	}

}

if (!$resarray) stderr($tracker_lang['error'],"Ничего не найдено. <a href=\"javascript: history.go(-1)\">Назад</a>");

$ids = array_keys($resarray);
$ids = implode(",",$ids);

$rqueryarray = array();

$rquery = sql_query("SELECT id, comments, seeders, leechers, IF(numratings < {$CACHEARRAY['minvotes']}, NULL, ROUND(ratingsum / numratings, 1)) AS rating FROM torrents WHERE id IN ($ids)");
while ($rres = mysql_fetch_array($rquery)) $rqueryarray[$rres['id']] = $rres;

foreach ($resarray as $key => $value) {
	$resarray[$key]['seeders'] = $rqueryarray[$key]['seeders'];
	$resarray[$key]['leechers'] = $rqueryarray[$key]['leechers'];
	$resarray[$key]['rating'] = $rqueryarray[$key]['rating'];
	$resarray[$key]['comments'] = $rqueryarray[$key]['comments'];
}

if (!$nocache) $cache->set('browse-'.$cachetype, $cachename.'query'.(($page==0)?"":$page), $resarray);



if (!$ajax) {       stdhead($tracker_lang['browse']);

?>

<style type="text/css">
a.browselink {
	background-image: url('pic/new.png');
	background-position: right;
	background-repeat: no-repeat;
	padding: 0 43px 0 0;
}

a.browselink:visited {
	background-image: none;
	padding: 0 0 0 0;
}

a.catlink:link,a.catlink:visited {
	text-decoration: none;
}

a.catlink:hover {
	color: #A83838;
}
</style>

<table class="embedded" cellspacing="0" cellpadding="5" width="100%">
	<tr>
		<td class="colhead" align="center" colspan="10">Список релизов</td>
	</tr>
	<tr>
		<td colspan="10">

		<table class="embedded" align="center">
			<tr>
				<td class="bottom">
				<table class="bottom">

				<?

				$cats = genrelist();
				foreach ($cats as $cat)
				{
					$tags = taggenrelist($cat["id"]);
					$tagarray='';
					if (!$tags)
					$tagarray .= "  <i>Теги/жанры для данной категории не определены.</i>";
					else {
						foreach ($tags as $tag)
						$tagarray .= ', <a href="browse.php?tag='.$tag["name"].'">'.htmlspecialchars($tag["name"]).'</a>';
					}
					$tagarray = substr($tagarray,2);
					print("<tr><td width=\"30%\"><a class=\"catlink\" href=\"browse.php?cat=$cat[id]\">" . htmlspecialchars($cat[name]) . "</a></td><td><small><i>Список тегов/жанров для этой категории</i>:<br/>$tagarray</small></td></tr>\n");
				}

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
					value="008925083164290612781:v-qk13aiplq" type="hidden" /> <input
					name="ie" value="windows-1251" type="hidden" /> <input name="q"
					size="31" type="text" /> <input name="sa" value="Поиск Google!"
					type="submit" /></form>
				<!-- Google Search --></td>
			</tr>
		</table>
		<?

		if (isset($cleansearchstr))
		print("<tr><td class=\"index\" colspan=\"12\">".$tracker_lang['search_results_for']." \"" . htmlspecialchars($searchstr) . "\"</td></tr>\n");
		if (isset($cleantagstr))
		print("<tr><td class=\"index\" colspan=\"12\">Результаты поиска по тэгу: \"" . htmlspecialchars($tagstr) . "\"</td></tr>\n");
		print("</td></tr></table>");

}

?> <script language="javascript" type="text/javascript">

var no_ajax = true;

function pageswitcher(page) {

   (function($){
     if ($) no_ajax = false;
   $("#releases-table").empty();
   $("#releases-table").append('<div align="center"><img src="pic/loading.gif" border="0"/></div>');
   $.get("browse.php", { ajax: "", <?=($dead?"dead: '', ":"").($nofile?"nofile: '', ":"")?>cat: <?=$catajax?>, tag: "<?=base64_encode($_GET["tag"])?>", page: page }, function(data){
   $("#releases-table").empty();
   $("#releases-table").append(data);

});
})(jQuery);

window.location.href = "#releases-table";

return no_ajax;
}
</script> <?php
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

print("</div></table>");
stdfoot();

?>