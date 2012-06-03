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

INIT();


//loggedinorreturn();

$page = (int) $_GET["page"];

$cat = (array) $_GET['cat'];
$cat = array_map('intval',$cat);

$relgroup = (int) $_GET['relgroup'];

$tree = make_tree();

if (isset($_GET['mainpage'])) $mainpage = 1; else $mainpage = 0;
if (isset($_GET['nofile'])) $nofile = 1; else $nofile = 0;
if (isset($_GET['unchecked'])) $unchecked = 1; else $unchecked = 0;

$searchstr = (string) $_GET['search'];
$cleansearchstr = htmlspecialchars($searchstr);
if (empty($cleansearchstr))
	unset($cleansearchstr);
$addparam[] = 'browse';

$allowed_orders = array('seeders','leechers','moderated','moderatedby','banned','visible','added','comments','size');

$order = (string)$_GET['order'];

if ($order&&!in_array($order,$allowed_orders)) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Unknown order type'));
elseif (!$order) $order='added';

$ordertype = ($_GET['asc']?'ASC':'DESC');

//search for other addparam asc at bottom

foreach ($cat as $catid) {
	$catq[] = " FIND_IN_SET($catid,torrents.category) ";

	$addparam[] = 'cat[]';
	$addparam[] = $catid;
}
if ($catq) {
	$catq = implode('OR',$catq);
	$wherea['cat'] = "($catq)";
}

if ($mainpage) {
	$wherea['mainpage'] = "torrents.visible = 0";
	$addparam[] = 'mainpage';
	$addparam[] = 1;
	$mainpage = "''";
}
if ($nofile) {
	$wherea['nofile'] = "torrents.filename = 'nofile'";
	$addparam[] = 'nofile';
	$addparam[] = 1;
	$nofile = "''";

}
if ($unchecked) {
	$wherea['unchecked'] = "torrents.moderatedby = 0";
	$addparam[] = 'unchecked';
	$addparam[] = 1;
	$unchecked = "''";
}

if ($relgroup) {
	$wherea['relgroup'] = "torrents.relgroup =  $relgroup";
	$addparam[] = 'relgroup';
	$addparam[] = $relgroup;
}

if (get_privilege('is_moderator',false)) {
	$modview=true;
}


if ($unchecked && !$modview) $REL_TPL->stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('unchecked_only_moders'));

if (!is_array($wherea) && !$modview) $wherea[] = "torrents.banned=0 AND torrents.moderatedby<>0";

if (isset($cleansearchstr))
{
	$wherea['search'] = "torrents.name LIKE '%" . sqlwildcardesc($searchstr) . "%'";
	$addparam[] = 'search';
	$addparam[] = urlencode($searchstr);

}


if (is_array($wherea)) $where = implode(" AND ", $wherea);

// CACHE SYSTEM REMOVED UNTIL 2.75

$res = $REL_DB->query("SELECT SUM(1) FROM torrents".($where?" WHERE $where":''));
$row = mysql_fetch_array($res);
$count = $row[0];

// ordering

foreach ($allowed_orders as $ord) {
	$orderlink[$ord] = $addparam;
	$orderlink[$ord][] = 'asc';
	$orderlink[$ord][] = ($_GET['asc']?'0':'1');
	$orderlink[$ord][] = 'order';
	$orderlink[$ord][] = $ord;
	
}
$REL_TPL->assign('orderlink',$orderlink);

$addparam[] = 'asc';
$addparam[] = ($_GET['asc']?'1':'0');
$addparam[] = 'order';
$addparam[] = $order;

// ordering end
$limit = ajaxpager($REL_CONFIG['torrentsperpage'], $count, $addparam, "torrenttable");

