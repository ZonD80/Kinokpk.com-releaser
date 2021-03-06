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

dbconn();

loggedinorreturn();


$REL_TPL->stdhead($REL_LANG->say_by_key('my_releases'));

$where = "WHERE owner = " . $CURUSER["id"] . " AND banned=0";
$res = sql_query("SELECT SUM(1) FROM torrents $where");
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$count) {
	stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('not_releases'),'error');
	$REL_TPL->stdfoot();
	die();
}
else {

	list($pagertop, $pagerbottom, $limit) = pager(20, $count, array('mytorrents'));
	$tree = make_tree();
	$res = sql_query("SELECT torrents.images, torrents.comments, SUM(trackers.leechers) AS leechers, SUM(trackers.seeders) AS seeders, torrents.id, torrents.name, filename, numfiles, added, size, views, visible, free, hits, times_completed, category FROM torrents LEFT JOIN trackers ON torrents.id=trackers.torrent $where GROUP BY id ORDER BY id DESC $limit");
	$resarray = prepare_for_torrenttable($res);

	if (!$resarray) {stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('not_releases'),'error'); $REL_TPL->stdfoot(); die(); }

	$REL_TPL->begin_frame($REL_LANG->say_by_key('my_releases'));
	print('<div id="releases-table">');

	print("<p>$pagertop</p>");

	torrenttable($resarray, "mytorrents");

	print("<p>$pagerbottom</p>");
	print '</div>';
	$REL_TPL->end_frame();
}

$REL_TPL->stdfoot();

?>