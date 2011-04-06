<?php
/**
 * Bookmarking parser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
INIT();

loggedinorreturn();

function bark($msg, $error = true) {
	global $REL_LANG,$REL_TPL;
	$REL_TPL->stdhead(($error ? $REL_LANG->say_by_key('error') : $REL_LANG->say_by_key('torrent')." ".$REL_LANG->say_by_key('bookmarked')));
	$REL_TPL->stdmsg(($error ? $REL_LANG->say_by_key('error') : $REL_LANG->say_by_key('success')), $msg, ($error ? 'error' : 'success'));
	$REL_TPL->stdfoot();
	exit;
}

$id = (int) $_GET["torrent"];

if (!isset($id))
bark($REL_LANG->say_by_key('torrent_not_selected'));

$res = sql_query("SELECT name FROM torrents WHERE id = $id") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_array($res);

if (!$arr) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('invalid_id'));

if ((get_row_count("bookmarks", "WHERE userid = $CURUSER[id] AND torrentid = $id")))
bark($REL_LANG->say_by_key('torrent')." \"".$arr['name']."\"".$REL_LANG->say_by_key('already_bookmarked'));

sql_query("INSERT INTO bookmarks (userid, torrentid) VALUES ($CURUSER[id], $id)") or sqlerr(__FILE__,__LINE__);

safe_redirect($REL_SEO->make_link('browse'),3);
bark($REL_LANG->say_by_key('torrent')." \"".$arr['name']."\"".$REL_LANG->say_by_key('bookmarked'),false);

?>