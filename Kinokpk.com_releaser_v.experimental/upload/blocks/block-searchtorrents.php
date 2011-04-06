<?php

global $REL_LANG, $REL_CACHE, $REL_CONFIG, $CURUSER, $REL_SEO;




if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../".$REL_SEO->make_link('index'));
	exit;
}

$blocktitle = "<span>Релизы</span>".(get_user_class() >= UC_USER ? "<font class=\"small\"> - [<a class=\"altlink\" href=\"".$REL_SEO->make_link('upload')."\"><b>Залить</b></a>]  </font>" : "<font class=\"small\"> - (новые поступления)</font>");

$page = (int) $_GET['page'];


$count = $REL_CACHE->get('block-indextorrents','count');
if ($count===false) {
	$count = get_row_count('torrents'," WHERE visible=1 AND banned=0 AND moderatedby<>0");
	$REL_CACHE->set('block-indextorrents','count',$count);
}

if (!$count) { $content = "<div align=\"center\">Нет релизов</div>"; } else {

	$content = '<div id="releases-table">';

	$perpage = 9;
	$limit = pager($perpage, $count, array(str_replace('.php','',$_SERVER['PHP_SELF'])) );

	$content .=pagertop();
	
	$resarray = $REL_CACHE->get('block-indextorrents','page'.($page?$page:''));
	if ($resarray===false) {
		$resarray=array();
		$res = sql_query("SELECT id,name,images,free,category FROM torrents WHERE visible=1 AND banned=0 AND moderatedby<>0 ORDER BY added DESC $limit") or sqlerr(__FILE__, __LINE__);
		while ($row = mysql_fetch_assoc($res)) $resarray[] = $row;
		$REL_CACHE->set('block-indextorrents','page'.($page?$page:''),$resarray);
	}
	$num = count($resarray);

	$content .= "<table border=\"1\" cellspacing=\"0\" style=\"border-collapse: collapse\" width=\"100%\">";
	$nc=1;

	$REL_CONFIG['pron_cats'] = explode(',',$REL_CONFIG['pron_cats']);

	foreach ($resarray as $row) {
		$pron=false;
		if ($nc == 1) { $content .= "<tr>"; }
		$content .= "<td  valign=\"top\">";
		if ($row['category'] && $REL_CONFIG['pron_cats'] && !$CURUSER['pron']) {
			$categories = explode(',',$row['category']);
			// if (get_user_class()==UC_SYSOP) { print ('<pre>'); print_r($REL_CONFIG['pron_cats']); }
			foreach ($categories as $category)
			if (in_array($category,$REL_CONFIG['pron_cats'])) { $pron=true; break; }
		}
		if ($pron) { $image = 'pic/nopron.gif';  $row['name'] = $REL_LANG->say_by_key('xxx_release'); } else
		if ($row['images']) $image = array_shift(explode(",",$row['images'])); else $image='pic/noimage.gif';
		$content .= "<div align=\"center\"><a href=\"".$REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name']))."\"><img border=\"0\" src=\"$image\" width=\"170\" height=\"200\" title=\"{$row['name']}\" alt=\"{$row['name']}\"/></a>";
		$content .= "<br/><br/>".(($row['free'])?'<img border="0" src="pic/freedownload.gif" alt="Золотой торрент"/>&nbsp;':'')."<a href=\"".$REL_SEO->make_link('details','id',$row['id'],'name',translit($row['name']))."\">{$row['name']}</a></div>";

		$content .= "</td>";
		++$nc;
		if ($nc == 4) { $nc=1; $content .= "</tr>"; }
	}

	$content .= "</table></div>";
	$content.=$pagerbottom;

}
?>




