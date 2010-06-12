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
dbconn();
getlang('bookmark');
loggedinorreturn();

function bark($msg, $error = true) {
	global $tracker_lang;
	stdhead(($error ? $tracker_lang['error'] : $tracker_lang['torrent']." ".$tracker_lang['bookmarked']));
	stdmsg(($error ? $tracker_lang['error'] : $tracker_lang['success']), $msg, ($error ? 'error' : 'success'));
	stdfoot();
	exit;
}

$id = (int) $_GET["torrent"];

if (!isset($id))
bark($tracker_lang['torrent_not_selected']);

$res = sql_query("SELECT name FROM torrents WHERE id = $id") or sqlerr(__FILE__, __LINE__);
$arr = mysql_fetch_array($res);

if (!$arr) stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

if ((get_row_count("bookmarks", "WHERE userid = $CURUSER[id] AND torrentid = $id")))
bark($tracker_lang['torrent']." \"".$arr['name']."\"".$tracker_lang['already_bookmarked']);

sql_query("INSERT INTO bookmarks (userid, torrentid) VALUES ($CURUSER[id], $id)") or sqlerr(__FILE__,__LINE__);

safe_redirect("browse.php",3);
bark($tracker_lang['torrent']." \"".$arr['name']."\"".$tracker_lang['bookmarked'],false);

?>