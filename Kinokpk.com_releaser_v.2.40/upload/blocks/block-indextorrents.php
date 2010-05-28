<?php

if (isset($_GET['ajax']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
	$ajax = 1;

	require_once(dirname(dirname(__FILE__))."/include/bittorrent.php");
	dbconn(false);
	header ("Content-Type: text/html; charset=" . $tracker_lang['language_charset']);

	$page = (int) $_GET["page"];

} else $ajax=0;


if (!defined('BLOCK_FILE') && !$ajax) {
	Header("Location: ../index.php");
	exit;
}

if (!$ajax) $blocktitle = "Релизы".(get_user_class() >= UC_USER ? "<font class=\"small\"> - [<a class=\"altlink\" href=\"upload.php\"><b>Залить</b></a>]  </font>" : "<font class=\"small\"> - (новые поступления)</font>");

$page = (int) $_GET['page'];

if (!defined("CACHE_REQUIRED")){
	require_once(ROOT_PATH . 'classes/cache/cache.class.php');
	require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
}
$cache=new Cache();
$cache->addDriver('file', new FileCacheDriver());

$count = $cache->get('block-indextorrents', 'count');
if($count===false){
	$count = sql_query("SELECT COUNT(*) FROM torrents WHERE banned = 'no' AND visible = 'yes'");
	$count = @mysql_result($count,0);
	$cache->set('block-indextorrents', 'count', $count);

}

if (!$count) { $content = "<div align=\"center\">Нет релизов</div>"; } else {

	$content = '<div id="releases-table">';

	$perpage = 5;
	list($pagertop, $pagerbottom, $limit) = browsepager($perpage, $count, $_SERVER['PHP_SELF'] . "?" , "#releases-table" );

	$content.='<script language="javascript" type="text/javascript">

var no_ajax = true;

function pageswitcher(page) {
     (function($){
    if ($) no_ajax = false;
    $("#releases-table").empty();
    $("#releases-table").append(\'<div align="center"><img src="pic/loading.gif" border="0"/></div>\');
    $.get("blocks/block-indextorrents.php", { ajax: "", page: page }, function(data){
    $("#releases-table").empty();
    $("#releases-table").append(data);
});
})(jQuery);

window.location.href = "#releases-table";

return no_ajax;
}
</script>
';

	// get ID's
	$ids = $cache->get('block-indextorrents', 'ids'.(($page==0)?"":$page));

	if($ids===false){

		$idsrow = sql_query("SELECT id FROM torrents WHERE banned = 'no' AND visible = 'yes' ORDER BY added DESC $limit");
		while (list($id) = mysql_fetch_array($idsrow))
		$ids[] = $id;

		$time = time();
		$cache->set('block-indextorrents', 'ids'.(($page==0)?"":$page), $ids);

	}
	// get ID's END

	$content .= '<table width="100%">';
	$content .= "<tr><td>";
	$content .= $pagertop;
	$content .= "</td></tr>";
	foreach ($ids as $id) {
		$peers[$id] = array();
		$dd[$id] = array();
	}

	$ids = implode(",",$ids);



	$reldata = $cache->get('block-indextorrents', 'query'.(($page==0)?"":$page));
	if($reldata===false){

		$res = sql_query("SELECT torrents.*, categories.id AS catid, categories.name AS catname, categories.image AS catimage, users.username, users.id AS userid, users.class, descr_torrents.value, descr_details.name AS dname, descr_details.input, descr_details.spoiler FROM torrents LEFT JOIN users ON torrents.owner = users.id LEFT JOIN categories ON torrents.category = categories.id LEFT JOIN descr_torrents ON torrents.id = descr_torrents.torrent LEFT JOIN descr_details ON descr_details.id = descr_torrents.typeid  WHERE banned = 'no' AND visible = 'yes' AND torrents.id IN ($ids) AND descr_details.mainpage = 'yes' ORDER BY torrents.added DESC, descr_details.sort ASC");
		 
		while ($relarray = mysql_fetch_array($res))
		$reldata[] = $relarray;

		$cache->set('block-indextorrents', 'query'.(($page==0)?"":$page), $reldata);

	}

	// Now fucking my brain...

	foreach ($reldata as $release) {
		$namear[$release['id']] = $release['name'];
		$filenamear[$release['id']] = $release['filename'];
		$imagesar[$release['id']] = explode(",",$release['images']);
		$cat[$release['id']] = array('id'=>$release['catid'],'name' => $release['catname'],'img'=>$release['catimage']);
		$usernamear[$release['id']] = $release['username'];
		$useridar[$release['id']] = $release['userid'];
		$userclassar[$release['id']] = $release['class'];
		$ownerar[$release['id']] = $release['owner'];
		$sizear[$release['id']] = $release['size'];
		$addedar[$release['id']] = $release['added'];
		$commentsar[$release['id']] = $release['comments'];

		$tagsar[$release['id']] = $release['tags'];
		array_push($dd[$release['id']],array('name'=>$release['dname'],'value'=>$release['value'],'spoiler'=>$release['spoiler']));

	}
	//print_r($dd);

	foreach ($namear as $id => $tname) {
		$filename = $filenamear[$id];
		$torid = $id;
		$catid = $cat[$id]['id'];
		$catname = $cat[$id]['name'];
		$catimage = $cat[$id]['img'];
		$torname = $tname;
		$descr = '<table width="100%" border="1">';

		$tags = '';
		foreach(explode(",", $tagsar[$id]) as $tag)
		$tags .= "<a href=\"browse.php?tag=".$tag."\">".$tag."</a>, ";

		if ($tags)
		$tags = substr($tags, 0, -2);

		$descr .= "<tr><td valign=\"top\"><b>Жанр:</b></td><td>".$tags."</td></tr>";

		$spbegin = "<div style=\"position: static;\" class=\"news-wrap\"><div class=\"news-head folded clickable\"><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td class=bottom width=50%><i>Посмотреть</i></td></tr></table></div><div class=\"news-body\">";
		$spend = "</div></div>";
		foreach ($dd[$id] as $dddescr) {
			if ($dddescr['spoiler'] == 'yes') $spoiler=1; else $spoiler=0;
			if ($dddescr['value'] != '') $descr .= "<tr><td valign=\"top\"><b>".$dddescr['name'].":</b>".($spoiler?"<br/><small>Скрыто спойлером</small>":"")."</td><td>".($spoiler?$spbegin:'').format_comment($dddescr['value']).($spoiler?$spend:'')."</td></tr>";
		}
		 
		$descr .="</table>";

		$uprow = (isset($usernamear[$id]) ? ("<a href=userdetails.php?id=" . $ownerar[$id] . ">" . htmlspecialchars($usernamear[$id]) . "</a>") : "<i>Аноним</i>");

		$img1 = "<a href=\"details.php?id=$id\"><img src=\"pic/noimage.gif\" width=\"160\" border=\"0\" /></a>";
		$img2 = '';

		$content .= "<tr><td>";
		$content .= "<table width=\"100%\" class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">";
		$content .= "<tr>";
		$content .= "<td class=\"colhead\" colspan=\"2\" align=center>";
		$content .= $namear[$id];
		$content .= "<a class=\"altlink_white\" href=\"bookmark.php?torrent=$id\">";
		$content .= "</font></td>";
		$content .= "</tr>";
		$img1 = array_shift($imagesar[$id]);

		if ($img1 != NULL)
		$img1 = "<a href=\"details.php?id=$id\"><img width=\"160\" border='0' src=\"thumbnail.php?image=$img1&for=index\" /></a>"; else $img1 = "<a href=\"details.php?id=$id\"><img src=\"pic/noimage.gif\" width=\"160\" border=\"0\" /></a>";
		$content .= "<tr valign=\"top\"><td align=\"center\" width=\"160\">";
		$content .= $img1;
		$img2 = array_shift($imagesar[$id]);

		if ($img2 != NULL) {
			$img2 = "<a href=\"details.php?id=$id\"><img width=\"160\" border='0' src=\"thumbnail.php?image=$img2&for=index\" /></a>";
			$content .= "<br /><br />$img2"; }
			$content .= "</td>";
			$content .= "<td><div align=\"left\">".$descr."</div>
            <table width=\"100%\"><tr><td>
            <hr />
            <b>Выложил: </b><a href=\"userdetails.php?id=$useridar[$id]\">".get_user_class_color($userclassar[$id],$usernamear[$id])."</a><br>
            <b>Размер: </b>".mksize($sizear[$id])."<br>";

			$content .= "<b>Добавлен: </b>$addedar[$id]
            <hr />
                        <b>Коментарии: </b>$commentsar[$id]</b><br></td><td>
            <div align=\"right\">".(!empty($catname) ? "<a href=\"browse.php?cat=$catid\">
            <img src=\"pic/cats/$catimage\" alt=\"$catname\" title=\"$catname\" border=\"0\" /></a>" : "")."<br/>
            [<a href=\"details.php?id=$id\" alt=\"$namear[$id]\" title=\"$namear[$id]\"><b>Просмотреть</b></a>] [<a href=\"browse.php\">Полный список релизов</a>]</div></td></table></td>";
			$content .= "</tr>";
			$content .= "</table>";
			$content .= "</td></tr>";
			//        print_r ($cat);
	}
	$content .= "<tr><td>";
	$content .= $pagerbottom;
	$content .= "</td></tr>";
	$content .= "</table>";

}

$content.="</div>";

if ($ajax) {
	print ('
<script language="javascript" type="text/javascript">
$(document).ready(
function(){
  $(\'div.news-head\')
  .click(function() {
    $(this).toggleClass(\'unfolded\');
    $(this).next(\'div.news-body\').slideToggle(\'slow\');
  });
    $(function() {
  $("a[@href^=http]").each(
    function(){
            if(this.href.indexOf(location.hostname) == -1) {
        $(this).addClass(\'external\').attr(\'target\', \'_blank\');
      }
    }
  )
  });
});
</script>
');
	print ($content);
}

?>
