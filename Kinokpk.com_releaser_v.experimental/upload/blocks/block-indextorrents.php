<?php
global  $REL_LANG, $REL_CACHE, $REL_CONFIG, $CURUSER, $REL_SEO, $REL_DB;

if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'&& $_GET["AJAXPAGER"]) {
	$pager=true;
	require_once ('../include/bittorrent.php');
	INIT();
	$page=(int)$_GET['page'];
} else {

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


	$perpage = 21;

	$resarray = $REL_CACHE->get('block-indextorrents','page'.($page?$page:''));
	$limit = ajaxpager(20, $count, array('blocks/block-indextorrents'), 'releases-table');
	if ($resarray===false) {
		$resarray=array();
		$res = $REL_DB->query("SELECT id,name,descr,images,free,category FROM torrents WHERE visible=1 AND banned=0 AND moderatedby<>0 ORDER BY added DESC $limit");
		while ($row = mysql_fetch_assoc($res)) $resarray[] = $row;
		$REL_CACHE->set('block-indextorrents','page'.($page?$page:''),$resarray);
	}
	$num = count($resarray);

	if (!$pager) {
	$content .= "<div id=\"pager_scrollbox\" align=\"center\"><table id=\"releases-table\" border=\"1\" cellspacing=\"0\" style=\"border-collapse: collapse\" width=\"95%\" class=\"tbnew\">";
	}
	$nc=1;

	$REL_CONFIG['pron_cats'] = explode(',',$REL_CONFIG['pron_cats']);
	
	foreach ($resarray as $row) {
		$pron=false;
	if ($nc == 1) { $content .= "<tr>"; }
		
		
		if ($row['category'] && $REL_CONFIG['pron_cats'] && !$CURUSER['pron']) {
			$categories = explode(',',$row['category']);
			foreach ($categories as $category)
			if (in_array($category,$REL_CONFIG['pron_cats'])) { $pron=true; break; }
		}
	
		

if (strlen($row['descr'])>1000) $row['descr'] = substr($row['descr'],0,1000).'...';

	if ($pron) { $image = 'pic/nopron.gif';  $row['name'] = $REL_LANG->say_by_key('xxx_release'); } else
		if ($row['images']) $image = array_shift(explode(",",$row['images'])); else $image='pic/noimage.gif';
		



 //  $content .= "<span class=\"fl mr10\"><a href=\"{$REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name']))}\"><img border=\"0\" src=\"$image\" width=\"170\" class=\"corners\" height=\"200\" title=\"{$row['name']}\" alt=\"{$row['name']}\"/></a></span><h5><b><a href=\"{$REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name']))}\">{$row['name']}</a></b></h5><br/><h5> <div align=\"left\">{$row['descr']}</div></h5><div class=\"clear5\"></div><div class=\"lineGrayDot\"></div><div class=\"clear5\"></div>";

 $content .= "<td  valign=\"top\"><div class=\"blProgrammsMain mr10\"><div class=\"clear10\"></div><a href=\"{$REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name']))}\"><img border=\"0\" src=\"$image\" width=\"170\" class=\"corners\" height=\"200\" title=\"{$row['name']}\" alt=\"{$row['name']}\"/></a><div class=\"clear10\"></div><h5>".(($row['free'])?'<img border="0" src="pic/freedownload.gif" alt="'.$REL_LANG->_('Golden release').'"/>&nbsp;':'')."<a href=\"{$REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name']))}\"><b>{$row['name']}</b></a></h5><div class=\"clear5\"></div><div class=\"clear10\"></div></div>";



















$content .= "</td>"; 
  


		++$nc;
	if ($nc == 5) { $nc=1; $content .= "</tr>"; }
	}
	if (!$pager)
	$content.='</table></div>';
	else print $content;
}
?>