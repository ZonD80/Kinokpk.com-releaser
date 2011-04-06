<?php
/**
 * Bookmarks Browser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require_once("include/bittorrent.php");

INIT();

loggedinorreturn();


$REL_TPL->stdhead($REL_LANG->say_by_key('bookmarks'));

$res = sql_query("SELECT SUM(1) FROM bookmarks WHERE userid = ".sqlesc($CURUSER["id"]));
$row = mysql_fetch_array($res);
$count = $row[0];

if (!$count) {
	stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('you_have_no_bookmarks'),'error');
	$REL_TPL->stdfoot();
	die();
} else {

	print ("<form method=\"post\" action=\"".$REL_SEO->make_link('takedelbookmark')."\"/>");
	$REL_TPL->begin_frame($REL_LANG->_('Release bookmarks'));
	print('<div id="releases-table">');

	$tree = make_tree();

	$res = sql_query("SELECT bookmarks.id AS bookmarkid, users.username, users.class, users.id AS owner, torrents.id, torrents.name, torrents.comments, leechers, seeders, torrents.images, torrents.numfiles, torrents.added, torrents.filename, torrents.size, torrents.views, torrents.visible, torrents.free, torrents.hits, torrents.category, IF((torrents.relgroup=0) OR (relgroups.private=0) OR FIND_IN_SET({$CURUSER['id']},relgroups.owners) OR FIND_IN_SET({$CURUSER['id']},relgroups.members),1,(SELECT 1 FROM rg_subscribes WHERE rgid=torrents.relgroup AND userid={$CURUSER['id']})) AS relgroup_allowed FROM bookmarks INNER JOIN torrents ON bookmarks.torrentid = torrents.id LEFT JOIN relgroups ON torrents.relgroup=relgroups.id LEFT JOIN users ON torrents.owner = users.id WHERE bookmarks.userid = ".sqlesc($CURUSER["id"])." ORDER BY torrents.id DESC") or sqlerr(__FILE__, __LINE__);

	$resarray = prepare_for_torrenttable($res);
	if (!$resarray) {
		stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('you_have_no_bookmarks'),'error');
		$REL_TPL->stdfoot();
		die();
	}

	torrenttable($resarray, "bookmarks");
	print '</div>';
	print("<div align=\"right\"><input type=\"submit\" OnClick=\"return confirm('Вы уверены?');\" value=\"".$REL_LANG->say_by_key('delete')."\"/></div></form>\n");
	$REL_TPL->end_frame();
}

$REL_TPL->stdfoot();

?>