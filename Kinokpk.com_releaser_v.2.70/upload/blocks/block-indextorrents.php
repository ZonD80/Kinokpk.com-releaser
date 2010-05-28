<?php

global $tracker_lang, $CACHE;

if (isset($_GET['ajax']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
	$ajax = 1;

	require_once(dirname(dirname(__FILE__))."/include/bittorrent.php");
	dbconn();
	header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);

	$page = (int) $_GET["page"];

} else $ajax=0;


if (!defined('BLOCK_FILE') && !$ajax) {
	Header("Location: ../index.php");
	exit;
}

if (!$ajax) $blocktitle = "Релизы".(get_user_class() >= UC_USER ? "<font class=\"small\"> - [<a class=\"altlink\" href=\"upload.php\"><b>Залить</b></a>]  </font>" : "<font class=\"small\"> - (новые поступления)</font>");

$page = (int) $_GET['page'];


$count = $CACHE->get('block-indextorrents','count');
if ($count===false) {
	$count = get_row_count('torrents'," WHERE visible=1 AND banned=0 AND moderatedby<>0");
	$CACHE->set('block-indextorrents','count',$count);
}

if (!$count) { $content = "<div align=\"center\">Нет релизов</div>"; } else {

	$content = '<div id="releases-table">';

	$perpage = 12;
	list($pagertop, $pagerbottom, $limit) = browsepager($perpage, $count, $_SERVER['PHP_SELF'] . "?" , "#releases-table" );

	if (!$ajax) $content.='<script language="javascript" type="text/javascript">
//<![CDATA[
var no_ajax = true;

function pageswitcher(page) {
     (function($){
    if ($) no_ajax = false;
    $("#releases-table").empty();
    $("#releases-table").append(\'<div align="center"><img src="pic/loading.gif" border="0" alt="loading"/></div>\');
    $.get("blocks/block-indextorrents.php", { ajax: 1, page: page }, function(data){
    $("#releases-table").empty();
    $("#releases-table").append(data);
});
})(jQuery);

window.location.href = "#releases-table";

return no_ajax;
}
//]]>
</script>
';

	$resarray = $CACHE->get('block-indextorrents','page'.($page?$page:''));
	if ($resarray===false) {
		$resarray=array();
		$res = sql_query("SELECT id,name,images,free FROM torrents WHERE visible=1 AND banned=0 AND moderatedby<>0 ORDER BY added DESC $limit") or sqlerr(__FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($res)) $resarray[] = $row;
		$CACHE->set('block-indextorrents','page'.($page?$page:''),$resarray);
	}
	$num = count($resarray);

	$content .= "<table border=\"1\" cellspacing=\"0\" style=\"border-collapse: collapse\" width=\"100%\">";
	$content .=('<tr><td colspan="5">'.$pagertop.'</td></tr>');
	$nc=1;
	foreach ($resarray as $row) {
		if ($nc == 1) { $content .= "<tr>"; }
		$content .= "<td  valign=\"top\">";
		if ($row['images']) $image = array_shift(explode(",",$row['images'])); else $image='pic/noimage.gif';
		$content .= "<div align=\"center\"><a href=\"details.php?id={$row['id']}\"><img border=\"0\" src=\"$image\" width=\"170\" height=\"200\" title=\"{$row['name']}\" alt=\"{$row['name']}\"/></a>";
		$content .= "<br/><br/>".(($row['free'])?'<img border="0" src="pic/freedownload.gif" alt="Золотой торрент"/>&nbsp;':'')."<a href=\"details.php?id={$row['id']}\">{$row['name']}</a></div>";

		$content .= "</td>";
		++$nc;
		if ($nc == 5) { $nc=1; $content .= "</tr>"; }
	}

	$content .= "<tr><td colspan=\"5\">$pagerbottom</td></tr></table></div>";

	if ($ajax) print $content;
}
?>




