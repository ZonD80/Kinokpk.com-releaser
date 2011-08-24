<?php

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'&& $_GET["AJAXPAGER"]) {
	$pager=true;
	require_once ('../include/bittorrent.php');
	INIT();
	$page=(int)$_GET['page'];
} else {
global $REL_LANG, $REL_CACHE, $REL_CONFIG, $CURUSER, $REL_SEO;



if (!defined('BLOCK_FILE')) {
	safe_redirect($REL_SEO->make_link('index'));
	exit;
}


$blocktitle = "<span>{$REL_LANG->_('Releases')}</span>".(get_privilege('upload_releases',false) ? "<font class=\"small\"> - [<a class=\"altlink\" href=\"".$REL_SEO->make_link('upload')."\"><b>{$REL_LANG->_('Upload')}</b></a>]  </font>" : "<font class=\"small\"> - ({$REL_LANG->_('new releases')}</font>");


}

$count = $REL_CACHE->get('block-indextorrents','count');
if ($count===false) {
	$count = get_row_count('torrents'," WHERE visible=1 AND banned=0 AND moderatedby<>0");
	$REL_CACHE->set('block-indextorrents','count',$count);
}

if (!$count) { $content = "<div align=\"center\">{$REL_LANG->_('No releases yet')}</div>"; } else {


	$perpage = 9;

	$resarray = $REL_CACHE->get('block-indextorrents','page'.($page?$page:''));
	$limit = ajaxpager(12, $count, array('blocks/block-indextorrents'), 'releases-table > tbody:last');
	if ($resarray===false) {
		$resarray=array();
		$res = sql_query("SELECT id,name,images,free,category FROM torrents WHERE visible=1 AND banned=0 AND moderatedby<>0 ORDER BY added DESC $limit") or sqlerr(__FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($res)) $resarray[] = $row;
		$REL_CACHE->set('block-indextorrents','page'.($page?$page:''),$resarray);
	}
	$num = count($resarray);

	if (!$pager) {
	$content .= "<div id=\"pager_scrollbox\"><table id=\"releases-table\" border=\"1\" cellspacing=\"0\" style=\"border-collapse: collapse\" width=\"100%\">";
	}
	$nc=1;

	$REL_CONFIG['pron_cats'] = explode(',',$REL_CONFIG['pron_cats']);
	
	foreach ($resarray as $row) {
		$pron=false;
		if ($nc == 1) { $content .= "<tr>"; }
		$content .= "<td  valign=\"top\">";
		if ($row['category'] && $REL_CONFIG['pron_cats'] && !$CURUSER['pron']) {
			$categories = explode(',',$row['category']);
			foreach ($categories as $category)
			if (in_array($category,$REL_CONFIG['pron_cats'])) { $pron=true; break; }
		}
		if ($pron) { $image = 'pic/nopron.gif';  $row['name'] = $REL_LANG->say_by_key('xxx_release'); } else
		if ($row['images']) $image = array_shift(explode(",",$row['images'])); else $image='pic/noimage.gif';
		$content .= "<div align=\"center\"><a href=\"".$REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name']))."\"><img border=\"0\" src=\"$image\" width=\"170\" height=\"200\" title=\"{$row['name']}\" alt=\"{$row['name']}\"/></a>";
		$content .= "<br/><br/>".(($row['free'])?'<img border="0" src="pic/freedownload.gif" alt="'.$REL_LANG->_('Golden release').'"/>&nbsp;':'')."<a href=\"".$REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name']))."\">{$row['name']}</a></div>";

		$content .= "</td>";
		++$nc;
		if ($nc == 4) { $nc=1; $content .= "</tr>"; }
	}
	if (!$pager)
	$content.='</table></div>';
	else print $content;
}
?>