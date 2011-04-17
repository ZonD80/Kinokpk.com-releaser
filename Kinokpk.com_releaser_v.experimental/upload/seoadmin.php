<?php
/**
 * Human readable urls administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

INIT();
loggedinorreturn();

get_privilege('seo_admincp');

httpauth();

function list_rewrites($server_type) {
	global $REL_CACHE;
	$cache = $REL_CACHE->get('system','seorules');
	if ($cache) {
		foreach ($cache as $row) {
			$scripts[] = $row['script'];
			$SR[$row['script']][$row['parameter']] = $row['repl'];
			$SO[$row['script']][$row['parameter']] = $row['sort'];
			$SU[$row['script']][$row['parameter']] = explode(',', $row['unset_params']);
		}
	}
	else return "# no rewrites has been configured yet";
		
	foreach ($scripts as $script) {
		if (isset($SR[$script]['{base}'])) $destar[0] = $SR[$script]['{base}'];
		else $destar['{base}'] = "$script.php";

		foreach ($SR[$script] as $param => $value) {

			foreach ($SU[$script][$param] as $to_unset) {
				unset($destar[$SO[$script][$to_unset]]);
				unset($orig[$SO[$script][$to_unset]]);
			}
			$destar[$SO[$script][$param]] = $value;
			$orig[$SO[$script][$param]] = "$param=%s";
		}
		ksort($destar);
		ksort($orig);
		$i=0;
		foreach ($orig AS $key => $uri_part) {
			if ($key=='{base}') unset($orig[$key]); else {
				$i++;
				$orig[$key] = sprintf($uri_part,'$'.$i);
			}
		}
			
		$return[] = ($server_type=='apache'?'RewriteRule ^':'rewrite ^/').str_replace('%s','(.*?)',implode('',$destar))."&nbsp;&nbsp;&nbsp;&nbsp;/$script.php".($orig?"?".implode('&',$orig):'').($server_type=='apache'?'&nbsp; [L, QSA]': '&nbsp; last;');
		unset($orig);
		unset($destar);
	}
	return "# here are rewrites configured for $server_type<br/># please verify all rewrites manually before applying to server configuration<br/><br/>".implode("<br/>",$return);
}
/**
 * Prepares data to use in SQL-query
 * @param array $arr data
 * @return array Prepared data
 */
function prepare_data($arr) {
	$stripped = array('script','parameter','repl','unset_params');
	foreach ($stripped as $k) $arr[$k] = sqlesc(trim(htmlspecialchars((string)$arr[$k])));
	$arr['sort'] = intval($arr['sort']);
	$arr['enabled'] = ($arr['enabled']?1:0);
	return $arr;
}
$action = trim((string)$_GET['a']);
$id = (int)$_GET['id'];
$allowed_actions = array('add','edit','saveedit','saveadd','reorder','react','delete','genrewrites');
if ($action&&!in_array($action,$allowed_actions)) stderr($REL_LANG->_('Error'),$REL_LANG->_('Unknown action'));

