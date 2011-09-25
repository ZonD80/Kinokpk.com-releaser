<?php
/**
 * User torrents viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require_once("include/bittorrent.php");

INIT();

loggedinorreturn();


$REL_TPL->stdhead($REL_LANG->say_by_key('my_releases'));

$where = "WHERE owner = " . $CURUSER["id"] . " AND banned=0";
$res = $REL_DB->query("SELECT SUM(1) FROM torrents $where");
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$count) {
	$REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('not_releases'),'error');
	$REL_TPL->stdfoot();
	die();
}
else {

	$tree = make_tree();
	$res = $REL_DB->query("SELECT torrents.images, torrents.comments, torrents.leechers, torrents.seeders, torrents.id, torrents.name, filename, numfiles, added, size, views, visible, free, hits, category FROM torrents $where GROUP BY id ORDER BY id DESC $limit");
	$resarray = prepare_for_torrenttable($res);

	if (!$resarray) {$REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('not_releases'),'error'); $REL_TPL->stdfoot(); die(); }

	$REL_TPL->begin_frame($REL_LANG->say_by_key('my_releases'));
	print('<div id="releases-table">');

	torrenttable($resarray, "mytorrents");
	print '</div>';
	$REL_TPL->end_frame();
}

$REL_TPL->stdfoot();

?>