$query = "SELECT torrents.id, torrents.comments, torrents.seeders, torrents.leechers, torrents.freefor,".($modview?" torrents.moderated, torrents.moderatedby, (SELECT username FROM users WHERE id=torrents.moderatedby) AS modname, (SELECT class FROM users WHERE id=torrents.moderatedby) AS modclass, torrents.visible, torrents.banned,":'')." torrents.category, torrents.tags, torrents.images, torrents.free, torrents.name, torrents.size, torrents.added, torrents.numfiles, torrents.filename, torrents.sticky, torrents.owner, torrents.relgroup AS rgid, relgroups.name AS rgname, relgroups.image AS rgimage,".($CURUSER?" IF((torrents.relgroup=0) OR (relgroups.private=0) OR FIND_IN_SET({$CURUSER['id']},relgroups.owners) OR FIND_IN_SET({$CURUSER['id']},relgroups.members),1,(SELECT 1 FROM rg_subscribes WHERE rgid=torrents.relgroup AND userid={$CURUSER['id']}))":' IF((torrents.relgroup=0) OR (relgroups.private=0),1,0)')." AS relgroup_allowed, " .
		"users.username, users.class, users.donor, users.enabled, users.warned FROM torrents LEFT JOIN relgroups ON torrents.relgroup=relgroups.id LEFT JOIN users ON torrents.owner = users.id".($where?" WHERE $where":'')." ORDER BY torrents.sticky DESC, torrents.$order $ordertype $limit";
$res = $REL_DB->query($query);


$resarray = prepare_for_torrenttable($res);

if (!$resarray) $REL_TPL->stderr($REL_LANG->say_by_key('error'),"Ничего не найдено. <a href=\"javascript: history.go(-1)\">Назад</a>");

if (!pagercheck()) {
	$REL_TPL->stdhead($REL_LANG->say_by_key('browse'));

	$REL_TPL->begin_frame('Список релизов '.($modview?'[<a href="'.$REL_SEO->make_link('browse','unchecked','').'">Показать непроверенные релизы отдельно</a>]':''));


	$rgarrayres = $REL_DB->query("SELECT id,name FROM relgroups ORDER BY added DESC");
	while($rgarrayrow = mysql_fetch_assoc($rgarrayres)) {
		$rgarray[$rgarrayrow['id']] = $rgarrayrow['name'];
	}

	if ($rgarray) {
		$rgselect = '<span class="browse_relgroup">'.$REL_LANG->_('Select release group').':</span> <select style="width: 120px;" name="relgroup"><option value="0">'.$REL_LANG->say_by_key('choose').'</option>';
		foreach ($rgarray as $rgid=>$rgname) $rgselect.='<option   value="'.$rgid.'"'.(($relgroup==$rgid)?" selected=\"1\"":'').'>'.$rgname."</option>\n";
		$rgselect.='</select>';
	}





























	print("<div align=\"center\"><form action=\"".$REL_SEO->make_link('browse')."\" method=\"get\">".$REL_LANG->_('Type name here').':&nbsp;<input type="text" class="browse_search" name="search" size="30" style="margin-right: 10px;" value="'.$cleansearchstr.'"/>
			&nbsp;'.gen_select_area('cat',$tree,implode(',',$cat), true,true).'
			'.$rgselect.'

			<input class="button" type="submit" size="40" value="'.$REL_LANG->say_by_key('search').'!" />
			</form></div>
			');
	$REL_TPL->end_frame();

	if (isset($cleansearchstr)){
		$REL_TPL->begin_frame($REL_LANG->say_by_key('search_results_for')." \"" . $cleansearchstr . "\". {$REL_LANG->_('Found %s releases',$count)}\n");
	}else{
		$REL_TPL->begin_frame($REL_LANG->_('Releases'));
	}


	if ($count) {

		$returnto = urlencode(basename($_SERVER["REQUEST_URI"]));
		torrenttable($resarray, "index", $returnto);
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

	$REL_TPL->end_frame();
	$REL_TPL->stdfoot();
} else {
	$returnto = urlencode(basename($_SERVER["REQUEST_URI"]));
	torrenttable($resarray, "index", $returnto);
}
?>
