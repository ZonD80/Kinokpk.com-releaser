<?php
/**
 * Recounter for different data
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once('include/bittorrent.php');
INIT();
loggedinorreturn();
get_privilege('recountadmin');
httpauth();

$action = trim((string)$_GET['a']);

if ($action=='recountorrents') {
	do {

		$res = sql_query("SELECT id, filename FROM torrents") or sqlerr(__FILE__,__LINE__);
		$ar = array();
		while ($row = mysql_fetch_array($res)) {
			$id = $row[0];
			$ar[$id] = 1;
			$far[$id] = $row[1];
		}

		if (!count($ar))
		break;

		$dp = @opendir(ROOT_PATH."torrents");
		if (!$dp)
		break;

		$ar2 = array();
		while (($file = @readdir($dp)) !== false) {
			if (!preg_match('/^(\d+)\.torrent$/', $file, $m))
			continue;
			$id = $m[1];
			$ar2[$id] = 1;
			if (isset($ar[$id]) && $ar[$id])
			continue;
			$ff = ROOT_PATH.'torrents/'.$file;
			@unlink($ff);
		}
		@closedir($dp);

		if (!count($ar2))
		break;

		$delids = array();
		foreach (array_keys($ar) as $k) {
			if (isset($ar2[$k]) && $ar2[$k])
			continue;
			if ($far[$k] != 'nofile')
			$delids[] = $k;
			unset($ar[$k]);
		}
		if ($delids)
		foreach ($delids as $did) deletetorrent($did);
	} while (0);
	safe_redirect($REL_SEO->make_link('recountadmin'),3);
	stderr($REL_LANG->say_by_key('success'),sprintf($REL_LANG->say_by_key('torrent_recounted'),count($delids)),'success');
}
elseif ($action=='recountcomments') {
	$allowed_types = array(''=>'torrents','poll'=>'polls','news'=>'news','user'=>'users','req'=>'requests','rg'=>'relgroups','rgnews'=>'rgnews','forum'=>'forum_topics');
	foreach ($allowed_types AS $ctype=>$table) {
		sql_query("UPDATE $table SET comments = (SELECT SUM(1) FROM comments WHERE type='$ctype' AND toid=$table.id) WHERE $table.id=$table.id");
		$num_changed = mysql_affected_rows();
		if ($num_changed) clear_comment_caches($ctype.'comments');
		$to_msg .= $REL_LANG->_("Difference in %s was %s<br/>",$REL_LANG->_($ctype.'comments'),$num_changed);
	}
	safe_redirect($REL_SEO->make_link('recountadmin'),3);
	stderr($REL_LANG->_("Successfull"),$to_msg,'success');
}

$REL_TPL->stdhead($REL_LANG->_('Recounter'));
$REL_TPL->begin_frame($REL_LANG->_('This page allows you to sync database values'));
$REL_TPL->output();
$REL_TPL->end_frame();
$REL_TPL->stdfoot();
?>