if ($action=='genrewrites') {
	$type = (string)trim($_GET['type']);

	$pages_for = explode(',','index,bookmarks,browse,friends,mytorrents,newsarchive,newsoverview,online,peers,polloverview,pollsarchive,present,relgroups,requests,rgnewsarchive,rgnewsoverview,topten,userhistory,users,viewrequests,votesview');
	if (!$type) $REL_TPL->stderr($REL_LANG->_('Select server type'),$REL_LANG->_('Please select server type to generate rewrites:<br/><a href="%s">Apache</a> | <a href="%s">Nginx</a>',$REL_SEO->make_link('seoadmin','a','genrewrites','type','apache'),$REL_SEO->make_link('seoadmin','a','genrewrites','type','nginx')),'success');
	elseif ($type=='apache'||$type=='nginx') {
		$REL_TPL->stdhead($REL_LANG->_('Rewrites for %s listing',$type));
		$REL_TPL->begin_frame($REL_LANG->_('This list will help you to configure rewrites.').' <a href="'.$REL_SEO->make_link('seoadmin').'">'.$REL_LANG->_('Back to').' '.$REL_LANG->_("Human Readable URLs configuration (SEO)").'</a>');

		print '<pre>';
		print (list_rewrites($type));
		print '</pre>';
		$REL_TPL->end_frame();
		$REL_TPL->stdfoot();
		die();
	}
	else $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Unknown server type'));
}
if ($action=='react') {
	$curact = @mysql_result(sql_query("SELECT enabled FROM seorules WHERE id=$id"),0);
	sql_query("UPDATE seorules SET enabled=".($curact?0:1)." WHERE id=$id");
	$REL_CACHE->clearCache('system','seorules');
	headers(REL_AJAX);
	die($curact?$REL_LANG->_("No"):$REL_LANG->_("Yes"));
} elseif ($action=='delete') {
	sql_query("DELETE FROM seorules WHERE id=$id");
	$REL_CACHE->clearCache('system','seorules');
	stderr($REL_LANG->_("Successful"),$REL_LANG->_("Rule deleted"),'success');
} elseif ($action=='reorder') {
	$arr = array_map('intval',(array)$_POST['order']);
	if (!$arr) stderr($REL_LANG->_("Error"),$REL_LANG->_("Missing form data"));
	foreach ($arr as $bid=>$order) {
		sql_query("UPDATE seorules SET sort=$order WHERE id=".(int)$bid) or sqlerr(__FILE__,__LINE__);
	}
	$REL_CACHE->clearCache('system','seorules');
	safe_redirect($REL_SEO->make_link("seoadmin"),1);
	stderr($REL_LANG->_("Successful"),$REL_LANG->_("Parametres order saved"),'success');
}
$REL_TPL->stdhead($REL_LANG->_("SEO administration panel"));

$REL_TPL->begin_frame("<a href=\"{$REL_SEO->make_link('seoadmin')}\">{$REL_LANG->_("SEO administration panel")}</a> | <a href=\"{$REL_SEO->make_link("seoadmin","a",'add')}\">{$REL_LANG->_("Add new rule")}</a> | <a href=\"{$REL_SEO->make_link("seoadmin","a",'genrewrites')}\">{$REL_LANG->_("Generate rewrites")}</a>");
if (!$action) {
	$array = array();
	$res = sql_query("SELECT * FROM seorules ORDER BY script,parameter DESC, sort ASC");
	while ($row = mysql_fetch_assoc($res)) {
		$array[$row['script']][] = $row;
	}
	$REL_TPL->assign('rules',$array);
}
elseif ($action=='edit'||$action=='add') {
	if ($action=='edit') {
		$rule = sql_query("SELECT * FROM seorules WHERE id=$id");
		$rule = mysql_fetch_assoc($rule);
		if (!$rule) {
			stdmsg($REL_LANG->_("Error"),$REL_LANG->_("Invalid id"));
			$REL_TPL->stdfoot();
			die();
		}
	}
	$REL_TPL->assignByRef('rule',$rule);

	$REL_TPL->assign('ACTION',$action);
}
elseif ($action=='saveedit'||$action=='saveadd') {
	$arr = (array)$_POST['arr'];
	if (!$arr) stderr($REL_LANG->_("Error"),$REL_LANG->_("Missing form data"));
	$arr = prepare_data($arr);
	if ($action=='saveadd') {
		sql_query("INSERT INTO seorules (".implode(',',array_keys($arr)).") VALUES (".implode(',',array_values($arr)).")");
	} else {
		foreach ($arr as $k=>$a) $to_query[] = "$k=$a";
		sql_query("UPDATE seorules SET ".implode(',',$to_query)." WHERE id=$id");
	}
	$REL_CACHE->clearCache('system','seorules');

	if (!mysql_errno()) stdmsg($REL_LANG->_("Successful"),$REL_LANG->_("Rule saved"),'success');
	else stdmsg($REL_LANG->_("Error"),$REL_LANG->_("Rule does not saved due MySQL error:").' '.mysql_error());
	$REL_TPL->end_frame();
	$REL_TPL->stdfoot();
	die();
}
$REL_TPL->output(($action=='add')?'edit':$action);
$REL_TPL->end_frame();

$REL_TPL->stdfoot();
?>